<div id="createTypeModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl p-6 relative">

        <button onclick="closeCreateModal()"
                class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            إضافة نوع طلب
        </h2>

        <form method="POST"
              action="{{ route('dashboard.request-types.store') }}"
              class="space-y-4">

            @csrf

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    اسم النوع
                </label>
                <input type="text" name="name"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                       required>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    التصنيف
                </label>
                <select name="service_category"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>
                    <option value="passport">جواز</option>
                    <option value="card">بطاقة</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    السعر
                </label>
                <input type="number" name="price"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                       bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                       required>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    العملة
                </label>
                <select name="currency_id"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>

                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">
                            {{ $currency->code }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                        onclick="closeCreateModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                    حفظ
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    function openCreateModal() {
        const modal = document.getElementById('createTypeModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCreateModal() {
        const modal = document.getElementById('createTypeModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
