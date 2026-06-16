@extends('frontend.layouts.app')

@section('title','أسعار الصرف')

@section('content')

    <div class="max-w-7xl mx-auto space-y-8">

        <!-- HEADER -->

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div>

                <h2 class="text-2xl font-bold">
                    أسعار الصرف
                </h2>

                <p class="text-gray-500">
                    إدارة أسعار تحويل العملات
                </p>

            </div>

            <button
                type="button"
                data-open-rate
                class="px-6 py-3 rounded-2xl bg-blue-600 text-white">

                + إضافة سعر صرف

            </button>

        </div>

        <!-- STATS -->

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow">

                <div class="text-gray-500 text-sm">

                    إجمالي الأسعار

                </div>

                <div class="text-3xl font-bold mt-2">

                    {{ $rates->count() }}

                </div>

            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow">

                <div class="text-gray-500 text-sm">

                    آخر تحديث

                </div>

                <div class="text-lg font-bold mt-2">

                    {{ optional($rates->first())->updated_at?->format('Y-m-d') ?? '-' }}

                </div>

            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow">

                <div class="text-gray-500 text-sm">

                    العملات

                </div>

                <div class="text-3xl font-bold mt-2">

                    {{ $currencies->count() }}

                </div>

            </div>

        </div>

        <!-- CARDS -->

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($rates as $rate)

                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow p-6">

                    <div class="flex justify-between items-center">

                        <div class="font-bold">

                            {{ $rate->fromCurrency->code }}

                            →

                            {{ $rate->toCurrency->code }}

                        </div>

                        <span
                            class="text-xs px-3 py-1 rounded-full bg-green-100 text-green-600">

                        فعال

                    </span>

                    </div>

                    <div class="mt-5">

                        <div class="text-gray-500 text-sm">

                            سعر الصرف

                        </div>

                        <div class="text-4xl font-bold mt-2">

                            {{ $rate->rate }}

                        </div>

                    </div>

                    <div class="mt-5 text-sm text-gray-500">

                        {{ $rate->rate_date }}

                    </div>

                    <div class="mt-5">

                        <button
                            type="button"
                            data-edit-rate
                            data-rate='@json($rate)'
                            class="px-4 py-2 rounded-xl bg-yellow-100 text-yellow-600">

                            تعديل

                        </button>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

    @include('frontend.exchange_rates.partials.modal')

@endsection
