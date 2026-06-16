@extends('frontend.layouts.app')

@section('content')

    @php
        $isRefund = $invoice->is_refund;
        $reference_type = $invoice->reference_type;
        $currency = $invoice->currency->symbol ?? '';
        $progress = $invoice->total_amount > 0 ? ($invoice->paid_amount / $invoice->total_amount) * 100 : 0;

        // استخراج وتحديد مسمى العملية المرتبطة وتفاصيلها لعرضها
        $operationTitle = 'فاتورة عامة';
        $operationDetail = null;
        $relatedStatus = '—';
        $relatedStatusClass = 'text-gray-500';
        $statusRaw = null;

        if ($reference_type === 'booking' && $invoice->booking) {
            $operationTitle = 'حجز سفر';
            $statusRaw = $invoice->booking->status;
        } elseif ($reference_type === 'visa' && $invoice->visa) {
            $operationTitle = 'تأشيرة';
            $operationDetail = $invoice->visa->visaType->name ?? null;
            $statusRaw = $invoice->visa->status;
        } elseif ($reference_type === 'request' && $invoice->request) {
            $operationTitle = 'طلب خدمات';
            $operationDetail = $invoice->request->requestType->name ?? null;
            $statusRaw = $invoice->request->status;
        }

        if ($statusRaw) {
            if (in_array($statusRaw, ['confirmed', 'paid', 'completed', 'active'])) {
                $relatedStatus = 'مؤكد';
                $relatedStatusClass = 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
            } elseif (in_array($statusRaw, ['cancelled', 'rejected', 'refunded'])) {
                $relatedStatus = 'ملغي';
                $relatedStatusClass = 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
            } else {
                $relatedStatus = 'قيد الانتظار';
                $relatedStatusClass = 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
            }
        }
    @endphp

    <style>
        /* تنسيقات مخصصة للطباعة لتبدو كفواتير الشركات الكبرى */
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .no-print { display: none !important; }
            .print-container { box-shadow: none !important; border: none !important; p: 0 !important; }
            .print-bill-header { display: flex !important; }
            .card-wrapper { border: 1px solid #e2e8f0 !important; border-radius: 12px !important; box-shadow: none !important; }
        }
    </style>

    <div class="p-4 md:p-6 space-y-8 print-container">

        {{-- الترويسة الرسمية الخاصة بالطباعة فقط (مخفية في المتصفح تلقائياً) --}}
        <div class="hidden print-bill-header justify-between items-center pb-6 border-b-2 border-slate-900 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $isRefund ? 'إشعار دائن (Credit Note)' : 'فاتورة حساب رسمية' }}
                </h2>
                <p class="text-sm text-slate-500 font-mono mt-1">رقم المستند: #{{ $invoice->id }}</p>
                <p class="text-sm text-slate-500 mt-0.5">التاريخ: {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                @if($isRefund)
                    <p class="text-xs text-red-600 mt-1 font-semibold">مرتبط بالفاتورة الأصلية رقم: #{{ $invoice->reversed_invoice_id }}</p>
                @endif
            </div>
            <div class="text-left">
                <h3 class="text-lg font-bold text-slate-900">اسم الشركة للنظم والتوكيلات</h3>
                <p class="text-sm text-slate-500 mt-0.5">صنعاء - الجمهورية اليمنية</p>
                <p class="text-xs text-slate-400 font-mono">الهاتف: 777-xxx-xxx</p>
            </div>
        </div>

        {{-- الترويسة البرمجية للموقع (مخفية أثناء الطباعة) --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 no-print">
            <div>
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full {{ $isRefund ? 'bg-rose-600' : 'bg-blue-600' }}"></span>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                        {{ $isRefund ? 'فاتورة مسترجع' : 'تفاصيل الفاتورة الحالية' }}
                        <span class="font-mono text-gray-400">#{{ $invoice->id }}</span>
                    </h1>
                </div>
                <p class="text-sm text-gray-500 mt-1.5 flex items-center gap-1">
                    📅 تاريخ الإنشاء الفعلي للطلب: <span class="font-mono font-medium">{{ $invoice->created_at->format('Y-m-d H:i') }}</span>
                </p>
            </div>

            {{-- لوحة أزرار الإجراءات الفورية --}}
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('dashboard.invoices.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 text-sm font-semibold transition-all flex items-center gap-1.5">
                    ⬅️ رجوع
                </a>

                <button onclick="window.print()"
                        class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-600/10 transition-all flex items-center gap-1.5">
                    🖨️ طباعة السند
                </button>

                <a href="{{ route('dashboard.invoices.pdf', $invoice->id) }}"
                   class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold shadow-md shadow-purple-600/10 transition-all flex items-center gap-1.5">
                    📥 تحميل PDF
                </a>

                @if(!$isRefund && $invoice->remaining_amount > 0)
                    <button onclick="openPaymentModal()"
                            class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md shadow-emerald-600/10 transition-all flex items-center gap-1.5">
                        ➕ تسجيل دفعة مالية
                    </button>
                @endif
            </div>
        </div>

        {{-- بطاقة المعلومات والتحليلات الرئيسية --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border card-wrapper
                {{ $isRefund ? 'border-rose-300 dark:border-rose-900/50' : 'border-gray-200 dark:border-gray-700' }}
                p-6 md:p-8 space-y-6">

            {{-- 1. القسم الأول: البيانات العامة التوضيحية --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pb-6 border-b border-gray-100 dark:border-gray-700/50">
                <div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">العميل المستفيد</div>
                    <div class="font-bold text-lg text-gray-800 dark:text-gray-100 mt-1">
                        {{ $invoice->client->full_name }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">حالة الفاتورة المالي</div>
                    <div class="mt-1">
                        <span class="inline-flex text-xs px-3 py-1 rounded-full font-bold
                            @if($invoice->status === 'paid')
                                bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                            @elseif($invoice->status === 'partial')
                                bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                            @else
                                bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400
                            @endif">
                            @if($invoice->status === 'paid')
                                مدفوعة بالكامل
                            @elseif($invoice->status === 'partial')
                                مدفوعة جزئيًا
                            @else
                                غير مدفوعة
                            @endif
                        </span>
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">العملة المعتمدة</div>
                    <div class="font-bold text-gray-800 dark:text-gray-200 mt-1 flex items-center gap-1.5">
                        <span class="text-sm px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-700 font-mono font-bold">{{ $currency }}</span>
                        {{ $invoice->currency->name ?? 'غير محددة' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">العملية الأساسية / حالتها</div>
                    <div class="mt-1 flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">
                            {{ $operationTitle }} @if($operationDetail) <span class="text-xs font-normal text-gray-400">({{ $operationDetail }})</span> @endif
                        </span>
                        @if($reference_type && $reference_type !== 'refund')
                            <span class="text-[10px] px-2 py-0.5 rounded-md font-bold {{ $relatedStatusClass }}">
                                {{ $relatedStatus }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. القسم الثاني: المبالغ المالية الإجمالية الفاتورة --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center bg-gray-50 dark:bg-gray-900/40 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/30">
                <div class="p-2">
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500">مبلغ الإجمالي</div>
                    <div class="text-2xl font-black mt-1.5 font-mono {{ $isRefund ? 'text-rose-600' : 'text-gray-900 dark:text-white' }}">
                        {{ number_format($invoice->total_amount, 2) }} <span class="text-xs font-normal text-gray-400">{{ $currency }}</span>
                    </div>
                </div>

                <div class="p-2 border-y md:border-y-0 md:border-x border-gray-200 dark:border-gray-700">
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500">المبلغ المدفوع حتي الآن</div>
                    <div class="text-2xl font-black mt-1.5 font-mono text-emerald-600 dark:text-emerald-400">
                        {{ number_format($invoice->paid_amount, 2) }} <span class="text-xs font-normal text-gray-400">{{ $currency }}</span>
                    </div>
                </div>

                <div class="p-2">
                    <div class="text-xs font-semibold text-gray-400 dark:text-gray-500">المبلغ المتبقي المطلوب</div>
                    <div class="text-2xl font-black mt-1.5 font-mono text-rose-600 dark:text-rose-400">
                        {{ number_format($invoice->remaining_amount, 2) }} <span class="text-xs font-normal text-gray-400">{{ $currency }}</span>
                    </div>
                </div>
            </div>

            {{-- 3. القسم الثالث: مؤشر شريط السداد النسبي (للفواتير العادية فقط) --}}
            @unless($isRefund)
                <div class="pt-2">
                    <div class="flex justify-between text-xs font-semibold mb-2">
                        <span class="text-gray-400">نسبة التغطية والسداد الإجمالية</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200 font-mono">{{ round($progress) }}%</span>
                    </div>

                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden border border-gray-200/20 dark:border-gray-600/20">
                        <div class="h-full rounded-full transition-all duration-500
                            @if($invoice->status === 'paid')
                                bg-emerald-500
                            @elseif($invoice->status === 'partial')
                                bg-amber-500
                            @else
                                bg-rose-500
                            @endif"
                             style="width: {{ $progress }}%">
                        </div>
                    </div>
                </div>
            @endunless

            {{-- تنبيه خاص للمسترجعات والإشعارات الدائنة --}}
            @if($isRefund)
                <div class="bg-rose-50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-400 p-4 rounded-xl text-sm border border-rose-100 dark:border-rose-900/30 flex items-center gap-2.5 font-medium">
                    <span>⚠️</span>
                    <div>
                        هذا المستند يمثل <strong>إشعار دائن (فاتورة مسترجعة)</strong>، حيث تم رد قيد بقيمة مالية تبلغ
                        <span class="font-mono font-bold text-rose-600 dark:text-rose-400">{{ number_format($invoice->total_amount, 2) }} {{ $currency }}</span> لحساب العميل المذكور أعلاه.
                    </div>
                </div>
            @endif
        </div>

        {{-- سجل الحركات والمدفوعات القييدية المرتبطة بالفاتورة --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 card-wrapper">
            <div class="flex items-center gap-2 mb-6 border-b border-gray-100 dark:border-gray-700/50 pb-4">
                <span class="text-xl">📊</span>
                <h2 class="font-bold text-lg text-gray-800 dark:text-gray-100">
                    كشف سجل الحركات والقيود المالية الملحقة
                </h2>
            </div>

            <div class="space-y-3.5">
                @forelse($invoice->payments as $payment)
                    <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 hover:border-gray-200 transition-all">
                        <div class="space-y-1">
                            <div class="font-black text-base font-mono {{ $isRefund ? 'text-rose-600' : 'text-slate-800 dark:text-slate-200' }}">
                                {{ $isRefund ? '-' : '+' }} {{ number_format($payment->amount, 2) }} <span class="text-xs font-normal text-gray-500">{{ $currency }}</span>
                            </div>
                            <div class="text-xs text-gray-400 dark:text-gray-500 flex items-center gap-1.5 font-medium">
                                💳 طريقة الدفع المعتمدة: <span class="text-gray-600 dark:text-gray-300">{{ $payment->payment_method ?? 'نقداً' }}</span>
                            </div>
                        </div>

                        <div class="text-xs md:text-sm text-gray-500 font-mono bg-white dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200/50 dark:border-gray-700/50 shadow-sm">
                            ⏰ {{ $payment->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                @empty
                    <div class="text-gray-400 dark:text-gray-500 text-center py-10 border border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                        📥 لا توجد أي دفعات أو قيود مالية مسجلة على هذه الفاتورة حتى الآن.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- تضمين مودال إضافة دفعات مالية للفواتير النشطة فقط --}}
    @if(!$isRefund)
        @include('frontend.invoices.modals.add_payment')
    @endif

@endsection
