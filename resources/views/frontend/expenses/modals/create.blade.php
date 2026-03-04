<div id="expenseModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <div onclick="closeExpenseModal()" class="absolute inset-0"></div>

    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-lg rounded-2xl shadow-2xl
                p-6 space-y-6">

        <button onclick="closeExpenseModal()"
                class="absolute top-3 left-3 text-gray-400 hover:text-red-500">
            ✕
        </button>

        <h2 class="text-xl font-bold text-red-600">
            تسجيل مصروف جديد
        </h2>

        <form method="POST"
              action="{{ route('dashboard.expenses.store') }}"
              class="space-y-4">

            @csrf

            <div>
                <label class="block text-sm mb-1">المبلغ</label>
                <input type="number" step="0.01" min="0.01"
                       name="amount"
                       required
                       class="w-full px-4 py-2 rounded-xl border dark:bg-gray-900">
            </div>

            <div>
                <label class="block text-sm mb-1">العملة</label>
                <select name="currency_id"
                        required
                        class="w-full px-4 py-2 rounded-xl border dark:bg-gray-900">
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">
                            {{ $currency->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">الوصف</label>
                <textarea name="description"
                          required
                          rows="3"
                          class="w-full px-4 py-2 rounded-xl border dark:bg-gray-900"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeExpenseModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-xl">
                    إلغاء
                </button>

                <button class="px-4 py-2 bg-red-600 text-white rounded-xl">
                    حفظ المصروف
                </button>

            </div>

        </form>

    </div>

</div>

<script>
    function openExpenseModal(){
        document.getElementById('expenseModal')
            .classList.replace('hidden','flex');
    }
    function closeExpenseModal(){
        document.getElementById('expenseModal')
            .classList.replace('flex','hidden');
    }
</script>
