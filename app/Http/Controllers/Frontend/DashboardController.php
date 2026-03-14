<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

public function index(Request $request)
{

$branchId = auth()->user()->employee->branch_id;

$from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
$to   = $request->to ?? now()->format('Y-m-d');


/*
|--------------------------------------------------------------------------
| Today Stats
|--------------------------------------------------------------------------
*/

$todayVisas = DB::table('visas')
->where('branch_id',$branchId)
->whereDate('created_at',today())
->count();

$todayBookings = DB::table('bookings')
->where('branch_id',$branchId)
->whereDate('created_at',today())
->count();

$todayRequests = DB::table('requests')
->where('branch_id',$branchId)
->whereDate('created_at',today())
->count();



/*
|--------------------------------------------------------------------------
| Revenue By Currency
|--------------------------------------------------------------------------
*/

$revenueByCurrency = DB::table('payments')
->join('currencies','payments.currency_id','=','currencies.id')
->select(
'currencies.id',
'currencies.code',
'currencies.symbol',
DB::raw('SUM(payments.amount) as total')
)
->where('payments.branch_id',$branchId)
->whereBetween('payments.created_at',[$from,$to])
->groupBy('currencies.id','currencies.code','currencies.symbol')
->get();



/*
|--------------------------------------------------------------------------
| Expenses By Currency
|--------------------------------------------------------------------------
*/

$expensesByCurrency = DB::table('expenses')
->join('currencies','expenses.currency_id','=','currencies.id')
->select(
'currencies.id',
'currencies.code',
'currencies.symbol',
DB::raw('SUM(expenses.amount) as total')
)
->where('expenses.branch_id',$branchId)
->whereBetween('expenses.created_at',[$from,$to])
->groupBy('currencies.id','currencies.code','currencies.symbol')
->get();



/*
|--------------------------------------------------------------------------
| Cashbox Balance
|--------------------------------------------------------------------------
*/

$cashbox = DB::table('branch_cashboxes')
->join('currencies','branch_cashboxes.currency_id','=','currencies.id')
->select(
'currencies.code',
'currencies.symbol',
'branch_cashboxes.balance'
)
->where('branch_cashboxes.branch_id',$branchId)
->get();



/*
|--------------------------------------------------------------------------
| Remaining Clients (By Currency)
|--------------------------------------------------------------------------
*/

$clientsRemaining = DB::table('invoices')
->join('currencies','invoices.currency_id','=','currencies.id')
->select(
'currencies.code',
'currencies.symbol',
DB::raw('SUM(invoices.remaining_amount) as total')
)
->where('invoices.branch_id',$branchId)
->groupBy('currencies.code','currencies.symbol')
->get();



/*
|--------------------------------------------------------------------------
| Agents Balance (By Currency)
|--------------------------------------------------------------------------
*/

$agentsRemaining = DB::table('agent_transactions')
->join('currencies','agent_transactions.currency_id','=','currencies.id')
->select(
'currencies.code',
'currencies.symbol',
DB::raw('SUM(agent_transactions.amount) as total')
)
->where('agent_transactions.branch_id',$branchId)
->groupBy('currencies.code','currencies.symbol')
->get();



/*
|--------------------------------------------------------------------------
| Visa Profit By Currency
|--------------------------------------------------------------------------
*/

$visaProfit = DB::table('visas')
->join('currencies','visas.currency_id','=','currencies.id')
->select(
'currencies.code',
'currencies.symbol',
DB::raw('SUM(visas.sale_price - visas.cost_price - visas.agent_cost) as profit')
)
->where('visas.branch_id',$branchId)
->whereBetween('visas.created_at',[$from,$to])
->groupBy('currencies.code','currencies.symbol')
->get();



/*
|--------------------------------------------------------------------------
| Booking Profit By Currency
|--------------------------------------------------------------------------
*/

$bookingProfit = DB::table('bookings')
->join('currencies','bookings.currency_id','=','currencies.id')
->select(
'currencies.code',
'currencies.symbol',
DB::raw('SUM(bookings.final_price - bookings.purchase_price) as profit')
)
->where('bookings.branch_id',$branchId)
->whereBetween('bookings.created_at',[$from,$to])
->groupBy('currencies.code','currencies.symbol')
->get();



/*
|--------------------------------------------------------------------------
| Monthly Revenue Chart
|--------------------------------------------------------------------------
*/

$monthlyRevenue = DB::table('payments')
->select(
DB::raw('DATE(created_at) as date'),
DB::raw('SUM(amount) as total')
)
->where('branch_id',$branchId)
->whereBetween('created_at',[$from,$to])
->groupBy(DB::raw('DATE(created_at)'))
->pluck('total','date');



/*
|--------------------------------------------------------------------------
| Visa Status
|--------------------------------------------------------------------------
*/

$visaStatus = DB::table('visas')
->select('status',DB::raw('COUNT(*) as total'))
->where('branch_id',$branchId)
->groupBy('status')
->pluck('total','status');



/*
|--------------------------------------------------------------------------
| Top Agents
|--------------------------------------------------------------------------
*/

$topAgents = DB::table('agent_transactions')
->join('agents','agent_transactions.agent_id','=','agents.id')
->select(
'agents.id',
'agents.name',
DB::raw('SUM(agent_transactions.amount) as total')
)
->where('agent_transactions.branch_id',$branchId)
->groupBy('agents.id','agents.name')
->orderByDesc('total')
->limit(5)
->get();



/*
|--------------------------------------------------------------------------
| Top Trips
|--------------------------------------------------------------------------
*/

$topTrips = DB::table('bookings')
->join('trips','bookings.trip_id','=','trips.id')
->select(
'trips.id',
'trips.from_city',
'trips.to_city',
DB::raw('COUNT(bookings.id) as total')
)
->where('bookings.branch_id',$branchId)
->groupBy('trips.id','trips.from_city','trips.to_city')
->orderByDesc('total')
->limit(5)
->get();



/*
|--------------------------------------------------------------------------
| Latest Visas
|--------------------------------------------------------------------------
*/

$latestVisas = DB::table('visas')
->join('clients','visas.client_id','=','clients.id')
->select(
'clients.full_name',
'visas.sale_price',
'visas.status'
)
->where('visas.branch_id',$branchId)
->latest('visas.created_at')
->limit(5)
->get();



/*
|--------------------------------------------------------------------------
| Latest Bookings
|--------------------------------------------------------------------------
*/

$latestBookings = DB::table('bookings')
->join('clients','bookings.client_id','=','clients.id')
->select(
'clients.full_name',
'bookings.final_price',
'bookings.status'
)
->where('bookings.branch_id',$branchId)
->latest('bookings.created_at')
->limit(5)
->get();



/*
|--------------------------------------------------------------------------
| System Stats
|--------------------------------------------------------------------------
*/

$stats = [

'visas'=>DB::table('visas')->where('branch_id',$branchId)->count(),

'bookings'=>DB::table('bookings')->where('branch_id',$branchId)->count(),

'clients'=>DB::table('clients')->where('branch_id',$branchId)->count(),

'agents'=>DB::table('agents')->where('branch_id',$branchId)->count(),

'trips'=>DB::table('trips')->where('branch_id',$branchId)->count(),

'drivers'=>DB::table('drivers')->where('branch_id',$branchId)->count(),

];



return view('frontend.dashboard.index',compact(

'from','to',

'todayVisas',
'todayBookings',
'todayRequests',

'revenueByCurrency',
'expensesByCurrency',

'cashbox',

'clientsRemaining',
'agentsRemaining',

'visaProfit',
'bookingProfit',

'monthlyRevenue',
'visaStatus',

'topAgents',
'topTrips',

'latestVisas',
'latestBookings',

'stats'

));

}

}