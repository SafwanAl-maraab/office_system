<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BranchCashbox;
use App\Models\CashboxExchange;
use App\Models\CashboxTransaction;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashboxExchangeController extends Controller
{
    //
    public function index()
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $currencies =
            Currency::orderBy('name')
                ->get();

        $exchanges =
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
                ->paginate(20);

        $todayExchanges =
            CashboxExchange::where(
                'branch_id',
                $branchId
            )
                ->whereDate(
                    'created_at',
                    today()
                )
                ->where(
                    'is_reversed',
                    false
                );

        $todayExchangesCount =
            (clone $todayExchanges)
                ->count();

        $lastExchange =
            CashboxExchange::with([
                'fromCurrency',
                'toCurrency'
            ])
                ->where(
                    'branch_id',
                    $branchId
                )
                ->where(
                    'is_reversed',
                    false
                )
                ->latest()
                ->first();

        return view(
            'frontend.cashbox-exchanges.index',
            compact(
                'currencies',
                'exchanges',
                'todayExchangesCount',
                'lastExchange'
            )
        );
    }


    public function getBalances(Request $request)
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $fromCurrencyId =
            $request->from_currency_id;

        $toCurrencyId =
            $request->to_currency_id;

        $fromCashbox =
            BranchCashbox::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'currency_id',
                    $fromCurrencyId
                )
                ->first();

        $toCashbox =
            BranchCashbox::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'currency_id',
                    $toCurrencyId
                )
                ->first();

        return response()->json([

            'from_balance' =>
                (float)(
                    $fromCashbox?->balance
                    ?? 0
                ),

            'to_balance' =>
                (float)(
                    $toCashbox?->balance
                    ?? 0
                )

        ]);
    }
    public function getRate(Request $request)
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $fromCurrencyId =
            $request->from_currency_id;

        $toCurrencyId =
            $request->to_currency_id;

        /*
        |--------------------------------------------------------------------------
        | السعر المباشر
        |--------------------------------------------------------------------------
        */

        $rate =
            ExchangeRate::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'from_currency_id',
                    $fromCurrencyId
                )
                ->where(
                    'to_currency_id',
                    $toCurrencyId
                )
                ->latest('rate_date')
                ->first();

        if($rate)
        {
            return response()->json([

                'success' => true,

                'rate' =>
                    (float)$rate->rate,

                'reverse' =>
                    false,

                'display' =>
                    $rate->display_rate

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | السعر العكسي
        |--------------------------------------------------------------------------
        */

        $reverseRate =
            ExchangeRate::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'from_currency_id',
                    $toCurrencyId
                )
                ->where(
                    'to_currency_id',
                    $fromCurrencyId
                )
                ->latest('rate_date')
                ->first();

        if(
            $reverseRate &&
            $reverseRate->rate > 0
        )
        {
            return response()->json([

                'success' => true,

                'rate' =>
                    (float)$reverseRate->rate,

                'reverse' =>
                    true,

                'display' =>
                    $reverseRate->display_rate

            ]);
        }

        return response()->json([

            'success' => false,

            'rate' => 0,

            'reverse' => false,

            'display' =>
                'لا يوجد سعر صرف'

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'from_currency_id' => 'required|exists:currencies,id',

            'to_currency_id' => 'required|exists:currencies,id',

            'from_amount' => 'required|numeric|min:0.01',

            'notes' => 'nullable|string|max:1000'
        ]);

        if (
            $request->from_currency_id ==
            $request->to_currency_id
        ) {
            return back()->with(
                'error',
                'لا يمكن المصارفة لنفس العملة'
            );
        }

        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | سعر الصرف
            |--------------------------------------------------------------------------
            */
            $rate = ExchangeRate::where(
                'branch_id',
                $branchId
            )
                ->where(
                    'from_currency_id',
                    $request->from_currency_id
                )
                ->where(
                    'to_currency_id',
                    $request->to_currency_id
                )
                ->latest('rate_date')
                ->first();

            $reverseRate = false;

            if(!$rate)
            {
                $rate = ExchangeRate::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'from_currency_id',
                        $request->to_currency_id
                    )
                    ->where(
                        'to_currency_id',
                        $request->from_currency_id
                    )
                    ->latest('rate_date')
                    ->first();

                $reverseRate = true;
            }

            if(!$rate)
            {
                throw new \Exception(
                    'لا يوجد سعر صرف بين العملتين'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | الخزنة المصدر
            |--------------------------------------------------------------------------
            */

            $fromCashbox =
                BranchCashbox::where(
                    'branch_id',
                    $branchId
                )
                    ->where(
                        'currency_id',
                        $request->from_currency_id
                    )
                    ->lockForUpdate()
                    ->first();

            if (!$fromCashbox) {

                throw new \Exception(
                    'الخزنة المصدر غير موجودة'
                );
            }

            if (
                $fromCashbox->balance <
                $request->from_amount
            ) {

                throw new \Exception(
                    'الرصيد غير كافٍ في الخزنة'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | الخزنة الهدف
            |--------------------------------------------------------------------------
            */

            $toCashbox =
                BranchCashbox::firstOrCreate(

                    [

                        'branch_id' =>
                            $branchId,

                        'currency_id' =>
                            $request->to_currency_id

                    ],

                    [

                        'balance' => 0

                    ]
                );

            /*
            |--------------------------------------------------------------------------
            | الحساب
            |--------------------------------------------------------------------------
            */

            $toAmount =
                $request->from_amount /
                $rate->rate;

            /*
            |--------------------------------------------------------------------------
            | إنشاء المصارفة
            |--------------------------------------------------------------------------
            */

            $exchange =
                CashboxExchange::create([

                    'branch_id' =>
                        $branchId,

                    'from_currency_id' =>
                        $request->from_currency_id,

                    'to_currency_id' =>
                        $request->to_currency_id,

                    'from_amount' =>
                        $request->from_amount,

                    'rate' =>
                        $rate->rate,

                    'to_amount' =>
                        $toAmount,

                    'notes' =>
                        $request->notes,

                    'created_by' =>
                        auth()->user()
                            ->employee
                            ->id

                ]);

            /*
            |--------------------------------------------------------------------------
            | خصم الخزنة الأولى
            |--------------------------------------------------------------------------
            */

            $fromCashbox->balance -=
                $request->from_amount;

            $fromCashbox->save();

            /*
            |--------------------------------------------------------------------------
            | إضافة للخزنة الثانية
            |--------------------------------------------------------------------------
            */

            $toCashbox->balance +=
                $toAmount;

            $toCashbox->save();

            /*
            |--------------------------------------------------------------------------
            | تسجيل حركة خروج
            |--------------------------------------------------------------------------
            */

            CashboxTransaction::create([

                'branch_id' =>
                    $branchId,

                'currency_id' =>
                    $request->from_currency_id,

                'amount' =>
                    -$request->from_amount,

                'type' =>
                    'exchange_out',

                'reference_type' =>
                    'cashbox_exchange',

                'reference_id' =>
                    $exchange->id,

                'notes' =>
                    'مصارفة من '
                    .$exchange->fromCurrency->code
                    .' إلى '
                    .$exchange->toCurrency->code,

                'created_by' =>
                    auth()->user()
                        ->employee
                        ->id

            ]);

            /*
            |--------------------------------------------------------------------------
            | تسجيل حركة دخول
            |--------------------------------------------------------------------------
            */

            CashboxTransaction::create([

                'branch_id' =>
                    $branchId,

                'currency_id' =>
                    $request->to_currency_id,

                'amount' =>
                    $toAmount,

                'type' =>
                    'exchange_in',

                'reference_type' =>
                    'cashbox_exchange',

                'reference_id' =>
                    $exchange->id,

                'notes' =>
                    'مصارفة من '
                    .$exchange->fromCurrency->code
                    .' إلى '
                    .$exchange->toCurrency->code,

                'created_by' =>
                    auth()->user()
                        ->employee
                        ->id

            ]);

            DB::commit();

            return back()->with(
                'success',
                'تم تنفيذ المصارفة بنجاح'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function reverse(
        CashboxExchange $exchange
    )
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | تم عكسها مسبقاً
            |--------------------------------------------------------------------------
            */

            if($exchange->is_reversed)
            {
                throw new \Exception(
                    'هذه العملية معكوسة مسبقاً'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | خزنة العملة الناتجة
            |--------------------------------------------------------------------------
            */

            $toCashbox =
                BranchCashbox::where(
                    'branch_id',
                    $exchange->branch_id
                )
                    ->where(
                        'currency_id',
                        $exchange->to_currency_id
                    )
                    ->lockForUpdate()
                    ->first();

            if(!$toCashbox)
            {
                throw new \Exception(
                    'الخزنة الهدف غير موجودة'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | منع الرصيد السالب
            |--------------------------------------------------------------------------
            */

            if(
                $toCashbox->balance <
                $exchange->to_amount
            ){
                throw new \Exception(
                    'لا يمكن عكس المصارفة لأن الرصيد الحالي غير كافٍ'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | خزنة العملة الأصلية
            |--------------------------------------------------------------------------
            */

            $fromCashbox =
                BranchCashbox::where(
                    'branch_id',
                    $exchange->branch_id
                )
                    ->where(
                        'currency_id',
                        $exchange->from_currency_id
                    )
                    ->lockForUpdate()
                    ->first();

            if(!$fromCashbox)
            {
                throw new \Exception(
                    'الخزنة الأصلية غير موجودة'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | تنفيذ العكس
            |--------------------------------------------------------------------------
            */

            $toCashbox->balance -=
                $exchange->to_amount;

            $toCashbox->save();

            $fromCashbox->balance +=
                $exchange->from_amount;

            $fromCashbox->save();

            /*
            |--------------------------------------------------------------------------
            | تسجيل حركة خروج معاكسة
            |--------------------------------------------------------------------------
            */

            CashboxTransaction::create([

                'branch_id' =>
                    $exchange->branch_id,

                'currency_id' =>
                    $exchange->to_currency_id,

                'amount' =>
                    -$exchange->to_amount,

                'type' =>
                    'exchange_out',

                'reference_type' =>
                    'cashbox_exchange_reverse',

                'reference_id' =>
                    $exchange->id,

                'notes' =>
                    'عكس مصارفة #' .
                    $exchange->id,

                'created_by' =>
                    auth()->user()
                        ->employee
                        ->id
            ]);

            /*
            |--------------------------------------------------------------------------
            | تسجيل حركة دخول معاكسة
            |--------------------------------------------------------------------------
            */

            CashboxTransaction::create([

                'branch_id' =>
                    $exchange->branch_id,

                'currency_id' =>
                    $exchange->from_currency_id,

                'amount' =>
                    $exchange->from_amount,

                'type' =>
                    'exchange_in',

                'reference_type' =>
                    'cashbox_exchange_reverse',

                'reference_id' =>
                    $exchange->id,

                'notes' =>
                    'عكس مصارفة #' .
                    $exchange->id,

                'created_by' =>
                    auth()->user()
                        ->employee
                        ->id
            ]);

            /*
            |--------------------------------------------------------------------------
            | تعليم العملية كمعكوسة
            |--------------------------------------------------------------------------
            */

            $exchange->update([

                'is_reversed' => true,

                'reversed_at' => now(),

                'reversed_by' =>
                    auth()->user()
                        ->employee
                        ->id

            ]);

            DB::commit();

            return back()->with(
                'success',
                'تم عكس المصارفة بنجاح'
            );

        }
        catch(\Exception $e)
        {
            DB::rollBack();

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

}
