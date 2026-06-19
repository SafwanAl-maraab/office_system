<div class="overflow-x-auto">
    <table class="w-full text-right border-collapse text-sm">
        <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase">
        <tr class="border-b dark:border-gray-700">
            <th class="p-3">التاريخ</th>
            <th class="p-3">الوصف / البيان</th>
            <th class="p-3">المبلغ</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-200">
        @forelse($latestExpenses as $expense)
            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition">
                <td class="p-3 font-mono text-xs text-gray-500 dark:text-gray-400">
                    {{ $expense->created_at->format('Y-m-d H:i') }}
                </td>
                <td class="p-3 text-xs max-w-xs truncate" title="{{ $expense->description }}">
                    {{ $expense->description }}
                </td>
                {{-- عرض المبلغ باللون الأحمر الصريح محاسبياً للمصروفات --}}
                <td class="p-3 text-red-600 dark:text-red-400 font-bold font-mono">
                    -{{ number_format($expense->amount, 2) }} {{ $expense->currency->code ?? '' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center p-8 text-gray-400 dark:text-gray-500 text-xs">
                    لا توجد مصروفات مسجلة مؤخراً.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
