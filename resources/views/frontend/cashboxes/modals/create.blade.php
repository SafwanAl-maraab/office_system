<div id="balanceModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl w-full max-w-md">

        <h2 class="text-lg font-bold mb-4">
            تعديل رصيد الخزنة
        </h2>

        <form method="POST" id="balanceForm">
            @csrf

            <div class="space-y-4">

                <div>
                    <label>الرصيد الجديد</label>
                    <input type="number" step="0.01" name="balance"
                           id="balanceInput"
                           class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <button type="button"
                            onclick="closeBalanceModal()"
                            class="bg-gray-400 text-white px-4 py-2 rounded-lg">
                        إلغاء
                    </button>

                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg">
                        حفظ
                    </button>

                </div>

            </div>

        </form>

    </div>
</div>

<script>

    function openBalanceModal(id,balance){

        document.getElementById('balanceModal').classList.remove('hidden');
        document.getElementById('balanceModal').classList.add('flex');

        document.getElementById('balanceInput').value=balance;

        document.getElementById('balanceForm').action =
            '/dashboard/cashboxes/balance/'+id;

    }

    function closeBalanceModal(){

        document.getElementById('balanceModal').classList.add('hidden');
        document.getElementById('balanceModal').classList.remove('flex');

    }

</script>
