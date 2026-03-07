<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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
}
