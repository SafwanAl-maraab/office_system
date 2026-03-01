<div id="createRequestModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-xl p-6 relative">

        {{-- Close Button --}}
        <button onclick="toggleCreateModal()"
                class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            إضافة طلب جديد
        </h2>

        <form method="POST" action="{{ route('dashboard.requests.store') }}"
              class="space-y-5">

            @csrf

            {{-- اختيار العميل --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    العميل
                </label>
                <select name="client_id"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
    bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>

                    <option value="">اختر العميل</option>

                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->full_name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- نوع الطلب --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    نوع الطلب
                </label>
                <select id="requestTypeSelect"
                        name="request_type_id"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
        bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>

                    <option value="">اختر النوع</option>

                    @foreach($requestTypes as $type)
                        <option value="{{ $type->id }}"
                                data-price="{{ $type->price }}">
                            {{ $type->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- السعر --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    السعر
                </label>
                <input type="number"
                       id="priceField"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                              bg-gray-100 dark:bg-gray-700
                              text-gray-700 dark:text-gray-200"
                       readonly>
            </div>

            {{-- إعادة إدخال السعر --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    إعادة إدخال السعر للتأكيد
                </label>
                <input type="number"
                       name="confirm_price"
                       id="confirmPriceField"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-900
                              text-gray-700 dark:text-gray-200"
                       required>
                <p id="priceError"
                   class="text-red-500 text-xs mt-1 hidden">
                    السعر غير مطابق
                </p>
            </div>

            {{-- ملاحظات --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    ملاحظات
                </label>
                <textarea name="notes"
                          rows="3"
                          class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-900
                                 text-gray-700 dark:text-gray-200"></textarea>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="toggleCreateModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        id="submitBtn"
                        class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">
                    حفظ الطلب
                </button>

            </div>

        </form>

    </div>

</div>

{{-- Script --}}
<script>
    function toggleCreateModal() {
        const modal = document.getElementById('createRequestModal');
        modal.classList.toggle('hidden');
        modal.classList.toggle('flex');
    }

    const priceField = document.getElementById('priceField');
    const confirmPriceField = document.getElementById('confirmPriceField');
    const submitBtn = document.getElementById('submitBtn');
    const priceError = document.getElementById('priceError');

    confirmPriceField?.addEventListener('input', function () {

        if (confirmPriceField.value !== priceField.value) {
            priceError.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
        } else {
            priceError.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
        }

    });
</script>


<script>
    const requestTypeSelect = document.getElementById('requestTypeSelect');
    const priceField = document.getElementById('priceField');
    const confirmPriceField = document.getElementById('confirmPriceField');
    const submitBtn = document.getElementById('submitBtn');
    const priceError = document.getElementById('priceError');

    requestTypeSelect?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        priceField.value = price ?? '';
        confirmPriceField.value = '';

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50');
    });

    confirmPriceField?.addEventListener('input', function () {

        if (confirmPriceField.value !== priceField.value) {
            priceError.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
        } else {
            priceError.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50');
        }

    });
</script>
