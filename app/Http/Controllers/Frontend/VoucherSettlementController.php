<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientBalanceLog;
use App\Models\Invoice;
use App\Models\Currency;
use App\Models\ClientVoucher;
use App\Models\ExchangeRate;

use App\Models\Visa;
use App\Services\ClientBalanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Payment;
use App\Models\VoucherAllocation;
use App\Models\CurrencyExchangeAllocation;

class VoucherSettlementController extends Controller
{

public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        // 1. نبدأ بإنشاء الباني (Query Builder) وتخزينه في $query بشكل صحيح
        $query = Invoice::query()
            ->with([
                'client',
                'currency',
                'booking',
                'visa',
                'request'
            ])
            ->where('remaining_amount', '>', 0)
            ->where(function ($q) {
                $q->whereDoesntHave('booking')
                    ->orWhereHas('booking', function ($booking) {
                        $booking->where('status', '!=', 'cancelled');
                    });
            })
            ->where(function ($q) {
                $q->whereDoesntHave('visa')
                    ->orWhereHas('visa', function ($visa) {
                        $visa->where('status', '!=', 'cancelled');
                    });
            })
            ->where(function ($q) {
                $q->whereDoesntHave('request')
                    ->orWhereHas('request', function ($request) {
                        $request->where('status', '!=', 'cancelled');
                    });
            });

        /*
        |--------------------------------------------------------------------------
        | البحث (يعمل الآن بسلاسة لأن $query معرّف)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($client) use ($search) {
                    $client->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('passport_number', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%");
                })
                    ->orWhere('id', $search);
            });
        }

        // 2. نقوم بعمل الترقيم (Pagination) من الـ Builder بعد تطبيق شروط البحث
        $invoices = $query->latest()->paginate(12);

        // 3. معالجة البيانات (Transformation)
        $invoices->getCollection()->transform(function ($invoice) {

            /* الرصيد المتاح */
            $balances = ClientBalanceService::getBalances($invoice->client_id);
            $canSettle = false;

            foreach ($balances as $balance) {
                if ($balance['balance'] > 0) {
                    $canSettle = true;
                    break;
                }
            }

            /* عنوان ورقم العملية بناءً على الـ polymorphic type */
            $operationTitle = 'فاتورة';
            $operationNumber = $invoice->reference_id;

            if ($invoice->reference_type == 'booking' && $invoice->booking) {
                $operationTitle = 'حجز';
                $operationNumber = $invoice->booking->id;
            } elseif ($invoice->reference_type == 'visa' && $invoice->visa) {
                $operationTitle = 'تأشيرة';
                $operationNumber = $invoice->visa->visa_number ?? $invoice->visa->id;
            } elseif ($invoice->reference_type == 'request' && $invoice->request) {
                $operationTitle = 'طلب';
                $operationNumber = $invoice->request->request_number ?? $invoice->request->id;
            }

            /* المسترجعات */
            $refundAmount = $invoice->refundInvoices()->sum('total_amount');

            $invoice->operation_title = $operationTitle;
            $invoice->operation_number = $operationNumber;
            $invoice->refund_amount = $refundAmount;
            $invoice->balances = $balances;
            $invoice->can_settle = $canSettle;

            return $invoice;
        });

        return view('frontend.voucher_settlements.index', compact('invoices'));
    }
    public function clientData($id)
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;


$client = Client::where(
    'branch_id',
    $branchId
)->findOrFail($id);

/*
|--------------------------------------------------------------------------
| الأرصدة
|--------------------------------------------------------------------------
*/

$balances =
    ClientBalanceService::getBalances(
        $client->id
    );

/*
|--------------------------------------------------------------------------
| الفواتير المفتوحة
|--------------------------------------------------------------------------
*/

$invoices = Invoice::with([
    'currency'
])
->where(
    'client_id',
    $client->id
)
->where(
    'is_refund',
    false
)
->whereIn(
    'status',
    [
        'unpaid',
        'partial'
    ]
)
->where(
    'remaining_amount',
    '>',
    0
)
->latest()
->get()
->map(function ($invoice){

    return [

        'id' =>
            $invoice->id,

        'reference_type' =>
            $invoice->reference_type,

        'reference_id' =>
            $invoice->reference_id,

        'total_amount' =>
            $invoice->total_amount,

        'paid_amount' =>
            $invoice->paid_amount,

        'remaining_amount' =>
            $invoice->remaining_amount,

        'currency_id' =>
            $invoice->currency_id,

        'currency' => [

            'id' =>
                $invoice->currency->id,

            'code' =>
                $invoice->currency->code,

            'name' =>
                $invoice->currency->name

        ],

        'status' =>
            $invoice->status

    ];

})
->values();

