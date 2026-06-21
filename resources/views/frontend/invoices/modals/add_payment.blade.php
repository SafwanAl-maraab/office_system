{{-- ================= PAYMENT MODAL ================= --}}
<div id="paymentModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    {{-- خلفية لإغلاق المودال --}}
    <div onclick="closePaymentModal()" class="absolute inset-0"></div>

    {{-- محتوى المودال --}}
    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-lg rounded-2xl shadow-2xl
                p-6 md:p-8 animate-scaleIn">

        {{-- زر إغلاق --}}
        <button onclick="closePaymentModal()"
                class="absolute top-4 left-4 text-gray-400 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            تسجيل دفعة جديدة
        </h2>

        @php
            $currency = $invoice->currency->symbol ?? '';
        @endphp

        <form id="paymentForm" method="post" action="{{ route('dashboard.addInvoice')}}" class="space-y-6">
            @csrf

            {{-- المبلغ --}}
            <div>
                <label class="block text-sm mb-2 text-gray-700 dark:text-gray-300 font-medium">
                    المبلغ المراد دفعه
                </label>
                <input type="hidden" value="{{$invoice->id}}" name="invId" >

                <div class="relative">
                    <input type="number"
                           step="0.01"
                           name="amount"
                           id="paymentAmount"
                           max="{{ $invoice->remaining_amount }}"
                           class="w-full px-4 py-3 rounded-xl border
                                  dark:bg-gray-900 dark:border-gray-600
                                  focus:ring-2 focus:ring-green-500 outline-none transition"
                           placeholder="0.00"
                           required>

                    <span class="absolute left-4 top-3 text-gray-400 text-sm">
                        {{ $currency }}
                    </span>
                </div>

                {{-- الحقول الديناميكية الجديدة المضافة أسفل الحقل مباشرة --}}
                <div id="liveCalculations" class="hidden mt-3 p-3 rounded-xl bg-blue-50/50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/50 text-xs space-y-1.5 transition-all">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>المدفوع الإجمالي بعد الحفظ:</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400"><span id="liveTotalPaid">0.00</span> {{ $currency }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>المتبقي بعد الحفظ:</span>
                        <span class="font-semibold text-gray-700 dark:text-gray-300"><span id="liveTotalRemaining">0.00</span> {{ $currency }}</span>
                    </div>
                </div>

                {{-- رسالة التحذير --}}
                <p id="overAmountWarning"
                   class="hidden text-sm text-red-600 mt-2 font-semibold flex items-center gap-1">
                    ⚠ المبلغ أكبر من المتبقي في الفاتورة
                </p>
            </div>

            {{-- طريقة الدفع --}}
            <div>
                <label class="block text-sm mb-2 text-gray-700 dark:text-gray-300 font-medium">
                    طريقة الدفع
                </label>

                <select name="payment_method"
                        class="w-full px-4 py-3 rounded-xl border
                               dark:bg-gray-900 dark:border-gray-600
                               focus:ring-2 focus:ring-green-500 outline-none"
                        required>
                    <option value="cash">نقدي</option>
                    <option value="transfer">تحويل بنكي</option>
                </select>
            </div>

            {{-- معلومات سريعة الحالية بالفاتورة --}}
            <div class="bg-gray-50 dark:bg-gray-900/60 rounded-xl p-4 text-sm space-y-2 border border-gray-100 dark:border-gray-700">
                <div class="text-xs font-semibold text-gray-400 mb-1">تفاصيل الفاتورة الحالية:</div>
                <div class="flex justify-between">
                    <span>الإجمالي:</span>
                    <span class="font-medium">{{ number_format($invoice->total_amount,2) }} {{ $currency }}</span>
                </div>

                <div class="flex justify-between text-green-600 dark:text-green-400">
                    <span>المدفوع حالياً:</span>
                    <span class="font-medium">{{ number_format($invoice->paid_amount,2) }} {{ $currency }}</span>
                </div>

                <div class="flex justify-between text-red-600 dark:text-red-400 font-semibold">
                    <span>المتبقي حالياً:</span>
                    <span>{{ number_format($invoice->remaining_amount,2) }} {{ $currency }}</span>
                </div>
            </div>

            {{-- أزرار التحكم --}}
            <div class="flex flex-col md:flex-row gap-3 justify-end pt-2">
                <button type="button"
                        onclick="closePaymentModal()"
                        class="px-5 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 text-sm font-medium transition">
                    إلغاء
                </button>

                <button type="submit"
                        id="submitBtn"
                        class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition disabled:opacity-50 disabled:cursor-not-allowed">
                    حفظ الدفعة
                </button>
            </div>

        </form>

    </div>
</div>

{{-- Animation --}}
<style>
    @keyframes scaleIn {
        from { transform: scale(.95); opacity: 0 }
        to { transform: scale(1); opacity: 1 }
    }
    .animate-scaleIn {
        animation: scaleIn .2s ease-out;
    }
</style>

{{-- JS تحديث الحسابات اللحظي --}}
<script>
    // جلب البيانات من السيرفر كأرقام حقيقية
    const currentPaid = parseFloat("{{ $invoice->paid_amount }}") || 0;
    const currentRemaining = parseFloat("{{ $invoice->remaining_amount }}") || 0;

    // عناصر واجهة المستخدم
    const amountInput = document.getElementById('paymentAmount');
    const warning = document.getElementById('overAmountWarning');
    const liveCalculations = document.getElementById('liveCalculations');
    const liveTotalPaid = document.getElementById('liveTotalPaid');
    const liveTotalRemaining = document.getElementById('liveTotalRemaining');
    const submitBtn = document.getElementById('submitBtn');
    const paymentForm = document.getElementById('paymentForm');

    function openPaymentModal() {
        document.getElementById('paymentModal').classList.replace('hidden','flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.replace('flex','hidden');
        // تفريغ المدخلات عند الإغلاق
        if(amountInput) {
            amountInput.value = '';
            liveCalculations.classList.add('hidden');
            warning.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    // مراقبة عملية الإدخال اللحظية
    amountInput?.addEventListener('input', function () {
        const enteredAmount = parseFloat(this.value) || 0;

        // إذا كان الحقل فارغاً أو صفر، نخفي صندوق الحسابات اللحظي
        if (enteredAmount <= 0) {
            liveCalculations.classList.add('hidden');
            warning.classList.add('hidden');
            submitBtn.disabled = false;
            return;
        }

        // إظهار صندوق العمليات الحسابية اللحظية
        liveCalculations.classList.remove('hidden');

        // العمليات الحسابية
        const newPaid = currentPaid + enteredAmount;
        const newRemaining = currentRemaining - enteredAmount;

        // تحديث النصوص في الواجهة وتنسيقها لرقمين عشريين
        liveTotalPaid.innerText = newPaid.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        liveTotalRemaining.innerText = Math.max(0, newRemaining).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // التحقق من تجاوز الحد الأقصى للمتبقي
        if (enteredAmount > currentRemaining) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true; // تعطيل زر الحفظ لحماية النظام
            liveCalculations.classList.add('border-red-200', 'bg-red-50/30'); // تغيير شكل الصندوق للتحذير
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false; // تفعيل زر الحفظ
            liveCalculations.classList.remove('border-red-200', 'bg-red-50/30');
        }
    });

    // حماية إضافية لمنع الإرسال بالـ Enter إذا كان المبلغ خاطئاً
    paymentForm?.addEventListener('submit', function (e) {
        const enteredAmount = parseFloat(amountInput.value) || 0;
        if (enteredAmount > currentRemaining || enteredAmount <= 0) {
            e.preventDefault();
        }
    });
</script>
