<!-- STATUS MODAL -->
<div id="statusModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 p-8">

        <h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">
            تغيير حالة التأشيرة
        </h2>

        <form method="POST" id="statusForm" enctype="multipart/form-data">
            @csrf

            <!-- الحالة -->
            <div class="space-y-2">
                <label class="block text-sm font-medium">
                    اختر الحالة الجديدة
                </label>

                <select name="status"
                        id="statusSelect"
                        required
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                               bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                               focus:ring-2 focus:ring-blue-500 focus:outline-none">

                    <option value="pending">قيد المعالجة</option>
                    <option value="issued">صادرة</option>
                    <option value="cancelled">ملغية</option>

                </select>
            </div>

            <!-- سبب الإلغاء -->
            <div id="cancelReasonBox" class="hidden mt-4 space-y-2">

                <label class="block text-sm font-medium text-red-600">
                    سبب الإلغاء
                </label>

                <textarea name="cancel_reason"
                          rows="3"
                          class="w-full px-4 py-2 rounded-xl border border-red-300 dark:border-red-700
                                 bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                                 focus:ring-2 focus:ring-red-500 focus:outline-none"
                          placeholder="اكتب سبب الإلغاء هنا..."></textarea>

            </div>

            <!-- رفع ملف التأشيرة -->
            <div id="visaFileBox" class="hidden mt-4 space-y-2">

                <label class="text-sm font-medium text-green-600">
                    ملف التأشيرة (صورة أو PDF)
                </label>

                <input type="file"
                       name="visa_file"
                       accept="image/*,.pdf"
                       class="w-full border border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800
                              text-gray-800 dark:text-white
                              rounded-xl px-4 py-2">

                <p class="text-xs text-gray-500">
                    يمكن رفع صورة أو ملف PDF
                </p>

            </div>

            <!-- الأزرار -->
            <div class="flex justify-end gap-4 pt-6">

                <button type="button"
                        onclick="closeStatusModal()"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl">
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

function openStatusModal(id){

    if(!id){
        alert("خطأ: لم يتم العثور على رقم التأشيرة");
        return;
    }

    let form = document.getElementById('statusForm');

    form.action = "/visas/" + id + "/change-status";

    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal(){
    document.getElementById('statusModal').classList.add('hidden');
}

let statusSelect = document.getElementById('statusSelect');

if(statusSelect){

    statusSelect.addEventListener('change',function(){

        let cancelBox = document.getElementById('cancelReasonBox');
        let visaFileBox = document.getElementById('visaFileBox');

        if(this.value === 'cancelled'){

            cancelBox.classList.remove('hidden');
            visaFileBox.classList.add('hidden');

        }
        else if(this.value === 'issued'){

            visaFileBox.classList.remove('hidden');
            cancelBox.classList.add('hidden');

        }
        else{

            cancelBox.classList.add('hidden');
            visaFileBox.classList.add('hidden');

        }

    });

}

</script>
