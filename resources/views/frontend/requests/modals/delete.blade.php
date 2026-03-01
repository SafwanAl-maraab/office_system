<div id="deleteModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl p-6 relative text-center">

        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
            هل أنت متأكد من إلغاء هذا الطلب؟
        </h2>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
            سيتم تحويل الحالة إلى "ملغي"
        </p>

        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')

            <div class="flex justify-center gap-4">

                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white">
                    تأكيد
                </button>

            </div>

        </form>

    </div>
</div>


<script>
    function openDeleteModal(id) {

        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');

        form.action = '/dashboard/requests/' + id;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
