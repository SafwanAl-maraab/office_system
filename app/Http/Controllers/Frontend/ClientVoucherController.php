<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientVoucher;
use App\Models\Currency;
use App\Models\Invoice;
use App\Services\ClientBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientVoucherController extends Controller
{
    public function index(Request $request)
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $search =
            $request->search;

        /*
        |--------------------------------------------------------------------------
        | العملات
        |--------------------------------------------------------------------------
        */
        $currencies =
            Currency::where(
                'status',
                true
            )
                ->orderBy('code')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | السندات
        |--------------------------------------------------------------------------
        */
        $query =
            ClientVoucher::with([
                'client',
                'currency',
                'employee'
            ])
                ->withSum(
                    'allocations',
                    'amount'
                )
                ->withCount(
                    'allocations'
                )
                ->where(
                    'branch_id',
                    $branchId
                );

        if($search)
        {
            $query->whereHas(
                'client',
                function($q) use ($search){
                    $q->where(
                        'full_name',
                        'like',
                        "%{$search}%"
                    )
                        ->orWhere(
                            'phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'passport_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'national_id',
                            'like',
                            "%{$search}%"
                        );
                }
            );
        }

        $vouchers =
            $query
                ->latest()
                ->paginate(12);

        /*
        |--------------------------------------------------------------------------
        | الإحصائيات
        |--------------------------------------------------------------------------
        */
        $receiptCount =
            ClientVoucher::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'type',
                    'receipt'
                )
                ->count();

        $paymentCount =
            ClientVoucher::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'type',
                    'payment'
                )
                ->count();

        $vouchersCount =
            ClientVoucher::where(
                'branch_id',
                $branchId
            )
                ->count();

        $openVoucherCount = 0;

        foreach($vouchers as $voucher)
        {
            $allocated =
                $voucher->allocations_sum_amount
                ?? 0;

            $remaining =
                $voucher->amount
                -
                $allocated;

            if($remaining > 0)
            {
                $openVoucherCount++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | أرصدة السندات المفتوحة حسب العملة
        |--------------------------------------------------------------------------
        */
        $currencyBalances = [];

        foreach($currencies as $currency)
        {
            $balance = 0;

            $currencyVouchers =
                ClientVoucher::withSum(
                    'allocations',
                    'amount'
                )
                    ->where(
                        'branch_id',
                        $branchId
                    )
                    ->where(
                        'currency_id',
                        $currency->id
                    )
                    ->get();

            foreach(
                $currencyVouchers
                as
                $voucher
            ){
                $allocated =
                    $voucher->allocations_sum_amount
                    ?? 0;

                $balance +=
                    (
                        $voucher->amount
                        -
                        $allocated
                    );
            }

            if($balance > 0)
            {
                $currencyBalances[] = [
                    'currency_id' => $currency->id,
                    'code' => $currency->code,
                    'name' => $currency->name,
                    'symbol' => $currency->symbol,
                    'amount' => $balance
                ];
            }
        }

        return view(
            'frontend.client_vouchers.index',
            compact(
                'vouchers',
                'currencies',
                'search',
                'receiptCount',
                'paymentCount',
                'vouchersCount',
                'openVoucherCount',
                'currencyBalances'
            )
        );
    }

    /**
     * عرض تفاصيل السند الأساسية فقط بناءً على التحديث المحاسبي الجديد
     */
    public function show(ClientVoucher $voucher)
    {
        // تم الإبقاء فقط على العلاقات الأساسية اللازمة لمعلومات السند وعميله والموظف منشئ السند
        $voucher->load([
            'client',
            'currency',
            'employee',
        ]);

        return view(
            'frontend.client_vouchers.show',
            compact('voucher')
        );
    }
    public function store(Request $request)
    {
        $request->validate([


    'client_id'   => 'required|exists:clients,id',

    'currency_id' => 'required|exists:currencies,id',

    'type'        => 'required|in:receipt,payment',

    'amount'      => 'required|numeric|min:0.01',

    'notes'       => 'nullable|string'
]);

/*
|--------------------------------------------------------------------------
| منع الصرف إذا الرصيد غير كافٍ
|--------------------------------------------------------------------------
*/

if($request->type == 'payment')
{
    $balance =
        ClientBalanceService::getBalance(
            $request->client_id,
            $request->currency_id
        );

    if($balance < $request->amount)
    {
        return back()->with(
            'error',
            'رصيد العميل غير كافٍ'
        );
    }
}

/*
|--------------------------------------------------------------------------
| إنشاء السند
|--------------------------------------------------------------------------
*/

$voucher = ClientVoucher::create([

    'branch_id' =>
        auth()->user()->employee->branch_id,

    'client_id' =>
        $request->client_id,

    'currency_id' =>
        $request->currency_id,

    'created_by' =>
        auth()->user()->employee->id,

    'type' =>
        $request->type,

    'amount' =>
        $request->amount,

    'notes' =>
        $request->notes

]);

/*
|--------------------------------------------------------------------------
| حركة الرصيد
|--------------------------------------------------------------------------
*/

        $logAmount = in_array(
            $request->type,
            [
                'receipt',
                'opening_balance'
            ]
        )
            ? $voucher->amount
            : -$voucher->amount;

ClientBalanceService::addLog([

    'client_id' =>
        $voucher->client_id,

    'currency_id' =>
        $voucher->currency_id,

    'amount' =>
        $logAmount,

    'type' =>
        $voucher->type,

    'reference_type' =>
        'voucher',

    'reference_id' =>
        $voucher->id,

    'notes' =>
        $voucher->notes,

    'created_by' =>
        auth()->user()->employee->id

]);

return back()->with(
    'success',
    $request->type == 'receipt'
        ? 'تم إنشاء سند القبض بنجاح'
        : 'تم إنشاء سند الصرف بنجاح'
);


}

    /**
     * AJAX
     */
    public function clientInfo($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $client = Client::where(
            'branch_id',
            $branchId
        )->findOrFail($id);

        $currencies = Currency::where('status', true)
            ->orderBy('code')
            ->get();

        $balances = [];

        foreach ($currencies as $currency) {

            /*
            |--------------------------------------------------------------------------
            | الفواتير المستحقة
            |--------------------------------------------------------------------------
            */

            $invoiceDue = Invoice::where(
                'client_id',
                $client->id
            )
                ->where(
                    'currency_id',
                    $currency->id
                )
                ->where(
                    'is_refund',
                    false
                )
                ->sum('remaining_amount');

            /*
            |--------------------------------------------------------------------------
            | فواتير الاسترجاع
            |--------------------------------------------------------------------------
            */

            $refundDue = Invoice::where(
                'client_id',
                $client->id
            )
                ->where(
                    'currency_id',
                    $currency->id
                )
                ->where(
                    'is_refund',
                    true
                )
                ->sum('total_amount');

            /*
            |--------------------------------------------------------------------------
            | سندات القبض غير المستخدمة
            |--------------------------------------------------------------------------
            */

            $receipts = ClientVoucher::withSum(
                'allocations',
                'amount'
            )
                ->where('client_id', $client->id)
                ->where('currency_id', $currency->id)
                ->where('type', 'receipt')
                ->get();

            $availableCredit = 0;

            foreach ($receipts as $voucher) {

                $used = $voucher->allocations_sum_amount ?? 0;

                $availableCredit += max(
                    0,
                    $voucher->amount - $used
                );
            }

            /*
            |--------------------------------------------------------------------------
            | سندات الصرف غير المستخدمة
            |--------------------------------------------------------------------------
            */

            $payments = ClientVoucher::withSum(
                'allocations',
                'amount'
            )
                ->where('client_id', $client->id)
                ->where('currency_id', $currency->id)
                ->where('type', 'payment')
                ->get();

            $availableDebit = 0;

            foreach ($payments as $voucher) {

                $used = $voucher->allocations_sum_amount ?? 0;

                $availableDebit += max(
                    0,
                    $voucher->amount - $used
                );
            }

            /*
            |--------------------------------------------------------------------------
            | الحساب النهائي
            |--------------------------------------------------------------------------
            */

            $totalDue =
                $invoiceDue +
                $refundDue +
                $availableDebit;

            $totalCredit =
                $availableCredit;

            $netBalance =
                $totalDue -
                $totalCredit;

            $balances[] = [

                'currency_id' => $currency->id,

                'currency' => $currency->code,

                'due' => round($totalDue, 2),

                'credit' => round($totalCredit, 2),

                'balance' => round($netBalance, 2)

            ];
        }

        return response()->json([

            'client' => [

                'id' => $client->id,

                'name' => $client->full_name,

                'phone' => $client->phone

            ],

            'balances' => $balances

        ]);
    }


}
