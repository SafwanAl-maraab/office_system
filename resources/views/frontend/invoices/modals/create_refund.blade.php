{{-- ===============================
    مودال إنشاء فاتورة مسترجع
================================ --}}
<div id="refundModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    {{-- خلفية للإغلاق --}}
    <div class="absolute inset-0"
         onclick="closeRefundModal()"></div>

    {{-- الصندوق --}}
    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-lg rounded-2xl shadow-2xl
                p-6 md:p-8 space-y-6
                animate-fadeIn">

        {{-- زر إغلاق --}}
        <button onclick="closeRefundModal()"
                class="absolute top-4 left-4 text-gray-400 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-red-600">
            إنشاء فاتورة مسترجع
        </h2>

        {{-- معلومات الفاتورة --}}
        <div id="refundInvoiceInfo"
             class="bg-gray-50 dark:bg-gray-900
                    p-4 rounded-xl text-sm space-y-2">
        </div>

        {{-- النموذج --}}
        <form method="POST"
              id="refundForm"
              class="space-y-4">

            @csrf

            <div>
                <label class="block text-sm mb-1">
                    المبلغ المسترجع
                </label>

                <input type="number"
                       step="0.01"
                       min="0.01"
                       name="refund_amount"
                       id="refundAmount"
                       required
                       class="w-full px-4 py-2 rounded-xl border
                              dark:bg-gray-900 dark:border-gray-700">

                <p id="refundError"
                   class="hidden text-red-600 text-sm mt-2">
                    لا يمكن استرجاع أكثر من المدفوع
                </p>
            </div>

            <div>
                <label class="block text-sm mb-1">
                    سبب الاسترجاع (اختياري)
                </label>

                <textarea name="refund_reason"
                          rows="3"
                          class="w-full px-4 py-2 rounded-xl border
                                 dark:bg-gray-900 dark:border-gray-700"></textarea>
            </div>
            <div class="space-y-3">


                <label class="block text-sm font-medium">
                    طريقة الاسترجاع
                </label>

                <div class="space-y-2">

                    <label class="flex items-center gap-3">

                        <input type="radio"
                               name="refund_method"
                               value="cash"
                               checked>

                        <span>
            تسليم المبلغ للعميل الآن (خصم من الخزنة)
        </span>

                    </label>

                    <label class="flex items-center gap-3">

                        <input type="radio"
                               name="refund_method"
                               value="balance">

                        <span>
            إضافة المبلغ إلى رصيد العميل
        </span>

                    </label>

                </div>


            </div>


            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeRefundModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-xl">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700
                               text-white rounded-xl">
                    تأكيد إنشاء المسترجع
                </button>

            </div>

        </form>

    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity:0; transform:scale(.95); }
        to { opacity:1; transform:scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn .2s ease-out;
    }
</style>

