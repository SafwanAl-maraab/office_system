<!-- PAYMENT MODAL -->
<div id="paymentModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 p-8">

        <h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">
            إضافة دفعة جديدة
        </h2>

        <form method="POST"
              action="{{ route('visas.addPayment',$visa->id) }}"
              class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm mb-2">المبلغ</label>
                <input type="number"
                       name="amount"
                       step="0.01"
                       max="{{ $remaining }}"
                       required
                       class="input-style">
                <p class="text-xs text-gray-400 mt-1">
                    المتبقي: {{ number_format($remaining,2) }}
                </p>
            </div>

            <div>
                <label class="block text-sm mb-2">طريقة الدفع</label>
                <select name="payment_method" class="input-style">
                    <option value="cash">نقدي</option>
                    <option value="transfer">تحويل</option>
                </select>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <button type="button"
                        onclick="closePaymentModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-xl">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                    حفظ
                </button>
            </div>

        </form>

    </div>
</div>


<script>
function openPaymentModal(){
    document.getElementById('paymentModal').classList.remove('hidden');
}
function closePaymentModal(){
    document.getElementById('paymentModal').classList.add('hidden');
}
window.addEventListener('click',function(e){
    if(e.target.id==='paymentModal'){
        closePaymentModal();
    }
});
</script>