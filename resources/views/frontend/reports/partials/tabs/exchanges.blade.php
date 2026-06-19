<div class="overflow-x-auto">
    <table class="w-full text-right border-collapse text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase">
        <tr class="border-b dark:border-gray-700">
            <th class="p-3">التاريخ</th>
            <th class="p-3">من عملة</th>
            <th class="p-3">إلى عملة</th>
            <th class="p-3">الخارج (المدفوع)</th>
            <th class="p-3">الداخل (المستلم)</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-200">
        @forelse($latestExchanges as $exchange)
            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition">
                <td class="p-3 font-mono text-xs text-gray-500 dark:text-gray-400">
                    {{ $exchange->created_at->format('Y-m-d H:i') }}
                </td>
                <td class="p-3">
                        <span class="px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-xs font-bold">
                            {{ $exchange->fromCurrency->code }}
                        </span>
                </td>
                <td class="p-3">
                        <span class="px-2 py-0.5 rounded bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-400 text-xs font-bold">
                            {{ $exchange->toCurrency->code }}
                        </span>
                </td>
                <td class="p-3 text-red-500 font-bold font-mono">
                    {{ number_format($exchange->from_amount, 2) }}
                </td>
                <td class="p-3 text-green-600 dark:text-green-400 font-bold font-mono">
                    {{ number_format($exchange->to_amount, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center p-8 text-gray-400 dark:text-gray-500 text-xs">
                    لا توجد عمليات مصارفة مسجلة مؤخراً.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
