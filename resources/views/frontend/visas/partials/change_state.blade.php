<!-- STATUS MODAL -->
<div id="statusModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 p-8">

        <h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">
            تغيير حالة التأشيرة
        </h2>

        <form method="POST"
              action="{{ route('visas.changeStatus',$visa->id) }}"
              class="space-y-6">
            @csrf

            <!-- SELECT STATUS -->
            <div>
                <label class="block text-sm mb-2">اختر الحالة الجديدة</label>

                <select name="status"
                        id="statusSelect"
                        required
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                               bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                               focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <option value="pending" {{ $visa->isPending() ? 'selected':'' }}>
                        Pending
                    </option>

                    <option value="issued" {{ $visa->isIssued() ? 'selected':'' }}>
                        Issued
                    </option>

                    <option value="cancelled" {{ $visa->isCancelled() ? 'selected':'' }}>
                        Cancelled
                    </option>

                </select>
            </div>

            <!-- CANCEL REASON -->
            <div id="cancelReasonBox"
                 class="hidden">

                <label class="block text-sm mb-2 text-red-600">
                    سبب الإلغاء
                </label>

                <textarea name="cancel_reason"
                          rows="3"
                          class="w-full px-4 py-2 rounded-xl border border-red-300 dark:border-red-700
                                 bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                                 focus:ring-2 focus:ring-red-500 focus:outline-none"
                          placeholder="اكتب سبب الإلغاء هنا..."></textarea>

            </div>

            <!-- ACTIONS -->
            <div class="flex justify-end gap-4 pt-4">

                <button type="button"
                        onclick="closeStatusModal()"
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
function openStatusModal(){
    document.getElementById('statusModal').classList.remove('hidden');
}
function closeStatusModal(){
    document.getElementById('statusModal').classList.add('hidden');
}

// إظهار سبب الإلغاء عند اختيار cancelled
document.getElementById('statusSelect').addEventListener('change',function(){
    let box=document.getElementById('cancelReasonBox');
    if(this.value==='cancelled'){
        box.classList.remove('hidden');
    }else{
        box.classList.add('hidden');
    }
});
</script>
