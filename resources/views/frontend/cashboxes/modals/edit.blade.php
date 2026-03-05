<div id="editCurrencyModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800
            w-full max-w-md p-6 rounded-2xl shadow-lg">

        <h2 class="text-lg font-bold mb-4">
            تعديل العملة
        </h2>

        <form method="POST"
              id="editCurrencyForm">

            @csrf
            @method('PUT')

            <div class="space-y-4">

                <div>

                    <label class="text-sm">اسم العملة</label>

                    <input type="text"
                           name="name"
                           id="editCurrencyName"
                           required
                           class="w-full border rounded-lg px-3 py-2">

                </div>


                <div>

                    <label class="text-sm">رمز العملة</label>

                    <input type="text"
                           name="symbol"
                           id="editCurrencySymbol"
                           required
                           class="w-full border rounded-lg px-3 py-2">

                </div>


                <div>

                    <label class="text-sm">الحالة</label>

                    <select name="status"
                            id="editCurrencyStatus"
                            class="w-full border rounded-lg px-3 py-2">

                        <option value="1">مفعلة</option>
                        <option value="0">غير مفعلة</option>

                    </select>

                </div>


                <div class="flex justify-end gap-3 pt-4">

                    <button type="button"
                            onclick="closeEditCurrencyModal()"
                            class="bg-gray-400 text-white px-4 py-2 rounded-lg">

                        إلغاء

                    </button>

                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">

                        حفظ

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

    function openEditCurrencyModal(id,name,symbol,status){

        document.getElementById('editCurrencyModal').classList.remove('hidden')
        document.getElementById('editCurrencyModal').classList.add('flex')

        document.getElementById('editCurrencyName').value=name
        document.getElementById('editCurrencySymbol').value=symbol
        document.getElementById('editCurrencyStatus').value=status

        document.getElementById('editCurrencyForm').action =
            '/dashboard/cashboxes/update/'+id

    }

    function closeEditCurrencyModal(){

        document.getElementById('editCurrencyModal').classList.add('hidden')
        document.getElementById('editCurrencyModal').classList.remove('flex')

    }

</script>
