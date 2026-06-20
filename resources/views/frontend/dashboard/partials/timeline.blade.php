<div
    class="bg-white dark:bg-gray-900
           rounded-3xl
           shadow-sm
           border border-gray-100
           dark:border-gray-800
           p-6">

    <div class="mb-6">

        <h2
            class="text-xl
                   font-black
                   text-gray-800
                   dark:text-white">

            آخر العمليات

        </h2>

        <p
            class="text-sm
                   text-gray-500">

            أحدث النشاطات داخل النظام

        </p>

    </div>

    <div class="space-y-4">

        @forelse($timeline as $item)

            <div
                class="flex items-start gap-4
                   p-4
                   rounded-2xl
                   border border-gray-100
                   dark:border-gray-800
                   hover:bg-gray-50
                   dark:hover:bg-gray-800/50
                   transition">

                {{-- Icon --}}
                <div
                    class="w-14 h-14
                       shrink-0
                       rounded-2xl
                       bg-gray-100
                       dark:bg-gray-800
                       flex items-center
                       justify-center
                       text-xl">

                    {{ $item['icon'] }}

                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">

                    <div class="flex items-center gap-2 flex-wrap">

                        <h3
                            class="font-bold
                               text-gray-800
                               dark:text-white">

                            {{ $item['title'] }}

                        </h3>

                        <span
                            class="text-xs
                               px-2 py-1
                               rounded-full
                               bg-gray-100
                               dark:bg-gray-800
                               text-gray-500">

                        {{ $item['date']->diffForHumans() }}

                    </span>

                    </div>

                    @if(!empty($item['description']))
                        <div
                            class="text-sm
                               text-gray-500
                               mt-1">

                            {{ $item['description'] }}

                        </div>
                    @endif

                    @if(!empty($item['amount']))

                        <div
                            class="mt-3
                               inline-flex
                               items-center
                               gap-2
                               px-3 py-1.5
                               rounded-xl
                               bg-green-50
                               dark:bg-green-900/20">

                        <span
                            class="font-black
                                   text-green-600">

                            {{ number_format($item['amount'],2) }}

                        </span>

                            <span
                                class="text-sm
                                   text-green-700
                                   dark:text-green-400">

                            {{ $item['currency'] }}

                        </span>

                        </div>

                    @endif

                </div>

            </div>

        @empty

            <div
                class="text-center
                   py-16
                   text-gray-500">

                لا توجد عمليات

            </div>

        @endforelse

    </div>

</div>
