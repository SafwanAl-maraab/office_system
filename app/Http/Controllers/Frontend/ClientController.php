<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ClientBalanceLog;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\RoleOld;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * عرض العملاء
     */
    public function index(Request $request)
    {
        $branchId = $this->getBranchId();

        $query = Client::where('branch_id', $branchId);


        // البحث
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('passport_number', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(12);

        return view('frontend.clients.index', compact('clients'));
    }

    /**
     * تخزين عميل جديد
     */
    public function store(Request $request)
    {
        $branchId = $this->getBranchId();

        $data = $request->validate([
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:50',
            'passport_number' => 'nullable|string|max:100',
            'national_id'     => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|boolean',
        ]);

        $data['branch_id'] = $branchId;

        Client::create($data);

        return redirect()
            ->route('clients.index')
            ->with('success', 'تم إضافة العميل بنجاح');
    }

    /**
     * تحديث عميل
     */
    public function update(Request $request, $id)
    {
        $branchId = $this->getBranchId();

        $client = Client::where('id', $id)
            ->where('branch_id', $branchId)
            ->firstOrFail();

        $data = $request->validate([
            'full_name'       => 'required|string|max:255',
            'phone'           => 'required|string|max:50',
            'passport_number' => 'nullable|string|max:100',
            'national_id'     => 'nullable|string|max:100',
            'address'         => 'nullable|string|max:255',
            'notes'           => 'nullable|string',
            'status'          => 'required|boolean',
        ]);

        $client->update($data);

        return redirect()
            ->route('clients.index')
            ->with('success', 'تم تحديث بيانات العميل');
    }

    /**
     * حذف عميل
     */
    public function destroy($id)
    {
        $branchId = $this->getBranchId();

        $client = Client::where('id', $id)
            ->where('branch_id', $branchId)
            ->firstOrFail();

        // منع الحذف إذا لديه تأشيرات
        if ($client->visas()->exists()) {
            return redirect()
                ->route('clients.index')
                ->with('error', 'لا يمكن حذف العميل لوجود عمليات مرتبطة به');
        }

        $client->delete();

        return redirect()
            ->route('clients.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }

    /**
     * جلب رقم الفرع من المستخدم المسجل
     */
    private function getBranchId()
    {
        $user = auth()->user();

        if (!$user || !$user->employee || !$user->employee->branch) {
            abort(403, 'المستخدم غير مرتبط بفرع');
        }

        return $user->employee->branch->id;
    }

    public function search(Request $request)
    {
        $branchId = auth()->user()->employee->branch_id;

        $search = trim($request->search);

        if(strlen($search) < 2){
            return response()->json([]);
        }

        $clients = Client::query()

            ->where('branch_id',$branchId)

            ->where(function($q) use ($search){

                $q->where('full_name','like',"%{$search}%")
                    ->orWhere('phone','like',"%{$search}%")
                    ->orWhere('passport_number','like',"%{$search}%")
                    ->orWhere('national_id','like',"%{$search}%");

            })

            ->limit(15)

            ->get([
                'id',
                'full_name',
                'phone',
                'passport_number'
            ]);

        return response()->json($clients);
    }


    public function statement(Client $client, Request $request)
    {
        // 1. بناء الاستعلام الأساسي بالتصفية والتصفيف التصاعدي لحساب الرصيد المتتابع بدقة
        $logsQuery = ClientBalanceLog::with(['currency', 'employee'])
            ->where('client_id', $client->id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc');

        if ($request->filled('currency_id')) {
            $logsQuery->where('currency_id', $request->currency_id);
        }

        if ($request->filled('type')) {
            $logsQuery->where('type', $request->type);
        }

        $rawLogs = $logsQuery->get();

        $payments = Payment::with([
            'currency',
            'employee',
            'invoice'
        ])
            ->where('client_id', $client->id);

        if ($request->filled('currency_id')) {
            $payments->where(
                'currency_id',
                $request->currency_id
            );
        }

        if ($request->filled('type'))
        {
            if ($request->type == 'invoice_payment')
            {
                $payments->where(
                    'payment_method',
                    'cash'
                );
            }
            elseif ($request->type == 'refund_cash')
            {
                $payments->where(
                    'payment_method',
                    'refund'
                );
            }
            else
            {
                // إذا اختار أي نوع آخر
                // لا نعرض أي Payments

                $payments->whereRaw('1 = 0');
            }
        }

        $payments = $payments->get();


        foreach ($payments as $payment) {

            if ($payment->payment_method === 'refund')
            {
                $payment->type =
                    'refund_cash';

                $payment->type_label =
                    'مسترجع نقدي';


                $payment->operation_title =
                    'مسترجع نقدي';

                $payment->operation_details =
                    'استرجاع نقدي للفاتورة #'
                    .$payment->invoice_id;
            }
            else
            {
                $payment->type =
                    'invoice_payment';

                $payment->type_label =
                    'دفعة فاتورة';

                $payment->operation_title =
                    'دفعة فاتورة';

                $payment->operation_details =
                    'سداد نقدي للفاتورة #'
                    .$payment->invoice_id;
            }

            $payment->running_balance = null;

            $payment->invoice_number =
                $payment->invoice_id;

            if ($payment->payment_method === 'refund')
            {
                $payment->type = 'refund_cash';

                $payment->type_label = 'مسترجع نقدي';

                $payment->operation_title = 'مسترجع نقدي';

                $payment->operation_details =
                    'استرجاع للفاتورة #'
                    .$payment->invoice_id;
            }
            else
            {
                $payment->type = 'invoice_payment';

                $payment->type_label = 'دفعة فاتورة';

                $payment->operation_title = 'دفعة فاتورة';

                $payment->operation_details =
                    'سداد للفاتورة #'
                    .$payment->invoice_id;
            }
            
            $payment->source_type =
                'payment';
            $payment->notes =
                $payment->payment_method === 'refund'
                    ? 'استرجاع نقدي'
                    : 'سداد نقدي';
        }


        // 💡 حل مشكلة N+1 المقترحة والذكية من طرفك
        $invoiceIds = $rawLogs->where('reference_type', 'invoice')
            ->pluck('reference_id')
            ->unique();

        $invoices = Invoice::with([
            'booking.trip',
            'visa.visaType',
            'request.requestType'
        ])
            ->whereIn('id', $invoiceIds)
            ->get()
            ->keyBy('id');

        $runningBalances = [];

        // 2. التدوير الحسابي الذكي بدون استعلامات متكررة داخل الـ Loop
        foreach ($rawLogs as $log) {
            $currencyId = $log->currency_id;

            if (!isset($runningBalances[$currencyId])) {
                $runningBalances[$currencyId] = 0;
            }

            $runningBalances[$currencyId] += $log->amount;
            $log->running_balance = $runningBalances[$currencyId];

            // تهيئة الحقول الافتراضية للعملية
            $log->operation_title = '-';
            $log->operation_details = '';
            $log->invoice_number = null;

            if ($log->reference_type === 'invoice') {
                // استدعاء الفاتورة من الذاكرة مباشرة بسرعة O(1)
                $invoice = $invoices[$log->reference_id] ?? null;

                if ($invoice) {
                    $log->invoice_number = $invoice->id;

                    if ($invoice->reference_type === 'booking' && $invoice->booking) {
                        $trip = $invoice->booking->trip;
                        $log->operation_title = 'حجز مقعد';
                        if ($trip) {
                            // 💡 تعديل اتجاه السهم بناءً على ملاحظتك
                            $log->operation_details = ($trip->from_city ?? '') . ' → ' . ($trip->to_city ?? '');
                        }
                    } elseif ($invoice->reference_type === 'visa' && $invoice->visa) {
                        $log->operation_title = 'تأشيرة';
                        $log->operation_details = $invoice->visa->visaType?->name ?? ($invoice->visa->visa_number ?? '');
                    } elseif ($invoice->reference_type === 'request' && $invoice->request) {
                        $log->operation_title = 'طلب خدمة';
                        $log->operation_details = $invoice->request->requestType?->name ?? ($invoice->request->request_number ?? '');
                    }
                }
            } elseif ($log->reference_type === 'voucher') {
                $log->operation_title = 'سند عميل';
                $log->operation_details = $log->notes ?? 'قيد سند مالي رسمي';
            }

        }

        // 3. عكس الحركات الماليّة ليظهر أحدث إجراء مالي في الأعلى بالـ Blade
        $logs = $rawLogs
            ->concat($payments)
            ->sortByDesc('created_at')
            ->values();

        // 4. استخراج الأرصدة الإجمالية المجمعة حسب العملات
        $balances = [];
        foreach ($rawLogs->groupBy('currency_id') as $currencyLogs) {
            $balances[] = [
                'currency' => $currencyLogs->first()->currency,
                'balance'  => $currencyLogs->sum('amount')
            ];
        }

        // 5. بناء بطاقات ملخص الحساب (Summary Boxes) الشاملة والمبسطة
        $currencies = Currency::orderBy('code')->get();
        $summary = [];

        foreach ($currencies as $currency) {
            $balance = ClientBalanceLog::where('client_id', $client->id)
                ->where('currency_id', $currency->id)
                ->sum('amount');

            $receivable = Invoice::where('client_id', $client->id)
                ->where('currency_id', $currency->id)
                ->where('status', '!=', 'cancelled')
                ->where(function($q) {
                    $q->whereNull('is_refund')->orWhere('is_refund', false);
                })
                ->sum('remaining_amount');

            $net = $balance - $receivable;

            if ($balance != 0 || $receivable != 0) {
                $summary[] = [
                    'currency'   => $currency,
                    'balance'    => $balance,
                    'receivable' => $receivable,
                    'net'        => $net
                ];
            }
        }

        return view('frontend.clients.statement', compact(
            'client',
            'logs',
            'balances',
            'summary',
            'currencies'
        ));
    }
}
