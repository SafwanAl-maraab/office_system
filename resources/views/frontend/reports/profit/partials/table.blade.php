<div
    class="bg-white dark:bg-gray-900
           rounded-3xl
           border border-gray-100
           dark:border-gray-800
           shadow-sm
           overflow-hidden">

    <div
        class="p-6 border-b
               border-gray-100
               dark:border-gray-800">

        <h3
            class="text-xl
                   font-bold
                   text-gray-800
                   dark:text-gray-100">

            التفاصيل المالية حسب النشاط

        </h3>

        <p
            class="text-sm
                   text-gray-500
                   mt-1">

            مقارنة شاملة بين التأشيرات والحجوزات والطلبات

        </p>

    </div>

    <div class="overflow-x-auto">

        <table
            class="w-full">

            <thead>

            <tr
                class="bg-gray-50
                       dark:bg-gray-800
                       text-gray-700
                       dark:text-gray-200">

                <th class="p-4 text-right">

                    النشاط

                </th>

                <th class="p-4">

                    عدد العمليات

                </th>

                <th class="p-4">

                    المبيعات

                </th>

                <th class="p-4">

                    التكلفة

                </th>

                <th class="p-4">

                    الربح المتوقع

                </th>

                <th class="p-4">

                    الربح المؤكد

                </th>

                <th class="p-4">

                    المتبقي

                </th>

                <th class="p-4">

                    هامش الربح %

                </th>

                <th class="p-4">

                    نسبة التحصيل %

                </th>

            </tr>

            </thead>

            <tbody>

            {{-- التأشيرات --}}

            @php

                $visaMargin =
                    $analysis['visas']['sales'] > 0
                    ? (
                        $analysis['visas']['expected_profit']
                        /
                        $analysis['visas']['sales']
                    ) * 100
                    : 0;

                $visaCollection =
                    $analysis['visas']['sales'] > 0
                    ? (
                        (
                            $analysis['visas']['sales']
                            -
                            $analysis['visas']['remaining']
                        )
                        /
                        $analysis['visas']['sales']
                    ) * 100
                    : 0;

            @endphp

            <tr
                class="border-b
                       dark:border-gray-800">

                <td class="p-4 font-bold">

                    🛂 التأشيرات

                </td>

                <td class="p-4 text-center">

                    {{ number_format($analysis['visas']['count']) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($analysis['visas']['sales'],2) }}

                </td>

                <td class="p-4 text-center text-orange-600">

                    {{ number_format($analysis['visas']['cost'],2) }}

                </td>

                <td class="p-4 text-center text-indigo-600 font-bold">

                    {{ number_format($analysis['visas']['expected_profit'],2) }}

                </td>

                <td class="p-4 text-center text-green-600 font-bold">

                    {{ number_format($analysis['visas']['confirmed_profit'],2) }}

                </td>

                <td class="p-4 text-center text-red-600">

                    {{ number_format($analysis['visas']['remaining'],2) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($visaMargin,2) }}%

                </td>

                <td class="p-4 text-center">

                    {{ number_format($visaCollection,2) }}%

                </td>

            </tr>

            {{-- الحجوزات --}}

            @php

                $bookingMargin =
                    $analysis['bookings']['sales'] > 0
                    ? (
                        $analysis['bookings']['expected_profit']
                        /
                        $analysis['bookings']['sales']
                    ) * 100
                    : 0;

                $bookingCollection =
                    $analysis['bookings']['sales'] > 0
                    ? (
                        (
                            $analysis['bookings']['sales']
                            -
                            $analysis['bookings']['remaining']
                        )
                        /
                        $analysis['bookings']['sales']
                    ) * 100
                    : 0;

            @endphp

            <tr
                class="border-b
                       dark:border-gray-800">

                <td class="p-4 font-bold">

                    ✈️ الحجوزات

                </td>

                <td class="p-4 text-center">

                    {{ number_format($analysis['bookings']['count']) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($analysis['bookings']['sales'],2) }}

                </td>

                <td class="p-4 text-center text-orange-600">

                    {{ number_format($analysis['bookings']['cost'],2) }}

                </td>

                <td class="p-4 text-center text-indigo-600 font-bold">

                    {{ number_format($analysis['bookings']['expected_profit'],2) }}

                </td>

                <td class="p-4 text-center text-green-600 font-bold">

                    {{ number_format($analysis['bookings']['confirmed_profit'],2) }}

                </td>

                <td class="p-4 text-center text-red-600">

                    {{ number_format($analysis['bookings']['remaining'],2) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($bookingMargin,2) }}%

                </td>

                <td class="p-4 text-center">

                    {{ number_format($bookingCollection,2) }}%

                </td>

            </tr>

            {{-- الطلبات --}}

            @php

                $serviceMargin =
                    $analysis['services']['sales'] > 0
                    ? (
                        $analysis['services']['expected_profit']
                        /
                        $analysis['services']['sales']
                    ) * 100
                    : 0;

                $serviceCollection =
                    $analysis['services']['sales'] > 0
                    ? (
                        (
                            $analysis['services']['sales']
                            -
                            $analysis['services']['remaining']
                        )
                        /
                        $analysis['services']['sales']
                    ) * 100
                    : 0;

            @endphp

            <tr
                class="border-b
                       dark:border-gray-800">

                <td class="p-4 font-bold">

                    🧾 الطلبات

                </td>

                <td class="p-4 text-center">

                    {{ number_format($analysis['services']['count']) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($analysis['services']['sales'],2) }}

                </td>

                <td class="p-4 text-center text-orange-600">

                    {{ number_format($analysis['services']['cost'],2) }}

                </td>

                <td class="p-4 text-center text-indigo-600 font-bold">

                    {{ number_format($analysis['services']['expected_profit'],2) }}

                </td>

                <td class="p-4 text-center text-green-600 font-bold">

                    {{ number_format($analysis['services']['confirmed_profit'],2) }}

                </td>

                <td class="p-4 text-center text-red-600">

                    {{ number_format($analysis['services']['remaining'],2) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($serviceMargin,2) }}%

                </td>

                <td class="p-4 text-center">

                    {{ number_format($serviceCollection,2) }}%

                </td>

            </tr>

            </tbody>

            <tfoot>

            <tr
                class="bg-gray-50
                       dark:bg-gray-800
                       font-bold">

                <td class="p-4">

                    الإجمالي

                </td>

                <td class="p-4 text-center">

                    {{ number_format($totals['count']) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($totals['sales'],2) }}

                </td>

                <td class="p-4 text-center">

                    {{ number_format($totals['cost'],2) }}

                </td>

                <td class="p-4 text-center text-indigo-600">

                    {{ number_format($totals['expected_profit'],2) }}

                </td>

                <td class="p-4 text-center text-green-600">

                    {{ number_format($totals['confirmed_profit'],2) }}

                </td>

                <td class="p-4 text-center text-red-600">

                    {{ number_format($totals['remaining'],2) }}

                </td>

                <td colspan="2"></td>

            </tr>

            </tfoot>

        </table>

    </div>

</div>