return response()->json([

    'client' => [

        'id' =>
            $client->id,

        'name' =>
            $client->full_name,

        'phone' =>
            $client->phone

    ],

    'balances' =>
        $balances,

    'invoices' =>
        $invoices

]);


}



    public function settle(Request $request)
    {
        $request->validate([


    'client_id'          => 'required|exists:clients,id',

    'invoice_id'         => 'required|exists:invoices,id',

    'source_currency_id' => 'required|exists:currencies,id',

    'amount'             => 'required|numeric|min:0.01',

    'notes'              => 'nullable|string'
]);

DB::beginTransaction();

try {

    $employee =
        auth()->user()->employee;

    $invoice =
        Invoice::with('currency')
            ->findOrFail(
                $request->invoice_id
            );

    /*
    |--------------------------------------------------------------------------
    | حماية العميل
    |--------------------------------------------------------------------------
    */

    if(
        $invoice->client_id
        !=
        $request->client_id
    ){
        throw new \Exception(
            'الفاتورة لا تخص العميل'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | حماية الفاتورة
    |--------------------------------------------------------------------------
    */


    // 1. التحقق من حالة الحجز (Booking)
    if ($invoice->reference_type === 'booking' && $invoice->booking && $invoice->booking->status === 'cancelled') {
        throw new \Exception('لا يمكن تسوية فاتورة حجز ملغي');
    }

// 2. التحقق من حالة التأشيرة (Visa)
    if ($invoice->reference_type === 'visa' && $invoice->visa && $invoice->visa->status === 'cancelled') {
        throw new \Exception('لا يمكن تسوية فاتورة تأشيرة ملغية');
    }

// 3. التحقق من حالة الطلب (Request)
    if ($invoice->reference_type === 'request' && $invoice->request && $invoice->request->status === 'cancelled') {
        throw new \Exception('لا يمكن تسوية فاتورة طلب ملغي');
    }


    if(
        $invoice->remaining_amount <= 0
    ){
        throw new \Exception(
            'الفاتورة مسددة بالكامل'
        );
    }

    $sourceCurrencyId =
        (int)$request->source_currency_id;

    $targetCurrencyId =
        (int)$invoice->currency_id;

    /*
    |--------------------------------------------------------------------------
    | نفس العملة
    |--------------------------------------------------------------------------
    */

    if(
        $sourceCurrencyId
        ==
        $targetCurrencyId
    ){

        $balance =
            ClientBalanceService::getBalance(
                $request->client_id,
                $sourceCurrencyId
            );

        if(
            $balance <
            $request->amount
        ){
            throw new \Exception(
                'الرصيد غير كافٍ'
            );
        }

        $settlementAmount =
            min(
                $request->amount,
                $invoice->remaining_amount
            );

        ClientBalanceLog::create([

            'client_id' =>
                $request->client_id,

            'currency_id' =>
                $sourceCurrencyId,

            'amount' =>
                -$settlementAmount,

            'type' =>
                'settlement',

            'reference_type' =>
                'invoice',

            'reference_id' =>
                $invoice->id,

            'notes' =>
                $request->notes,

            'created_by' =>
                $employee->id

        ]);

        Payment::create([

            'branch_id' =>
                $employee->branch_id,

            'client_id' =>
                $request->client_id,

            'invoice_id' =>
                $invoice->id,

            'amount' =>
                $settlementAmount,

            'currency_id' =>
                $invoice->currency_id,

            'payment_method' =>
                'client_balance',

            'created_by' =>
                $employee->id

        ]);

        $invoice->paid_amount +=
            $settlementAmount;

        $invoice->remaining_amount -=
            $settlementAmount;
    }

    /*
    |--------------------------------------------------------------------------
    | عملة مختلفة
    |--------------------------------------------------------------------------
    */

    else {

        $rate =
            ExchangeRate::where(
                'branch_id',
                $employee->branch_id
            )
                ->where(
                    'from_currency_id',
                    $sourceCurrencyId
                )
                ->where(
                    'to_currency_id',
                    $targetCurrencyId
                )
                ->latest('rate_date')
                ->first();

        $direction = 'direct';

        if(!$rate)
        {
            $rate =
                ExchangeRate::where(
                    'branch_id',
                    $employee->branch_id
                )
                    ->where(
                        'from_currency_id',
                        $targetCurrencyId
                    )
                    ->where(
                        'to_currency_id',
                        $sourceCurrencyId
                    )
                    ->latest('rate_date')
                    ->first();


$direction = 'reverse';


}

        if(!$rate)
        {
            throw new \Exception(
                'لا يوجد سعر صرف'
            );
        }

        /*
        135 YER = 1 SAR
        */

        if(
            $direction === 'direct'
        ){
            $targetAmount =
                $request->amount
                /
                $rate->rate;
        }
        else
        {
            $targetAmount =
                $request->amount
                *
                $rate->rate;
        }


        if(
            $targetAmount >
            $invoice->remaining_amount
        ){

            $targetAmount =
                $invoice->remaining_amount;

            if(
                $direction === 'direct'
            ){
                $requestAmount =
                    $targetAmount
                    *
                    $rate->rate;
            }
            else
            {
                $requestAmount =
                    $targetAmount
                    /
                    $rate->rate;
            }


        }else{

            $requestAmount =
                $request->amount;
        }

        $balance =
            ClientBalanceService::getBalance(
                $request->client_id,
                $sourceCurrencyId
            );

        if(
            $balance <
            $requestAmount
        ){
            throw new \Exception(
                'الرصيد غير كافٍ'
            );
        }

        /*
        خروج من العملة الأصلية
        */

        ClientBalanceLog::create([

            'client_id' =>
                $request->client_id,

            'currency_id' =>
                $sourceCurrencyId,

            'amount' =>
                -$requestAmount,

            'type' =>
                'exchange_out',

            'reference_type' =>
                'invoice',

            'reference_id' =>
                $invoice->id,

            'notes' =>
                $request->notes,

            'created_by' =>
                $employee->id

        ]);

        /*
        دخول للعملة الجديدة
        */

        ClientBalanceLog::create([

            'client_id' =>
                $request->client_id,

            'currency_id' =>
                $targetCurrencyId,

            'amount' =>
                $targetAmount,

            'type' =>
                'exchange_in',

            'reference_type' =>
                'invoice',

            'reference_id' =>
                $invoice->id,

            'notes' =>
                $request->notes,

            'created_by' =>
                $employee->id

        ]);

        /*
        تسوية الفاتورة
        */

        ClientBalanceLog::create([

            'client_id' =>
                $request->client_id,

            'currency_id' =>
                $targetCurrencyId,

            'amount' =>
                -$targetAmount,

            'type' =>
                'settlement',

            'reference_type' =>
                'invoice',

            'reference_id' =>
                $invoice->id,

            'notes' =>
                $request->notes,

            'created_by' =>
                $employee->id

        ]);

        Payment::create([

            'branch_id' =>
                $employee->branch_id,

            'client_id' =>
                $request->client_id,

            'invoice_id' =>
                $invoice->id,

            'amount' =>
                $targetAmount,

            'currency_id' =>
                $targetCurrencyId,

            'payment_method' =>
                'currency_exchange',

            'created_by' =>
                $employee->id

        ]);

        $invoice->paid_amount +=
            $targetAmount;

        $invoice->remaining_amount -=
            $targetAmount;
    }

    if(
        $invoice->remaining_amount <= 0
    ){

        $invoice->remaining_amount = 0;

        $invoice->status = 'paid';

    }else{

        $invoice->status = 'partial';
    }

    $invoice->save();

    DB::commit();

    return response()->json([

        'success' => true,

        'message' =>
            'تمت التسوية بنجاح'

    ]);

} catch (\Exception $e){

    DB::rollBack();

    return response()->json([

        'success' => false,

        'message' =>
            $e->getMessage()

    ],422);
}


}

}
