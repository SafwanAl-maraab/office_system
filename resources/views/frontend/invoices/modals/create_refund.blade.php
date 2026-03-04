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
