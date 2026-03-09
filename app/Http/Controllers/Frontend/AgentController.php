<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Agent;
use App\Models\AgentPayment;
use App\Models\AgentTransaction;
use App\Models\BranchCashbox;
use App\Models\Currency;

class AgentController extends Controller
{

/*
|--------------------------------------------------------------------------
| عرض الوكلاء
|--------------------------------------------------------------------------
*/

public function index(Request $request)
{

$query = Agent::query();

if($request->search){

$query->where('name','like','%'.$request->search.'%')
      ->orWhere('phone','like','%'.$request->search.'%');

}

$agents = $query->latest()->paginate(20);


/*
|-----------------------------------
| احصائيات
|-----------------------------------
*/

$totalAgents = Agent::count();

$totalDue = AgentTransaction::where('type','visa_cost')->sum('amount');

$totalPayments = AgentTransaction::where('type','payment')->sum('amount');

$currentBalance = AgentTransaction::sum('amount');


return view('frontend.agents.index',compact(
'agents',
'totalAgents',
'totalDue',
'totalPayments',
'currentBalance'
));

}


/*
|--------------------------------------------------------------------------
| إنشاء وكيل
|--------------------------------------------------------------------------
*/

public function store(Request $request)
{

$request->validate([
'name'=>'required|string|max:255'
]);

Agent::create([

'branch_id'=>auth()->user()->employee->branch_id,
'name'=>$request->name,
'phone'=>$request->phone,
'country'=>$request->country,
'city'=>$request->city,
'status'=>1

]);

return back()->with('success','تم إنشاء الوكيل بنجاح');

}


/*
|--------------------------------------------------------------------------
| عرض صفحة الوكيل
|--------------------------------------------------------------------------
*/

public function show($id)
{

$agent = Agent::findOrFail($id);


/*
|-----------------------------------
| كشف الحساب
|-----------------------------------
*/

$transactions = AgentTransaction::with(['currency','visa'])
->where('agent_id',$agent->id)
->latest()
->paginate(30);


/*
|-----------------------------------
| الرصيد
|-----------------------------------
*/

$balance = AgentTransaction::where('agent_id',$agent->id)->sum('amount');


/*
|-----------------------------------
| العملات
|-----------------------------------
*/

$currencies = Currency::where('status',1)->get();


return view('frontend.agents.show',compact(

'agent',
'transactions',
'balance',
'currencies'

));

}


/*
|--------------------------------------------------------------------------
| دفع للوكيل
|--------------------------------------------------------------------------
*/

public function storePayment(Request $request,$id)
{

$request->validate([

'amount'=>'required|numeric|min:1',
'currency_id'=>'required'

]);

$agent = Agent::findOrFail($id);

$branchId = auth()->user()->employee->branch_id;


/*
|-----------------------------------
| التحقق من الخزنة قبل أي عملية
|-----------------------------------
*/

$cashbox = BranchCashbox::where('branch_id',$branchId)
    ->where('currency_id',$request->currency_id)
    ->first();

if(!$cashbox){

return back()->with('error','لا توجد خزنة لهذه العملة');

}

if($cashbox->balance < $request->amount){

return back()->with('error','رصيد الخزنة غير كافي لإتمام الدفع');

}


/*
|-----------------------------------
| إنشاء سجل الدفع
|-----------------------------------
*/

$payment = AgentPayment::create([

'branch_id'=>$branchId,
'agent_id'=>$agent->id,
'amount'=>$request->amount,
'currency_id'=>$request->currency_id,
'description'=>$request->description

]);


/*
|-----------------------------------
| تسجيل العملية المالية
|-----------------------------------
*/

AgentTransaction::create([

'agent_id'=>$agent->id,
'branch_id'=>$branchId,
'agent_payment_id'=>$payment->id,
'type'=>'payment',
'amount'=>-$request->amount,
'currency_id'=>$request->currency_id

]);


/*
|-----------------------------------
| تحديث الخزنة
|-----------------------------------
*/

$cashbox->decrement('balance',$request->amount);


return back()->with('success','تم تسجيل الدفع للوكيل');

}


/*
|--------------------------------------------------------------------------
| حذف وكيل
|--------------------------------------------------------------------------
*/

public function destroy($id)
{

$agent = Agent::findOrFail($id);


/*
|-----------------------------------
| منع الحذف إذا لديه معاملات
|-----------------------------------
*/

if($agent->transactions()->count() > 0){

return back()->with('error','لا يمكن حذف الوكيل لوجود معاملات مرتبطة به');

}

$agent->delete();

return back()->with('success','تم حذف الوكيل');

}

}