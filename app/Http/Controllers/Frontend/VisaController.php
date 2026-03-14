<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Visa;
use App\Models\Client;
use App\Models\VisaType;
use App\Models\ServicePackage;
use App\Models\TripGroup;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BranchCashbox;
use App\Models\AgentTransaction;
use App\Models\VisaStatusHistory;

class VisaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | عرض جميع التأشيرات + بحث + فلترة
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Visa::with(['client','visaType','agent','invoice']);

        // بحث باسم العميل
        if ($request->filled('search')) {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('full_name','like','%'.$request->search.'%');
            });
        }

        // فلترة حسب النوع
        if ($request->filled('visa_type')) {
            $query->where('visa_type_id',$request->visa_type);
        }

        $visas = $query->latest()->paginate(12);

        $visaTypes = VisaType::where('status',1)->get();

        return view('frontend.visas.index', compact('visas','visaTypes'));
    }


    /*
    |--------------------------------------------------------------------------
    | إنشاء تأشيرة كاملة (مع فاتورة + دفع + وكيل)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
{
    $request->validate([
        'client_id' => 'required|exists:clients,id',
        'visa_type_id' => 'required|exists:visa_types,id',
        'currency_id' => 'required|exists:currencies,id',
        'sale_price' => 'required|numeric|min:0',
        'cost_price' => 'required|numeric|min:0',
        'original_price' => 'nullable|numeric|min:0',
        'discount_percentage' => 'nullable|numeric|min:0|max:100',
        'agent_id' => 'nullable|exists:agents,id',
        'agent_cost' => 'nullable|numeric|min:0',
        'paid_amount' => 'nullable|numeric|min:0'
    ]);

    DB::beginTransaction();

    try {
if (empty($request->agent_id) && (float)$request->agent_cost > 0) {
    return back()->with('error', 'لم يتم اختيار وكيل');
}

        $employee = auth()->user()->employee;
        $branchId = $employee->branch_id;

        /*
        |--------------------------------------------------------------------------
        | حساب الخصم
        |--------------------------------------------------------------------------
        */
        $discountAmount = 0;

        if ($request->filled('discount_percentage') && $request->original_price) {
            $discountAmount =
                ($request->original_price * $request->discount_percentage) / 100;
        }

        /*
        |--------------------------------------------------------------------------
        | إنشاء التأشيرة
        |--------------------------------------------------------------------------
        */

        $visaNumber = 'V-' . date('Y') . '-' . str_pad(Visa::count()+1,5,'0',STR_PAD_LEFT);
       $visa = Visa::create([

'visa_number' => $visaNumber,

'branch_id' => $branchId,

'client_id' => $request->client_id,

'visa_type_id' => $request->visa_type_id,

'agent_id' => $request->agent_id,

'passport_number' => $request->passport_number,

'original_price' => $request->original_price,

'discount_percentage' => $request->discount_percentage,

'discount_amount' => $discountAmount,

'sale_price' => $request->sale_price,

'cost_price' => $request->cost_price,

'agent_cost' => $request->agent_cost,

'currency_id' => $request->currency_id,

'status' => 'pending',

'created_by' => $employee->id

]);

/*
|--------------------------------------------------------------------------
| تسجيل دين الوكيل
|--------------------------------------------------------------------------
*/

