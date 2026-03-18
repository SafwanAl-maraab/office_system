<div id="paymentModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div onclick="closePaymentModal()" class="absolute inset-0"></div>

    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-md mx-4
                rounded-2xl shadow-xl p-6">

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            تسجيل دفعة جديدة
        </h2>

        <form method="post"
              action="{{ route('dashboard.addInvoice' )}}"
              class="space-y-5">

            @csrf

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    المبلغ
                </label>
                <input type="hidden" value="{{$request->invoice->id}}" name="invId" >
                <input type="number"
                       name="amount"
                       max="{{ $request->invoice->remaining_amount }}"
                       class="w-full px-3 py-2 rounded-xl border
                              dark:bg-gray-900 dark:border-gray-600"
                       required>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    طريقة الدفع
                </label>
                <select name="payment_method"
                        class="w-full px-3 py-2 rounded-xl border
                               dark:bg-gray-900 dark:border-gray-600"
                        required>
                    <option value="cash">نقدي</option>
                    <option value="transfer">تحويل</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closePaymentModal()"
                        class="px-4 py-2 rounded-xl bg-gray-400 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white">
                    حفظ الدفعة
                </button>

            </div>

        </form>

    </div>
</div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentModal')
            .classList.replace('hidden','flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal')
            .classList.replace('flex','hidden');
    }
</script>
