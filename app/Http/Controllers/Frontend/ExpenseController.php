<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\BranchCashbox;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{

    /* =======================================
        عرض المصروفات مع البحث الكامل
    ======================================== */
    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $query = Expense::with(['employee','currency'])
            ->where('branch_id',$branchId)
            ->latest();

        // 🔎 بحث في الوصف
        if ($request->description) {
            $query->where('description','like','%'.$request->description.'%');
        }

        // 💱 فلترة حسب العملة
        if ($request->currency_id) {
            $query->where('currency_id',$request->currency_id);
        }

        // 📅 بحث من تاريخ
        if ($request->date_from) {
            $query->whereDate('created_at','>=',$request->date_from);
        }

        // 📅 بحث إلى تاريخ
        if ($request->date_to) {
            $query->whereDate('created_at','<=',$request->date_to);
        }

        $expenses = $query->paginate(12)->withQueryString();

        $currencies = Currency::where('status',1)->get();

        return view('frontend.expenses.index',
            compact('expenses','currencies'));
    }


    /* =======================================
        تخزين مصروف
    ======================================== */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'currency_id' => 'required|exists:currencies,id',
            'description' => 'required|string|max:500',
        ]);

        $branchId = auth()->user()->employee->branch_id;

        $cashbox = BranchCashbox::where('branch_id',$branchId)
            ->where('currency_id',$request->currency_id)
            ->first();

        if(!$cashbox){
            return back()->withErrors([
                'currency_id' => 'الخزنة غير موجودة لهذه العملة'
            ])->withInput();
        }

        if($cashbox->balance < $request->amount){
            return back()->withErrors([
                'amount' => 'الرصيد في الخزنة غير كافٍ'
            ])->withInput();
        }

        DB::transaction(function() use ($request,$branchId,$cashbox){

            Expense::create([
                'branch_id' => $branchId,
                'amount' => $request->amount,
                'currency_id' => $request->currency_id,
                'description' => $request->description,
                'created_by' => auth()->user()->employee->id,
            ]);

            $cashbox->balance -= $request->amount;
            $cashbox->save();

        });

        return back()->with('success','تم تسجيل المصروف بنجاح');
    }

}
