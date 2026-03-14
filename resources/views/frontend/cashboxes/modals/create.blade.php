<div id="createCurrencyModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-800
            w-full max-w-md
            rounded-2xl shadow-xl
            p-6
            max-h-[90vh] overflow-y-auto">

        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
            إضافة عملة جديدة
        </h2>

        <form method="POST"
              action="{{ route('dashboard.cashboxes.store') }}">

            @csrf

            <div class="space-y-4">

                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        اسم العملة
                    </label>

                    <input type="text"
                           name="name"
                           required
                           class="w-full border rounded-lg px-3 py-2
                           dark:bg-gray-700 dark:text-white">
                </div>


                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        رمز العملة
                    </label>

                    <input type="text"
                           name="symbol"
                           required
                           class="w-full border rounded-lg px-3 py-2
                           dark:bg-gray-700 dark:text-white">
                </div>


                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">
                        كود العملة
                    </label>

                    <input type="text"
                           name="code"
                           class="w-full border rounded-lg px-3 py-2
                           dark:bg-gray-700 dark:text-white">
                </div>


                <div class="flex justify-end gap-3 pt-4">

                    <button type="button"
                            onclick="closeCreateCurrencyModal()"
                            class="bg-gray-400 text-white px-4 py-2 rounded-lg">
                        إلغاء
                    </button>

                    <button
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                        حفظ
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<script>

    function openCreateCurrencyModal(){

        const modal = document.getElementById('createCurrencyModal')

        modal.classList.remove('hidden')
        modal.classList.add('flex')

    }

    function closeCreateCurrencyModal(){

        const modal = document.getElementById('createCurrencyModal')

        modal.classList.add('hidden')
        modal.classList.remove('flex')

    }


    document.getElementById('createCurrencyModal')
        .addEventListener('click',function(e){

            if(e.target === this){

                closeCreateCurrencyModal()

            }

        })

</script>
