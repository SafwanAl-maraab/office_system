<!-- Modal -->
<div id="createRequestModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4 transition-opacity duration-300">

    <div id="modalBox"
         class="bg-white dark:bg-gray-800 w-full max-w-lg
                max-h-[90vh] overflow-y-auto
                rounded-2xl shadow-2xl
                p-4 sm:p-6 relative
                transform scale-95 opacity-0
                transition-all duration-300">

        <!-- Close -->
        <button onclick="toggleCreateModal()"
                class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            إضافة طلب جديد
        </h2>

        <form method="POST"
              action="{{ route('dashboard.requests.store') }}"
              class="space-y-5">

            @csrf

            <!-- العميل -->
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    العميل
                </label>

                <select name="client_id"
                        class="w-full px-3 py-2 rounded-lg border
                               border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900
                               text-gray-700 dark:text-gray-200"
                        required>

                    <option value="">اختر العميل</option>

                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- نوع الطلب -->
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    نوع الطلب
                </label>

                <select id="requestTypeSelect"
                        name="request_type_id"
                        class="w-full px-3 py-2 rounded-lg border
                               border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900
                               text-gray-700 dark:text-gray-200"
                        required>

                    <option value="">اختر النوع</option>

                    @foreach($requestTypes as $type)
                        <option value="{{ $type->id }}"
                                data-price="{{ $type->price }}"
                                data-currency="{{ $type->currency->symbol }}">
                            {{ $type->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- السعر -->
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    السعر
                </label>

                <div class="flex gap-2 items-center">
                    <input type="number"
                           id="priceField"
                           readonly
                           class="w-full px-3 py-2 rounded-lg border
                                  border-gray-300 dark:border-gray-600
                                  bg-gray-100 dark:bg-gray-700
                                  text-gray-700 dark:text-gray-200">

                    <span id="currencyLabel"
                          class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-600 text-sm">
                        --
                    </span>
                </div>
            </div>

            <!-- تأكيد السعر -->
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    إعادة إدخال السعر للتأكيد
                </label>

                <input type="number"
                       name="confirm_price"
                       id="confirmPriceField"
                       min="0"
                       class="w-full px-3 py-2 rounded-lg border
                              border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-900
                              text-gray-700 dark:text-gray-200"
                       required>

                <p id="priceError"
                   class="text-red-500 text-xs mt-1 hidden">
                    السعر غير مطابق
                </p>
            </div>

            <!-- ملاحظات -->
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    ملاحظات
                </label>

                <textarea name="notes"
                          rows="3"
                          class="w-full px-3 py-2 rounded-lg border
                                 border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-900
                                 text-gray-700 dark:text-gray-200"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">

                <button type="button"
                        onclick="toggleCreateModal()"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg
                               bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        id="submitBtn"
                        disabled
                        class="w-full sm:w-auto px-4 py-2 rounded-lg
                               bg-blue-600 hover:bg-blue-700
                               text-white opacity-50">
                    حفظ الطلب
                </button>

            </div>

        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('createRequestModal');
        const modalBox = document.getElementById('modalBox');
        const requestTypeSelect = document.getElementById('requestTypeSelect');
        const priceField = document.getElementById('priceField');
        const confirmPriceField = document.getElementById('confirmPriceField');
        const submitBtn = document.getElementById('submitBtn');
        const priceError = document.getElementById('priceError');
        const currencyLabel = document.getElementById('currencyLabel');

        window.toggleCreateModal = function () {

            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');

            setTimeout(() => {
                modalBox.classList.toggle('scale-95');
                modalBox.classList.toggle('opacity-0');
            }, 10);
        };

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                toggleCreateModal();
            }
        });

        requestTypeSelect.addEventListener('change', function () {

            const selectedOption = this.options[this.selectedIndex];

            if (!selectedOption.dataset.price) {
                priceField.value = '';
                currencyLabel.textContent = '--';
                return;
            }

            const price = parseFloat(selectedOption.dataset.price);
            const currency = selectedOption.dataset.currency;

            priceField.value = price.toFixed(2);
            currencyLabel.textContent = currency;

            confirmPriceField.value = '';
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50');
        });

        confirmPriceField.addEventListener('input', function () {

            if (parseFloat(confirmPriceField.value) !== parseFloat(priceField.value)) {
                priceError.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
            } else {
                priceError.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50');
            }

        });

    });
</script>
