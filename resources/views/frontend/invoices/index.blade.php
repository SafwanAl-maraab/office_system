@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-10">

        {{-- Header --}}
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                إدارة الفواتير
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                جميع الفواتير (عادية + مسترجعة)
            </p>
        </div>


        {{-- فلترة متكاملة --}}
        <form method="GET"
              class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5
                 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

            {{-- رقم الفاتورة --}}
            <input type="number"
                   name="invoice_number"
                   value="{{ request('invoice_number') }}"
                   placeholder="رقم الفاتورة"
                   class="px-4 py-2 rounded-xl border dark:bg-gray-900">

            {{-- اسم العميل --}}
            <input type="text"
                   name="client"
                   value="{{ request('client') }}"
                   placeholder="اسم العميل"
                   class="px-4 py-2 rounded-xl border dark:bg-gray-900">

            {{-- نوع الفاتورة --}}
            <select name="type"
                    class="px-4 py-2 rounded-xl border dark:bg-gray-900">
                <option value="">كل الأنواع</option>
                <option value="normal" {{ request('type')=='normal'?'selected':'' }}>
                    عادية
                </option>
                <option value="refund" {{ request('type')=='refund'?'selected':'' }}>
                    مسترجعة
                </option>
                <option value="booking" {{ request('type')=='booking'?'selected':'' }}>
                    حجز سفر
                </option>

                <option value="visa" {{ request('type')=='visa'?'selected':'' }}>
                    تاشيرات
                </option>
                <option value="request" {{ request('type')=='request'?'selected':'' }}>
                    طلب
                </option>

            </select>

            {{-- حالة الفاتورة --}}
            <select name="status"
                    class="px-4 py-2 rounded-xl border dark:bg-gray-900">
                <option value="">كل الحالات</option>
                <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>
                    غير مدفوعة
                </option>
                <option value="partial" {{ request('status')=='partial'?'selected':'' }}>
                    مدفوعة جزئيًا
                </option>
                <option value="paid" {{ request('status')=='paid'?'selected':'' }}>
                    مدفوعة بالكامل
                </option>
            </select>

            <button class="bg-blue-600 hover:bg-blue-700
                       text-white rounded-xl px-4 py-2">
                تطبيق
            </button>

        </form>


        {{-- بطاقات الفواتير --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($invoices as $invoice)

                @php
                    $reference_type = $invoice->reference_type;
                    $requestName = $invoice->request->requestType->name ?? 'طلب غير معروف';
                    $visaName = $invoice->visa->visaType->name ?? 'تأشيرة غير معروف';
                    $isRefund = $invoice->is_refund;
                    $currency = $invoice->currency->symbol ?? '';
                    $progress = $invoice->total_amount > 0
                        ? ($invoice->paid_amount / $invoice->total_amount) * 100
                        : 0;

                    // ترجمة حالة العملية المرتبطة
                    $relatedStatus = '—';
                    $relatedStatusClass = 'text-gray-500';

                    if ($reference_type === 'booking' && $invoice->booking) {
                        $statusRaw = $invoice->booking->status;
                    } elseif ($reference_type === 'visa' && $invoice->visa) {
                        $statusRaw = $invoice->visa->status;
                    } elseif ($reference_type === 'request' && $invoice->request) {
                        $statusRaw = $invoice->request->status;
                    } else {
                        $statusRaw = null;
                    }

                    if ($statusRaw) {
                        if (in_array($statusRaw, ['confirmed', 'paid', 'completed', 'active'])) {
                            $relatedStatus = 'مؤكد';
                            $relatedStatusClass = 'text-green-600 dark:text-green-400';
                        } elseif (in_array($statusRaw, ['cancelled', 'rejected', 'refunded'])) {
                            $relatedStatus = 'ملغي';
                            $relatedStatusClass = 'text-red-600 dark:text-red-400';
                        } else {
                            $relatedStatus = 'قيد الانتظار';
                            $relatedStatusClass = 'text-amber-600 dark:text-amber-400';
                        }
                    }
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
                        border
                        {{ $isRefund
                            ? 'border-red-400 dark:border-red-600'
                            : 'border-gray-200 dark:border-gray-700' }}
                        p-6 space-y-5 transition hover:shadow-2xl flex flex-col justify-between">

                    <div class="space-y-4">
                        {{-- رأس البطاقة --}}
                        <div class="flex justify-between items-start">

                            <div>
                                <div class="text-xs text-gray-500">
                                    فاتورة #
                                </div>
                                <div class="font-bold text-lg font-mono text-gray-900 dark:text-white">
                                    {{ $invoice->id }}
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5 items-end">
                                {{-- تسمية نوع العملية عربي --}}
                                <span class="text-xs px-3 py-1 rounded-full font-medium
                                    {{ $isRefund
                                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                        : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                    @if($reference_type === "booking")
                                        حجز سفر
                                    @elseif($reference_type === "visa")
                                        تأشيرة ({{ $visaName }})
                                    @elseif($reference_type === "request")
                                        طلب ({{ $requestName }})
                                    @elseif($reference_type === "refund" || $isRefund)
                                        مسترجع
                                    @else
                                        فاتورة عادية
                                    @endif
                                </span>

                                {{-- حالة الفاتورة عربي وملون --}}
                                <span class="text-xs px-3 py-1 rounded-full font-semibold
                                    @if($invoice->status=='paid')
                                        bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($invoice->status=='partial')
                                        bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                                    @else
                                        bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    @if($invoice->status == 'paid')
                                        مدفوعة بالكامل
                                    @elseif($invoice->status == 'partial')
                                        مدفوعة جزئيًا
                                    @else
                                        غير مدفوعة
                                    @endif
                                </span>
                            </div>

                        </div>

                        {{-- في حال مسترجعة --}}
                        @if($isRefund)
                            <div class="text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/20 px-3 py-1.5 rounded-xl border border-red-100 dark:border-red-900/30">
                                مرتبطة بالفاتورة الأصلية رقم #{{ $invoice->reversed_invoice_id }}
                            </div>
                        @endif

                        {{-- العميل ومعلومات التوقيت --}}
                        <div class="space-y-2 border-b border-gray-100 dark:border-gray-700/50 pb-3">
                            <div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">العميل</div>
                                <div class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $invoice->client->full_name }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1 text-xs">
                                <div>
                                    <span class="text-gray-400">تاريخ الإنشاء:</span>
                                    <span class="font-medium text-gray-600 dark:text-gray-400 font-mono block mt-0.5">
                                        {{ $invoice->created_at->format('Y-m-d H:i') }}
                                    </span>
                                </div>
                                @if($reference_type && $reference_type !== 'refund')
                                    <div class="text-left">
                                        <span class="text-gray-400">حالة العملية:</span>
                                        <span class="font-bold block mt-0.5 {{ $relatedStatusClass }}">
                                        {{ $relatedStatus }}
                                    </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- المبالغ المأخوذة مالياً --}}
                        <div class="grid grid-cols-3 gap-2 text-center bg-gray-50 dark:bg-gray-900/40 p-3 rounded-xl border border-gray-100 dark:border-gray-700/30">
                            <div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mb-1">الإجمالي</div>
                                <div class="font-extrabold text-sm text-gray-800 dark:text-gray-200 font-mono">
                                    {{ number_format($invoice->total_amount, 2) }} <span class="text-[10px] font-normal text-gray-500">{{ $currency }}</span>
                                </div>
                            </div>

                            <div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mb-1">المدفوع</div>
                                <div class="font-extrabold text-sm text-emerald-600 dark:text-emerald-400 font-mono">
                                    {{ number_format($invoice->paid_amount, 2) }} <span class="text-[10px] font-normal text-gray-500">{{ $currency }}</span>
                                </div>
                            </div>

                            <div>
                                <div class="text-xs text-gray-400 dark:text-gray-500 mb-1">المتبقي</div>
                                <div class="font-extrabold text-sm text-rose-600 dark:text-rose-400 font-mono">
                                    {{ number_format($invoice->remaining_amount, 2) }} <span class="text-[10px] font-normal text-gray-500">{{ $currency }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- شريط السداد (للعادية فقط) --}}
                        @unless($isRefund)
                            <div class="pt-1">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-400">نسبة السداد والمحصلة</span>
                                    <span class="font-bold text-gray-700 dark:text-gray-300 font-mono">{{ round($progress) }}%</span>
                                </div>

                                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                                    <div class="h-2 rounded-full bg-emerald-500 transition-all duration-500"
                                         style="width: {{ $progress }}%">
                                    </div>
                                </div>
                            </div>
                        @endunless
                    </div>

                    {{-- الأزرار والإجراءات التشغيلية --}}
                    <div class="flex gap-2 pt-2">
                        {{-- زر عرض التفاصيل --}}
                        <a href="{{ route('dashboard.invoices.show',$invoice->id) }}"
                           class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-blue-600/10 active:scale-[0.98] transition-all">
                            عرض التفاصيل
                        </a>

                        @if(!$invoice->is_refund && $invoice->paid_amount > 0)
                            <button
                                onclick="openRefundInvoiceModal(
                                    {{ $invoice->id }},
                                    {{ $invoice->paid_amount }},
                                    '{{ $invoice->client->full_name }}',
                                    '{{ $invoice->currency->symbol }}'
                                )"
                                class="flex-1 text-center bg-rose-600 hover:bg-rose-700 text-white rounded-xl py-2.5 text-sm font-semibold shadow-md shadow-rose-600/10 active:scale-[0.98] transition-all">
                                إنشاء مسترجع
                            </button>
                        @endif
                    </div>
                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-20 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    📦 لا توجد أي فواتير مطابقة لخيارات الفرز الحالية
                </div>

            @endforelse

        </div>

        <div class="pt-4">
            {{ $invoices->links() }}
        </div>

    </div>
    @include('frontend.invoices.modals.create_refund')
@endsection
