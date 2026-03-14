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
                $reference_type =$invoice->reference_type;
                $requestName =$invoice->request->requestType->name ?? 'طلب غير معروف';
                $visaName =$invoice->visa->visaType->name ?? 'تاشيرة غير معروف';
                    $isRefund = $invoice->is_refund;
                    $currency = $invoice->currency->symbol ?? '';
                    $progress = $invoice->total_amount > 0
                        ? ($invoice->paid_amount / $invoice->total_amount) * 100
                        : 0;
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
                        border
                        {{ $isRefund
                            ? 'border-red-400 dark:border-red-600'
                            : 'border-gray-200 dark:border-gray-700' }}
                        p-6 space-y-5 transition hover:shadow-2xl">

                    {{-- رأس البطاقة --}}
                    <div class="flex justify-between items-start">

                        <div>
                            <div class="text-xs text-gray-500">
                                فاتورة #
                            </div>
                            <div class="font-bold text-lg">
                                {{ $invoice->id }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-1 items-end">



 <span class="text-xs px-3 py-1 rounded-full
                            {{ $isRefund
                                ? 'bg-red-100 text-red-700'
                                : 'bg-blue-100 text-blue-700' }}">
@if($reference_type === "booking")

    {{$visaName}}
     @elseif($reference_type === "visa")
{{'تاشيرة'}}
     @elseif($reference_type === "request")
{{$requestName}}

     @elseif($reference_type === "refund")
{{'مسترجع'}}
     @endif


                        </span>

                            <span class="text-xs px-3 py-1 rounded-full
                            @if($invoice->status=='paid')
                                bg-green-100 text-green-700
                            @elseif($invoice->status=='partial')
                                bg-yellow-100 text-yellow-700
                            @else
                                bg-gray-200 text-gray-700
                            @endif
                        ">
                            {{ $invoice->status }}
                        </span>

                        </div>

                    </div>

                    {{-- في حال مسترجعة --}}
                    @if($isRefund)
                        <div class="text-xs text-red-600">
                            مرتبطة بالفاتورة رقم #{{ $invoice->reversed_invoice_id }}
                        </div>
                    @endif

                    {{-- العميل --}}
                    <div>
                        <div class="text-sm text-gray-500">العميل</div>
                        <div class="font-semibold">
                            {{ $invoice->client->full_name }}
                        </div>
                    </div>

                    {{-- المبالغ --}}
                    <div class="grid grid-cols-3 gap-3 text-center">

                        <div>
                            <div class="text-xs text-gray-500">الإجمالي</div>
                            <div class="font-bold">
                                {{ number_format($invoice->total_amount,2) }} {{ $currency }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">المدفوع</div>
                            <div class="font-bold text-green-600">
                                {{ number_format($invoice->paid_amount,2) }} {{ $currency }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">المتبقي</div>
                            <div class="font-bold text-red-600">
                                {{ number_format($invoice->remaining_amount,2) }} {{ $currency }}
                            </div>
                        </div>

                    </div>

                    {{-- شريط السداد (للعادية فقط) --}}
                    @unless($isRefund)
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span>نسبة السداد</span>
                                <span>{{ round($progress) }}%</span>
                            </div>

                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full bg-green-600"
                                     style="width: {{ $progress }}%">
                                </div>
                            </div>
                        </div>
                    @endunless
                    <div class="flex gap-2">
                    {{-- زر عرض التفاصيل --}}
                    <a href="{{ route('dashboard.invoices.show',$invoice->id) }}"
                       class="block w-full text-center bg-blue-600 hover:bg-blue-700
                          text-white py-2 rounded-xl text-sm mt-1">
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
                            class="block w-full text-center bg-red-600 hover:bg-red-700
               text-white  rounded-xl  py-2 text-sm mt-1">
                            إنشاء  مسترجع
                        </button>

                    @endif
                    </div>
                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 py-16">
                    لا توجد فواتير
                </div>

            @endforelse

        </div>

        <div>
            {{ $invoices->links() }}
        </div>

    </div>
    @include('frontend.invoices.modals.create_refund')
@endsection
