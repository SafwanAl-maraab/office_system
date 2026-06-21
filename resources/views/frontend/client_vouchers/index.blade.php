@extends('frontend.layouts.app')

@section('title','سندات العملاء')
@section('subtitle','إدارة سندات القبض والصرف')

@section('content')

    <div class="max-w-7xl mx-auto space-y-8">

        <!-- 1- STATS (البطاقات الأربع العلوية بعد التعديل والمطابقة مع الـ Controller) -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">
                    عدد سندات القبض
                </div>
                <div class="text-3xl font-bold mt-3">
                    {{ $receiptCount }}
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">
                    عدد سندات الصرف
                </div>
                <div class="text-3xl font-bold mt-3">
                    {{ $paymentCount }}
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">
                    سندات مفتوحة
                </div>
                <div class="text-3xl font-bold mt-3">
                    {{ $openVoucherCount }}
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">
                    إجمالي السندات
                </div>
                <div class="text-3xl font-bold mt-3">
                    {{ $vouchersCount }}
                </div>
            </div>

        </div>

        <!-- 2- قسم الأرصدة حسب العملة المفتوحة (مباشرة بعد البطاقات) -->
        @if(count($currencyBalances))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($currencyBalances as $balance)
                    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow border border-gray-100 dark:border-gray-800 p-5">
                        <div class="text-sm text-gray-500">
                            الرصيد المفتوح
                        </div>
                        <div class="text-2xl font-bold mt-2 text-gray-800 dark:text-white">
                            {{ number_format($balance['amount'], 2) }}
                            <span class="text-sm font-semibold text-gray-400">{{ $balance['code'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- TOP BAR -->
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">

            <form method="GET" class="w-full lg:w-auto">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="بحث بالاسم أو الهاتف أو الجواز..."
                    class="w-full lg:w-96 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </form>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('voucher-settlements.index') }}"
                   class="px-5 py-3 rounded-2xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-600/10 font-medium">
                    التسويات المالية
                </a>

                <button
                    id="openVoucherModal"
                    type="button"
                    class="px-5 py-3 rounded-2xl bg-green-600 text-white hover:bg-green-700 transition-colors shadow-lg shadow-green-600/10 font-medium">
                    + إنشاء سند
                </button>
            </div>

        </div>

        <!-- CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($vouchers as $voucher)
                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden flex flex-col justify-between">

                    <div>
                        <!-- HEADER -->
                        <div class="p-6">
                            <div class="flex justify-between items-start gap-3">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800 dark:text-white">
                                        {{ $voucher->client->full_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $voucher->client->phone }}
                                    </p>
                                </div>

                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($voucher->type == 'receipt')
                                        bg-green-100 text-green-600 dark:bg-green-950/40 dark:text-green-400
                                    @else
                                        bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400
                                    @endif">
                                    {{ $voucher->type == 'receipt' ? 'سند قبض' : 'سند صرف' }}
                                </span>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="px-6 pb-6 space-y-3 text-gray-800 dark:text-gray-200">

                            <div class="flex justify-between">
                                <span class="text-gray-500">العملة</span>
                                <b class="font-semibold">{{ $voucher->currency->code }}</b>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-500">المبلغ</span>
                                <b class="font-mono">{{ number_format($voucher->amount, 2) }}</b>
                            </div>

{{--                            <!-- 3- تصحيح اسم بيان حقل المستخدم داخل البطاقة -->--}}
{{--                            <div class="flex justify-between">--}}
{{--                                <span class="text-gray-500">المستخدم من السند</span>--}}
{{--                                <b class="text-indigo-600 font-mono">--}}
{{--                                    {{ number_format($voucher->allocated_amount ?? $voucher->allocations_sum_amount ?? 0, 2) }}--}}
{{--                                </b>--}}
{{--                            </div>--}}

{{--                            <div class="flex justify-between">--}}
{{--                                <span class="text-gray-500">المتبقي</span>--}}
{{--                                <b class="text-blue-600 font-mono">--}}
{{--                                    {{ number_format($voucher->amount - ($voucher->allocated_amount ?? $voucher->allocations_sum_amount ?? 0), 2) }}--}}
{{--                                </b>--}}
{{--                            </div>--}}

{{--                            <div class="flex justify-between">--}}
{{--                                <span class="text-gray-500">عدد التسويات</span>--}}
{{--                                <b class="font-mono">{{ $voucher->allocations_count }}</b>--}}
{{--                            </div>--}}

                            <div class="flex justify-between">
                                <span class="text-gray-500">الموظف</span>
                                <b class="font-medium">{{ $voucher->employee->full_name ?? '-' }}</b>
                            </div>

                            <!-- 4- إضافة حقل تاريخ السند بعد حقل الموظف مباشرة -->
                            <div class="flex justify-between">
                                <span class="text-gray-500">التاريخ</span>
                                <b class="font-mono text-sm">{{ $voucher->created_at->format('Y-m-d') }}</b>
                            </div>

                        </div>
                    </div>

                    <div>
                        <!-- 5- معالجة وإصلاح بلوك الحالة المطور والمحدث -->
                        <div class="px-6 pb-4">
                            @php
                                $allocated = $voucher->allocated_amount ?? $voucher->allocations_sum_amount ?? 0;
                                $remaining = $voucher->amount - $allocated;
                            @endphp

                            @if($remaining == $voucher->amount)
                                <div class="w-full py-2 text-center text-sm font-semibold rounded-xl bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                                    غير مستخدم
                                </div>
                            @elseif($remaining > 0)
                                <div class="w-full py-2 text-center text-sm font-semibold rounded-xl bg-yellow-100 text-yellow-600 dark:bg-yellow-950/40 dark:text-yellow-400">
                                    مستخدم جزئياً
                                </div>
                            @else
                                <div class="w-full py-2 text-center text-sm font-semibold rounded-xl bg-green-100 text-green-600 dark:bg-green-950/40 dark:text-green-400">
                                    مسوى بالكامل
                                </div>
                            @endif
                        </div>

                        <!-- 6- روابط العمليات بعد تحويلها وتعديلها للمسارات الجديدة الموجهة لـ Resource -->
                        <div class="border-t border-gray-100 dark:border-gray-800 p-4 flex justify-between bg-gray-50/50 dark:bg-gray-900/50">
                            <a href="{{ route('client-vouchers.show', $voucher) }}"
                               class="text-blue-600 dark:text-blue-400 text-sm font-bold hover:underline">
                                التفاصيل
                            </a>

                            <a
{{--                                href="{{ route('client-vouchers.allocations', $voucher) }}"--}}
                               class="text-indigo-600 dark:text-indigo-400 text-sm font-bold hover:underline">
                                التسويات
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-16 text-center text-gray-400 border border-gray-100 dark:border-gray-800">
                        لا توجد سندات حالياً
                    </div>
                </div>
            @endforelse

        </div>

        <!-- PAGINATION -->
        <div class="pt-4">
            {{ $vouchers->links() }}
        </div>

    </div>

    @include('frontend.client_vouchers.partials.modal')

@endsection
