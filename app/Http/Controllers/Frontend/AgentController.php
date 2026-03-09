<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Agent;
use App\Models\AgentPayment;
use App\Models\AgentTransaction;
use App\Models\BranchCashbox;
use App\Models\Currency;
use Barryvdh\DomPDF\Facade\Pdf;

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

$query->where(function($q) use ($request){

$q->where('name','like','%'.$request->search.'%')
  ->orWhere('phone','like','%'.$request->search.'%');

});

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

/*
|-----------------------------------
| العملات
|-----------------------------------
*/

$currencies = Currency::where('status',1)->get();

return view('frontend.agents.index',compact(
'agents',
'totalAgents',
'totalDue',
'totalPayments',
'currentBalance',
'currencies'
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

$transactions = AgentTransaction::with([
'currency:id,code,symbol',
'visa:id,visa_number'
])
->where('agent_id',$agent->id)
->latest()
->paginate(30);

$runningBalance = 0;

foreach ($transactions as $transaction) {

$runningBalance += $transaction->amount;

$transaction->balance_after = $runningBalance;

}

$balance = AgentTransaction::where('agent_id',$agent->id)->sum('amount');

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

DB::beginTransaction();

try{

$agent = Agent::findOrFail($id);

$branchId = auth()->user()->employee->branch_id;

$cashbox = BranchCashbox::where('branch_id',$branchId)
    ->where('currency_id',$request->currency_id)
    ->first();

if(!$cashbox){

throw new \Exception('لا توجد خزنة لهذه العملة');

}

if($cashbox->balance < $request->amount){

throw new \Exception('رصيد الخزنة غير كافي لإتمام الدفع');

}

$payment = AgentPayment::create([

'branch_id'=>$branchId,
'agent_id'=>$agent->id,
'amount'=>$request->amount,
'currency_id'=>$request->currency_id,
'description'=>$request->description

]);

AgentTransaction::create([

'agent_id'=>$agent->id,
'branch_id'=>$branchId,
'agent_payment_id'=>$payment->id,
'type'=>'payment',
'amount'=>-$request->amount,
'currency_id'=>$request->currency_id

]);

$cashbox->decrement('balance',$request->amount);

DB::commit();

return back()->with('success','تم تسجيل الدفع للوكيل');

}catch(\Exception $e){

DB::rollBack();

return back()->with('error',$e->getMessage());

}

}


/*
|--------------------------------------------------------------------------
| حذف وكيل
|--------------------------------------------------------------------------
*/

public function destroy($id)
{

$agent = Agent::findOrFail($id);

if($agent->transactions()->count() > 0){

return back()->with('error','لا يمكن حذف الوكيل لوجود معاملات مرتبطة به');

}

$agent->delete();

return back()->with('success','تم حذف الوكيل');

}


/*
|--------------------------------------------------------------------------
| تحديث وكيل
|--------------------------------------------------------------------------
*/

public function update(Request $request,$id)
{

$agent = Agent::findOrFail($id);

$agent->update([

'name'=>$request->name,
'phone'=>$request->phone,
'country'=>$request->country,
'city'=>$request->city

]);

return back()->with('success','تم تحديث الوكيل');

}


/*
|--------------------------------------------------------------------------
| طباعة كشف حساب وكيل PDF
|--------------------------------------------------------------------------
*/

public function statementPDF($id)
{

$agent = Agent::findOrFail($id);

$transactions = AgentTransaction::with(['currency','visa'])
->where('agent_id',$agent->id)
->orderBy('created_at')
->get();

$balance = 0;

foreach($transactions as $t){

$balance += $t->amount;

$t->balance_after = $balance;

}

$pdf = Pdf::loadView(
'frontend.agents.pdf.statement',
compact('agent','transactions','balance')
)
->setPaper('a4')
->setOption('defaultFont','DejaVu Sans');

return $pdf->download('agent-statement-'.$agent->id.'.pdf');

}


/*
|--------------------------------------------------------------------------
| طباعة كشف حساب جميع الوكلاء
|--------------------------------------------------------------------------
*/

public function statementAll(Request $request)
{

$query = Agent::query();

/*
|-----------------------------------
| فلترة بالبحث
|-----------------------------------
*/

if($request->search){

$query->where(function($q) use ($request){

$q->where('name','like','%'.$request->search.'%')
  ->orWhere('phone','like','%'.$request->search.'%');

});

}

/*
|-----------------------------------
| فلترة بالتاريخ
|-----------------------------------
*/

if($request->from_date){

$query->whereHas('transactions',function($q) use ($request){

$q->whereDate('created_at','>=',$request->from_date);

});

}

if($request->to_date){

$query->whereHas('transactions',function($q) use ($request){

$q->whereDate('created_at','<=',$request->to_date);

});

}

$agents = $query->with('transactions.currency')->get();

/*
|-----------------------------------
| إنشاء PDF
|-----------------------------------
*/
$pdf = Pdf::loadView(
'frontend.agents.pdf.all',
compact('agents')
)
->setPaper('A4')
->setOption('defaultFont','DejaVu Sans');

return $pdf->download('agents-statement.pdf');

}
}