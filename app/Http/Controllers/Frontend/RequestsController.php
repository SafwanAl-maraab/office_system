<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BranchCashbox;
use App\Models\CashboxTransaction;
use App\Models\ClientBalanceLog;
use App\Models\Payment;
use App\Models\Request as RequestModel;
use App\Models\RequestStatusHistory;
use Illuminate\Http\Request;
use App\Models\RequestType;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Exception;

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

        // 1. التحقق من البيانات المرسلة (بعد حذف confirm_price)
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'request_type_id' => 'required|exists:request_types,id',
            'cost_price' => 'required|numeric|min:0',
        ]);

        try {
            // بدء المعاملة الآمنة لقاعدة البيانات
            DB::transaction(function () use ($request, $branchId, $employeeId) {

                // جلب نوع الطلب والتحقق أنه تابع لنفس الفرع
                $requestType = RequestType::where('id', $request->request_type_id)
                    ->where('branch_id', $branchId)
                    ->firstOrFail();

                // 2. التحقق من أن سعر البيع أعلى من التكلفة (اعتماداً على سعر النوع الفعلي)
                if ((float)$requestType->price <= (float)$request->cost_price) {
                    // نلقي استثناء مخصص ليتم التراجع عن أي عملية واصطياده في الـ catch
                    throw new Exception('سعر البيع المحدد لهذا الطلب أقل من أو يساوي سعر التكلفة.');
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

                // إنشاء الفاتورة تلقائيًا (تم استبدال $request->confirm_price بسعر النوع الأصلي)
                Invoice::create([
                    'branch_id' => $branchId,
                    'client_id' => $request->client_id,
                    'reference_type' => 'request',
                    'reference_id' => $newRequest->id,
                    'total_amount' => $requestType->price,
                    'paid_amount' => 0,
                    'remaining_amount' => $requestType->price,
                    'cost' => $request->cost_price,
                    'currency_id' => $requestType->currency_id,
                    'status' => 'unpaid',
                    'is_refund' => false,
                ]);
            });

            // في حال النجاح: التوجيه لصفحة الاندكس مع رسالة النجاح للـ Toast
            return redirect()
                ->route('dashboard.requests.index')
                ->with('success', 'تم إنشاء الطلب والفاتورة بنجاح.');

        } catch (Exception $e) {
            // في حال حدوث أي خطأ: العودة للخلف مع رسالة الخطأ المتوافقة مع الـ Toast
            return redirect()
                ->back()
                ->withInput() // لإبقاء البيانات المدخلة في الحقول منعاً لإعادة كتابتها
                ->with('error', $e->getMessage());
        }
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
        $request->validate([

            'new_status'    => 'required|string',

            'notes'         => 'nullable|string|max:1000',

            'refund_method' => 'nullable|in:cash,balance'

        ]);

        DB::beginTransaction();

        try {

            $order = RequestModel::findOrFail($id);

            $oldStatus = $order->status;

            /*
            |--------------------------------------------------------------------------
            | منع نفس الحالة
            |--------------------------------------------------------------------------
            */

            if (
                $oldStatus === $request->new_status
            ) {

                throw new \Exception(
                    'لا يمكن اختيار نفس الحالة الحالية'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | الطلبات المنتهية
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $oldStatus,
                    ['delivered','cancelled','rejected']
                )
            ) {

                throw new \Exception(
                    'لا يمكن تعديل هذا الطلب'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | الإلغاء
            |--------------------------------------------------------------------------
            */

            if (
                $request->new_status === 'cancelled'
            ) {

                $invoice =
                    Invoice::where(
                        'reference_type',
                        'request'
                    )
                        ->where(
                            'reference_id',
                            $order->id
                        )
                        ->first();

                if ($invoice) {

                    /*
                    |--------------------------------------------------------------------------
                    | يوجد مدفوعات
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $invoice->paid_amount > 0
                    ) {

                        $paidAmount =
                            $invoice->paid_amount;

                        /*
                        |--------------------------------------------------------------------------
                        | إنشاء فاتورة مسترجع
                        |--------------------------------------------------------------------------
                        */

                        $refundInvoice =
                            Invoice::create([

                                'branch_id' =>
                                    $invoice->branch_id,

                                'client_id' =>
                                    $invoice->client_id,

                                'reference_type' =>
                                    'refund',

                                'reference_id' =>
                                    $invoice->id,

                                'total_amount' =>
                                    $paidAmount,

                                'paid_amount' =>
                                    $paidAmount,

                                'remaining_amount' =>
                                    0,

                                'currency_id' =>
                                    $invoice->currency_id,

                                'status' =>
                                    'paid',

                                'cost' =>
                                    0,

                                'is_refund' =>
                                    true,

                                'reversed_invoice_id' =>
                                    $invoice->id

                            ]);

                        /*
                        |--------------------------------------------------------------------------
                        | إضافة لرصيد العميل
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $request->refund_method
                            ===
                            'balance'
                        ) {

                            ClientBalanceLog::create([

                                'client_id' =>
                                    $invoice->client_id,

                                'currency_id' =>
                                    $invoice->currency_id,

                                'amount' =>
                                    $paidAmount,

                                'type' =>
                                    'refund',

                                'reference_type' =>
                                    'invoice',

                                'reference_id' =>
                                    $refundInvoice->id,

                                'notes' =>
                                    'استرجاع طلب ملغي',

                                'created_by' =>
                                    auth()->user()
                                        ->employee
                                        ->id

                            ]);
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | استرجاع نقدي
                        |--------------------------------------------------------------------------
                        */

                        else {

                            Payment::create([

                                'branch_id' =>
                                    $invoice->branch_id,

                                'client_id' =>
                                    $invoice->client_id,

                                'invoice_id' =>
                                    $refundInvoice->id,

                                'amount' =>
                                    $paidAmount,

                                'currency_id' =>
                                    $invoice->currency_id,

                                'payment_method' =>
                                    'refund',

                                'created_by' =>
                                    auth()->user()
                                        ->employee
                                        ->id

                            ]);

                            $cashbox =
                                BranchCashbox::where(
                                    'branch_id',
                                    $invoice->branch_id
                                )
                                    ->where(
                                        'currency_id',
                                        $invoice->currency_id
                                    )
                                    ->first();

                            if (!$cashbox) {

                                throw new \Exception(
                                    'الخزنة غير موجودة'
                                );
                            }

                            if (
                                $cashbox->balance
                                <
                                $paidAmount
                            ) {

                                throw new \Exception(
                                    'رصيد الخزنة غير كاف'
                                );
                            }

                            $cashbox->decrement(
                                'balance',
                                $paidAmount
                            );
                            CashboxTransaction::create([

                                'branch_id' =>
                                    $invoice->branch_id,

                                'currency_id' =>
                                    $invoice->currency_id,

                                'amount' =>
                                    -$paidAmount,

                                'type' =>
                                    'refund',

                                'reference_type' =>
                                    'request',

                                'reference_id' =>
                                    $order->id,

                                'notes' =>
                                    'استرجاع طلب ملغي',

                                'created_by' =>
                                    auth()->user()
                                        ->employee
                                        ->id

                            ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | إلغاء الفاتورة الأصلية
                    |--------------------------------------------------------------------------
                    */

                    $invoice->update([

                        'status' =>
                            'cancelled',

                        'paid_amount' =>
                            0,

                        'remaining_amount' =>
                            0

                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | تحديث الطلب
            |--------------------------------------------------------------------------
            */

            $order->update([

                'status' =>
                    $request->new_status

            ]);

            /*
            |--------------------------------------------------------------------------
            | تسجيل التاريخ
            |--------------------------------------------------------------------------
            */

            RequestStatusHistory::create([

                'request_id' =>
                    $order->id,

                'old_status' =>
                    $oldStatus,

                'new_status' =>
                    $request->new_status,

                'changed_by' =>
                    auth()->user()
                        ->employee
                        ->id,

                'notes' =>
                    $request->notes

            ]);

            DB::commit();

            return back()->with(
                'success',
                'تم تغيير الحالة بنجاح'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function update(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'request_type_id' => 'required|exists:request_types,id',
            'notes' => 'nullable|string',
        ]);

        try {
            $order = \App\Models\Request::with('invoice')
                ->where('branch_id', $branchId)
                ->findOrFail($id);

            // 🔒 منع التعديل بعد الإغلاق برمي Exception
            if (in_array($order->status, ['delivered', 'cancelled', 'rejected'])) {
                throw new \Exception('لا يمكن تعديل هذا الطلب لأنه مغلق أو منتهي.');
            }

            // 🔒 منع تغيير النوع إذا هناك دفعات برمي Exception
            if ($order->invoice && $order->invoice->paid_amount > 0) {
                if ($order->request_type_id != $request->request_type_id) {
                    throw new \Exception('لا يمكن تغيير نوع الطلب بعد تسجيل دفعات مالية على الفاتورة.');
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

        } catch (\Exception $e) {
            // في حال حدوث أي Exception، نرجع للخلف ونعرض نص الخطأ المكتوب فوق
            return back()->with('error', $e->getMessage());
        }
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
