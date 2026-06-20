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

                الخزائن

            </h2>

            <p
                class="text-sm
                       text-gray-500">

                أرصدة الخزائن الحالية حسب العملات

            </p>

        </div>

        <div
            class="w-14 h-14
                   rounded-2xl
                   bg-indigo-100
                   dark:bg-indigo-900/30
                   flex items-center justify-center">

            💳

        </div>

    </div>

    <div
        class="grid
               grid-cols-1
               md:grid-cols-2
               xl:grid-cols-3
               gap-6">

        @forelse($cashboxes as $cashbox)

            <div
                class="relative overflow-hidden
                       rounded-3xl
                       bg-gradient-to-br
                       from-white
                       to-gray-50
                       dark:from-gray-800
                       dark:to-gray-900
                       border border-gray-200
                       dark:border-gray-700
                       p-6">

                {{-- دائرة زخرفية --}}
                <div
                    class="absolute
                           -top-10
                           -left-10
                           w-32
                           h-32
                           rounded-full
                           bg-indigo-100
                           dark:bg-indigo-900/20">
                </div>

                <div
                    class="relative z-10">

                    <div
                        class="flex justify-between items-start">

                        <div>

                            <div
                                class="text-xs
                                       text-gray-500">

                                العملة

                            </div>

                            <h3
                                class="text-2xl
                                       font-black
                                       mt-1">

                                {{ $cashbox->currency->code }}

                            </h3>

                            <div
                                class="text-sm
                                       text-gray-500">

                                {{ $cashbox->currency->name }}

                            </div>

                        </div>

                        <div
                            class="w-12 h-12
                                   rounded-2xl
                                   bg-indigo-100
                                   dark:bg-indigo-900/30
                                   flex items-center justify-center">

                            💰

                        </div>

                    </div>

                    <div
                        class="mt-8">

                        <div
                            class="text-sm
                                   text-gray-500">

                            الرصيد الحالي

                        </div>

                        <div
                            class="text-3xl
                                   font-black
                                   text-green-600
                                   mt-2">

                            {{ number_format($cashbox->balance,2) }}

                        </div>

                        <div
                            class="text-sm
                                   text-gray-500
                                   mt-1">

                            {{ $cashbox->currency->symbol }}

                        </div>

                    </div>

                    <div
                        class="mt-6
                               flex gap-2">

                        <a
                            href="{{ route('cashboxes.transactions',$cashbox->currency_id) }}"
                            class="flex-1
                                   text-center
                                   py-3
                                   rounded-2xl
                                   bg-indigo-600
                                   hover:bg-indigo-700
                                   text-white
                                   text-sm
                                   font-semibold">

                            سجل الحركات

                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div
                class="col-span-full
                       text-center
                       py-20
                       text-gray-500">

                لا توجد خزائن

            </div>

        @endforelse

    </div>

</div>