<script>

    let maxRefundValue = 0;

    function openRefundInvoiceModal(invoiceId, paidAmount, clientName, currencySymbol) {

        maxRefundValue = parseFloat(paidAmount);

        const modal = document.getElementById('refundModal');
        const form  = document.getElementById('refundForm');
        const info  = document.getElementById('refundInvoiceInfo');

        // ضبط الرابط
        form.action = `/dashboard/invoices/${invoiceId}/refund`;

        // تعبئة المعلومات
        info.innerHTML = `
        <div>العميل: <strong>${clientName}</strong></div>
        <div>إجمالي المدفوع: <strong>${paidAmount.toFixed(2)} ${currencySymbol}</strong></div>
        <div class="text-xs text-gray-500">
            يمكنك استرجاع مبلغ لا يتجاوز المدفوع.
        </div>
    `;

        // إعادة تعيين الإدخال
        document.getElementById('refundAmount').value = '';
        document.getElementById('refundError').classList.add('hidden');

        // إظهار
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRefundModal() {
        const modal = document.getElementById('refundModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // حماية الإدخال
    document.addEventListener('DOMContentLoaded', function(){

        const input = document.getElementById('refundAmount');

        input.addEventListener('input', function(){

            const value = parseFloat(this.value);

            if (value > maxRefundValue) {
                document.getElementById('refundError')
                    .classList.remove('hidden');
            } else {
                document.getElementById('refundError')
                    .classList.add('hidden');
            }

        });

    });
</script>
<script>

    let currentRate = 0;
    let reverseRate = false;

    const fromCurrency =
        document.getElementById(
            'fromCurrency'
        );

    const toCurrency =
        document.getElementById(
            'toCurrency'
        );

    const amountInput =
        document.getElementById(
            'fromAmount'
        );

    function openExchangeModal()
    {
        const modal =
            document.getElementById(
                'exchangeModal'
            );

        modal.classList.remove(
            'hidden'
        );

        modal.classList.add(
            'flex'
        );

        document.body.style.overflow =
            'hidden';

        loadExchangeData();
    }

    function closeExchangeModal()
    {
        const modal =
            document.getElementById(
                'exchangeModal'
            );

        modal.classList.remove(
            'flex'
        );

        modal.classList.add(
            'hidden'
        );

        document.body.style.overflow =
            'auto';
    }

    async function loadExchangeData()
    {
        if(
            fromCurrency.value ===
            toCurrency.value
        ){
            document.getElementById(
                'rateDisplay'
            ).innerHTML =
                'اختر عملتين مختلفتين';

            document.getElementById(
                'resultAmount'
            ).innerHTML =
                '0.00';

            return;
        }

        /*
        ============================
        الأرصدة
        ============================
        */

        const balanceResponse =
            await fetch(

                `{{ route('cashbox-exchanges.get-balances') }}?from_currency_id=${fromCurrency.value}&to_currency_id=${toCurrency.value}`

            );

        const balanceData =
            await balanceResponse.json();

        document.getElementById(
            'fromBalance'
        ).innerHTML =

            'الرصيد الحالي: <strong>' +
            Number(
                balanceData.from_balance
            ).toLocaleString() +
            '</strong>';

        document.getElementById(
            'toBalance'
        ).innerHTML =

            'الرصيد الحالي: <strong>' +
            Number(
                balanceData.to_balance
            ).toLocaleString() +
            '</strong>';

        /*
        ============================
        سعر الصرف
        ============================
        */

        const rateResponse =
            await fetch(

                `{{ route('cashbox-exchanges.get-rate') }}?from_currency_id=${fromCurrency.value}&to_currency_id=${toCurrency.value}`

            );

        const rateData =
            await rateResponse.json();

        if(rateData.success)
        {
            currentRate =
                parseFloat(
                    rateData.rate
                );

            reverseRate =
                rateData.reverse;

            document.getElementById(
                'rateDisplay'
            ).innerHTML =
                rateData.display;
        }
        else
        {
            currentRate = 0;

            reverseRate = false;

            document.getElementById(
                'rateDisplay'
            ).innerHTML =
                'لا يوجد سعر صرف';
        }

        calculateExchange();
    }

    function calculateExchange()
    {
        const amount =
            parseFloat(
                amountInput.value
            ) || 0;

        if(
            currentRate <= 0
        ){
            document.getElementById(
                'resultAmount'
            ).innerHTML =
                '0.00';

            return;
        }

        let result = 0;

        if(reverseRate)
        {
            result =
                amount *
                currentRate;
        }
        else
        {
            result =
                amount /
                currentRate;
        }

        document.getElementById(
            'resultAmount'
        ).innerHTML =
            result.toFixed(2);

        const summary =
            document.getElementById(
                'exchangeSummary'
            );

        if(summary)
        {
            summary.innerHTML =

                `سيتم خصم ${amount.toFixed(2)}
             وإضافة ${result.toFixed(2)}`;
        }
    }

    fromCurrency.addEventListener(
        'change',
        loadExchangeData
    );

    toCurrency.addEventListener(
        'change',
        loadExchangeData
    );

    amountInput.addEventListener(
        'input',
        calculateExchange
    );

</script>
