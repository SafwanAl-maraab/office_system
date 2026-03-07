<div id="expenseModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    {{-- خلفية الإغلاق --}}
    <div class="absolute inset-0" onclick="closeExpenseModal()"></div>

    {{-- الصندوق --}}
    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-lg rounded-2xl shadow-2xl
                p-6 md:p-8 space-y-6">

        {{-- زر الإغلاق --}}
        <button onclick="closeExpenseModal()"
                class="absolute top-3 left-3 text-gray-400 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-red-600">
            تسجيل مصروف جديد
        </h2>

        {{-- تنبيه --}}
        <div class="bg-yellow-50 dark:bg-yellow-900/40
                    text-yellow-700 dark:text-yellow-300
                    p-3 rounded-xl text-sm">
            سيتم خصم المبلغ مباشرة من خزنة الفرع حسب العملة المختارة.
        </div>

        <form method="POST"
              action="{{ route('dashboard.expenses.store') }}"
              class="space-y-4">

            @csrf

            {{-- المبلغ --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    المبلغ
                </label>

                <input type="number"
                       name="amount"
                       step="0.01"
                       min="0.01"
                       value="{{ old('amount') }}"
                       required
                       class="w-full px-4 py-2 rounded-xl border
                              border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-900
                              text-gray-700 dark:text-gray-200">

                @error('amount')
                <p class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>


            {{-- العملة --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    العملة
                </label>

                <select name="currency_id"
                        required
                        class="w-full px-4 py-2 rounded-xl border
                               border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900
                               text-gray-700 dark:text-gray-200">

                    <option value="">اختر العملة</option>

                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}"
                            {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                            {{ $currency->name }} ({{ $currency->symbol }})
                        </option>
                    @endforeach

                </select>

                @error('currency_id')
                <p class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>


            {{-- الوصف --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    وصف المصروف
                </label>

                <textarea name="description"
                          rows="3"
                          required
                          class="w-full px-4 py-2 rounded-xl border
                                 border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-900
                                 text-gray-700 dark:text-gray-200">{{ old('description') }}</textarea>

                @error('description')
                <p class="text-red-600 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror
            </div>


            {{-- الأزرار --}}
            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeExpenseModal()"
                        class="px-4 py-2 rounded-xl bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white">
                    حفظ المصروف
                </button>

            </div>

        </form>

    </div>
</div>


<script>

    function openExpenseModal() {

        const modal = document.getElementById('expenseModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }

    function closeExpenseModal() {

        const modal = document.getElementById('expenseModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }


    /* فتح المودال تلقائياً إذا كان هناك خطأ */
    @if($errors->any())
    document.addEventListener("DOMContentLoaded", function() {
        openExpenseModal();
    });
    @endif

</script>
