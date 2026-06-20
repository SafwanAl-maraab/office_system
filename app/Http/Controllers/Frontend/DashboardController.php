<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\AgentTransaction;
use App\Models\Booking;
use App\Models\BranchCashbox;
use App\Models\CashboxExchange;
use App\Models\Client;
use App\Models\ClientVoucher;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Info;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Visa;
use App\Models\Request as RequestModel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $branchId = auth()->user()
            ->employee
            ->branch_id;


/*
--------------------------------------------------------------------------
معلومات المكتب
--------------------------------------------------------------------------
*/

$officeInfo = Info::where(
    'branch_id',
    $branchId
)->first();

/*
--------------------------------------------------------------------------
KPIs
--------------------------------------------------------------------------
*/

$kpis = [

    'clients' => Client::where(
        'branch_id',
        $branchId
    )->count(),

    'agents' => Agent::where(
        'branch_id',
        $branchId
    )->count(),

    'open_invoices' => Invoice::query()

        ->where(
            'branch_id',
            $branchId
        )

        ->where(
            'is_refund',
            false
        )

        ->whereNotIn(
            'status',
            [
                'cancelled',
                'rejected'
            ]
        )

        ->where(
            'remaining_amount',
            '>',
            0
        )

        ->count(),

    'today_operations' =>

        Visa::where(
            'branch_id',
            $branchId
        )
        ->whereDate(
            'created_at',
            today()
        )
        ->count()

        +

        Booking::where(
            'branch_id',
            $branchId
        )
        ->whereDate(
            'created_at',
            today()
        )
        ->count()

        +

        RequestModel::where(
            'branch_id',
            $branchId
        )
        ->whereDate(
            'created_at',
            today()
        )
        ->count()
];

/*
--------------------------------------------------------------------------
الخزائن
--------------------------------------------------------------------------
*/

$cashboxes = BranchCashbox::with(
    'currency'
)

->where(
    'branch_id',
    $branchId
)

->orderByDesc(
    'balance'
)

->get();

/*
--------------------------------------------------------------------------
التحصيلات الفعلية
من الفواتير فقط
يستثني الملغي والمرفوض والمسترجع
--------------------------------------------------------------------------
*/

