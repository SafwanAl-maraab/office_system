<div id="editBusModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl p-6">

        <h2 class="font-bold text-lg mb-4">
            تعديل الباص
        </h2>

        <form method="POST"
              id="editBusForm"
              class="space-y-4">

            @csrf
            @method('PUT')


            <input id="edit_plate_number"
                   name="plate_number"
                   class="w-full border rounded-lg px-4 py-2">


            <input id="edit_model"
                   name="model"
                   class="w-full border rounded-lg px-4 py-2">


            <input id="edit_capacity"
                   name="capacity"
                   class="w-full border rounded-lg px-4 py-2">


            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeEditBusModal()"
                        class="bg-gray-400 text-white px-4 py-2 rounded">

                    إلغاء

                </button>

                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                    تحديث

                </button>

            </div>

        </form>

    </div>

</div>
