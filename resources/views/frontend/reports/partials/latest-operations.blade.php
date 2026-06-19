<div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">

    <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            📊 آخر العمليات المالية
        </h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            آخر العمليات المنفذة في النظام بمختلف مستنداتها المالية
        </p>
    </div>

    {{-- Tabs Buttons --}}
    <div class="flex flex-wrap gap-2 p-4 bg-gray-50/30 dark:bg-gray-900/10 border-b border-gray-100 dark:border-gray-700">
        <button
            onclick="showFinancialTab(event, 'payments')"
            class="financial-tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition bg-blue-600 text-white shadow-sm">
            المدفوعات
        </button>

        <button
            onclick="showFinancialTab(event, 'incomes')"
            class="financial-tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
            الإيرادات
        </button>

        {{-- تم تصحيح الاسم هنا من expenses.blade.php إلى expenses --}}
        <button
            onclick="showFinancialTab(event, 'expenses')"
            class="financial-tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
            المصروفات
        </button>

        <button
            onclick="showFinancialTab(event, 'exchanges')"
            class="financial-tab-btn px-4 py-2 rounded-xl text-sm font-semibold transition bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
            المصارفات
        </button>
    </div>

    {{-- Tabs Content Container --}}
    <div class="p-2 sm:p-4">
        {{-- Payments --}}
        <div id="payments-tab" class="financial-tab-content block">
            @include('frontend.reports.partials.tabs.payments')
        </div>

        {{-- Incomes --}}
        <div id="incomes-tab" class="financial-tab-content hidden">
            @include('frontend.reports.partials.tabs.incomes')
        </div>

        {{-- Expenses --}}
        <div id="expenses-tab" class="financial-tab-content hidden">
            @include('frontend.reports.partials.tabs.expenses')
        </div>

        {{-- Exchanges --}}
        <div id="exchanges-tab" class="financial-tab-content hidden">
            @include('frontend.reports.partials.tabs.exchanges')
        </div>
    </div>

</div>

<script>
    function showFinancialTab(event, tabName) {
        // 1. إخفاء جميع محتويات التابات بالدقة الصحيحة للكلاس الفيزيائي
        document.querySelectorAll('.financial-tab-content').forEach(el => {
            el.classList.remove('block');
            el.classList.add('hidden');
        });

        // 2. إعادة تهيئة مظهر جميع الأزرار وإلغاء تفعيلها متوافقاً مع الـ Dark Mode
        document.querySelectorAll('.financial-tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            btn.classList.add('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-200');
        });

        // 3. إظهار التاب المحددة حالياً بناءً على الـ ID الممرر
        const activeTab = document.getElementById(tabName + '-tab');
        if (activeTab) {
            activeTab.classList.remove('hidden');
            activeTab.classList.add('block');
        }

        // 4. تطبيق ستايل التنشيط الفوري للزر الذي تم النقر عليه حالياً
        const clickedButton = event.currentTarget;
        clickedButton.classList.remove('bg-gray-100', 'text-gray-700', 'dark:bg-gray-700', 'dark:text-gray-200');
        clickedButton.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
    }
</script>
