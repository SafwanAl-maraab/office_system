<div
    class="grid
           grid-cols-1
           sm:grid-cols-2
           xl:grid-cols-5
           gap-4">

    {{-- إجمالي المبيعات --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-2xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               p-5">

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm
                           text-gray-500">

                    إجمالي المبيعات

                </p>

                <h3
                    class="mt-2
                           text-2xl
                           font-bold
                           text-blue-600">

                    {{ number_format($totals['sales'],2) }}

                </h3>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-blue-100
                       flex items-center justify-center">

                💰

            </div>

        </div>

    </div>

    {{-- إجمالي التكلفة --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-2xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               p-5">

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm
                           text-gray-500">

                    إجمالي التكلفة

                </p>

                <h3
                    class="mt-2
                           text-2xl
                           font-bold
                           text-orange-600">

                    {{ number_format($totals['cost'],2) }}

                </h3>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-orange-100
                       flex items-center justify-center">

                📦

            </div>

        </div>

    </div>

    {{-- الربح المتوقع --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-2xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               p-5">

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm
                           text-gray-500">

                    الربح المتوقع

                </p>

                <h3
                    class="mt-2
                           text-2xl
                           font-bold
                           text-indigo-600">

                    {{ number_format($totals['expected_profit'],2) }}

                </h3>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-indigo-100
                       flex items-center justify-center">

                📈

            </div>

        </div>

    </div>

    {{-- الربح المؤكد --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-2xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               p-5">

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm
                           text-gray-500">

                    الربح المؤكد

                </p>

                <h3
                    class="mt-2
                           text-2xl
                           font-bold
                           text-green-600">

                    {{ number_format($totals['confirmed_profit'],2) }}

                </h3>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-green-100
                       flex items-center justify-center">

                ✅

            </div>

        </div>

    </div>

    {{-- الذمم --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-2xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               p-5">

        <div class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm
                           text-gray-500">

                    المتبقي للتحصيل

                </p>

                <h3
                    class="mt-2
                           text-2xl
                           font-bold
                           text-red-600">

                    {{ number_format($totals['remaining'],2) }}

                </h3>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-red-100
                       flex items-center justify-center">

                ⏳

            </div>

        </div>

    </div>

</div>

{{-- بطاقة إضافية ملخص عام --}}
<div
    class="mt-4
           bg-gradient-to-r
           from-blue-600
           to-indigo-600
           text-white
           rounded-2xl
           p-6
           shadow-lg">

    <div
        class="grid
               grid-cols-1
               md:grid-cols-3
               gap-6">

        <div>

            <p class="text-sm opacity-80">

                عدد العمليات

            </p>

            <h3
                class="text-3xl
                       font-bold
                       mt-2">

                {{ number_format($totals['count']) }}

            </h3>

        </div>

        <div>

            <p class="text-sm opacity-80">

                هامش الربح المتوقع

            </p>

            <h3
                class="text-3xl
                       font-bold
                       mt-2">

                @if($totals['sales'] > 0)

                    {{ number_format(
                        ($totals['expected_profit'] / $totals['sales']) * 100,
                        2
                    ) }}

                    %

                @else

                    0 %

                @endif

            </h3>

        </div>

        <div>

            <p class="text-sm opacity-80">

                هامش الربح المؤكد

            </p>

            <h3
                class="text-3xl
                       font-bold
                       mt-2">

                @if($totals['sales'] > 0)

                    {{ number_format(
                        ($totals['confirmed_profit'] / $totals['sales']) * 100,
                        2
                    ) }}

                    %

                @else

                    0 %

                @endif

            </h3>

        </div>

    </div>

</div>
