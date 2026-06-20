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

            التنبيهات

        </h2>

        <p
            class="text-sm
                   text-gray-500">

            أهم الأمور التي تحتاج متابعة

        </p>

    </div>

    <div
        class="space-y-4">

        @forelse($alerts as $alert)

            <div
                class="flex items-start gap-4
                       p-4 rounded-2xl

                       @if($alert['color'] == 'red')
                           bg-red-50 dark:bg-red-900/10
                       @elseif($alert['color'] == 'yellow')
                           bg-yellow-50 dark:bg-yellow-900/10
                       @else
                           bg-orange-50 dark:bg-orange-900/10
                       @endif">

                <div
                    class="text-2xl">

                    {{ $alert['icon'] }}

                </div>

                <div>

                    <div
                        class="font-bold">

                        {{ $alert['title'] }}

                    </div>

                    <div
                        class="text-sm
                               text-gray-600
                               dark:text-gray-400
                               mt-1">

                        {{ $alert['description'] }}

                    </div>

                </div>

            </div>

        @empty

            <div
                class="text-center
                       py-10">

                <div
                    class="text-5xl mb-3">

                    ✅

                </div>

                <div
                    class="text-gray-500">

                    لا توجد تنبيهات حالياً

                </div>

            </div>

        @endforelse

    </div>

</div>
