<div id="createRequestModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4 transition-opacity duration-300">

    <div id="modalBox"
         class="bg-white dark:bg-gray-800 w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl p-4 sm:p-6 relative transform scale-95 opacity-0 transition-all duration-300">

        <button type="button" onclick="toggleCreateModal()"
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

            <div class="relative">
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    العميل (ابحث بالاسم، الهاتف، جواز السفر أو الرقم الوطني)
                </label>

                <input type="text"
                       id="clientSearchInput"
                       placeholder="اكتب للبحث عن عميل..."
                       autocomplete="off"
                       required
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 outline-none focus:ring-2 focus:ring-blue-500">

                <input type="hidden" name="client_id" id="clientIdField" required>

                <div id="clientSearchResults"
                     class="absolute left-0 right-0 mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-xl max-h-60 overflow-y-auto hidden z-50 divide-y divide-gray-100 dark:divide-gray-600">
                </div>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    نوع الطلب
                </label>

                <select id="requestTypeSelect"
                        name="request_type_id"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 outline-none"
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

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    السعر
                </label>

                <div class="flex gap-2 items-center">
                    <input type="number"
                           id="priceField"
                           readonly
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 outline-none">

                    <span id="currencyLabel"
                          class="px-3 py-2 rounded-lg bg-gray-200 dark:bg-gray-600 text-sm dark:text-white">
                        --
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    التكلفة على المكتب
                </label>

                <div class="flex gap-2 items-center">
                    <input type="number"
                           min="0"
                           step="any"
                           name="cost_price"
                           id="costPriceField"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    ملاحظات
                </label>

                <textarea name="notes"
                          rows="3"
                          class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 outline-none"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">

                <button type="button"
                        onclick="toggleCreateModal()"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white transition">
                    إلغاء
                </button>

                <button type="submit"
                        id="submitBtn"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
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
        const currencyLabel = document.getElementById('currencyLabel');
        const submitBtn = document.getElementById('submitBtn');

        // عناصر البحث الحي عن العملاء
        const clientSearchInput = document.getElementById('clientSearchInput');
        const clientIdField = document.getElementById('clientIdField');
        const clientSearchResults = document.getElementById('clientSearchResults');

        let debounceTimer;

        // دالة فتح وإغلاق المودال الأصلية
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

        // مراقبة اختيار نوع الطلب لتحديث السعر تلقائياً
        requestTypeSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];

            if (!selectedOption || !selectedOption.dataset.price) {
                priceField.value = '';
                currencyLabel.textContent = '--';
                return;
            }

            const price = parseFloat(selectedOption.dataset.price);
            const currency = selectedOption.dataset.currency;

            priceField.value = price.toFixed(2);
            currencyLabel.textContent = currency;
        });

        // 🧠 منطق البحث الحي عن العملاء (AJAX Debounce البحث)
        clientSearchInput.addEventListener('input', function () {
            const query = this.value.trim();

            // تنظيف المعرف الحالي عند تغيير النص لضمان صحة الإرسال
            clientIdField.value = '';

            clearTimeout(debounceTimer);

            if (query.length < 2) {
                clientSearchResults.innerHTML = '';
                clientSearchResults.classList.add('hidden');
                return;
            }

            // تأخير الإرسال 300ms لحماية السيرفر من تتابع النقرات السريع (Debouncing)
            debounceTimer = setTimeout(() => {
                // استدعاء دالة البحث بالمسار الصحيح للميثود بالكنترولر
                fetch(`/dashboard/clients/search?search=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        clientSearchResults.innerHTML = '';

                        if (data.length === 0) {
                            clientSearchResults.innerHTML = `<div class="p-3 text-sm text-gray-500 dark:text-gray-400">لا توجد نتائج مطابقة</div>`;
                            clientSearchResults.classList.remove('hidden');
                            return;
                        }

                        data.forEach(client => {
                            const div = document.createElement('div');
                            div.className = "p-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-gray-600 cursor-pointer transition-colors";

                            // بناء نص الوصف المساعد داخل منقّب القائمة
                            let extraInfo = client.phone ? `(${client.phone})` : '';
                            if(client.passport_number) extraInfo += ` - ج: ${client.passport_number}`;

                            div.innerHTML = `<strong>${client.full_name}</strong> <span class="text-xs text-gray-400 block mt-0.5">${extraInfo}</span>`;

                            // عند اختيار العميل من القائمة المنسدلة
                            div.addEventListener('click', function () {
                                clientSearchInput.value = client.full_name;
                                clientIdField.value = client.id; // تعيين المعرّف للحقل المخفي
                                clientSearchResults.innerHTML = '';
                                clientSearchResults.classList.add('hidden');
                            });

                            clientSearchResults.appendChild(div);
                        });

                        clientSearchResults.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching clients:', error);
                    });
            }, 300);
        });

        // إغلاق قائمة البحث عند النقر خارجها
        document.addEventListener('click', function (e) {
            if (e.target !== clientSearchInput && e.target !== clientSearchResults) {
                clientSearchResults.classList.add('hidden');
            }
        });
    });
</script>
