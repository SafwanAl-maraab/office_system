<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BranchCashbox;
use App\Models\CashboxExchange;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Info;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $info->address = $arabic->utf8Glyphs($info->address);
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

}
