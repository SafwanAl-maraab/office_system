<div id="exchangeModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm">

    <div onclick="closeExchangeModal()" class="absolute inset-0"></div>

    <div class="relative bg-white dark:bg-gray-900 w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all border border-gray-100 dark:border-gray-800 flex flex-col max-h-[95vh]">

        {{-- Header --}}
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">
            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                💵 إنشاء قيد مصارفة عملات جديد
            </h2>
            <button onclick="closeExchangeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition bg-white dark:bg-gray-700 w-8 h-8 rounded-full flex items-center justify-center shadow-sm">✕</button>
        </div>

        {{-- Form Content --}}
        <form method="POST" action="{{ route('cashbox-exchanges.store') }}" class="p-6 overflow-y-auto space-y-4 text-right">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- من عملة --}}
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400">من العملة (الخارجة من الخزنة)</label>
                    <select name="from_currency_id" id="fromCurrency" onchange="loadExchangeDataReal()"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" data-code="{{ $currency->code }}">{{ $currency->code }}</option>
                        @endforeach
                    </select>
                    <div id="fromBalance" class="mt-1.5 text-xs text-amber-600 dark:text-amber-400 font-mono font-medium">جاري جلب الرصيد...</div>
                </div>

                {{-- إلى عملة --}}
                <div>
                    <label class="block mb-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400">إلى العملة (الداخلة للخزنة)</label>
                    <select name="to_currency_id" id="toCurrency" onchange="loadExchangeDataReal()"
                            class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" data-code="{{ $currency->code }}">{{ $currency->code }}</option>
                        @endforeach
                    </select>
                    <div id="toBalance" class="mt-1.5 text-xs text-emerald-600 dark:text-emerald-400 font-mono font-medium">جاري جلب الرصيد...</div>
                </div>
            </div>

            {{-- 1️⃣ بطاقة التحذير الفورية الحقيقية --}}
            <div id="exchangeWarning"
                 class="hidden rounded-xl border border-red-200 bg-red-50 dark:bg-red-950/20 dark:border-red-900 p-3 text-red-700 dark:text-red-400 text-xs font-semibold">
            </div>

            {{-- المبلغ المراد صرفه --}}
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400">المبلغ المراد صرفه وتحويله</label>
                <input type="number" step="0.01" name="from_amount" id="fromAmount" oninput="calculateExchangeReal()" required
                       class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-3 font-mono text-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none transition" placeholder="0.00">
            </div>

            {{-- 2️⃣ بطاقة الرصيد بعد التنفيذ الحركي --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-xl bg-amber-50 dark:bg-amber-950/10 p-3 border border-amber-100/50 dark:border-amber-900/30">
                    <div class="text-[11px] text-gray-400">الرصيد بعد الخصم</div>
                    <div id="afterFromBalance" class="text-lg font-black font-mono text-amber-700 dark:text-amber-400">--</div>
                </div>
                <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/10 p-3 border border-emerald-100/50 dark:border-emerald-900/30">
                    <div class="text-[11px] text-gray-400">الرصيد بعد الإضافة</div>
                    <div id="afterToBalance" class="text-lg font-black font-mono text-emerald-700 dark:text-emerald-400">--</div>
                </div>
            </div>

            {{-- اتجاه السعر والناتج التقديري --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/30 dark:border-indigo-900/30 rounded-xl p-3">
                    <span class="text-xs text-indigo-600 dark:text-indigo-400 block font-medium">اتجاه سعر الصرف الفعلي بالنظام</span>
                    <div id="rateDisplay" class="text-sm font-bold font-mono mt-1 text-indigo-900 dark:text-indigo-200">--</div>
                </div>

                <div class="bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100/30 dark:border-emerald-900/30 rounded-xl p-3">
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 block font-medium">الناتج المضاف المتوقع للطرف الآخر</span>
                    <div id="resultAmount" class="text-xl font-black font-mono mt-0.5 text-emerald-900 dark:text-emerald-200">0.00</div>
                </div>
            </div>

            {{-- 3️⃣ بطاقة ملخص العملية المستندى --}}
            <div class="rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-4 shadow-sm">
                <div class="text-[11px] opacity-80">ملخص قيد العملية المباشر</div>
                <div id="exchangeSummary" class="mt-1 text-sm font-medium">يرجى تحديد العملات وإدخال المبلغ لحساب الفواتير الحقيقية...</div>
            </div>

            {{-- ملاحظات --}}
            <div>
                <label class="block mb-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400">ملاحظات وقيد العملية</label>
                <textarea name="notes" rows="2" placeholder="اكتب تفاصيل أو سبب المصارفة إن وجد..."
                          class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 p-3 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition"></textarea>
            </div>

            {{-- أزرار التحكم والتعطيل الميكانيكي --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800 mt-4">
                <button type="button" onclick="closeExchangeModal()"
                        class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-300 font-medium text-sm transition">إلغاء</button>
                <button id="submitExchangeBtn" type="submit" disabled
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm transition shadow-sm disabled:opacity-40 disabled:cursor-not-allowed">
                    تأكيد وتنفيذ المصارفة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // متغيرات الجلسة اللحظية المسحوبة عبر الـ Ajax حقيقياً
    let globalFromBalance = 0;
    let globalToBalance = 0;
    let globalRate = 0;

    function openExchangeModal() {
        const modal = document.getElementById('exchangeModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        document.getElementById('fromAmount').value = '';
        resetCalculationsReal();
        loadExchangeDataReal();
    }

    function closeExchangeModal() {
        const modal = document.getElementById('exchangeModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // 🌐 جلب البيانات الحقيقية من دوال الكنترولر المتوفرة بالكامل عبر الـ Fetch API
    function loadExchangeDataReal() {
        const fromSel = document.getElementById('fromCurrency');
        const toSel = document.getElementById('toCurrency');
        const warning = document.getElementById('exchangeWarning');
        const submitBtn = document.getElementById('submitExchangeBtn');

        const fromId = fromSel.value;
        const toId = toSel.value;

        const fromCode = fromSel.options[fromSel.selectedIndex].getAttribute('data-code');
        const toCode = toSel.options[toSel.selectedIndex].getAttribute('data-code');

        // 4️⃣ منع اختيار العملات المتطابقة
        if (fromId === toId) {
            warning.innerHTML = '⚠️ خطأ محاسبي: لا يمكن إجراء عملية مصارفة لنفس العملة المطابقة.';
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
            resetCalculationsReal();
            return;
        }

        warning.classList.add('hidden');

        // أ: سحب الأرصدة الحقيقية الحالية من دالة getBalances بالكنترولر
        fetch(`{{ route('cashbox-exchanges.get-balances') }}?from_currency_id=${fromId}&to_currency_id=${toId}`)
            .then(res => res.json())
            .then(data => {
                globalFromBalance = parseFloat(data.from_balance) || 0;
                globalToBalance = parseFloat(data.to_balance) || 0;

                document.getElementById('fromBalance').innerHTML = `الرصيد المتاح حالياً: ${globalFromBalance.toLocaleString('en-US', {minimumFractionDigits: 2})} ${fromCode}`;
                document.getElementById('toBalance').innerHTML = `الرصيد الحالي بالخزنة: ${globalToBalance.toLocaleString('en-US', {minimumFractionDigits: 2})} ${toCode}`;

                // ب: جلب أسعار الصرف الحية المخزنة بنظامك من دالة getRate بالكنترولر
                return fetch(`{{ route('cashbox-exchanges.get-rate') }}?from_currency_id=${fromId}&to_currency_id=${toId}`);
            })
            .then(res => res.json())
            .then(rateData => {
                if (rateData.success) {
                    globalRate = parseFloat(rateData.rate) || 0;
                    // 5️⃣ إظهار اتجاه السعر الحقيقي المطابق للكنترولر (المبلغ الخارج / السعر)
                    document.getElementById('rateDisplay').innerHTML = `1 ${toCode} = ${globalRate.toFixed(4)} ${fromCode}`;
                } else {
                    globalRate = 0;
                    document.getElementById('rateDisplay').innerHTML = `لا يوجد سعر صرف مدخل`;
                    warning.innerHTML = '❌ خطأ: لا يوجد سعر صرف معرف بين العملتين في لوحة الإدارة لفرعك.';
                    warning.classList.remove('hidden');
                    submitBtn.disabled = true;
                }
                calculateExchangeReal();
            })
            .catch(err => {
                console.error("Error fetching data:", err);
            });
    }

    // 🧮 إجراء الحسابات والتحقق الاستباقي لمنع الأخطاء البشرية قبل الحفظ
    function calculateExchangeReal() {
        const amount = parseFloat(document.getElementById('fromAmount').value) || 0;
        const fromSel = document.getElementById('fromCurrency');
        const toSel = document.getElementById('toCurrency');
        const warning = document.getElementById('exchangeWarning');
        const submitBtn = document.getElementById('submitExchangeBtn');

        const fromCode = fromSel.options[fromSel.selectedIndex].getAttribute('data-code');
        const toCode = toSel.options[toSel.selectedIndex].getAttribute('data-code');

        if (fromSel.value === toSel.value || globalRate <= 0) {
            submitBtn.disabled = true;
            return;
        }

        // الحساب الفعلي المعتمد بالكنترولر: $toAmount = $request->from_amount / $rate->rate;
        let result = amount / globalRate;
        document.getElementById('resultAmount').innerHTML = `${result.toLocaleString('en-US', {maximumFractionDigits: 2})} ${toCode}`;

        // حساب الرصيد المتوقع بعد الحسم والإضافة الفورية
        let afterFrom = globalFromBalance - amount;
        let afterTo = globalToBalance + result;

        document.getElementById('afterFromBalance').innerHTML = `${afterFrom.toLocaleString('en-US', {maximumFractionDigits: 2})} ${fromCode}`;
        document.getElementById('afterToBalance').innerHTML = `${afterTo.toLocaleString('en-US', {maximumFractionDigits: 2})} ${toCode}`;

        // فحص الرصيد الكافي لمنع تجاوز الخزنة
        if (amount > globalFromBalance) {
            warning.innerHTML = `❌ رصيد غير كافٍ: القيمة المدخلة أعلى من المتاح بالخزنة المصدر (${globalFromBalance.toLocaleString('en-US')} ${fromCode}).`;
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
            document.getElementById('afterFromBalance').className = "text-lg font-black font-mono text-red-600 dark:text-red-400";
            return;
        } else {
            document.getElementById('afterFromBalance').className = "text-lg font-black font-mono text-amber-700 dark:text-amber-400";
        }

        if (amount > 0) {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
            // 3️⃣ تحديث بطاقة الملخص النهائي المباشر
            document.getElementById('exchangeSummary').innerHTML = `💸 سيتم خصم <strong class="underline">${amount.toLocaleString('en-US', {maximumFractionDigits: 2})} ${fromCode}</strong> من الخزنة الأصلية، وسيتم إضافة <strong class="underline">${result.toLocaleString('en-US', {maximumFractionDigits: 2})} ${toCode}</strong> إلى الخزنة المستهدفة طبقاً للنظام المالي المعتمد لجناحك.`;
        } else {
            document.getElementById('exchangeSummary').innerHTML = 'يرجى كتابة المبلغ المستهدف الحقيقي...';
            submitBtn.disabled = true;
        }
    }

    function resetCalculationsReal() {
        document.getElementById('resultAmount').innerHTML = '0.00';
        document.getElementById('afterFromBalance').innerHTML = '--';
        document.getElementById('afterToBalance').innerHTML = '--';
        document.getElementById('exchangeSummary').innerHTML = '--';
    }
</script>
