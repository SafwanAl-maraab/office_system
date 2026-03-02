<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BranchCashbox;
use Illuminate\Http\Request;
use DB;

class PaymentController extends Controller
{
    public function store(Request $request, $invoiceId)
    {
        $employee = auth()->user()->employee;
        $branchId = $employee->branch_id;

        $invoice = Invoice::where('branch_id', $branchId)
            ->findOrFail($invoiceId);

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string'
        ]);

        if ($request->amount > $invoice->remaining_amount) {
            return back()->withErrors([
                'error' => 'المبلغ أكبر من المتبقي.'
            ]);
        }

        DB::transaction(function () use ($request, $invoice, $branchId, $employee) {

            // 1️⃣ إنشاء الدفع
            Payment::create([
                'branch_id' => $branchId,
                'client_id' => $invoice->client_id,
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'currency_id' => $invoice->currency_id,
                'payment_method' => $request->payment_method,
                'created_by' => $employee->id,
            ]);

            // 2️⃣ تحديث الفاتورة
            $invoice->increment('paid_amount', $request->amount);
            $invoice->decrement('remaining_amount', $request->amount);

            if ($invoice->remaining_amount == 0) {
                $invoice->update(['status' => 'paid']);
            } else {
                $invoice->update(['status' => 'partial']);
            }

            // 3️⃣ تحديث الخزنة
            $cashbox = BranchCashbox::firstOrCreate([
                'branch_id' => $branchId,
                'currency_id' => $invoice->currency_id
            ], [
                'balance' => 0
            ]);

            $cashbox->increment('balance', $request->amount);
        });

        return back()->with('success', 'تم تسجيل الدفعة بنجاح.');
    }
}
