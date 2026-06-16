<!-- DELETE MODAL -->

<div id="deleteModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-[9999] p-4">

    <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">

        <!-- HEADER -->

        <div class="p-6 border-b border-gray-200 dark:border-gray-800">

            <div class="flex items-center gap-4">

                <div class="h-14 w-14 rounded-2xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7 text-red-600 dark:text-red-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 9v2m0 4h.01M5.07 19H18.93C20.54 19 21.55 17.25 20.75 15.84L13.82 3.84C13.01 2.43 10.99 2.43 10.18 3.84L3.25 15.84C2.45 17.25 3.46 19 5.07 19z"/>
                    </svg>

                </div>

                <div>

                    <h3 class="text-lg font-bold">
                        تأكيد الحذف
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        سيتم حذف السائق نهائياً
                    </p>

                </div>

            </div>

        </div>

        <!-- BODY -->

        <div class="p-6">

            <p class="text-gray-600 dark:text-gray-300 leading-7">

                هل أنت متأكد من حذف هذا السائق؟

                <br>

                لا يمكن التراجع بعد تنفيذ العملية.

            </p>

        </div>

        <!-- FOOTER -->

        <div class="p-6 border-t border-gray-200 dark:border-gray-800 flex justify-end gap-3">

            <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-5 py-2.5 rounded-2xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 transition">

                إلغاء

            </button>

            <form id="deleteForm" method="POST">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="px-5 py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white transition">

                    حذف السائق

                </button>

            </form>

        </div>

    </div>

</div>

<script>

    function confirmDelete(id)
    {
        const modal = document.getElementById('deleteModal');

        const form = document.getElementById('deleteForm');

        form.action = "/dashboard/drivers/" + id;

        modal.classList.remove('hidden');

        modal.classList.add('flex');
    }

    function closeDeleteModal()
    {
        const modal = document.getElementById('deleteModal');

        modal.classList.remove('flex');

        modal.classList.add('hidden');
    }

    document.addEventListener('keydown', function(e){

        if(e.key === 'Escape'){

            closeDeleteModal();
        }

    });

</script>
