<div id="cancelModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-900 w-full max-w-md rounded-2xl p-6 text-center">

        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
            هل أنت متأكد من إلغاء التأشيرة؟
        </h2>

        <form method="POST" action="#">
            @csrf
            @method('DELETE')

            <div class="flex justify-center gap-3 mt-6">
                <button type="button" onclick="closeCancelModal()"
                    class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                    إلغاء
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg">
                    تأكيد
                </button>
            </div>

        </form>

    </div>
</div>