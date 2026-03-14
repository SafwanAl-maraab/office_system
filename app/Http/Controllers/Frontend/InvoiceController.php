<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BranchCashbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    /* =========================================
       1️⃣ عرض جميع الفواتير
    ========================================= */
    public function index(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $query = Invoice::with(['client','currency' ,'request.requestType' ,'visa.visaType'])
            ->where('branch_id',$branchId)
            ->latest();

        // بحث برقم الفاتورة
        if ($request->invoice_number) {
            $query->where('id',$request->invoice_number);
        }

        // بحث باسم العميل
        if ($request->client) {
            $query->whereHas('client', function($q) use ($request){
                $q->where('full_name','like','%'.$request->client.'%');
            });
        }

        // تصفية بنوع الفاتورة
        if ($request->type) {
            if ($request->type === 'normal') {
                $query->where('is_refund',false);
            }
            if ($request->type === 'refund') {
                $query->where('is_refund',true);
            }

            if ($request->type === 'visa') {
                $query->where('reference_type','visa');
            }
            if ($request->type === 'request') {
                $query->where('reference_type','request');
            }
            if ($request->type === 'booking') {
                $query->where('reference_type','booking');
            }
        }

        // تصفية بالحالة
        if ($request->status) {
            $query->where('status',$request->status);
        }

        $invoices = $query->paginate(12);

        return view('frontend.invoices.index', compact('invoices'));
    }
    /* =========================================
       2️⃣ عرض تفاصيل الفاتورة
    ========================================= */
    public function show($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $invoice = Invoice::with([
            'client',
            'currency',
            'payments',
            'refundInvoices'
        ])
            ->where('branch_id',$branchId)
            ->findOrFail($id);

        return view('frontend.invoices.show', compact('invoice'));
    }


    /* =========================================
       3️⃣ إنشاء فاتورة مسترجع (Credit Note)
    ========================================= */
    public function createRefund(Request $request, $id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $originalInvoice = Invoice::where('branch_id',$branchId)
            ->where('is_refund',false)
            ->findOrFail($id);

        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01'
        ]);

        // حماية: لا يمكن استرجاع أكثر من المدفوع
        if ($request->refund_amount > $originalInvoice->paid_amount) {
            return back()->withErrors([
                'refund_amount' => 'المبلغ أكبر من المدفوع'
            ]);
        }

        DB::transaction(function() use ($request, $originalInvoice){

            /* ===============================
               1️⃣ إنشاء فاتورة مسترجع
            =============================== */

            $refundInvoice = Invoice::create([
                'branch_id' => $originalInvoice->branch_id,
                'client_id' => $originalInvoice->client_id,
                'reference_type' => 'refund',
                'reference_id' => $originalInvoice->id,
                'total_amount' => $request->refund_amount,
                'paid_amount' => $request->refund_amount,
                'remaining_amount' => 0,
                'currency_id' => $originalInvoice->currency_id,
                'status' => 'paid',
                'cost' => 0,
                'is_refund' => true,
                'reversed_invoice_id' => $originalInvoice->id,
            ]);


            /* ===============================
               2️⃣ تسجيل حركة دفع عليها
            =============================== */

            Payment::create([
                'branch_id'   => $originalInvoice->branch_id,
                'client_id'   => $originalInvoice->client_id,
                'invoice_id'  => $refundInvoice->id,
                'amount'      => $request->refund_amount,
                'currency_id' => $originalInvoice->currency_id,
                'payment_method' => 'refund',
                'created_by'  => auth()->user()->employee->id,
            ]);


            /* ===============================
               3️⃣ تعديل الفاتورة الأصلية
            =============================== */

            $originalInvoice->paid_amount -= $request->refund_amount;
            $originalInvoice->remaining_amount =
                $originalInvoice->total_amount - $originalInvoice->paid_amount;

            if ($originalInvoice->paid_amount <= 0) {
                $originalInvoice->status = 'unpaid';
            } else {
                $originalInvoice->status = 'partial';
            }

            $originalInvoice->save();


            /* ===============================
               4️⃣ إنقاص الخزنة
            =============================== */

            $cashbox = BranchCashbox::where('branch_id',$originalInvoice->branch_id)
                ->where('currency_id',$originalInvoice->currency_id)
                ->first();

            if (!$cashbox) {
                abort(403,'الخزنة غير موجودة');
            }

            if ($cashbox->balance < $request->refund_amount) {
                abort(403,'الرصيد في الخزنة غير كافٍ');
            }

            $cashbox->balance -= $request->refund_amount;
            $cashbox->save();

        });

        return back()->with('success','تم إنشاء فاتورة مسترجع بنجاح');
    }


    /* =========================================
       4️⃣ توليد PDF (مشترك)
    ========================================= */
    public function generatePDF($id)
    {
        $branchId = auth()->user()->employee->branch_id;

        $invoice = Invoice::with(['client','currency','payments'])
            ->where('branch_id',$branchId)
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'frontend.invoices.pdf',
            compact('invoice')
        );

        return $pdf->download('invoice-'.$invoice->id.'.pdf');
    }

}
