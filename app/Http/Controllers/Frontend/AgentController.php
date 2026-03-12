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

/*
|-----------------------------------
| البحث
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

$agents = $query->latest()->paginate(20);


/*
|-----------------------------------
| عدد الوكلاء
|-----------------------------------
*/

$totalAgents = Agent::count();


/*
|-----------------------------------
| الاحصائيات حسب العملة
|-----------------------------------
*/

$stats = AgentTransaction::selectRaw('
    currency_id,
    SUM(CASE WHEN type="visa_cost" THEN amount ELSE 0 END) as total_due,
    SUM(CASE WHEN type="payment" THEN amount ELSE 0 END) as total_payment,
    SUM(amount) as balance
')
->groupBy('currency_id')
->with('currency')
->get();


/*
|-----------------------------------
| العملات
|-----------------------------------
*/

$currencies = Currency::where('status',1)->get();


return view('frontend.agents.index',compact(

'agents',
'totalAgents',
'stats',
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


/*
|--------------------------------------------------------------------------
| الرصيد حسب العملة
|--------------------------------------------------------------------------
*/

$balances = AgentTransaction::selectRaw('currency_id, SUM(amount) as total')
->where('agent_id',$agent->id)
->groupBy('currency_id')
->with('currency')
->get();


/*
|--------------------------------------------------------------------------
| العملات التي لدى الوكيل معاملات بها
|--------------------------------------------------------------------------
*/

$agentCurrencies = AgentTransaction::select('currency_id')
->where('agent_id',$agent->id)
->groupBy('currency_id')
->with('currency')
->get();


/*
|--------------------------------------------------------------------------
| حساب الرصيد التراكمي
|--------------------------------------------------------------------------
*/

$runningBalance = 0;

foreach ($transactions as $transaction) {

$runningBalance += $transaction->amount;

$transaction->balance_after = $runningBalance;

}


/*
|--------------------------------------------------------------------------
| احصائيات الوكيل المالية حسب العملة
|--------------------------------------------------------------------------
*/

$financialStats = AgentTransaction::selectRaw('
currency_id,
SUM(CASE WHEN type="visa_cost" THEN amount ELSE 0 END) as total_due,
SUM(CASE WHEN type="payment" THEN amount ELSE 0 END) as total_paid,
SUM(amount) as balance
')
->where('agent_id',$agent->id)
->groupBy('currency_id')
->with('currency')
->get();


/*
|--------------------------------------------------------------------------
| العملات
|--------------------------------------------------------------------------
*/

$currencies = Currency::where('status',1)->get();


return view('frontend.agents.show',compact(

'agent',
'transactions',
'balances',
'currencies',
'agentCurrencies',
'financialStats'

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

/*
|--------------------------------------------------------------------------
| التأكد أن الوكيل لديه معاملات بهذه العملة
|--------------------------------------------------------------------------
*/

$currencyCheck = AgentTransaction::where('agent_id',$agent->id)
->where('currency_id',$request->currency_id)
->exists();

if(!$currencyCheck){

throw new \Exception('لا يمكن الدفع بهذه العملة لعدم وجود دين بها');

}

/*
|--------------------------------------------------------------------------
| الخزنة
|--------------------------------------------------------------------------
*/

$cashbox = BranchCashbox::where('branch_id',$branchId)
->where('currency_id',$request->currency_id)
->first();

if(!$cashbox){

throw new \Exception('لا توجد خزنة لهذه العملة');

}

if($cashbox->balance < $request->amount){

throw new \Exception('رصيد الخزنة غير كافي لإتمام الدفع');

}

/*
|--------------------------------------------------------------------------
| إنشاء الدفع
|--------------------------------------------------------------------------
*/

$payment = AgentPayment::create([

'branch_id'=>$branchId,
'agent_id'=>$agent->id,
'amount'=>$request->amount,
'currency_id'=>$request->currency_id,
'description'=>$request->description

]);

/*
|--------------------------------------------------------------------------
| تسجيل الحركة المالية
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| تحديث الخزنة
|--------------------------------------------------------------------------
*/

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
| كشف حساب وكيل PDF
|--------------------------------------------------------------------------
*/
public function statementPDF($id)
{

$agent = Agent::findOrFail($id);

$transactions = AgentTransaction::with(['currency','visa'])
->where('agent_id',$agent->id)
->orderBy('created_at')
->get();

/*
|--------------------------------
| الرصيد التراكمي
|--------------------------------
*/

$balance = 0;

foreach($transactions as $t){

$balance += $t->amount;

$t->balance_after = $balance;

}

/*
|--------------------------------
| اصلاح العربية
|--------------------------------
*/

$Arabic = new Arabic();
$Arabic->setMaxChars(5000);

$html = view(
'frontend.agents.pdf.statement',
compact('agent','transactions','balance')
)->render();

$html = $Arabic->utf8Glyphs($html);

/*
|--------------------------------
| إنشاء PDF
|--------------------------------
*/

$pdf = Pdf::loadHTML($html)
->setPaper('A4');

return $pdf->download('agent-statement-'.$agent->id.'.pdf');

}

/*
|--------------------------------------------------------------------------
| كشف حساب جميع الوكلاء
|--------------------------------------------------------------------------
*/

public function statementAll(Request $request)
{

$query = Agent::query();

if($request->search){

$query->where(function($q) use ($request){

$q->where('name','like','%'.$request->search.'%')
->orWhere('phone','like','%'.$request->search.'%');

});

}

$agents = $query->with('transactions.currency')->get();

$pdf = Pdf::loadView(
'frontend.agents.pdf.all',
compact('agents')
)
->setPaper('A4')
->setOption('defaultFont','DejaVu Sans');

return $pdf->download('agents-statement.pdf');

}

}