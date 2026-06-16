@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">

            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    تسوية أرصدة العملاء
                </h1>

                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    الفواتير التي يمكن تسويتها من أرصدة العملاء
                </p>
            </div>

        </div>

        <form method="GET"
              class="mb-8">

            <div class="relative">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="بحث باسم العميل أو الجواز أو رقم الفاتورة..."
                    class="w-full rounded-2xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-4 focus:ring-2 focus:ring-blue-500">

            </div>

        </form>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            @forelse($invoices as $invoice)

                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-lg border border-gray-200 dark:border-gray-800 overflow-hidden"
                     data-invoice-card

                     data-client-id="{{ $invoice->client_id }}"
                     data-client-name="{{ $invoice->client->full_name }}"

                     data-total="{{ $invoice->total_amount }}"
                     data-paid="{{ $invoice->paid_amount }}"
                     data-remaining="{{ $invoice->remaining_amount }}"

                     data-currency-id="{{ $invoice->currency_id }}"
                     data-currency-code="{{ $invoice->currency->code }}"

                     data-balances='@json($invoice->balances)'

                >

                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">

                        <div class="flex justify-between items-start">



                            <div>

                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">

                                    {{ $invoice->client->full_name }}

                                </h3>

                                <p class="text-sm text-gray-500 mt-1">

                                    {{ $invoice->client->phone }}

                                </p>

                            </div>

                            @if($invoice->can_settle)

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">

                قابلة للتسوية

            </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">

                لا يوجد رصيد

            </span>

                            @endif

                        </div>

                    </div>

                    <div class="p-6">

                        <div class="grid grid-cols-2 gap-4">

                            <div>

                                <div class="text-xs text-gray-500">
                                    نوع العملية
                                </div>

                                <div class="font-semibold">

                                    {{ $invoice->operation_title }}

                                </div>

                            </div>

                            <div>

                                <div class="text-xs text-gray-500">
                                    رقم العملية
                                </div>

                                <div class="font-semibold">

                                    {{ $invoice->operation_number }}

                                </div>

                            </div>

                        </div>


                        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">

                            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-4">

                                <div class="text-xs text-gray-500">
                                    الإجمالي
                                </div>

                                <div class="font-bold mt-1">

                                    {{ number_format($invoice->total_amount,2) }}

                                </div>

                            </div>

                            <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl p-4">

                                <div class="text-xs text-gray-500">
                                    المدفوع
                                </div>

                                <div class="font-bold mt-1 text-green-600">

                                    {{ number_format($invoice->paid_amount,2) }}

                                </div>

                            </div>

                            <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-4">

                                <div class="text-xs text-gray-500">
                                    المتبقي
                                </div>

                                <div class="font-bold mt-1 text-red-600">

                                    {{ number_format($invoice->remaining_amount,2) }}

                                </div>

                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-4">

                                <div class="text-xs text-gray-500">
                                    العملة
                                </div>

                                <div class="font-bold mt-1">

                                    {{ $invoice->currency->code }}

                                </div>

                            </div>

                        </div>

                        @if($invoice->refund_amount > 0)

                            <div class="mt-6 bg-orange-50 dark:bg-orange-900/20 rounded-2xl p-4">

                                <div class="text-orange-700 font-semibold">

                                    مسترجع سابق

                                    {{ number_format($invoice->refund_amount,2) }}

                                    {{ $invoice->currency->code }}

                                </div>

                            </div>

    @endif

                        <div class="mt-6">

                            <h4 class="font-semibold mb-3">

                                الأرصدة المتاحة

                            </h4>

                            <div class="flex flex-wrap gap-3">

                                @foreach($invoice->balances as $balance)

                                    <div class="px-4 py-2 rounded-2xl bg-gray-100 dark:bg-gray-800">

                                        {{ $balance['currency_code'] }}

                                        :

                                        {{ number_format($balance['balance'],2) }}

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        <div class="mt-6">

                            @if($invoice->can_settle)

                                <button
                                    class="openSettlementModal w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-semibold"
                                    data-invoice="{{ $invoice->id }}">

                                    تسوية الفاتورة

                                </button>

                            @endif

                        </div>

                    </div>
                </div>

            @empty

                <div class="col-span-full">

                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-16 text-center">

                        <div class="text-gray-500">

                            لا توجد فواتير بحاجة للتسوية

                        </div>

                    </div>

                </div>

            @endforelse

        </div>

        <div class="mt-10">

            {{ $invoices->links() }}

        </div>

    </div>

    @include('frontend.voucher_settlements.partials.modal')

@endsection


