<div class="space-y-5">

    <div class="flex items-center justify-between">

        <div>

            <h3 class="text-xl font-bold
                       text-gray-800 dark:text-white">

                أرصدة الخزائن الحالية

            </h3>

            <p class="text-sm text-gray-500">

                السيولة المتوفرة حالياً بكل العملات

            </p>

        </div>

        <div class="text-right">

            <div class="text-xs text-gray-400">

                إجمالي السيولة

            </div>

            <div class="text-xl font-bold text-green-600">

                {{ number_format($totalLiquidity,2) }}

            </div>

        </div>

    </div>

    <div class="grid
                grid-cols-1
                md:grid-cols-2
                xl:grid-cols-4
                gap-5">

        @foreach($cashboxes as $cashbox)

            <div class="bg-white dark:bg-gray-900
                        rounded-2xl
                        border border-gray-100 dark:border-gray-800
                        shadow-sm
                        p-5">

                <div class="flex justify-between">

                    <div>

                        <h4 class="font-bold text-lg">

                            {{ $cashbox->currency->code }}

                        </h4>

                        <div class="text-xs text-gray-500">

                            {{ $cashbox->currency->name }}

                        </div>

                    </div>

                    <div>

                        <span class="px-2 py-1
                                     rounded-full
                                     text-xs
                                     bg-green-100
                                     text-green-700">

                            {{ $cashbox->percentage }}%

                        </span>

                    </div>

                </div>

                <div class="mt-5">

                    <div class="text-xs text-gray-400">

                        الرصيد الحالي

                    </div>

                    <div class="text-3xl
                                font-black
                                text-green-600">

                        {{ number_format($cashbox->balance,2) }}

                    </div>

                </div>

                <div class="mt-5 space-y-2">

                    <div class="flex justify-between">

                        <span class="text-gray-500 text-sm">

                            عدد الحركات

                        </span>

                        <span class="font-bold">

                            {{ $cashbox->transactions_count }}

                        </span>

                    </div>

                    <div class="flex justify-between">

                        <span class="text-gray-500 text-sm">

                            آخر حركة

                        </span>

                        <span class="font-bold text-xs">

                            {{
                                optional(
                                    $cashbox->last_transaction
                                )->created_at
                                ?->diffForHumans()
                            }}

                        </span>

                    </div>

                </div>

                <div class="mt-4">

                    <div class="w-full
                                h-2
                                bg-gray-200
                                rounded-full">

                        <div
                            class="h-2
                                   bg-green-500
                                   rounded-full"
                            style="width: {{ min($cashbox->percentage,100) }}%">
                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>
