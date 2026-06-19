<div class="grid
            grid-cols-1
            md:grid-cols-2
            xl:grid-cols-4
            gap-5">

    {{-- المبيعات --}}
    <div class="bg-white dark:bg-gray-900
                rounded-2xl p-5 shadow">

        <div class="text-sm text-gray-500">

            إجمالي المبيعات

        </div>

        <div class="mt-3
                    text-3xl
                    font-bold
                    text-blue-600">

            {{ number_format($salesTotal,2) }}

        </div>

    </div>

    {{-- المقبوضات --}}
    <div class="bg-white dark:bg-gray-900
                rounded-2xl p-5 shadow">

        <div class="text-sm text-gray-500">

            إجمالي المقبوضات

        </div>

        <div class="mt-3
                    text-3xl
                    font-bold
                    text-green-600">

            {{ number_format($paymentsTotal,2) }}

        </div>

    </div>

    {{-- الإيرادات --}}
    <div class="bg-white dark:bg-gray-900
                rounded-2xl p-5 shadow">

        <div class="text-sm text-gray-500">

            إجمالي الإيرادات

        </div>

        <div class="mt-3
                    text-3xl
                    font-bold
                    text-emerald-600">

            {{ number_format($incomeTotal,2) }}

        </div>

    </div>

    {{-- المصروفات --}}
    <div class="bg-white dark:bg-gray-900
                rounded-2xl p-5 shadow">

        <div class="text-sm text-gray-500">

            إجمالي المصروفات

        </div>

        <div class="mt-3
                    text-3xl
                    font-bold
                    text-red-600">

            {{ number_format($expenseTotal,2) }}

        </div>

    </div>

</div>
