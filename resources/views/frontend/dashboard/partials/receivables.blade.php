<div
    class="grid
           grid-cols-1
           xl:grid-cols-2
           gap-6">

    {{-- ذمم العملاء --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <div>

                <h2
                    class="text-xl
                           font-black
                           text-gray-800
                           dark:text-white">

                    المتبقي عند العملاء

                </h2>

                <p
                    class="text-sm
                           text-gray-500">

                    الفواتير غير المسددة

                </p>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-orange-100
                       dark:bg-orange-900/30
                       flex items-center justify-center">

                👤

            </div>

        </div>

        <div class="space-y-4">

            @forelse($clientReceivables as $item)

                <div
                    class="flex
                           justify-between
                           items-center
                           p-4
                           rounded-2xl
                           bg-orange-50
                           dark:bg-orange-900/10">

                    <div>

                        <div
                            class="font-bold">

                            {{ $item->currency->code }}

                        </div>

                        <div
                            class="text-xs
                                   text-gray-500">

                            {{ $item->currency->name }}

                        </div>

                    </div>

                    <div
                        class="text-xl
                               font-black
                               text-orange-600">

                        {{ number_format($item->total,2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center
                           py-10
                           text-gray-500">

                    لا توجد ذمم عملاء

                </div>

            @endforelse

        </div>

    </div>

    {{-- ذمم الوكلاء --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <div>

                <h2
                    class="text-xl
                           font-black
                           text-gray-800
                           dark:text-white">

                    ذمم الوكلاء

                </h2>

                <p
                    class="text-sm
                           text-gray-500">

                    أرصدة وتعاملات الوكلاء

                </p>

            </div>

            <div
                class="w-14 h-14
                       rounded-2xl
                       bg-blue-100
                       dark:bg-blue-900/30
                       flex items-center justify-center">

                🤝

            </div>

        </div>

        <div class="space-y-4">

            @forelse($agentBalances as $item)

                <div
                    class="flex
                           justify-between
                           items-center
                           p-4
                           rounded-2xl
                           bg-blue-50
                           dark:bg-blue-900/10">

                    <div>

                        <div
                            class="font-bold">

                            {{ $item->currency->code }}

                        </div>

                        <div
                            class="text-xs
                                   text-gray-500">

                            {{ $item->currency->name }}

                        </div>

                    </div>

                    <div
                        class="text-xl
                               font-black
                               {{ $item->total >= 0
                                    ? 'text-green-600'
                                    : 'text-red-600' }}">

                        {{ number_format($item->total,2) }}

                    </div>

                </div>

            @empty

                <div
                    class="text-center
                           py-10
                           text-gray-500">

                    لا توجد بيانات وكلاء

                </div>

            @endforelse

        </div>

    </div>

</div>
