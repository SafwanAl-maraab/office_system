<div id="paymentModal"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-2xl p-6 shadow-lg">

        <h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">
            إضافة دفعة
        </h2>

        <form method="POST" action="#">
            @csrf

            <input type="number" name="amount"
                placeholder="المبلغ"
                class="w-full border border-gray-300 dark:border-gray-700
                       bg-white dark:bg-gray-800
                       text-gray-800 dark:text-white
                       rounded-xl px-4 py-2
                       focus:outline-none focus:ring-2 focus:ring-emerald-500
                       transition duration-200">

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closePaymentModal()"
                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
                    إلغاء
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                    حفظ
                </button>
            </div>

        </form>

    </div>
</div>