<div id="deleteModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-xl p-6">

        <h2 class="text-lg font-bold mb-4 dark:text-white">
            تأكيد حذف الرحلة
        </h2>

        <p class="text-gray-600 dark:text-gray-300 mb-6">
            هل أنت متأكد من حذف هذه الرحلة؟
        </p>

        <form id="deleteForm" method="POST">

            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-3">

                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 rounded">

                    إلغاء

                </button>

                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">

                    حذف

                </button>

            </div>

        </form>

    </div>
</div>
