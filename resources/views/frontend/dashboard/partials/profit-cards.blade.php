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

                الأرباح المؤكدة

            </h2>

            <p
                class="text-sm
                       text-gray-500">

                الأرباح المحصلة فعلياً بعد خصم التكلفة

            </p>

        </div>

        <div
            class="w-14 h-14
                   rounded-2xl
                   bg-green-100
                   dark:bg-green-900/30
                   flex items-center justify-center">

            🏆

        </div>

    </div>

    <div
        class="grid
               grid-cols-1
               md:grid-cols-2
               xl:grid-cols-3
               gap-5">

        @foreach($profitCards as $item)

            <div
                class="rounded-3xl
                       p-6
                       bg-gradient-to-br
                       from-green-50
                       to-emerald-100
                       dark:from-green-900/20
                       dark:to-emerald-900/10">

                <div
                    class="flex
                           justify-between
                           items-start">

                    <div>

                        <div
                            class="text-sm
                                   text-gray-500">

                            العملة

                        </div>

                        <div
                            class="text-2xl
                                   font-black">

                            {{ $item['currency']->code }}

                        </div>

                    </div>

                    <div
                        class="text-3xl">

                        💰

                    </div>

                </div>

                <div
                    class="mt-6">

                    <div
                        class="text-sm
                               text-gray-500">

                        الربح المؤكد

                    </div>

                    <div
                        class="text-3xl
                               font-black
                               text-green-600
                               mt-2">

                        {{ number_format($item['profit'],2) }}

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>
