<div
    class="grid
           grid-cols-1
           sm:grid-cols-2
           xl:grid-cols-4
           gap-6">

    {{-- العملاء --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               p-6
               shadow-sm
               border border-gray-100
               dark:border-gray-800">

        <div
            class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm text-gray-500">

                    العملاء

                </p>

                <h2
                    class="mt-3
                           text-4xl
                           font-black
                           text-gray-800
                           dark:text-white">

                    {{ number_format($kpis['clients']) }}

                </h2>

            </div>

            <div
                class="w-16 h-16
                       rounded-2xl
                       bg-blue-100
                       dark:bg-blue-900/30
                       flex items-center justify-center">

                👥

            </div>

        </div>

    </div>

    {{-- الوكلاء --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               p-6
               shadow-sm
               border border-gray-100
               dark:border-gray-800">

        <div
            class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm text-gray-500">

                    الوكلاء

                </p>

                <h2
                    class="mt-3
                           text-4xl
                           font-black
                           text-gray-800
                           dark:text-white">

                    {{ number_format($kpis['agents']) }}

                </h2>

            </div>

            <div
                class="w-16 h-16
                       rounded-2xl
                       bg-green-100
                       dark:bg-green-900/30
                       flex items-center justify-center">

                🤝

            </div>

        </div>

    </div>

    {{-- الفواتير المفتوحة --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               p-6
               shadow-sm
               border border-gray-100
               dark:border-gray-800">

        <div
            class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm text-gray-500">

                    الفواتير المفتوحة

                </p>

                <h2
                    class="mt-3
                           text-4xl
                           font-black
                           text-orange-600">

                    {{ number_format($kpis['open_invoices']) }}

                </h2>

            </div>

            <div
                class="w-16 h-16
                       rounded-2xl
                       bg-orange-100
                       dark:bg-orange-900/30
                       flex items-center justify-center">

                📄

            </div>

        </div>

    </div>

    {{-- عمليات اليوم --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               p-6
               shadow-sm
               border border-gray-100
               dark:border-gray-800">

        <div
            class="flex items-center justify-between">

            <div>

                <p
                    class="text-sm text-gray-500">

                    عمليات اليوم

                </p>

                <h2
                    class="mt-3
                           text-4xl
                           font-black
                           text-purple-600">

                    {{ number_format($kpis['today_operations']) }}

                </h2>

            </div>

            <div
                class="w-16 h-16
                       rounded-2xl
                       bg-purple-100
                       dark:bg-purple-900/30
                       flex items-center justify-center">

                ⚡

            </div>

        </div>

    </div>

</div>
