<div
    class="grid
           grid-cols-1
           xl:grid-cols-3
           gap-6">

    {{-- ===================================== --}}
    {{-- التأشيرات --}}
    {{-- ===================================== --}}

    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               overflow-hidden">

        <div
            class="bg-blue-600
                   text-white
                   p-5">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="text-xl font-bold">

                        التأشيرات

                    </h3>

                    <p class="text-blue-100 text-sm">

                        تحليل أرباح التأشيرات

                    </p>

                </div>

                <div class="text-4xl">

                    🛂

                </div>

            </div>

        </div>

        <div class="p-5 space-y-4">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <p class="text-xs text-gray-500">
                        عدد العمليات
                    </p>

                    <h4 class="font-bold text-xl">
                        {{ number_format($analysis['visas']['count']) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        المبيعات
                    </p>

                    <h4 class="font-bold text-blue-600">
                        {{ number_format($analysis['visas']['sales'],2) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        التكلفة
                    </p>

                    <h4 class="font-bold text-orange-600">
                        {{ number_format($analysis['visas']['cost'],2) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        المتبقي
                    </p>

                    <h4 class="font-bold text-red-600">
                        {{ number_format($analysis['visas']['remaining'],2) }}
                    </h4>
                </div>

            </div>

            <hr>

            <div>

                <div class="flex justify-between">

                    <span>
                        الربح المتوقع
                    </span>

                    <strong class="text-indigo-600">

                        {{ number_format($analysis['visas']['expected_profit'],2) }}

                    </strong>

                </div>

                <div class="mt-3 flex justify-between">

                    <span>
                        الربح المؤكد
                    </span>

                    <strong class="text-green-600">

                        {{ number_format($analysis['visas']['confirmed_profit'],2) }}

                    </strong>

                </div>

            </div>

            <div class="pt-4">

                @php

                    $margin =
                    $analysis['visas']['sales'] > 0

                    ? (

                    $analysis['visas']['expected_profit']

                    /

                    $analysis['visas']['sales']

                    ) * 100

                    : 0;

                @endphp

                <div class="flex justify-between text-sm">

                    <span>
                        هامش الربح
                    </span>

                    <span>

                        {{ number_format($margin,2) }} %

                    </span>

                </div>

                <div
                    class="mt-2 h-3 rounded-full bg-gray-200">

                    <div
                        class="h-3 rounded-full bg-blue-600"
                        style="width:{{ min($margin,100) }}%">

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- ===================================== --}}
    {{-- الحجوزات --}}
    {{-- ===================================== --}}

    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               overflow-hidden">

        <div
            class="bg-emerald-600
                   text-white
                   p-5">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="text-xl font-bold">

                        الحجوزات

                    </h3>

                    <p class="text-emerald-100 text-sm">

                        تحليل أرباح الحجوزات

                    </p>

                </div>

                <div class="text-4xl">

                    ✈️

                </div>

            </div>

        </div>

        <div class="p-5 space-y-4">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <p class="text-xs text-gray-500">
                        عدد العمليات
                    </p>

                    <h4 class="font-bold text-xl">
                        {{ number_format($analysis['bookings']['count']) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        المبيعات
                    </p>

                    <h4 class="font-bold text-blue-600">
                        {{ number_format($analysis['bookings']['sales'],2) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        التكلفة
                    </p>

                    <h4 class="font-bold text-orange-600">
                        {{ number_format($analysis['bookings']['cost'],2) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        المتبقي
                    </p>

                    <h4 class="font-bold text-red-600">
                        {{ number_format($analysis['bookings']['remaining'],2) }}
                    </h4>
                </div>

            </div>

            <hr>

            <div class="flex justify-between">
                <span>الربح المتوقع</span>

                <strong class="text-indigo-600">
                    {{ number_format($analysis['bookings']['expected_profit'],2) }}
                </strong>
            </div>

            <div class="flex justify-between">
                <span>الربح المؤكد</span>

                <strong class="text-green-600">
                    {{ number_format($analysis['bookings']['confirmed_profit'],2) }}
                </strong>
            </div>

        </div>

    </div>

    {{-- ===================================== --}}
    {{-- الطلبات --}}
    {{-- ===================================== --}}

    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               overflow-hidden">

        <div
            class="bg-purple-600
                   text-white
                   p-5">

            <div class="flex justify-between items-center">

                <div>

                    <h3 class="text-xl font-bold">

                        الطلبات

                    </h3>

                    <p class="text-purple-100 text-sm">

                        تحليل أرباح الطلبات

                    </p>

                </div>

                <div class="text-4xl">

                    🧾

                </div>

            </div>

        </div>

        <div class="p-5 space-y-4">

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <p class="text-xs text-gray-500">
                        عدد العمليات
                    </p>

                    <h4 class="font-bold text-xl">
                        {{ number_format($analysis['services']['count']) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        المبيعات
                    </p>

                    <h4 class="font-bold text-blue-600">
                        {{ number_format($analysis['services']['sales'],2) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        التكلفة
                    </p>

                    <h4 class="font-bold text-orange-600">
                        {{ number_format($analysis['services']['cost'],2) }}
                    </h4>
                </div>

                <div>
                    <p class="text-xs text-gray-500">
                        المتبقي
                    </p>

                    <h4 class="font-bold text-red-600">
                        {{ number_format($analysis['services']['remaining'],2) }}
                    </h4>
                </div>

            </div>

            <hr>

            <div class="flex justify-between">
                <span>الربح المتوقع</span>

                <strong class="text-indigo-600">
                    {{ number_format($analysis['services']['expected_profit'],2) }}
                </strong>
            </div>

            <div class="flex justify-between">
                <span>الربح المؤكد</span>

                <strong class="text-green-600">
                    {{ number_format($analysis['services']['confirmed_profit'],2) }}
                </strong>
            </div>

        </div>

    </div>

</div>