if($request->agent_id && $request->agent_cost){

AgentTransaction::create([

'agent_id'=>$request->agent_id,

'branch_id'=>$branchId,

'visa_id'=>$visa->id,

'type'=>'visa_cost',

'amount'=>$request->agent_cost,

'currency_id'=>$request->currency_id

]);

}

        /*
        |--------------------------------------------------------------------------
        | إنشاء الفاتورة
        |--------------------------------------------------------------------------
        */
        $invoice = Invoice::create([
            'branch_id' => $branchId,
            'client_id' => $request->client_id,
            'reference_type' => 'visa',
            'reference_id' => $visa->id,
            'total_amount' => $request->sale_price,
            'paid_amount' => 0,
            'remaining_amount' => $request->sale_price,
           'cost' => $request->cost_price,
            'currency_id' => $request->currency_id,
            'status' => 'unpaid',
            'is_refund' => false

            
        ]);

        /*
        |--------------------------------------------------------------------------
        | إنشاء الدفع إذا تم إدخال مبلغ
        |--------------------------------------------------------------------------
        */
        if ($request->filled('paid_amount') && $request->paid_amount > 0) {

            if ($request->paid_amount > $invoice->remaining_amount) {
                throw new \Exception('المبلغ المدفوع أكبر من المتبقي');
            }

            Payment::create([
                'branch_id' => $branchId,
                'client_id' => $request->client_id,
                'invoice_id' => $invoice->id,
                'amount' => $request->paid_amount,
                'currency_id' => $request->currency_id,
                'payment_method' => 'cash',
                'created_by' => $employee->id
            ]);

            $invoice->paid_amount += $request->paid_amount;
            $invoice->remaining_amount -= $request->paid_amount;

            $invoice->status =
                $invoice->remaining_amount == 0
                ? 'paid'
                : 'partial';

            $invoice->save();

            /*
            |--------------------------------------------------------------------------
            | تحديث الخزنة حسب العملة
            |--------------------------------------------------------------------------
            */
            $cashbox = BranchCashbox::where('branch_id',$branchId)
                ->where('currency_id',$request->currency_id)
                ->first();

            if (!$cashbox) {
                throw new \Exception('لا توجد خزنة لهذه العملة');
            }

            $cashbox->increment('balance', $request->paid_amount);
        }

        /*
        |--------------------------------------------------------------------------
        | تسجيل دين الوكيل مباشرة
        |--------------------------------------------------------------------------
        */
       if ($request->agent_id && $request->agent_cost) {

    AgentTransaction::create([
        'agent_id' => $request->agent_id,
        'branch_id' => $branchId,
        'visa_id' => $visa->id,
        'type' => 'visa_cost',
        'amount' => $request->agent_cost,
        'currency_id' => $request->currency_id
    ]);

}

        /*
        |--------------------------------------------------------------------------
        | تسجيل حالة الإنشاء
        |--------------------------------------------------------------------------
        */
        VisaStatusHistory::create([
            'visa_id' => $visa->id,
            'old_status' => null,
            'new_status' => 'pending',
            'changed_by' => $employee->id,
            'notes' => 'إنشاء التأشيرة'
        ]);

        DB::commit();

        return back()->with('success','تم إنشاء التأشيرة بنجاح');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}


    /*
    |--------------------------------------------------------------------------
    | تحديث التأشيرة (مسموح حتى لو فيها مدفوعات)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $visa = Visa::findOrFail($id);

        $visa->update($request->only([
            'passport_number',
            'sale_price',
            'cost_price',
            'agent_cost'
        ]));

        return back()->with('success','تم تحديث التأشيرة');
    }


    /*
    |--------------------------------------------------------------------------
    | لا نحذف التأشيرة — فقط نغير الحالة
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $visa = Visa::findOrFail($id);
        $visa->update(['status'=>'cancelled']);

        return back()->with('success','تم إلغاء التأشيرة');
    }


    /*
    |--------------------------------------------------------------------------
    | عرض تفاصيل التأشيرة
    |--------------------------------------------------------------------------
    */public function show($id)
{
    $visa = Visa::with([

        // العلاقات الأساسية
        'client:id,full_name,passport_number',
        'visaType:id,name',
        'agent:id,name',
        'employee:id,full_name',
        'currency:id,code,symbol',

        // الحملة والباص (إن وجد)
        'tripGroup:id,name,departure_date,return_date',
        'package:id,name',
        'tripGroupBus.bus:id,plate_number,model',
        'tripGroupBus.driver:id,name',

        // الفاتورة والمدفوعات
        'invoice' => function ($q) {
            $q->select(
                'id',
                'reference_id',
                'reference_type',
                'total_amount',
                'paid_amount',
                'remaining_amount',
                'status',
                'currency_id'
            )->where('reference_type', 'visa')
             ->with([
                 'payments:id,invoice_id,amount,created_at'
             ]);
        },

        // سجل الحالات
        'statusHistories' => function ($q) {
            $q->with('employee:id,full_name')
              ->orderBy('created_at','desc');
        },

        // حركات الوكيل
        'agentTransactions:id,visa_id,amount,type,created_at'

    ])->findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | تجهيز بيانات إضافية للعرض
    |--------------------------------------------------------------------------
    */

    $invoice = $visa->invoice;

    $totalPaid = $invoice ? $invoice->paid_amount : 0;
    $remaining = $invoice ? $invoice->remaining_amount : 0;
    $isPaid    = $invoice ? $invoice->status === 'paid' : false;

$agentDebt = $visa->agentTransactions
        ->where('type','visa_cost')
        ->sum('amount');

    return view('frontend.visas.show', compact(
        'visa',
        'invoice',
        'totalPaid',
        'remaining',
        'isPaid',
        'agentDebt'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | تغيير الحالة من البطاقة
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ربط بحملة
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | ربط بباقة
    |--------------------------------------------------------------------------
    */public function searchClients(Request $request)
{

    $search = $request->q;

    $clients = \App\Models\Client::where('full_name','like','%'.$search.'%')
        ->limit(10)
        ->get([
            'id',
            'full_name',
            'passport_number'
        ]);

  return response()->json($clients);
}

public function storePayment(Request $request, $id)
{
    $request->validate([
        'amount' => 'required|numeric|min:0.01',
        'payment_method' => 'required|string|max:50'
    ]);

    DB::beginTransaction();

    try {

        $visa = Visa::with('invoice')->findOrFail($id);

        if (!$visa->invoice) {
            throw new \Exception('لا توجد فاتورة مرتبطة بهذه التأشيرة');
        }

        $invoice = $visa->invoice;

        if ($invoice->status === 'paid') {
            throw new \Exception('الفاتورة مدفوعة بالكامل');
        }

        if ($request->amount > $invoice->remaining_amount) {
            throw new \Exception('المبلغ أكبر من المتبقي');
        }

        $branchId = $visa->branch_id;

        /*
        |--------------------------------------------------------------------------
        | إنشاء Payment
        |--------------------------------------------------------------------------
        */
         $employee = auth()->user()->employee;
        Payment::create([
            'branch_id' => $branchId,
            'client_id' => $visa->client_id,
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'currency_id' => $invoice->currency_id,
            'payment_method' => $request->payment_method,
             'created_by' => $employee->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | تحديث الفاتورة
        |--------------------------------------------------------------------------
        */
        $invoice->paid_amount += $request->amount;
        $invoice->remaining_amount -= $request->amount;

        $invoice->status =
            $invoice->remaining_amount == 0
            ? 'paid'
            : 'partial';

        $invoice->save();

        /*
        |--------------------------------------------------------------------------
        | تحديث الخزنة حسب العملة
        |--------------------------------------------------------------------------
        */
        $cashbox = BranchCashbox::where('branch_id',$branchId)
            ->where('currency_id',$invoice->currency_id)
            ->first();

        if (!$cashbox) {
            throw new \Exception('لا توجد خزنة لهذه العملة');
        }

        $cashbox->increment('balance', $request->amount);

        DB::commit();

        return back()->with('success','تم تسجيل الدفعة بنجاح');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error',$e->getMessage());
    }
}

public function changeStatus(Request $request, $id)
{
    $request->validate([
    'status' => 'required|in:pending,issued,cancelled',
    'cancel_reason' => 'nullable|string|max:1000',
    'visa_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096'
]);

    DB::beginTransaction();

    try {

        $visa = Visa::findOrFail($id);
        $oldStatus = $visa->status;

        if ($oldStatus === $request->status) {
            throw new \Exception('الحالة الحالية هي نفسها المختارة');
        }

        // تحديث الحالة
        $visa->status = $request->status;
if($request->hasFile('visa_file')){

$file = $request->file('visa_file');

$name = time().'_'.$file->getClientOriginalName();

$file->storeAs('visas',$name,'public');

$extension = $file->getClientOriginalExtension();

if($extension == 'pdf'){

$visa->document_file = 'visas/'.$name;

}else{

$visa->image_file = 'visas/'.$name;

}

}

        if ($request->status === 'cancelled') {
            $visa->cancel_reason = $request->cancel_reason;
        }
        if ($request->status === 'cancelled' && $visa->agent_id && $visa->agent_cost) {

    AgentTransaction::create([
        'agent_id' => $visa->agent_id,
        'branch_id' => $visa->branch_id,
        'visa_id' => $visa->id,
        'type' => 'adjustment',
        'amount' => -$visa->agent_cost,
        'currency_id' => $visa->currency_id
    ]);

}

        $visa->save();

        // تسجيل في جدول التاريخ
        VisaStatusHistory::create([
            'visa_id' => $visa->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'changed_by' => auth()->user()->employee->id,
            'notes' => $request->status === 'cancelled'
                ? 'تم الإلغاء: '.$request->cancel_reason
                : 'تم تغيير الحالة'
        ]);

        DB::commit();

        return back()->with('success','تم تغيير الحالة بنجاح');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error',$e->getMessage());
    }
}


public function searchTripGroups(Request $request)
{
    $search = $request->q;

    $groups = \App\Models\TripGroup::where('status',1)
        ->whereDate('departure_date','>=',now())
        ->where('name','like','%'.$search.'%')
        ->limit(10)
        ->get(['id','name','departure_date']);

    return response()->json($groups);
}



public function getAvailableSeats($id)
{
    $tripGroup = \App\Models\TripGroup::with('tripGroupBuses')->findOrFail($id);

    if ($tripGroup->departure_date < now()) {
        return response()->json(['error'=>'الحملة انتهت'],422);
    }

    $totalSeats = $tripGroup->total_seats;

    $bookedSeats = \App\Models\Visa::where('trip_group_id',$id)
        ->whereNotNull('trip_group_bus_id')
        ->count();

    $remaining = $totalSeats - $bookedSeats;

    if ($remaining <= 0) {
        return response()->json(['error'=>'لا يوجد مقاعد متاحة'],422);
    }

    return response()->json([
        'remaining'=>$remaining,
        'buses'=>$tripGroup->tripGroupBuses()->with('bus','driver')->get()
    ]);
}

public function attachTripGroup(Request $request, $id)
{
    $request->validate([
        'trip_group_id'=>'required|exists:trip_groups,id',
        'trip_group_bus_id'=>'required|exists:trip_group_buses,id'
    ]);

    DB::beginTransaction();

    try {

        $visa = Visa::findOrFail($id);

        if ($visa->isCancelled()) {
            throw new \Exception('لا يمكن ربط تأشيرة ملغية');
        }

        $tripGroup = \App\Models\TripGroup::findOrFail($request->trip_group_id);

        if ($tripGroup->departure_date < now()) {
            throw new \Exception('الحملة انتهت');
        }

        $bookedSeats = Visa::where('trip_group_id',$tripGroup->id)
            ->whereNotNull('trip_group_bus_id')
            ->count();

        if ($bookedSeats >= $tripGroup->total_seats) {
            throw new \Exception('الحملة ممتلئة');
        }

        $visa->update([
            'trip_group_id'=>$tripGroup->id,
            'trip_group_bus_id'=>$request->trip_group_bus_id
        ]);

        DB::commit();

        return back()->with('success','تم ربط التأشيرة بالحملة');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error',$e->getMessage());
    }
}





}
