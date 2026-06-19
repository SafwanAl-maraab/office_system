<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ClientBalanceLog;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\BranchCashbox;
use App\Services\CashboxTransactionService;
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

        // 1. التحقق من البيانات المدخلة
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01',
            'refund_method' => 'required|in:cash,balance', // نقداً أو رصيد حساب
            'refund_reason' => 'nullable|string|max:500',  // الملاحظات أو السبب
        ]);

        try {
            $originalInvoice = Invoice::where('branch_id', $branchId)
                ->where('is_refund', false)
                ->findOrFail($id);

            // حماية محاسبية: لا يمكن استرجاع أكثر من المدفوع فعلياً
            if ($request->refund_amount > $originalInvoice->paid_amount) {
                throw new \Exception('المبلغ المراد استرجاعه أكبر من المبلغ المدفوع في الفاتورة الأصلية.');
            }

            DB::transaction(function() use ($request, $originalInvoice) {

                /* ==========================================================================
                   1️⃣ إنشاء فاتورة مسترجع (Refund Invoice)
                ========================================================================== */
                $refundInvoice = Invoice::create([
                    'branch_id'           => $originalInvoice->branch_id,
                    'client_id'           => $originalInvoice->client_id,
                    'reference_type'      => 'refund',
                    'reference_id'        => $originalInvoice->id,
                    'total_amount'        => $request->refund_amount,
                    'paid_amount'         => $request->refund_amount,
                    'remaining_amount'    => 0,
                    'currency_id'         => $originalInvoice->currency_id,
                    'status'              => 'paid',
                    'cost'                => 0,
                    'is_refund'           => true,
                    'reversed_invoice_id' => $originalInvoice->id,
                ]);


                /* ==========================================================================
                   2️⃣ معالجة طريقة الاسترجاع (نقداً أو رصيد حساب)
                ========================================================================== */
                if ($request->refund_method === 'balance') {

                    // أ: إضافة المبلغ إلى سجل رصيد العميل المتوفر
                    ClientBalanceLog::create([
                        'client_id'      => $originalInvoice->client_id,
                        'currency_id'    => $originalInvoice->currency_id,
                        'amount'         => $request->refund_amount, // يضاف كمبلغ موجب لأنه قيد إيداع لصالح العميل
                        'type'           => 'refund',
                        'reference_type' => 'invoice',
                        'reference_id'   => $refundInvoice->id,
                        'notes'          => $request->refund_reason,
                        'created_by'     => auth()->user()->employee->id,
                    ]);

                    // 💡 ملاحظة: إذا كان جدول العميل يحتوي على حقل إجمالي الرصيد المباشر (مثل: balance) يفضل تحديثه هنا أيضاً.

                } else {

                    // ب: الاسترجاع نقداً (كاش) -> نقوم بإنشاء حركة دفع مالي مع نوع الاسترجاع
                    Payment::create([
                        'branch_id'      => $originalInvoice->branch_id,
                        'client_id'      => $originalInvoice->client_id,
                        'invoice_id'     => $refundInvoice->id,
                        'amount'         => $request->refund_amount,
                        'currency_id'    => $originalInvoice->currency_id,
                        'payment_method' => 'refund',
                        'created_by'     => auth()->user()->employee->id,
                    ]);

                    /* ==========================================================================
                       3️⃣ إنقاص الخزنة (يتم فقط في حالة الاسترجاع النقدي cash)
                    ========================================================================== */
                    $cashbox = BranchCashbox::where('branch_id', $originalInvoice->branch_id)
                        ->where('currency_id', $originalInvoice->currency_id)
                        ->first();


                    if (!$cashbox) {
                        throw new \Exception('الخزنة الخاصة بهذه العملة غير موجودة في هذا الفرع.');
                    }

                    if ($cashbox->balance < $request->refund_amount) {
                        throw new \Exception('رصيد الخزنة الحالي غير كافٍ لإتمام عملية الاسترجاع النقدي.');
                    }

                    $cashbox->decrement('balance', $request->refund_amount);
                    CashboxTransactionService::log(

                        branchId:
                        $cashbox->branch_id,

                        currencyId:
                        $cashbox->currency_id,

                        amount:
                        -$request->refund_amount,

                        type:
                        'refund',

                        referenceType:
                        'invoice',

                        referenceId:
                        $refundInvoice->id,

                        notes:
                        'استرجاع فاتورة',

                        employeeId:
                        auth()->user()->employee->id

                    );

                }


                /* ==========================================================================
                   4️⃣ تحديث قيم وحالة الفاتورة الأصلية
                ========================================================================== */
                $originalInvoice->paid_amount -= $request->refund_amount;
                $originalInvoice->remaining_amount = $originalInvoice->total_amount - $originalInvoice->paid_amount;

                if ($originalInvoice->paid_amount <= 0) {
                    $originalInvoice->status = 'unpaid';
                } else {
                    $originalInvoice->status = 'partial';
                }

                $originalInvoice->save();

            });

            return back()->with('success', 'تم إنشاء حركة المسترجع وتحديث الحسابات بنجاح.');

        } catch (\Exception $e) {
            // العودة للخلف مع إظهار رسالة الخطأ للمستخدم بشكل مرن وأنيق
            return back()->with('error', $e->getMessage());
        }
    }


    public function cancelOperation(Request $request, Invoice $invoice)
    {
        $request->validate([

            'refund_method' =>

                'required|in:cash,balance'

        ]);

        DB::beginTransaction();

        try{

            /*
            |--------------------------------------------------------------------------
            | المبلغ المدفوع
            |--------------------------------------------------------------------------
            */

            $paidAmount =
                $invoice->paid_amount;

            /*
            |--------------------------------------------------------------------------
            | إنشاء فاتورة مسترجع كاملة
            |--------------------------------------------------------------------------
            */

            $refundInvoice =
                Invoice::create([

                    'branch_id' =>
                        $invoice->branch_id,

                    'client_id' =>
                        $invoice->client_id,

                    'reference_type' =>
                        'refund',

                    'reference_id' =>
                        $invoice->id,

                    'total_amount' =>
                        $paidAmount,

                    'paid_amount' =>
                        $paidAmount,

                    'remaining_amount' =>
                        0,

                    'currency_id' =>
                        $invoice->currency_id,

                    'status' =>
                        'paid',

                    'cost' =>
                        0,

                    'is_refund' =>
                        true,

                    'reversed_invoice_id' =>
                        $invoice->id

                ]);

            /*
            |--------------------------------------------------------------------------
            | استرجاع نقدي
            |--------------------------------------------------------------------------
            */

            if(
                $request->refund_method
                ===
                'cash'
            ){

                Payment::create([

                    'branch_id' =>
                        $invoice->branch_id,

                    'client_id' =>
                        $invoice->client_id,

                    'invoice_id' =>
                        $refundInvoice->id,

                    'amount' =>
                        $paidAmount,

                    'currency_id' =>
                        $invoice->currency_id,

                    'payment_method' =>
                        'refund',

                    'created_by' =>
                        auth()->user()
                            ->employee
                            ->id

                ]);

                $cashbox =
                    BranchCashbox::where(
                        'branch_id',
                        $invoice->branch_id
                    )
                        ->where(
                            'currency_id',
                            $invoice->currency_id
                        )
                        ->first();

                if(
                    !$cashbox
                ){
                    throw new \Exception(
                        'الخزنة غير موجودة'
                    );
                }

                if(
                    $cashbox->balance
                    <
                    $paidAmount
                ){
                    throw new \Exception(
                        'رصيد الخزنة غير كافٍ'
                    );
                }

                $cashbox->decrement(
                    'balance',
                    $paidAmount
                );
            }

            /*
            |--------------------------------------------------------------------------
            | تحويل لرصيد العميل
            |--------------------------------------------------------------------------
            */

            else{

                ClientBalanceLog::create([

                    'client_id' =>
                        $invoice->client_id,

                    'currency_id' =>
                        $invoice->currency_id,

                    'amount' =>
                        $paidAmount,

                    'type' =>
                        'refund',

                    'reference_type' =>
                        'invoice',

                    'reference_id' =>
                        $refundInvoice->id,

                    'notes' =>
                        'استرجاع ناتج عن إلغاء العملية',

                    'created_by' =>
                        auth()->user()
                            ->employee
                            ->id

                ]);
            }


            /*
|--------------------------------------------------------------------------
| إلغاء العملية الأصلية
|--------------------------------------------------------------------------
*/

            if(
                $invoice->reference_type
                ===
                'booking'
            ){
                Booking::where(
                    'id',
                    $invoice->reference_id
                )->update([

                    'status' =>
                        'cancelled'

                ]);
            }

            elseif(
                $invoice->reference_type
                ===
                'visa'
            ){
                Visa::where(
                    'id',
                    $invoice->reference_id
                )->update([

                    'status' =>
                        'cancelled'

                ]);
            }

            elseif(
                $invoice->reference_type
                ===
                'request'
            ){
                \App\Models\Request::where(
                    'id',
                    $invoice->reference_id
                )->update([

                    'status' =>
                        'cancelled'

                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | إلغاء الفاتورة
            |--------------------------------------------------------------------------
            */

            $invoice->update([

                'status' =>
                    'cancelled',

                'paid_amount' =>
                    0,

                'remaining_amount' =>
                    0

            ]);

            DB::commit();

            return back()->with(

                'success',

                'تم إلغاء العملية بنجاح'

            );



        }catch(\Exception $e){

            DB::rollBack();

            return back()->with(

                'error',

                $e->getMessage()

            );
        }
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
