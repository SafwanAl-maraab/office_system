<div id="statusModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div onclick="closeStatusModal()" class="absolute inset-0"></div>

    <div class="relative bg-white dark:bg-gray-800 w-full max-w-md
                rounded-2xl shadow-xl p-6 mx-4">

        <button onclick="closeStatusModal()"
                class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            تغيير حالة الطلب
        </h2>

        <form method="POST" id="statusForm">
            @csrf

            <div class="space-y-4">

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        الحالة الجديدة
                    </label>

                    <select name="new_status"
                            id="statusSelect"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                   bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                            required>

                        <option value="under_review">قيد المراجعة</option>
                        <option value="preparing">قيد التجهيز</option>
                        <option value="sent_to_south">تم الإرسال</option>
                        <option value="received_south">تم الاستلام</option>
                        <option value="ready">جاهز</option>
                        <option value="delivered">تم التسليم</option>
                        <option value="cancelled">ملغي</option>
                        <option value="rejected">مرفوض</option>

                    </select>

                    <div id="paymentWarning"
                         class="hidden mt-2 text-sm text-red-600 font-semibold">
                        ⚠ لم يتم اكتمال الدفع في الفاتورة
                    </div>

                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        سبب التغيير (إجباري عند الإلغاء أو الرفض)
                    </label>

                    <textarea name="notes"
                              rows="3"
                              class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                     bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <button type="button"
                            onclick="closeStatusModal()"
                            class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white">
                        إلغاء
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white">
                        حفظ التغيير
                    </button>

                </div>

            </div>
        </form>

    </div>
</div>
<script>

    let currentRemaining = 0;

    function openStatusModal(requestId, currentStatus, remainingAmount) {

        const modal = document.getElementById('statusModal');
        const form = document.getElementById('statusForm');
        const select = document.getElementById('statusSelect');

        currentRemaining = remainingAmount;

        // تحديد الرابط
        form.action = '/dashboard/requests/' + requestId + '/change-status';

        // تحديد الحالة الحالية تلقائيًا
        select.value = currentStatus;

        checkPaymentWarning();

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function checkPaymentWarning() {

        const selected = document.getElementById('statusSelect').value;
        const warning = document.getElementById('paymentWarning');

        if (
            currentRemaining > 0 &&
            (selected === 'ready' || selected === 'delivered')
        ) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }

    document.getElementById('statusSelect')
        .addEventListener('change', checkPaymentWarning);

</script>