$collections = Invoice::query()

    ->selectRaw('
        currency_id,
        SUM(paid_amount) as total
    ')

    ->where(
        'branch_id',
        $branchId
    )

    ->where(
        'is_refund',
        false
    )

    ->whereNotIn(
        'status',
        [
            'cancelled',
            'rejected'
        ]
    )

    ->groupBy(
        'currency_id'
    )

    ->with(
        'currency'
    )->get();


    /*
--------------------------------------------------------------------------
الإيرادات
--------------------------------------------------------------------------
*/

$incomes = Income::query()


->selectRaw('
    currency_id,
    SUM(amount) as total
')

->where(
    'branch_id',
    $branchId
)

->groupBy(
    'currency_id'
)

->with(
    'currency'
)

->get();


    /*
--------------------------------------------------------------------------
 المصروفات
--------------------------------------------------------------------------
*/

$expenses = Expense::query()


->selectRaw('
    currency_id,
    SUM(amount) as total
')

->where(
    'branch_id',
    $branchId
)

->groupBy(
    'currency_id'
)

->with(
    'currency'
)

->get();


    /*
--------------------------------------------------------------------------
المتبقي عند العملاء
--------------------------------------------------------------------------
*/

$clientReceivables = Invoice::query()


->selectRaw('
    currency_id,
    SUM(remaining_amount) as total
')

->where(
    'branch_id',
    $branchId
)

->where(
    'is_refund',
    false
)

->whereNotIn(
    'status',
    [
        'cancelled',
        'rejected'
    ]
)

->where(
    'remaining_amount',
    '>',
    0
)

->groupBy(
    'currency_id'
)

->with(
    'currency'
)

->get();


    /*
--------------------------------------------------------------------------
ذمم الوكلاء
--------------------------------------------------------------------------
*/

$agentBalances = AgentTransaction::query()


->selectRaw('
    currency_id,
    SUM(amount) as total
')

->where(
    'branch_id',
    $branchId
)

->groupBy(
    'currency_id'
)

->with(
    'currency'
)

->get();


    /*
--------------------------------------------------------------------------
إحصائيات اليوم
--------------------------------------------------------------------------
*/

$todayStats = [


'visas' =>

    Visa::where(
        'branch_id',
        $branchId
    )

    ->whereDate(
        'created_at',
        today()
    )

    ->count(),

'bookings' =>

    Booking::where(
        'branch_id',
        $branchId
    )

    ->whereDate(
        'created_at',
        today()
    )

    ->count(),

'requests' =>

    RequestModel::where(
        'branch_id',
        $branchId
    )

    ->whereDate(
        'created_at',
        today()
    )

    ->count(),

/*
--------------------------------------------------------------------------
عدد الدفعات اليوم
--------------------------------------------------------------------------
*/

'payments' =>

    Payment::where(
        'branch_id',
        $branchId
    )

    ->whereDate(
        'created_at',
        today()
    )

    ->count(),


];

       /*
--------------------------------------------------------------------------
آخر العمليات
--------------------------------------------------------------------------
*/

$recentPayments = Payment::query()


->where(
    'branch_id',
    $branchId
)

->latest()

->take(5)

->get();


$recentExpenses = Expense::query()


->where(
    'branch_id',
    $branchId
)

->latest()

->take(5)

->get();


$recentIncomes = Income::query()


->where(
    'branch_id',
    $branchId
)

->latest()

->take(5)

->get();


    /*
--------------------------------------------------------------------------
Alerts
--------------------------------------------------------------------------
*/

$alerts = [];

/*
--------------------------------------------------------------------------
فواتير غير مسددة
--------------------------------------------------------------------------
*/

$unpaidInvoices = Invoice::query()


->where(
    'branch_id',
    $branchId
)

->where(
    'is_refund',
    false
)

->whereNotIn(
    'status',
    [
        'cancelled',
        'rejected'
    ]
)

->where(
    'remaining_amount',
    '>',
    0
)

->count();


if($unpaidInvoices > 0)
{
    $alerts[] = [


    'icon' => '⚠️',

    'color' => 'yellow',

    'title' => 'فواتير غير مسددة',

    'description' =>
        $unpaidInvoices .
        ' فاتورة تحتاج متابعة'
];


}

/*
--------------------------------------------------------------------------
خزائن منخفضة
--------------------------------------------------------------------------
*/

foreach($cashboxes as $cashbox)
{
    if($cashbox->balance <= 100)
    {
        $alerts[] = [


        'icon' => '🚨',

        'color' => 'red',

        'title' =>
            'رصيد خزنة منخفض',

        'description' =>
            $cashbox->currency->code .
            ' أقل من الحد الأدنى'
    ];
}


}

    /*
--------------------------------------------------------------------------
عملاء لديهم ذمم كبيرة
--------------------------------------------------------------------------
*/

    $highDebts = Invoice::query()


->where(
    'branch_id',
    $branchId
)

->where(
    'is_refund',
    false
)

->whereNotIn(
    'status',
    [
        'cancelled',
        'rejected'
    ]
)

->where(
    'remaining_amount',
    '>',
    1000
)

->count();


if($highDebts > 0)
{
    $alerts[] = [


    'icon' => '👤',

    'color' => 'orange',

    'title' =>
        'عملاء لديهم مديونية مرتفعة',

    'description' =>
        $highDebts .
        ' حالة تحتاج متابعة'
];


}

/*
--------------------------------------------------------------------------
Timeline
--------------------------------------------------------------------------
*/

$timeline = collect();

/*
--------------------------------------------------------------------------
Payments
--------------------------------------------------------------------------
*/

foreach(


Payment::with([
    'client',
    'currency'
])

->where(
    'branch_id',
    $branchId
)

->latest()

->take(10)

->get()

as $item


)
{
    $timeline->push([


    'date' =>
        $item->created_at,

    'icon' =>

        $item->payment_method === 'refund'

            ? '↩️'

            : '💵',

    'title' =>

        $item->payment_method === 'refund'

            ? 'مسترجع نقدي'

            : 'دفعة فاتورة',

    'description' =>

        $item->client?->full_name,

    'amount' =>

        $item->amount,

    'currency' =>

        $item->currency?->code

]);


}

      /*
--------------------------------------------------------------------------
Expenses
--------------------------------------------------------------------------
*/

foreach(


Expense::with([
    'currency'
])

->where(
    'branch_id',
    $branchId
)

->latest()

->take(10)

->get()

as $item


)
{
    $timeline->push([


    'date' =>
        $item->created_at,

    'icon' =>
        '📉',

    'title' =>
        'مصروف',

    'description' =>
        $item->description,

    'amount' =>
        $item->amount,

    'currency' =>
        $item->currency?->code

]);


}

/*
--------------------------------------------------------------------------
Incomes
--------------------------------------------------------------------------
*/

foreach(


Income::with([
    'currency'
])

->where(
    'branch_id',
    $branchId
)

->latest()

->take(10)

->get()

as $item


)
{
    $timeline->push([


    'date' =>
        $item->created_at,

    'icon' =>
        '📈',

    'title' =>
        'إيراد',

    'description' =>
        $item->description,

    'amount' =>
        $item->amount,

    'currency' =>
        $item->currency?->code

]);


}

/*
--------------------------------------------------------------------------
Client Vouchers
--------------------------------------------------------------------------
*/

foreach(


ClientVoucher::with([
    'client',
    'currency'
])

->where(
    'branch_id',
    $branchId
)

->latest()

->take(10)

->get()

as $item


)
{
    $timeline->push([


    'date' =>
        $item->created_at,

    'icon' =>

        $item->type === 'receipt'

            ? '🟢'

            : '🔴',

    'title' =>

        $item->type === 'receipt'

            ? 'سند قبض'

            : 'سند صرف',

    'description' =>
        $item->client?->full_name,

    'amount' =>
        $item->amount,

    'currency' =>
        $item->currency?->code

]);


}

/*
--------------------------------------------------------------------------
Currency Exchanges
--------------------------------------------------------------------------
*/

foreach(


CashboxExchange::where(
    'branch_id',
    $branchId
)

->latest()

->take(10)

->get()

as $item


)
{
    $timeline->push([


    'date' =>
        $item->created_at,

    'icon' =>
        '🔄',

    'title' =>
        'مصارفة',

    'description' =>

        'تحويل '

        .

        number_format(
            $item->from_amount,
            2
        ),

    'amount' =>
        $item->to_amount,

    'currency' =>
        null

]);


}

/*
--------------------------------------------------------------------------
ترتيب العمليات
--------------------------------------------------------------------------
*/

$timeline =


$timeline

    ->sortByDesc(
        'date'
    )

    ->take(20)

    ->values();


/*
--------------------------------------------------------------------------
Charts
--------------------------------------------------------------------------
*/

/*
--------------------------------------------------------------------------
حالة التأشيرات
--------------------------------------------------------------------------
*/

$visaChart = [


'pending' =>

    Visa::where(
        'branch_id',
        $branchId
    )
    ->where(
        'status',
        'pending'
    )
    ->count(),

'issued' =>

    Visa::where(
        'branch_id',
        $branchId
    )
    ->where(
        'status',
        'issued'
    )
    ->count(),

'cancelled' =>

    Visa::where(
        'branch_id',
        $branchId
    )
    ->where(
        'status',
        'cancelled'
    )
    ->count(),


];

/*
--------------------------------------------------------------------------
توزيع العمليات
--------------------------------------------------------------------------
*/

$operationsChart = [


'visas' =>

    Visa::where(
        'branch_id',
        $branchId
    )->count(),

'bookings' =>

    Booking::where(
        'branch_id',
        $branchId
    )->count(),

'requests' =>

    RequestModel::where(
        'branch_id',
        $branchId
    )->count(),


];

 /*
--------------------------------------------------------------------------
آخر 12 شهر تحصيلات
المصدر الحقيقي = الفواتير
--------------------------------------------------------------------------
*/

$monthlyCollections = [];

for($i = 11; $i >= 0; $i--)
{
    $month =
        now()
            ->copy()
            ->subMonths($i);


$monthlyCollections[] = [

    'month' =>
        $month->format('M'),

    'total' =>

        Invoice::where(
            'branch_id',
            $branchId
        )

        ->where(
            'is_refund',
            false
        )

        ->whereNotIn(
            'status',
            [
                'cancelled',
                'rejected'
            ]
        )

        ->whereYear(
            'created_at',
            $month->year
        )

        ->whereMonth(
            'created_at',
            $month->month
        )

        ->sum(
            'paid_amount'
        )
];


}

/*
--------------------------------------------------------------------------
الأرباح المؤكدة حسب العملة
--------------------------------------------------------------------------
*/

$profitCards = [];

$currencies = Currency::all();

foreach($currencies as $currency)
{
    $confirmedProfit = 0;


$invoices =

    Invoice::where(
        'branch_id',
        $branchId
    )

    ->where(
        'currency_id',
        $currency->id
    )

    ->where(
        'is_refund',
        false
    )

    ->whereNotIn(
        'status',
        [
            'cancelled',
            'rejected'
        ]
    )

    ->get();

foreach($invoices as $invoice)
{
    if(
        $invoice->total_amount <= 0
    )
    {
        continue;
    }

    $ratio =

        $invoice->paid_amount

        /

        $invoice->total_amount;

    $recoveredCost =

        $invoice->cost

        *

        $ratio;

    $confirmedProfit +=

        $invoice->paid_amount

        -

        $recoveredCost;
}

if($confirmedProfit != 0)
{
    $profitCards[] = [

        'currency' =>
            $currency,

        'profit' =>
            round(
                $confirmedProfit,
                2
            )
    ];
}


}

/*
--------------------------------------------------------------------------
الأرباح الشهرية
--------------------------------------------------------------------------
*/

$monthlyProfit = [];

for($i = 11; $i >= 0; $i--)
{
    $month =
        now()
            ->copy()
            ->subMonths($i);


$profit = 0;

$invoices =

    Invoice::where(
        'branch_id',
        $branchId
    )

    ->whereYear(
        'created_at',
        $month->year
    )

    ->whereMonth(
        'created_at',
        $month->month
    )

    ->where(
        'is_refund',
        false
    )

    ->whereNotIn(
        'status',
        [
            'cancelled',
            'rejected'
        ]
    )

    ->get();

foreach($invoices as $invoice)
{
    if(
        $invoice->total_amount <= 0
    )
    {
        continue;
    }

    $ratio =

        $invoice->paid_amount

        /

        $invoice->total_amount;

    $profit +=

        $invoice->paid_amount

        -

        (
            $invoice->cost
            *
            $ratio
        );
}

$monthlyProfit[] = [

    'month' =>
        $month->format('M'),

    'total' =>
        round(
            $profit,
            2
        )
];


}

/*
--------------------------------------------------------------------------
المسترجعات
--------------------------------------------------------------------------
*/

$refunds =


Invoice::selectRaw('
    currency_id,
    SUM(total_amount) as total
')

->where(
    'branch_id',
    $branchId
)

->where(
    'is_refund',
    true
)

->groupBy(
    'currency_id'
)

->with(
    'currency'
)

->get();


/*
--------------------------------------------------------------------------
صافي التحصيلات
--------------------------------------------------------------------------
*/

$netCollections = [];

foreach($collections as $collection)
{
    $refund =


    $refunds

        ->where(
            'currency_id',
            $collection->currency_id
        )

        ->first();

$netCollections[] = [

    'currency' =>

        $collection->currency,

    'collections' =>

        $collection->total,

    'refunds' =>

        $refund->total ?? 0,

    'net' =>

        $collection->total

        -

        ($refund->total ?? 0)
];


}
return view(
    'frontend.dashboard.index',
    compact(

        'officeInfo',

        'kpis',

        'cashboxes',

        'collections',

        'incomes',

        'expenses',

        'clientReceivables',

        'agentBalances',

        'todayStats',

        'recentPayments',

        'recentExpenses',

        'recentIncomes',

        'alerts',

        'timeline',

        'visaChart',

        'operationsChart',

        'monthlyCollections',

        'profitCards',

        'refunds',

        'netCollections',
        'monthlyProfit',
    )
);
    }
}
