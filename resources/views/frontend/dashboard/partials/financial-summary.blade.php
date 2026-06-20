<div
    class="grid
           grid-cols-1
           xl:grid-cols-3
           gap-6">

    {{-- التحصيلات --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-bold
                       text-lg
                       text-gray-800
                       dark:text-white">

                التحصيلات

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-green-100
                       dark:bg-green-900/30
                       flex items-center justify-center">

                💰

            </div>

        </div>



        <div class="space-y-4">

            @forelse($collections as $item)

                <div
                    class="flex justify-between items-center
                           p-4 rounded-2xl
                           bg-gray-50 dark:bg-gray-800">

                    <div>

                        <div
                            class="font-bold">

                            {{ $item->currency->code }}

                        </div>

                        <div
                            class="text-xs text-gray-500">

                            {{ $item->currency->name }}

                        </div>

                    </div>

                    <div
                        class="text-green-600
                               font-black
                               text-xl">

                        {{ number_format($item->total,2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center
                           py-10
                           text-gray-500">

                    لا توجد بيانات

                </div>

            @endforelse

        </div>

    </div>

    {{-- المسترجعات --}}

    <div
        class="bg-white dark:bg-gray-900
           rounded-3xl
           shadow-sm
           border border-gray-100
           dark:border-gray-800
           p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-bold
                   text-lg">

                المسترجعات

            </h2>

            <div
                class="w-12 h-12
                   rounded-2xl
                   bg-orange-100
                   dark:bg-orange-900/30
                   flex items-center justify-center">

                ↩️

            </div>

        </div>

        <div class="space-y-4">

            @forelse($refunds as $item)

                <div
                    class="flex justify-between items-center
                       p-4 rounded-2xl
                       bg-gray-50 dark:bg-gray-800">

                    <div>

                        <div class="font-bold">

                            {{ $item->currency->code }}

                        </div>

                        <div class="text-xs text-gray-500">

                            {{ $item->currency->name }}

                        </div>

                    </div>

                    <div
                        class="text-orange-600
                           font-black
                           text-xl">

                        {{ number_format($item->total,2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center py-10 text-gray-500">

                    لا توجد بيانات

                </div>

            @endforelse

        </div>

    </div>

    {{-- صافي التحصيل --}}

    <div
        class="bg-white dark:bg-gray-900
           rounded-3xl
           shadow-sm
           border border-gray-100
           dark:border-gray-800
           p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-bold
                   text-lg">

                صافي التحصيل

            </h2>

            <div
                class="w-12 h-12
                   rounded-2xl
                   bg-emerald-100
                   dark:bg-emerald-900/30
                   flex items-center justify-center">

                🏦

            </div>

        </div>

        <div class="space-y-4">

            @forelse($netCollections as $item)

                <div
                    class="flex justify-between items-center
                       p-4 rounded-2xl
                       bg-gray-50 dark:bg-gray-800">

                    <div>

                        <div class="font-bold">

                            {{ $item['currency']->code }}

                        </div>

                        <div class="text-xs text-gray-500">

                            {{ $item['currency']->name }}

                        </div>

                    </div>

                    <div
                        class="text-emerald-600
                           font-black
                           text-xl">

                        {{ number_format($item['net'],2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center py-10 text-gray-500">

                    لا توجد بيانات

                </div>

            @endforelse

        </div>

    </div>

    {{-- الإيرادات --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-bold
                       text-lg
                       text-gray-800
                       dark:text-white">

                الإيرادات الأخرى

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-blue-100
                       dark:bg-blue-900/30
                       flex items-center justify-center">

                📈

            </div>

        </div>

        <div class="space-y-4">

            @forelse($incomes as $item)

                <div
                    class="flex justify-between items-center
                           p-4 rounded-2xl
                           bg-gray-50 dark:bg-gray-800">

                    <div>

                        <div
                            class="font-bold">

                            {{ $item->currency->code }}

                        </div>

                        <div
                            class="text-xs text-gray-500">

                            {{ $item->currency->name }}

                        </div>

                    </div>

                    <div
                        class="text-blue-600
                               font-black
                               text-xl">

                        {{ number_format($item->total,2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center
                           py-10
                           text-gray-500">

                    لا توجد بيانات

                </div>

            @endforelse

        </div>

    </div>

    {{-- المصروفات --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-bold
                       text-lg
                       text-gray-800
                       dark:text-white">

                المصروفات

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-red-100
                       dark:bg-red-900/30
                       flex items-center justify-center">

                📉

            </div>

        </div>

        <div class="space-y-4">

            @forelse($expenses as $item)

                <div
                    class="flex justify-between items-center
                           p-4 rounded-2xl
                           bg-gray-50 dark:bg-gray-800">

                    <div>

                        <div
                            class="font-bold">

                            {{ $item->currency->code }}

                        </div>

                        <div
                            class="text-xs text-gray-500">

                            {{ $item->currency->name }}

                        </div>

                    </div>

                    <div
                        class="text-red-600
                               font-black
                               text-xl">

                        {{ number_format($item->total,2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center
                           py-10
                           text-gray-500">

                    لا توجد بيانات

                </div>

            @endforelse

        </div>

    </div>

</div>
