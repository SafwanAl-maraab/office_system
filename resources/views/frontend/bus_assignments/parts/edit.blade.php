<div id="editModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-xl p-6">

        <h2 class="font-bold text-lg mb-4 dark:text-white">
            تعديل الوقت
        </h2>

        <form method="POST" id="editForm" class="space-y-4">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3">

                <input
                    type="time"
                    name="start_at"
                    id="edit_start"
                    class="border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white">

                <input
                    type="time"
                    name="end_at"
                    id="edit_end"
                    class="border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white">

            </div>


            <div class="flex justify-end gap-3 pt-4">

                <button
                    type="button"
                    onclick="closeEdit()"
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

<script>
function closeEdit(){

editModal.classList.add('hidden')

}
</script>
