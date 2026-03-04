@extends('frontend.layouts.app')

@section('content')

    @php
        $isRefund = $invoice->is_refund;
    @endphp

    <style>
        @media print {
            body { background:white !important; }
            .no-print { display:none !important; }
            .print-container { box-shadow:none !important; border:none !important; }
            .print-header {
                border-bottom:2px solid #000;
                padding-bottom:10px;
                margin-bottom:20px;
            }
        }
    </style>

    <div class="p-6 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>

                <h1 class="text-2xl font-bold
                {{ $isRefund ? 'text-red-600' : 'text-gray-800 dark:text-gray-100' }}">
                    {{ $isRefund ? 'فاتورة مسترجع' : 'تفاصيل الفاتورة' }}
                    #{{ $invoice->id }}
                </h1>

                <p class="text-sm text-gray-500">
                    تاريخ الإنشاء: {{ $invoice->created_at->format('Y-m-d') }}
                </p>

                <div class="print-header flex justify-between items-center mt-4">

                    <div>
                        <h2 class="text-xl font-bold
                        {{ $isRefund ? 'text-red-600' : '' }}">
                            {{ $isRefund ? 'إشعار دائن (Credit Note)' : 'فاتورة رسمية' }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            رقم الفاتورة: #{{ $invoice->id }}
                        </p>

                        @if($isRefund)
                            <p class="text-sm text-red-600">
                                مرتبطة بالفاتورة رقم
                                #{{ $invoice->reversed_invoice_id }}
                            </p>
                        @endif

                    </div>

                    <div class="text-right">
                        <p class="font-bold">اسم الشركة</p>
                        <p class="text-sm text-gray-500">
                            صنعاء - اليمن
                        </p>
                    </div>

                </div>

            </div>

            {{-- الأزرار --}}
            <div class="flex gap-3 no-print">

                <a href="{{ route('dashboard.invoices.index') }}"
                   class="px-4 py-2 rounded-xl bg-gray-400 text-white text-sm">
                    رجوع
                </a>

                <button onclick="window.print()"
                        class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm">
                    طباعة
                </button>

                <a href="{{ route('dashboard.invoices.pdf', $invoice->id) }}"
                   class="px-4 py-2 rounded-xl bg-purple-600 text-white text-sm">
                    تحميل PDF
                </a>

                {{-- زر دفع يظهر فقط للفواتير العادية --}}
                @if(!$isRefund && $invoice->remaining_amount > 0)
                    <button onclick="openPaymentModal()"
                            class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm">
                        + تسجيل دفعة
                    </button>
                @endif

            </div>

        </div>


        {{-- بطاقة المعلومات --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
                border
                {{ $isRefund ? 'border-red-400 dark:border-red-600' : 'border-gray-200 dark:border-gray-700' }}
                p-8 space-y-6">

            @php
                $currency = $invoice->currency->symbol ?? '';
                $progress = $invoice->total_amount > 0
                    ? ($invoice->paid_amount / $invoice->total_amount) * 100
                    : 0;
            @endphp

            {{-- معلومات عامة --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <div class="text-sm text-gray-500">العميل</div>
                    <div class="font-bold text-lg">
                        {{ $invoice->client->full_name }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">الحالة</div>
                    <div class="font-semibold
                    @if($invoice->status === 'paid')
                        text-green-600
                    @elseif($invoice->status === 'partial')
                        text-yellow-600
                    @else
                        text-red-600
                    @endif">
                        {{ $invoice->status }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">العملة</div>
                    <div class="font-semibold">
                        {{ $invoice->currency->name ?? '' }}
                    </div>
                </div>

            </div>

            {{-- المبالغ --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

                <div>
                    <div class="text-sm text-gray-500">الإجمالي</div>
                    <div class="text-xl font-bold
                    {{ $isRefund ? 'text-red-600' : '' }}">
                        {{ number_format($invoice->total_amount,2) }} {{ $currency }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">المدفوع</div>
                    <div class="text-xl font-bold text-green-600">
                        {{ number_format($invoice->paid_amount,2) }} {{ $currency }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-gray-500">المتبقي</div>
                    <div class="text-xl font-bold text-red-600">
                        {{ number_format($invoice->remaining_amount,2) }} {{ $currency }}
                    </div>
                </div>

            </div>

            {{-- شريط السداد للفواتير العادية فقط --}}
            @unless($isRefund)
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>نسبة السداد</span>
                        <span>{{ round($progress) }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                        <div class="h-3 rounded-full
                    @if($invoice->status === 'paid')
                        bg-green-600
                    @elseif($invoice->status === 'partial')
                        bg-yellow-500
                    @else
                        bg-red-500
                    @endif"
                             style="width: {{ $progress }}%">
                        </div>
                    </div>
                </div>
            @endunless

            {{-- ملاحظة خاصة بالمسترجع --}}
            @if($isRefund)
                <div class="bg-red-50 dark:bg-red-900/40
                        text-red-700 dark:text-red-300
                        p-4 rounded-xl text-sm">
                    هذه فاتورة مسترجع (إشعار دائن).
                    تم إرجاع مبلغ للعميل بقيمة
                    {{ number_format($invoice->total_amount,2) }} {{ $currency }}.
                </div>
            @endif

        </div>


        {{-- سجل الحركات --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
                border border-gray-200 dark:border-gray-700 p-6">

            <h2 class="font-bold text-lg mb-6">
                سجل الحركات المالية
            </h2>

            <div class="space-y-4">

                @forelse($invoice->payments as $payment)

                    <div class="flex justify-between items-center
                            p-4 rounded-xl bg-gray-50 dark:bg-gray-900">

                        <div>
                            <div class="font-semibold
                            {{ $isRefund ? 'text-red-600' : '' }}">
                                {{ number_format($payment->amount,2) }} {{ $currency }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $payment->payment_method }}
                            </div>
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ $payment->created_at->format('Y-m-d H:i') }}
                        </div>

                    </div>

                @empty

                    <div class="text-gray-500 text-center py-6">
                        لا توجد حركات مسجلة
                    </div>

                @endforelse

            </div>

        </div>

    </div>

    @if(!$isRefund)
        @include('frontend.invoices.modals.add_payment')
    @endif

@endsection
