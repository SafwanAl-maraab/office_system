<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Request as RequestModel;
use Illuminate\Http\Request;
use App\Models\RequestType;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class RequestsController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $query = \App\Models\Request::with([
            'client',
            'requestType',
            'employee'
        ])
            ->where('branch_id', $branchId);

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('request_number', 'like', "%$search%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('full_name', 'like', "%$search%");
                    });
            });
        }

        // Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date
        if ($request->filled('date')) {
            $query->whereDate('request_date', $request->date);
        }

        $requests = $query
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // 🔥 تحميل أنواع الطلبات الخاصة بالفرع
        $requestTypes = \App\Models\RequestType::where('branch_id', $branchId)
            ->where('status', true)
            ->get();

        // 🔥 تحميل العملاء للفرع
        $clients = \App\Models\Client::where('branch_id', $branchId)
            ->where('status', true)
            ->get();
        $travels = \App\Models\Travel::where('branch_id', $branchId)
            ->latest()
            ->get();



        return view('frontend.requests.index', compact(
            'requests',
            'requestTypes',
            'clients',
              'travels',

        ));
    }
    public function store(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;
        $employeeId = auth()->user()->employee->id;

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'request_type_id' => 'required|exists:request_types,id',
            'confirm_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $branchId, $employeeId) {

            // جلب نوع الطلب والتحقق أنه تابع لنفس الفرع
            $requestType = RequestType::where('id', $request->request_type_id)
                ->where('branch_id', $branchId)
                ->firstOrFail();

            // التحقق من تطابق السعر
            if ((float)$request->confirm_price !== (float)$requestType->price) {
                abort(403, 'السعر غير مطابق للسعر المحدد.');
            }

            // توليد رقم طلب تسلسلي
            $lastId = \App\Models\Request::max('id') + 1;
            $requestNumber = 'REQ-' . date('Y') . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

            // إنشاء الطلب
            $newRequest = \App\Models\Request::create([
                'branch_id' => $branchId,
                'client_id' => $request->client_id,
                'request_type_id' => $requestType->id,
                'request_number' => $requestNumber,
                'request_date' => now(),
                'status' => 'new',
                'received_by' => $employeeId,
                'notes' => $request->notes,
            ]);

            // إنشاء الفاتورة تلقائيًا
            Invoice::create([
                'branch_id' => $branchId,
                'client_id' => $request->client_id,
                'reference_type' => 'request',
                'reference_id' => $newRequest->id,
                'total_amount' => $requestType->price,
                'paid_amount' => 0,
                'remaining_amount' => $requestType->price,
                'currency_id' => $requestType->currency_id,
                'status' => 'unpaid',
                'is_refund' => false,
            ]);
        });

        return redirect()
            ->route('dashboard.requests.index')
            ->with('success', 'تم إنشاء الطلب بنجاح.');
    }

    public function show($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request = \App\Models\Request::with([
            'client',
            'requestType.currency',
            'employee',
            'invoice.payments',
            'statusHistories.employee',
            'invoice.currency'
        ])


            ->where('branch_id', $branchId)
            ->findOrFail($id);


        return view('frontend.requests.show', compact('request'));
    }

    public function changeStatus(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;
        $employeeId = auth()->user()->employee->id;

        $request->validate([
            'new_status' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $order = \App\Models\Request::where('branch_id', $branchId)
            ->findOrFail($id);

        // 🔒 منع التعديل بعد الإغلاق
        if (in_array($order->status, ['delivered', 'cancelled', 'rejected'])) {
            abort(403, 'لا يمكن تعديل هذا الطلب.');
        }


        $allowedTransitions = [

            'new' => [
                'under_review',
                'cancelled'
            ],

            'under_review' => [
                'preparing',
                'rejected',
                'cancelled',
                'new' // رجوع للخلف
            ],

            'preparing' => [
                'sent_to_south',
                'cancelled',
                'under_review' // رجوع
            ],

            'sent_to_south' => [
                'received_south',
                'cancelled',
                'preparing' // رجوع
            ],

            'received_south' => [
                'ready',
                'cancelled'
            ],

            'ready' => [
                'delivered',
                'cancelled'
            ],

        ];

        $currentStatus = $order->status;
        $newStatus = $request->new_status;




        if (!isset($allowedTransitions[$currentStatus]) ||
            !in_array($newStatus, $allowedTransitions[$currentStatus])) {

            abort(403, 'انتقال غير مسموح.');
        }

        // إذا إلغاء أو رفض يجب وجود سبب
        if (in_array($newStatus, ['cancelled', 'rejected']) && empty($request->notes)) {
            return back()->withErrors(['notes' => 'يجب إدخال سبب.']);
        }

        if (
            in_array($newStatus, ['ready', 'delivered']) &&
            $order->invoice &&
            $order->invoice->remaining_amount > 0
        ) {
            $request->notes .= ' | تم تغيير الحالة رغم وجود مبلغ متبقي';
        }

        \DB::transaction(function () use ($order, $newStatus, $employeeId, $request) {

            \App\Models\RequestStatusHistory::create([
                'request_id' => $order->id,
                'old_status' => $order->status,
                'new_status' => $newStatus,
                'changed_by' => $employeeId,
                'notes' => $request->notes,
            ]);

            $order->update([
                'status' => $newStatus
            ]);
        });

        return redirect()->back()->with('success', 'تم تغيير الحالة بنجاح.');
    }


    public function update(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'request_type_id' => 'required|exists:request_types,id',
            'notes' => 'nullable|string',
        ]);

        $order = \App\Models\Request::with('invoice')
            ->where('branch_id', $branchId)
            ->findOrFail($id);

        // 🔒 منع التعديل بعد الإغلاق
        if (in_array($order->status, ['delivered', 'cancelled', 'rejected'])) {
            abort(403, 'لا يمكن تعديل هذا الطلب.');
        }

        // 🔒 منع تغيير النوع إذا هناك دفعات
        if ($order->invoice && $order->invoice->paid_amount > 0) {
            if ($order->request_type_id != $request->request_type_id) {
                abort(403, 'لا يمكن تغيير نوع الطلب بعد تسجيل دفعات.');
            }
        }

        $requestType = \App\Models\RequestType::where('id', $request->request_type_id)
            ->where('branch_id', $branchId)
            ->firstOrFail();

        \DB::transaction(function () use ($order, $request, $requestType) {

            $order->update([
                'client_id' => $request->client_id,
                'request_type_id' => $requestType->id,
                'notes' => $request->notes,
            ]);

            // تحديث الفاتورة فقط إذا لم توجد دفعات
            if ($order->invoice && $order->invoice->paid_amount == 0) {

                $order->invoice->update([
                    'total_amount' => $requestType->price,
                    'remaining_amount' => $requestType->price,
                    'currency_id' => $requestType->currency_id,
                ]);
            }

        });

        return redirect()
            ->route('dashboard.requests.index')
            ->with('success', 'تم تحديث الطلب بنجاح.');
    }

    public function destroy($id)
    {
        $branchId = auth()->user()->employee->branch_id;
        $employeeId = auth()->user()->employee->id;

        $order = \App\Models\Request::with('invoice')
            ->where('branch_id', $branchId)
            ->findOrFail($id);

        // 🔒 لا يمكن حذف إذا تم التسليم
        if ($order->status === 'delivered') {
            abort(403, 'لا يمكن حذف طلب تم تسليمه.');
        }

        \DB::transaction(function () use ($order, $employeeId) {

            // تسجيل في history
            \App\Models\RequestStatusHistory::create([
                'request_id' => $order->id,
                'old_status' => $order->status,
                'new_status' => 'cancelled',
                'changed_by' => $employeeId,
                'notes' => 'تم الإلغاء عبر الحذف',
            ]);

            // تحديث الحالة
            $order->update([
                'status' => 'cancelled'
            ]);
        });

        return redirect()
            ->route('dashboard.requests.index')
            ->with('success', 'تم إلغاء الطلب بنجاح.');
    }

    public function attachTravel(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;
        $employeeId = auth()->user()->employee->id;

        $request->validate([
            'travel_id' => 'required|exists:travels,id',
            'seat_number' => 'nullable|string|max:20'
        ]);

        $order = \App\Models\Request::where('branch_id', $branchId)
            ->findOrFail($id);

        // 🔒 منع الربط إذا مغلق
        if (in_array($order->status, ['delivered', 'cancelled', 'rejected'])) {
            abort(403, 'لا يمكن ربط هذا الطلب.');
        }



        $travel = \App\Models\Travel::where('branch_id', $branchId)
            ->findOrFail($request->travel_id);

        if ($travel->requests()->count() >= $travel->capacity) {
            return back()->withErrors([
                'error' => 'الرحلة ممتلئة.'
            ]);
        }

        \DB::transaction(function () use ($order, $travel, $request, $employeeId) {

            // ربط في pivot
            $order->travels()->syncWithoutDetaching([
                $travel->id => [
                    'seat_number' => $request->seat_number
                ]
            ]);

            // تحديث الحالة إلى sent_to_south
            if ($order->status !== 'sent_to_south') {

                \App\Models\RequestStatusHistory::create([
                    'request_id' => $order->id,
                    'old_status' => $order->status,
                    'new_status' => 'sent_to_south',
                    'changed_by' => $employeeId,
                    'notes' => 'تم ربط الطلب برحلة',
                ]);

                $order->update([
                    'status' => 'sent_to_south'
                ]);
            }

        });

        return back()->with('success', 'تم ربط الطلب بالرحلة.');
    }


    public function detachTravel($id)
    {
        $branchId = auth()->user()->employee->branch_id;
        $employeeId = auth()->user()->employee->id;

        $order = \App\Models\Request::where('branch_id', $branchId)
            ->findOrFail($id);

        // لا يسمح بفك الربط بعد الجاهزية أو التسليم
        if (in_array($order->status, ['ready', 'delivered'])) {
            abort(403, 'لا يمكن فك الربط في هذه المرحلة.');
        }

        \DB::transaction(function () use ($order, $employeeId) {

            $order->travels()->detach();

            // إعادة الحالة إلى preparing
            \App\Models\RequestStatusHistory::create([
                'request_id' => $order->id,
                'old_status' => $order->status,
                'new_status' => 'preparing',
                'changed_by' => $employeeId,
                'notes' => 'تم فك الربط من الرحلة',
            ]);

            $order->update([
                'status' => 'preparing'
            ]);
        });

        return back()->with('success', 'تم فك الربط.');
    }


}
