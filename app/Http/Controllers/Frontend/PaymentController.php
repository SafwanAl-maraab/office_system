<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\BranchCashbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $query = \App\Models\Payment::with(['invoice','client','currency'])
            ->where('branch_id',$branchId)
            ->latest();

        // 🔎 بحث باسم العميل
        if ($request->client) {
            $query->whereHas('client', function($q) use ($request){
                $q->where('full_name','like','%'.$request->client.'%');
            });
        }

        // 🔎 بحث برقم الفاتورة
        if ($request->invoice_number) {
            $query->whereHas('invoice', function($q) use ($request){
                $q->where('id',$request->invoice_number);
            });
        }

        $payments = $query->paginate(15);
        $invoices = Invoice::where('branch_id',$branchId)->where('is_refund','true') ->get();

        return view('frontend.payments.index', compact('payments','invoices'));
    }

    public function store(Request $request  )
    {

        if(empty($request->invId)){

            return back()->with('success','لم يتم تحديد فاتورة '

            );
        }

        DB::transaction(function() use ($request ){

            $invoice = Invoice::findOrFail($request->invId);

            $payment = Payment::create([
                'branch_id'   => $invoice->branch_id,
                'client_id'   => $invoice->client_id,
                'invoice_id'  => $invoice->id,
                'amount'      => $request->amount,
                'currency_id' => $invoice->currency_id,
                'payment_method' => $request->payment_method,
                'created_by'  => auth()->user()->employee->id,
            ]);

            // تحديث الفاتورة
            $invoice->paid_amount += $request->amount;
            $invoice->remaining_amount =
                $invoice->total_amount - $invoice->paid_amount;

            if ($invoice->remaining_amount <= 0) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }

            $invoice->save();

            // زيادة الخزنة
            $cashbox = BranchCashbox::where('branch_id',$invoice->branch_id)
                ->where('currency_id',$invoice->currency_id)
                ->first();

            $cashbox->balance += $request->amount;
            $cashbox->save();
        });

        return back()->with('success','تم تسجيل الدفعة');
    }


    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        // مبدئياً حذف مباشر (يمكن لاحقاً تحويله SoftDelete)
        $payment->delete();

        return back()->with('success','تم حذف الدفعة');
    }
}
