{{-- الخلفية الشفافة للمودال --}}
<div id="createIncomeModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4 transition-all duration-300 print:hidden animate-fade-in">

    {{-- خلفية الإغلاق عند الضغط خارج المودال --}}
    <div onclick="closeCreateIncomeModal()"
         class="absolute inset-0 backdrop-blur-sm"></div>

    {{-- جسم المودال المتجاوب --}}
    <div class="relative bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl p-5 sm:p-6 shadow-2xl border border-gray-100 dark:border-gray-700 transform transition-all scale-95 duration-300">

        {{-- هيدر المودال وزر الإغلاق X --}}
        <div class="flex justify-between items-center mb-5 border-b border-gray-100 dark:border-gray-700 pb-3">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                💰 إضافة قيد إيراد جديد
            </h2>
            <button onclick="closeCreateIncomeModal()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition text-xl p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                ✕
            </button>
        </div>

        {{-- فورمة الإدخال --}}
        <form method="POST" action="{{ route('incomes.store') }}" class="space-y-4">
            @csrf

            {{-- حقل المبلغ --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-600 dark:text-gray-400">
                    المبلغ الكلي المحصل <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="number"
                        step="0.01"
                        name="amount"
                        placeholder="0.00"
                        required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-mono font-bold text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition pl-12 text-right">
                </div>
            </div>

            {{-- حقل اختيار العملة --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-600 dark:text-gray-400">
                    العملة الحركية <span class="text-red-500">*</span>
                </label>
                <select
                    name="currency_id"
                    required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                    <option value="" disabled selected>اختر عملة الحساب...</option>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">
                            {{ $currency->name }} ({{ $currency->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- حقل البيان / الوصف --}}
            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-600 dark:text-gray-400">
                    البيان / تفاصيل الإيراد <span class="text-red-500">*</span>
                </label>
                <textarea
                    name="description"
                    rows="3"
                    placeholder="اكتب تفاصيل الإيراد أو جهة التحصيل للتوثيق المحاسبي..."
                    required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition resize-none"></textarea>
            </div>

            {{-- أزرار التحكم والعمليات --}}
            <div class="flex flex-col sm:flex-row justify-end gap-2 pt-4 border-t border-gray-100 dark:border-gray-700 mt-5">
                <button
                    type="button"
                    onclick="closeCreateIncomeModal()"
                    class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl transition">
                    إلغاء التراجع
                </button>

                <button
                    type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition shadow-sm">
                    حفظ القيد المالي
                </button>
            </div>
        </form>

    </div>
</div>

{{-- سكربت فتح وإغلاق المودال بطريقة مرنة وسلسة تمنع تشنج الواجهة --}}
<script>
    function openCreateIncomeModal() {
        const modal = document.getElementById('createIncomeModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // تفاعل تأثير الأنيميشن التكبيري الصغير
        setTimeout(() => {
            modal.querySelector('.relative').classList.remove('scale-95');
            modal.querySelector('.relative').classList.add('scale-100');
        }, 10);
    }

    function closeCreateIncomeModal() {
        const modal = document.getElementById('createIncomeModal');
        modal.querySelector('.relative').classList.remove('scale-100');
        modal.querySelector('.relative').classList.add('scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 150);
    }
</script>
