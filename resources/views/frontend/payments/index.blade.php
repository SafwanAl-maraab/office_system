@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-10">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">

            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                    إدارة المدفوعات
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    عرض جميع العمليات المالية الخاصة بالفرع
                </p>
            </div>

            <div class="flex flex-wrap gap-3">

                <button onclick="openPaymentModal()"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm shadow">
                    + إضافة دفعة
                </button>


            </div>

        </div>


        {{-- فلترة --}}
        <form method="GET"
              class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5
             grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            {{-- بحث باسم العميل --}}
            <input type="text"
                   name="client"
                   value="{{ request('client') }}"
                   placeholder="بحث باسم العميل"
                   class="px-4 py-2 rounded-xl border
                  dark:bg-gray-900 dark:border-gray-700">

            {{-- بحث برقم الفاتورة --}}
            <input type="number"
                   name="invoice_number"
                   value="{{ request('invoice_number') }}"
                   placeholder="بحث برقم الفاتورة"
                   class="px-4 py-2 rounded-xl border
                  dark:bg-gray-900 dark:border-gray-700">

            {{-- زر البحث --}}
            <button class="bg-blue-600 hover:bg-blue-700
                   text-white rounded-xl px-4 py-2">
                بحث
            </button>

        </form>


        {{-- بطاقات المدفوعات --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($payments as $payment)

                @php
                    $isRefund = $payment->invoice->is_refund;

                    $currency = $payment->currency->symbol ?? '';
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
                        border border-gray-200 dark:border-gray-700
                        p-6 space-y-5 transition hover:shadow-2xl">

                    {{-- رأس البطاقة --}}
                    <div class="flex justify-between items-start">

                        <div>
                            <div class="text-xs text-gray-500">
                                عملية رقم
                            </div>
                            <div class="font-bold text-lg">
                                #{{ $payment->id }}
                            </div>
                        </div>

                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                        {{ $isRefund
                            ? 'bg-red-100 text-red-700'
                            : 'bg-green-100 text-green-700' }}">
                        {{ $isRefund ? 'استرجاع' : 'دفعة' }}
                    </span>

                    </div>


                    {{-- المبلغ --}}
                    <div class="text-center py-4 rounded-xl
                            {{ $isRefund
                                ? 'bg-red-50 dark:bg-red-900/30'
                                : 'bg-green-50 dark:bg-green-900/30' }}">

                        <div class="text-sm text-gray-500">
                            المبلغ
                        </div>

                        <div class="text-2xl font-bold
                        {{ $isRefund ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format(abs($payment->amount),2) }}
                            {{ $currency }}
                        </div>

                    </div>


                    {{-- التفاصيل --}}
                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between">
                            <span class="text-gray-500">العميل:</span>
                            <span class="font-semibold">
                            {{ $payment->invoice->client->full_name }}
                        </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">الفاتورة:</span>
                            <span>#{{ $payment->invoice->id }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">طريقة الدفع:</span>
                            @if($payment->payment_method === 'refund')
                            <span class="text-red-700 dark:bg-red-700 dark:text-red-100 rounded-xl py-1 ">{{ $payment->payment_method }}</span>
                            @else
                                <span>{{ $payment->payment_method }}</span>
                            @endif

                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">المنشئ:</span>
                            <span>{{ $payment->creator->full_name ?? '' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">التاريخ:</span>
                            <span>{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                        </div>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 py-16">
                    لا توجد عمليات مسجلة
                </div>

            @endforelse

        </div>

        <div>
            {{ $payments->links() }}
        </div>

    </div>

    @include('frontend.payments.modals.add_payment')


@endsection
