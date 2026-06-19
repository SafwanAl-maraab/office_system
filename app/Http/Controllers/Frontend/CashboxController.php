<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CashboxTransaction;
use Illuminate\Http\Request;
use App\Models\BranchCashbox;
use App\Models\Currency;
use Illuminate\Support\Facades\DB;

class CashboxController extends Controller
{

    /*
    ============================
    عرض الخزائن + البحث
    ============================
    */

    public function index(Request $request)
    {

        $branchId = auth()->user()->employee->branch_id;

        $query = BranchCashbox::with('currency')
            ->where('branch_id', $branchId);

        // البحث في الاسم أو الرمز
        if ($request->search) {

            $query->whereHas('currency', function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('symbol', 'like', '%' . $request->search . '%');

            });

        }

        $cashboxes = $query->latest()->get();

        return view(
            'frontend.cashboxes.index',
            compact('cashboxes')
        );
    }


    /*
    ============================
    إنشاء عملة جديدة
    ============================
    */

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:100',
            'symbol' => 'required|string|max:10|unique:currencies,symbol',
            'code' => 'nullable|string|max:10'
        ], [
            'symbol.unique' => 'رمز العملة مستخدم مسبقاً'
        ]);

        $branchId = auth()->user()->employee->branch_id;

        DB::transaction(function () use ($request, $branchId) {

            // إنشاء العملة

            $currency = Currency::create([
                'name' => $request->name,
                'symbol' => $request->symbol,
                'code' => $request->code,
                'status' => 1
            ]);

            // إنشاء خزنة برصيد صفر

            BranchCashbox::create([
                'branch_id' => $branchId,
                'currency_id' => $currency->id,
                'balance' => 0
            ]);

        });

        return back()->with('success', 'تم إنشاء العملة والخزنة بنجاح');
    }


    /*
    ============================
    تعديل اسم العملة أو الرمز
    ============================
    */

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:100',
            'symbol' => 'required|string|max:10|unique:currencies,symbol,' . $id ,

            'status' => 'required|boolean'
        ]);

        $currency = Currency::findOrFail($id);

        $currency->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
              'status' => $request->status
        ]);

        return back()->with('success', 'تم تعديل بيانات العملة');
    }

    public function transactions(Request $request, Currency $currency)
    {
        $branchId = auth()->user()->employee->branch_id;

        // جلب التواريخ من طلب البحث
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        // 1. جلب كافة حركات العملة تصاعدياً لحساب الأرصدة التتابعية بدقة
        $allTransactions = CashboxTransaction::with(['employee'])
            ->where('branch_id', $branchId)
            ->where('currency_id', $currency->id)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $runningBalance = 0;
        $openingBalance = 0;
        $totalIn = 0;
        $totalOut = 0;
        $filteredTransactions = collect();

        // 2. معالجة الحسابات الدفترية وفلترة النطاق الزمني لحظياً
        foreach ($allTransactions as $transaction) {
            $transaction->balance_before = $runningBalance;
            $runningBalance += $transaction->amount;
            $transaction->running_balance = $runningBalance;

            $txDate = $transaction->created_at->toDateString();
            $keep = true;

            if ($dateFrom && $txDate < $dateFrom) {
                $keep = false;
                // حساب الرصيد الافتتاحي (كل ما هو قبل تاريخ البداية المختار)
                $openingBalance += $transaction->amount;
            }
            if ($dateTo && $txDate > $dateTo) {
                $keep = false;
            }

            if ($keep) {
                $filteredTransactions->push($transaction);

                // حساب إجمالي الوارد والمنصرف للحركات المفلترة فقط
                if ($transaction->amount > 0) {
                    $totalIn += $transaction->amount;
                } else {
                    $totalOut += abs($transaction->amount);
                }
            }
        }

        // 3. عكس الحركات المفلترة لعرض الأحدث في الأعلى دائماً في الجدول
        $transactions = $filteredTransactions->reverse()->values();

        // جلب الرصيد الإجمالي الفعلي الحالي للخزنة
        $balance = BranchCashbox::where('branch_id', $branchId)
            ->where('currency_id', $currency->id)
            ->value('balance') ?? 0;

        return view('frontend.cashboxes.transactions', compact(
            'currency',
            'transactions',
            'balance',
            'openingBalance',
            'totalIn',
            'totalOut',
            'dateFrom',
            'dateTo'
        ));
    }
}


