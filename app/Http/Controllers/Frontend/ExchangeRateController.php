<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    //
    public function index()
    {
        $branchId = auth()->user()->employee->branch_id;

        $rates = ExchangeRate::with([
            'fromCurrency',
            'toCurrency'
        ])
            ->where('branch_id',$branchId)
            ->latest()
            ->get();

        $currencies = Currency::where(
            'status',
            true
        )->get();

        return view(
            'frontend.exchange_rates.index',
            compact(
                'rates',
                'currencies'
            )
        );
    }


    public function store(Request $request)
    {
        $request->validate([

            'from_currency_id' =>
                'required|exists:currencies,id',

            'to_currency_id' =>
                'required|exists:currencies,id',

            'rate' =>
                'required|numeric|min:0.000001'

        ]);

        if(
            $request->from_currency_id
            ==
            $request->to_currency_id
        ){
            return back()->with(
                'error',
                'لا يمكن اختيار نفس العملة'
            );
        }

        ExchangeRate::updateOrCreate(

            [

                'branch_id' =>
                    auth()->user()->employee->branch_id,

                'from_currency_id' =>
                    $request->from_currency_id,

                'to_currency_id' =>
                    $request->to_currency_id

            ],

            [

                'rate' =>
                    $request->rate,

                'rate_date' =>
                    now()->toDateString(),

                'created_by' =>
                    auth()->user()->employee->id

            ]

        );

        return back()->with(
            'success',
            'تم حفظ سعر الصرف'
        );
    }

    public function findRate(Request $request)
    {
        $employee =
            auth()->user()->employee;


/*
|--------------------------------------------------------------------------
| البحث المباشر
|--------------------------------------------------------------------------
*/

$rate =
    ExchangeRate::where(
        'branch_id',
        $employee->branch_id
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

if($rate)
{
    return response()->json([

        'success' => true,

        'rate' => $rate->rate,

        'direction' => 'direct'

    ]);
}

/*
|--------------------------------------------------------------------------
| البحث العكسي
|--------------------------------------------------------------------------
*/

$reverse =
    ExchangeRate::where(
        'branch_id',
        $employee->branch_id
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

if($reverse)
{
    return response()->json([

        'success' => true,

        'rate' => $reverse->rate,

        'direction' => 'reverse'

    ]);
}

return response()->json([

    'success' => false

]);


}

}
