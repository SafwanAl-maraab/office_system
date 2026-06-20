<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BranchCashbox;
use App\Models\CashboxExchange;
use App\Models\Currency;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Info;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic; // 💡 استدعاء المكتبة العربية


class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $from =
            $request->date_from
                ?: now()
                ->startOfMonth()
                ->toDateString();

        $to =
            $request->date_to
                ?: now()
                ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | الفواتير
        |--------------------------------------------------------------------------
        */

        $invoiceQuery =
            Invoice::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'is_refund',
                    false
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $salesTotal =
            (clone $invoiceQuery)
                ->sum('total_amount');

        $invoiceCount =
            (clone $invoiceQuery)
                ->count();

        $remainingInvoices =
            (clone $invoiceQuery)
                ->sum('remaining_amount');

        /*
        |--------------------------------------------------------------------------
        | المدفوعات
        |--------------------------------------------------------------------------
        */

        $paymentQuery =
            Payment::where(
                'branch_id',
                $branchId
            )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $paymentsTotal =
            (clone $paymentQuery)
                ->sum('amount');

        $paymentCount =
            (clone $paymentQuery)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | الإيرادات
        |--------------------------------------------------------------------------
        */

        $incomeQuery =
            Income::where(
                'branch_id',
                $branchId
            )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $incomeTotal =
            (clone $incomeQuery)
                ->sum('amount');

        $incomeCount =
            (clone $incomeQuery)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | المصروفات
        |--------------------------------------------------------------------------
        */

        $expenseQuery =
            Expense::where(
                'branch_id',
                $branchId
            )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $expenseTotal =
            (clone $expenseQuery)
                ->sum('amount');

        $expenseCount =
            (clone $expenseQuery)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | المصارفات
        |--------------------------------------------------------------------------
        */

        $exchangeCount =
            CashboxExchange::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'is_reversed',
                    false
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | صافي التدفق النقدي
        |--------------------------------------------------------------------------
        */

        $netCashFlow =
            (
                $paymentsTotal
                +
                $incomeTotal
            )
            -
            $expenseTotal;

        /*
        |--------------------------------------------------------------------------
        | الربح التشغيلي
        |--------------------------------------------------------------------------
        |
        | حالياً:
        | الإيرادات + المبيعات
        | ناقص المصروفات
        |
        | لاحقاً يمكن إضافة:
        | booking_cost
        | visa_cost
        | agent_cost
        |--------------------------------------------------------------------------
        */

        $operatingProfit =
            (
                $salesTotal
                +
                $incomeTotal
            )
            -
            $expenseTotal;

        /*
        |--------------------------------------------------------------------------
        | الخزائن الحالية
        |--------------------------------------------------------------------------
        */

        $cashboxes =
            BranchCashbox::with(
                'currency'
            )
                ->where(
                    'branch_id',
                    $branchId
                )
                ->orderBy(
                    'currency_id'
                )
                ->get();

        /*
|--------------------------------------------------------------------------
| إجمالي السيولة
|--------------------------------------------------------------------------
*/

        $totalLiquidity =
            $cashboxes
                ->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | معلومات إضافية للخزائن
        |--------------------------------------------------------------------------
        */

        foreach($cashboxes as $cashbox){

            $cashbox->transactions_count =
                \App\Models\CashboxTransaction::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'currency_id',
                        $cashbox->currency_id
                    )
                    ->count();

            $cashbox->last_transaction =
                \App\Models\CashboxTransaction::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'currency_id',
                        $cashbox->currency_id
                    )
                    ->latest()
                    ->first();

            $cashbox->percentage =
                $totalLiquidity > 0
                    ? round(
                    (
                        $cashbox->balance
                        /
                        $totalLiquidity
                    ) * 100,
                    2
                )
                    : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | آخر العمليات
        |--------------------------------------------------------------------------
        */

        $latestPayments =
            Payment::with(
                'client'
            )
                ->latest()
                ->limit(10)
                ->get();

        $latestExpenses =
            Expense::with(
                'currency'
            )
                ->latest()
                ->limit(10)
                ->get();

        $latestIncomes =
            Income::with(
                'currency'
            )
                ->latest()
                ->limit(10)
                ->get();


        /*
|--------------------------------------------------------------------------
| Chart 1
| الإيرادات والمصروفات حسب الأيام
|--------------------------------------------------------------------------
*/

        $incomeExpenseChart = [];

        $period =
            \Carbon\CarbonPeriod::create(
                $from,
                $to
            );

        foreach ($period as $date) {

            $day =
                $date->format('Y-m-d');

            $income =
                Income::where(
                    'branch_id',
                    $branchId
                )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('amount');

            $expense =
                Expense::where(
                    'branch_id',
                    $branchId
                )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('amount');

            $incomeExpenseChart[] = [

                'date'    => $day,

                'income'  => $income,

                'expense' => $expense

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Chart 2
        | المبيعات والمقبوضات
        |--------------------------------------------------------------------------
        */

        $salesPaymentsChart = [];

        foreach ($period as $date) {

            $day =
                $date->format('Y-m-d');

            $sales =
                Invoice::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'is_refund',
                        false
                    )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('total_amount');

            $payments =
                Payment::where(
                    'branch_id',
                    $branchId
                )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('amount');

            $salesPaymentsChart[] = [

                'date'      => $day,

                'sales'     => $sales,

                'payments'  => $payments

            ];
        }

        $latestExchanges =
            CashboxExchange::with([
                'fromCurrency',
                'toCurrency',
                'creator'
            ])
                ->where(
                    'branch_id',
                    $branchId
                )
                ->latest()
                ->limit(10)
                ->get();

        return view(
            'frontend.reports.financial',
            compact(

                'from',
                'to',

                'salesTotal',
                'paymentsTotal',

                'incomeTotal',
                'expenseTotal',

                'netCashFlow',
                'operatingProfit',

                'remainingInvoices',

                'invoiceCount',
                'paymentCount',

                'incomeCount',
                'expenseCount',

                'exchangeCount',

                'cashboxes',

                'latestPayments',
                'latestExpenses',
                'latestIncomes',
                'incomeExpenseChart',
                'salesPaymentsChart',
                'totalLiquidity',
                'latestExchanges',
            )
        );
    }

    public function exportPdf(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id ?? 1;
        $data = $this->buildReportData($request);
        $info = Info::where('branch_id', $branchId)->first();

        $viewData = array_merge($data, ['info' => $info]);

        // تهيئة مكتبة معالجة النصوص العربية
        $arabic = new \ArPHP\I18N\Arabic();

        // 💡 مصفوفة شاملة لكل العناوين الثابتة في التقرير
        $viewData['labels'] = [
            'title'             => $arabic->utf8Glyphs('التقرير المالي التنفيذي'),
            'sales'             => $arabic->utf8Glyphs('المبيعات'),
            'payments'          => $arabic->utf8Glyphs('المقبوضات'),
            'income'            => $arabic->utf8Glyphs('الإيرادات'),
            'expense'           => $arabic->utf8Glyphs('المصروفات'),
            'remaining'         => $arabic->utf8Glyphs('الذمم'),
            'summary_title'     => $arabic->utf8Glyphs('الملخص التنفيذي للمؤشرات'),
            'net_cash'          => $arabic->utf8Glyphs('صافي التدفق النقدي:'),
            'inv_count'         => $arabic->utf8Glyphs('عدد الفواتير:'),
            'pay_count'         => $arabic->utf8Glyphs('عدد المدفوعات:'),
            'inc_count'         => $arabic->utf8Glyphs('عدد الإيرادات:'),
            'exp_count'         => $arabic->utf8Glyphs('عدد المصروفات:'),
            'cashbox_section'   => $arabic->utf8Glyphs('أرصدة وحركات الخزائن الحالية'),
            'th_currency'       => $arabic->utf8Glyphs('العملة'),
            'th_balance'        => $arabic->utf8Glyphs('الرصيد الحالي'),
            'th_actions'        => $arabic->utf8Glyphs('عدد الحركات'),
            'income_section'    => $arabic->utf8Glyphs('آخر الإيرادات المسجلة'),
            'expense_section'   => $arabic->utf8Glyphs('آخر المصروفات المسجلة'),
            'th_date'           => $arabic->utf8Glyphs('التاريخ'),
            'th_desc'           => $arabic->utf8Glyphs('الوصف'),
            'th_amount'         => $arabic->utf8Glyphs('المبلغ'),
            'period'            => $arabic->utf8Glyphs('الفترة:'),
            'created_at'        => $arabic->utf8Glyphs('تاريخ الإنشاء:'),
            'no_data'           => $arabic->utf8Glyphs('لا توجد بيانات مسجلة في هذه الفترة'),
            'sign_admin'        => $arabic->utf8Glyphs('مسؤول النظام'),
            'sign_manager'      => $arabic->utf8Glyphs('مدير الفرع'),
        ];

        // تحويل النصوص القادمة ديناميكياً من قاعدة البيانات
        if (isset($viewData['cashboxes'])) {
            foreach ($viewData['cashboxes'] as $cashbox) {
                if (isset($cashbox->currency)) {
                    $cashbox->currency->name = $arabic->utf8Glyphs($cashbox->currency->name);
                }
            }
        }

        if (isset($viewData['latestIncomes'])) {
            foreach ($viewData['latestIncomes'] as $income) {
                $income->description = $arabic->utf8Glyphs($income->description);
            }
        }

        if (isset($viewData['latestExpenses'])) {
            foreach ($viewData['latestExpenses'] as $expense) {
                $expense->description = $arabic->utf8Glyphs($expense->description);
            }
        }

        if ($info) {
            $info->office_name = $arabic->utf8Glyphs($info->office_name);
            $info->a7ddress = $arabic->utf8Glyphs($info->address);
        }

        $pdf = Pdf::loadView('frontend.reports.pdf.financial', $viewData);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('financial-report-' . now()->format('YmdHis') . '.pdf');
    }
    private function buildReportData(
        Request $request
    )
    {
        /*
        كل الكود الموجود داخل index()
        */

        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $from =
            $request->date_from
                ?: now()
                ->startOfMonth()
                ->toDateString();

        $to =
            $request->date_to
                ?: now()
                ->toDateString();

        /*
        |--------------------------------------------------------------------------
        | الفواتير
        |--------------------------------------------------------------------------
        */

        $invoiceQuery =
            Invoice::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'is_refund',
                    false
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $salesTotal =
            (clone $invoiceQuery)
                ->sum('total_amount');

        $invoiceCount =
            (clone $invoiceQuery)
                ->count();

        $remainingInvoices =
            (clone $invoiceQuery)
                ->sum('remaining_amount');

        /*
        |--------------------------------------------------------------------------
        | المدفوعات
        |--------------------------------------------------------------------------
        */

        $paymentQuery =
            Payment::where(
                'branch_id',
                $branchId
            )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $paymentsTotal =
            (clone $paymentQuery)
                ->sum('amount');

        $paymentCount =
            (clone $paymentQuery)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | الإيرادات
        |--------------------------------------------------------------------------
        */

        $incomeQuery =
            Income::where(
                'branch_id',
                $branchId
            )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $incomeTotal =
            (clone $incomeQuery)
                ->sum('amount');

        $incomeCount =
            (clone $incomeQuery)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | المصروفات
        |--------------------------------------------------------------------------
        */

        $expenseQuery =
            Expense::where(
                'branch_id',
                $branchId
            )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                );

        $expenseTotal =
            (clone $expenseQuery)
                ->sum('amount');

        $expenseCount =
            (clone $expenseQuery)
                ->count();

        /*
        |--------------------------------------------------------------------------
        | المصارفات
        |--------------------------------------------------------------------------
        */

        $exchangeCount =
            CashboxExchange::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'is_reversed',
                    false
                )
                ->whereBetween(
                    'created_at',
                    [
                        $from.' 00:00:00',
                        $to.' 23:59:59'
                    ]
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | صافي التدفق النقدي
        |--------------------------------------------------------------------------
        */

        $netCashFlow =
            (
                $paymentsTotal
                +
                $incomeTotal
            )
            -
            $expenseTotal;

        /*
        |--------------------------------------------------------------------------
        | الربح التشغيلي
        |--------------------------------------------------------------------------
        |
        | حالياً:
        | الإيرادات + المبيعات
        | ناقص المصروفات
        |
        | لاحقاً يمكن إضافة:
        | booking_cost
        | visa_cost
        | agent_cost
        |--------------------------------------------------------------------------
        */

        $operatingProfit =
            (
                $salesTotal
                +
                $incomeTotal
            )
            -
            $expenseTotal;

        /*
        |--------------------------------------------------------------------------
        | الخزائن الحالية
        |--------------------------------------------------------------------------
        */

        $cashboxes =
            BranchCashbox::with(
                'currency'
            )
                ->where(
                    'branch_id',
                    $branchId
                )
                ->orderBy(
                    'currency_id'
                )
                ->get();

        /*
|--------------------------------------------------------------------------
| إجمالي السيولة
|--------------------------------------------------------------------------
*/

        $totalLiquidity =
            $cashboxes
                ->sum('balance');

        /*
        |--------------------------------------------------------------------------
        | معلومات إضافية للخزائن
        |--------------------------------------------------------------------------
        */

        foreach($cashboxes as $cashbox){

            $cashbox->transactions_count =
                \App\Models\CashboxTransaction::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'currency_id',
                        $cashbox->currency_id
                    )
                    ->count();

            $cashbox->last_transaction =
                \App\Models\CashboxTransaction::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'currency_id',
                        $cashbox->currency_id
                    )
                    ->latest()
                    ->first();

            $cashbox->percentage =
                $totalLiquidity > 0
                    ? round(
                    (
                        $cashbox->balance
                        /
                        $totalLiquidity
                    ) * 100,
                    2
                )
                    : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | آخر العمليات
        |--------------------------------------------------------------------------
        */

        $latestPayments =
            Payment::with(
                'client'
            )
                ->latest()
                ->limit(10)
                ->get();

        $latestExpenses =
            Expense::with(
                'currency'
            )
                ->latest()
                ->limit(10)
                ->get();

        $latestIncomes =
            Income::with(
                'currency'
            )
                ->latest()
                ->limit(10)
                ->get();


        /*
|--------------------------------------------------------------------------
| Chart 1
| الإيرادات والمصروفات حسب الأيام
|--------------------------------------------------------------------------
*/

        $incomeExpenseChart = [];

        $period =
            \Carbon\CarbonPeriod::create(
                $from,
                $to
            );

        foreach ($period as $date) {

            $day =
                $date->format('Y-m-d');

            $income =
                Income::where(
                    'branch_id',
                    $branchId
                )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('amount');

            $expense =
                Expense::where(
                    'branch_id',
                    $branchId
                )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('amount');

            $incomeExpenseChart[] = [

                'date'    => $day,

                'income'  => $income,

                'expense' => $expense

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Chart 2
        | المبيعات والمقبوضات
        |--------------------------------------------------------------------------
        */

        $salesPaymentsChart = [];

        foreach ($period as $date) {

            $day =
                $date->format('Y-m-d');

            $sales =
                Invoice::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'is_refund',
                        false
                    )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('total_amount');

            $payments =
                Payment::where(
                    'branch_id',
                    $branchId
                )
                    ->whereDate(
                        'created_at',
                        $day
                    )
                    ->sum('amount');

            $salesPaymentsChart[] = [

                'date'      => $day,

                'sales'     => $sales,

                'payments'  => $payments

            ];
        }

        $latestExchanges =
            CashboxExchange::with([
                'fromCurrency',
                'toCurrency',
                'creator'
            ])
                ->where(
                    'branch_id',
                    $branchId
                )
                ->latest()
                ->limit(10)
                ->get();

        return [
            'from' => $from,
            'to' => $to,

            'salesTotal' => $salesTotal,
            'invoiceCount' => $invoiceCount, // ✅ أضف هذا
            'remainingInvoices' => $remainingInvoices,

            'paymentsTotal' => $paymentsTotal,
            'paymentCount' => $paymentCount, // ✅ أضف هذا

            'incomeTotal' => $incomeTotal,
            'incomeCount' => $incomeCount, // ✅ أضف هذا

            'expenseTotal' => $expenseTotal,
            'expenseCount' => $expenseCount, // ✅ أضف هذا

            'netCashFlow' => $netCashFlow,
            'operatingProfit' => $operatingProfit,
            'cashboxes' => $cashboxes,

            'latestPayments' => $latestPayments,
            'latestExpenses' => $latestExpenses,
            'latestIncomes' => $latestIncomes,
            'latestExchanges' => $latestExchanges,
        ];
    }


    public function profitAnalysis(
        Request $request
    )
    {
        return view(

            'frontend.reports.profit-analysis',

            $this->buildProfitAnalysis(
                $request
            )

        );
    }





    public function profitAnalysisPdf(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id ?? 1;
        $data = $this->buildProfitAnalysis($request);

        // تهيئة مكتبة معالجة النصوص العربية
        $arabic = new Arabic();

        // 💡 مصفوفة العناوين الثابتة المترجمة للـ PDF
        $data['labels'] = [
            'title'            => $arabic->utf8Glyphs('تقرير تحليل الأرباح'),
            'from'             => $arabic->utf8Glyphs('من:'),
            'to'               => $arabic->utf8Glyphs('إلى:'),
            'sales'            => $arabic->utf8Glyphs('إجمالي Mبيعات'),
            'cost'             => $arabic->utf8Glyphs('إجمالي التكلفة'),
            'expected_profit'  => $arabic->utf8Glyphs('الربح المتوقع'),
            'confirmed_profit' => $arabic->utf8Glyphs('الربح المؤكد'),
            'remaining'        => $arabic->utf8Glyphs('المتبقي'),
            'section_title'    => $arabic->utf8Glyphs('تحليل الأرباح حسب النشاط'),
            'th_activity'      => $arabic->utf8Glyphs('النشاط'),
            'th_count'         => $arabic->utf8Glyphs('عدد العمليات'),
            'activity_visas'   => $arabic->utf8Glyphs('التأشيرات'),
            'activity_bookings'=> $arabic->utf8Glyphs('الحجوزات'),
            'activity_services'=> $arabic->utf8Glyphs('الطلبات'),
        ];

        // تحويل البيانات الديناميكية القادمة من قاعدة البيانات (اسم المكتب)
        if (isset($data['info'])) {
            $data['info']->office_name = $arabic->utf8Glyphs($data['info']->office_name);
        }

        // شحن البيانات للملف
        $pdf = Pdf::loadView('frontend.reports.profit.pdf', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('profit-analysis-' . now()->format('YmdHis') . '.pdf');
    }

    private function buildProfitAnalysis(
        Request $request
    )
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;


        $from =
            $request->date_from
                ?: now()->startOfMonth()->toDateString();

        $to =
            $request->date_to
                ?: now()->toDateString();

        $currencies =
            Currency::where(
                'status',
                1
            )->orderBy('name')->get();

        $currencyId =
            $request->currency_id;
        /*
        |--------------------------------------------------------------------------
        | استعلام أساسي
        |--------------------------------------------------------------------------
        */

        $baseInvoices =
            Invoice::where(
                'branch_id',
                $branchId
            );

        if($currencyId)
        {
            $baseInvoices->where(
                'currency_id',
                $currencyId
            );
        }

        $baseInvoices
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
            ->whereBetween(
                'created_at',
                [
                    $from.' 00:00:00',
                    $to.' 23:59:59'
                ]
            );
        /*
        |--------------------------------------------------------------------------
        | دالة تحليل نوع واحد
        |--------------------------------------------------------------------------
        */

        $calculate = function($referenceType) use ($baseInvoices){

            $invoices =
                (clone $baseInvoices)
                    ->where(
                        'reference_type',
                        $referenceType
                    )
                    ->get();

            $count = 0;

            $sales = 0;

            $cost = 0;

            $expectedProfit = 0;

            $confirmedProfit = 0;

            $remaining = 0;

            foreach($invoices as $invoice){

                $count++;

                $sales +=
                    $invoice->total_amount;

                $cost +=
                    $invoice->cost;

                $remaining +=
                    $invoice->remaining_amount;

                /*
                |--------------------------------------------------------------------------
                | الربح المتوقع
                |--------------------------------------------------------------------------
                */

                $expectedProfit +=
                    (
                        $invoice->total_amount
                        -
                        $invoice->cost
                    );

                /*
                |--------------------------------------------------------------------------
                | الربح المؤكد
                |--------------------------------------------------------------------------
                */

                if(
                    $invoice->total_amount > 0
                ){

                    $ratio =
                        $invoice->paid_amount
                        /
                        $invoice->total_amount;

                    $recoveredCost =
                        $invoice->cost
                        *
                        $ratio;

                    $confirmedProfit +=
                        (
                            $invoice->paid_amount
                            -
                            $recoveredCost
                        );
                }
            }

            return [

                'count' =>
                    $count,

                'sales' =>
                    $sales,

                'cost' =>
                    $cost,

                'expected_profit' =>
                    $expectedProfit,

                'confirmed_profit' =>
                    $confirmedProfit,

                'remaining' =>
                    $remaining,
            ];
        };

        /*
        |--------------------------------------------------------------------------
        | تحليل الأنشطة
        |--------------------------------------------------------------------------
        */

        $analysis = [

            'visas' =>

                $calculate(
                    'visa'
                ),

            'bookings' =>

                $calculate(
                    'booking'
                ),

            'services' =>

                $calculate(
                    'request'
                ),
        ];

        /*
        |--------------------------------------------------------------------------
        | إجماليات عامة
        |--------------------------------------------------------------------------
        */

        $totals = [

            'sales' =>

                collect($analysis)
                    ->sum('sales'),

            'cost' =>

                collect($analysis)
                    ->sum('cost'),

            'expected_profit' =>

                collect($analysis)
                    ->sum('expected_profit'),

            'confirmed_profit' =>

                collect($analysis)
                    ->sum('confirmed_profit'),

            'remaining' =>

                collect($analysis)
                    ->sum('remaining'),

            'count' =>

                collect($analysis)
                    ->sum('count'),
        ];
        $info = Info::where('branch_id', $branchId)->first();

        $selectedCurrency = null;

        if($currencyId)
        {
            $selectedCurrency =
                Currency::find(
                    $currencyId
                );
        }
        return [

            'analysis' => $analysis,

            'totals' => $totals,

            'from' => $from,

            'to' => $to,

            'info' => $info,

            'currencies' =>
                $currencies,

            'currencyId' =>
                $currencyId,

            'selectedCurrency' =>
                $selectedCurrency

        ];
    }

}
