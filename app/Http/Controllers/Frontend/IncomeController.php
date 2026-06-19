<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Currency;
use App\Models\BranchCashbox;
use App\Models\CashboxTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | عرض الإيرادات
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        $query =
            Income::with([
                'employee',
                'currency'
            ])
                ->where(
                    'branch_id',
                    $branchId
                )
                ->latest();

        if($request->description)
        {
            $query->where(
                'description',
                'like',
                '%'.$request->description.'%'
            );
        }

        if($request->currency_id)
        {
            $query->where(
                'currency_id',
                $request->currency_id
            );
        }

        if($request->date_from)
        {
            $query->whereDate(
                'created_at',
                '>=',
                $request->date_from
            );
        }

        if($request->date_to)
        {
            $query->whereDate(
                'created_at',
                '<=',
                $request->date_to
            );
        }

        $incomes =
            $query->paginate(12)
                ->withQueryString();

        $currencies =
            Currency::where(
                'status',
                1
            )->get();

        $todayIncome = Income::where(
            'branch_id',
            $branchId
        )
            ->whereDate(
                'created_at',
                today()
            )
            ->sum('amount');

        $totalIncome = Income::where(
            'branch_id',
            $branchId
        )
            ->sum('amount');

        $incomeCount = Income::where(
            'branch_id',
            $branchId
        )
            ->count();

        return view(
            'frontend.incomes.index',
            compact(
                'incomes',
                'currencies',
                'todayIncome',
                'totalIncome',
                'incomeCount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | تخزين الإيراد
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'amount' =>
                'required|numeric|min:0.01',

            'currency_id' =>
                'required|exists:currencies,id',

            'description' =>
                'required|string|max:500',

        ]);

        $branchId =
            auth()->user()
                ->employee
                ->branch_id;

        DB::transaction(function() use(
            $request,
            $branchId
        ){

            /*
            |--------------------------------------------------------------------------
            | الخزنة
            |--------------------------------------------------------------------------
            */

            $cashbox =
                BranchCashbox::firstOrCreate(

                    [

                        'branch_id' =>
                            $branchId,

                        'currency_id' =>
                            $request->currency_id

                    ],

                    [

                        'balance' => 0

                    ]
                );

            /*
            |--------------------------------------------------------------------------
            | إنشاء الإيراد
            |--------------------------------------------------------------------------
            */

            $income =
                Income::create([

                    'branch_id' =>
                        $branchId,

                    'amount' =>
                        $request->amount,

                    'currency_id' =>
                        $request->currency_id,

                    'description' =>
                        $request->description,

                    'created_by' =>
                        auth()->user()
                            ->employee
                            ->id

                ]);

            /*
            |--------------------------------------------------------------------------
            | زيادة الخزنة
            |--------------------------------------------------------------------------
            */

            $cashbox->balance +=
                $request->amount;

            $cashbox->save();

            /*
            |--------------------------------------------------------------------------
            | تسجيل حركة الخزنة
            |--------------------------------------------------------------------------
            */

            CashboxTransaction::create([

                'branch_id' =>
                    $branchId,

                'currency_id' =>
                    $request->currency_id,

                'amount' =>
                    $request->amount,

                'type' =>
                    'income',

                'reference_type' =>
                    'income',

                'reference_id' =>
                    $income->id,

                'notes' =>
                    $request->description,

                'created_by' =>
                    auth()->user()
                        ->employee
                        ->id

            ]);

        });

        return back()->with(
            'success',
            'تم تسجيل الإيراد بنجاح'
        );
    }

}
