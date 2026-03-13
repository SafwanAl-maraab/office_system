<div id="createCurrencyModal"
     class="fixed inset-0 bg-black/60 hidden items-center  justify-center z-50">

    <div class="bg-white dark:bg-gray-800
            w-full max-w-md p-6 rounded-2xl">

        <h2 class="text-lg font-bold mb-4">
            إضافة عملة جديدة
        </h2>

        <form method="POST"
              action="{{ route('dashboard.cashboxes.store') }}">

            @csrf

            <div class="space-y-4">

                <div>

                    <label class="text-sm">اسم العملة</label>

                    <input type="text"
                           name="name"
                           required

                           class="w-full border rounded-lg px-3 py-2">

                </div>


                <div>

                    <label class="text-sm">رمز العملة</label>

                    <input type="text"
                           name="symbol"
                           required

                           class="w-full border rounded-lg px-3 py-2">

                </div>


                <div>

                    <label class="text-sm">كود العملة</label>

                    <input type="text"
                           name="code"

                           class="w-full border rounded-lg px-3 py-2">

                </div>


                <div class="flex justify-end gap-3 pt-4">

                    <button type="button"
                            onclick="closeCreateCurrencyModal()"
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

    function openCreateCurrencyModal(){

        document.getElementById('createCurrencyModal').classList.remove('hidden')
        document.getElementById('createCurrencyModal').classList.add('flex')

    }

    function closeCreateCurrencyModal(){

        document.getElementById('createCurrencyModal').classList.add('hidden')
        document.getElementById('createCurrencyModal').classList.remove('flex')

    }

</script>
