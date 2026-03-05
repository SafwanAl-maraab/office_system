<div id="currencyModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">
            تعديل بيانات العملة
        </h2>

        <form method="POST" id="currencyForm">
            @csrf

            <input type="hidden" name="currency_id" id="currencyId">

            <div class="space-y-4">

                <div>
                    <label>اسم العملة</label>
                    <input type="text" name="name" id="currencyName"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label>الرمز</label>
                    <input type="text" name="symbol" id="currencySymbol"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <button type="button"
                            onclick="closeCurrencyModal()"
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

    function openCurrencyModal(id,name,symbol){

        document.getElementById('currencyModal').classList.remove('hidden');
        document.getElementById('currencyModal').classList.add('flex');

        document.getElementById('currencyName').value=name;
        document.getElementById('currencySymbol').value=symbol;

        document.getElementById('currencyForm').action =
            '/dashboard/cashboxes/currency/'+id;

    }

    function closeCurrencyModal(){

        document.getElementById('currencyModal').classList.add('hidden');
        document.getElementById('currencyModal').classList.remove('flex');

    }

</script>
