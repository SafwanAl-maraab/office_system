<<div id="voucherModal"
      class="fixed inset-0 z-50 hidden">

    <div id="voucherOverlay"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm">
    </div>

    <div class="relative w-full h-full flex items-center justify-center p-4">

        <div class="w-full max-w-4xl
                    max-h-[90vh]
                    overflow-y-auto
                    bg-white dark:bg-gray-900
                    rounded-3xl
                    shadow-2xl">

            <div class="sticky top-0 z-10 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-6 py-5">

                <div class="flex justify-between items-center">

                    <div>

                        <h3 class="text-xl font-bold">

                            إنشاء سند جديد

                        </h3>

                        <p class="text-sm text-gray-500">

                            سند قبض أو سند صرف للعميل

                        </p>

                    </div>

                    <button id="closeVoucherModal"
                            type="button"
                            class="h-10 w-10 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">

                        ✕

                    </button>

                </div>

            </div>

            <form method="POST"
                  action="{{ route('client-vouchers.store') }}"
                  class="p-6 space-y-6">

                @csrf

                <input type="hidden"
                       id="client_id"
                       name="client_id">

                <div>

                    <label class="block mb-2 font-medium">

                        العميل

                    </label>

                    <input
                        type="text"
                        id="clientSearch"
                        autocomplete="off"
                        placeholder="ابحث بالاسم أو الهاتف أو الجواز..."
                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">

                    <div id="clientResults"
                         class="hidden mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-lg max-h-64 overflow-y-auto">
                    </div>

                </div>

                <div id="selectedClientCard"
                     class="hidden rounded-3xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-5">

                    <div class="flex justify-between items-center">

                        <div>

                            <div id="selectedClientName"
                                 class="font-bold text-lg">
                            </div>

                            <div id="selectedClientPhone"
                                 class="text-sm text-gray-500 mt-1">
                            </div>

                        </div>

                        <div class="text-5xl">

                            👤

                        </div>

                    </div>

                </div>

                <div id="clientBalancesSection"
                     class="hidden">

                    <div class="font-bold mb-3">

                        الوضع المالي للعميل

                    </div>

                    <div id="clientBalances"
                         class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    </div>

                </div>

                <div class="grid md:grid-cols-2 gap-5">

                    <div>

                        <label class="block mb-2 font-medium">

                            نوع السند

                        </label>

                        <select
                            name="type"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">

                            <option value="receipt">

                                سند قبض

                            </option>

                            <option value="payment">

                                سند صرف

                            </option>

                        </select>

                    </div>

                    <div>

                        <label class="block mb-2 font-medium">

                            العملة

                        </label>

                        <select
                            name="currency_id"
                            required
                            class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">

                            @foreach($currencies as $currency)

                                <option value="{{ $currency->id }}">

                                    {{ $currency->code }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div>

                    <label class="block mb-2 font-medium">

                        المبلغ

                    </label>

                    <input type="number"
                           step="0.01"
                           name="amount"
                           required
                           class="w-full px-5 py-4 rounded-2xl border">

                </div>

                <div>

                    <label class="block mb-2 font-medium">

                        ملاحظات

                    </label>

                    <textarea
                        rows="3"
                        name="notes"
                        class="w-full px-5 py-4 rounded-2xl border"></textarea>

                </div>

                <div class="flex flex-col md:flex-row gap-3 pt-3">

                    <button type="submit"
                            class="flex-1 py-4 rounded-2xl bg-blue-600 text-white font-bold">

                        حفظ السند

                    </button>

                    <button type="button"
                            id="cancelVoucherModal"
                            class="flex-1 py-4 rounded-2xl bg-gray-200 dark:bg-gray-700">

                        إلغاء

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('voucherModal');

        const overlay = document.getElementById('voucherOverlay');

        const openBtn = document.getElementById('openVoucherModal');

        const closeBtn = document.getElementById('closeVoucherModal');

        const searchInput = document.getElementById('clientSearch');

        const resultsBox = document.getElementById('clientResults');

        const selectedCard = document.getElementById('selectedClientCard');

        const selectedName = document.getElementById('selectedClientName');

        const selectedPhone = document.getElementById('selectedClientPhone');

        const balancesSection = document.getElementById('clientBalancesSection');

        const balancesWrapper = document.getElementById('clientBalances');

        const clientIdInput = document.getElementById('client_id');



        /* ===========================
           OPEN / CLOSE
        ============================ */

        function openModal() {

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');

        }

        function closeModal() {

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');

        }

        openBtn?.addEventListener('click', openModal);

        closeBtn?.addEventListener('click', closeModal);

        overlay?.addEventListener('click', closeModal);


        const form =
            document.querySelector(
                '#voucherModal form'
            );

        form.addEventListener(
            'submit',
            function(e){

                const client =
                    clientIdInput.value;

                if(!client){

                    e.preventDefault();

                    alert(
                        'اختر العميل أولاً'
                    );

                    return;
                }

                const amount =
                    this.querySelector(
                        'input[name="amount"]'
                    ).value;

                if(
                    !amount ||
                    Number(amount) <= 0
                ){

                    e.preventDefault();

                    alert(
                        'أدخل المبلغ'
                    );

                }

            }
        );
        document.addEventListener('keydown', function(e){

            if(e.key === 'Escape'){

                closeModal();

            }

        });



        /* ===========================
           SEARCH CLIENT
        ============================ */

        let timer = null;

        searchInput.addEventListener('keyup', function(){

            clearTimeout(timer);

            const value = this.value.trim();

            if(value.length < 2){

                resultsBox.classList.add('hidden');

                resultsBox.innerHTML = '';

                return;

            }

            timer = setTimeout(function(){

                fetch('/dashboard/clients/search?search=' + encodeURIComponent(value))

                    .then(response => response.json())

                    .then(clients => {

                        resultsBox.innerHTML = '';

                        if(clients.length === 0){

                            resultsBox.innerHTML = `

                            <div class="p-4 text-center text-gray-500">

                                لا توجد نتائج

                            </div>

                        `;

                            resultsBox.classList.remove('hidden');

                            return;
                        }

                        clients.forEach(client => {

                            const row = document.createElement('div');

                            row.className = `
                            p-4
                            border-b
                            border-gray-100
                            dark:border-gray-700
                            cursor-pointer
                            hover:bg-blue-50
                            dark:hover:bg-gray-700
                            transition
                        `;

                            row.innerHTML = `

                            <div class="font-semibold">

                                ${client.full_name}

                            </div>

                            <div class="text-sm text-gray-500">

                                ${client.phone ?? ''}

                            </div>

                        `;

                            row.addEventListener('click', function(){

                                selectClient(client);

                            });

                            resultsBox.appendChild(row);

                        });

                        resultsBox.classList.remove('hidden');

                    });

            },300);

        });



        /* ===========================
           SELECT CLIENT
        ============================ */

        function selectClient(client)
        {
            clientIdInput.value = client.id;

            searchInput.value = client.full_name;

            selectedName.textContent = client.full_name;

            selectedPhone.textContent = client.phone ?? '';

            selectedCard.classList.remove('hidden');

            resultsBox.classList.add('hidden');

            loadClientInfo(client.id);
        }



        /* ===========================
           CLIENT INFO
        ============================ */

        function loadClientInfo(clientId)
        {

            fetch('/client-vouchers/client-info/' + clientId)

                .then(response => response.json())

                .then(data => {

                    console.log(data);

                    balancesWrapper.innerHTML = '';

                    balancesSection.classList.remove('hidden');

                    if(!data.balances.length){

                        balancesWrapper.innerHTML = `

                        <div class="col-span-full rounded-3xl bg-gray-100 dark:bg-gray-800 p-5 text-center">

                            لا توجد بيانات مالية

                        </div>

                    `;

                        return;
                    }

                    data.balances.forEach(balance => {
                        const currencySelect =
                            document.querySelector(
                                'select[name="currency_id"]'
                            );

                        const card = document.createElement('div');

                        card.className = `
                        rounded-3xl
                        border
                        border-gray-200
                        dark:border-gray-700
                        bg-white
                        dark:bg-gray-800
                        p-5
                    `;

                        card.innerHTML = `

                        <div class="flex justify-between items-center mb-4">

                            <div class="font-bold text-lg">

                                ${balance.currency}

                            </div>

                            <div class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-600">

                                عملة

                            </div>

                        </div>

                        <div class="space-y-3">

                            <div class="flex justify-between">

                                <span class="text-gray-500">

                                    عليه

                                </span>

                                <span class="font-bold text-red-600">

                                    ${Number(balance.due).toLocaleString()}

                                </span>

                            </div>

                            <div class="flex justify-between">

                                <span class="text-gray-500">

                                    له

                                </span>

                                <span class="font-bold text-green-600">

                                    ${Number(balance.credit).toLocaleString()}

                                </span>

                            </div>

                            <div class="border-t pt-3 flex justify-between">

                                <span class="font-semibold">

                                    الصافي

                                </span>

                                <span class="font-bold text-indigo-600">

                                    ${Number(balance.balance).toLocaleString()}

                                </span>

                            </div>

                        </div>

                    `;

                        balancesWrapper.appendChild(card);

                    });
                    const activeCurrencies =
                        data.balances.filter(b =>
                            Number(b.due) > 0 ||
                            Number(b.credit) > 0
                        );

                    if(activeCurrencies.length === 1){

                        const code =
                            activeCurrencies[0].currency;

                        [...currencySelect.options]
                            .forEach(option => {

                                if(option.text.trim() === code){

                                    currencySelect.value =
                                        option.value;

                                }

                            });

                    }

                })

                .catch(error => {

                    console.error(error);

                });

        }

    });

</script>
