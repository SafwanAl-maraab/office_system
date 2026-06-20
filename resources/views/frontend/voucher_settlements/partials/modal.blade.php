<div id="settlementModal" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden" aria-modal="true" role="dialog">

    {{-- الخلفية المعتمة وضبابية الشاشة --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    {{-- حاوية التموضع لضمان التوسط الدائم --}}
    <div class="flex min-h-screen items-center justify-center p-4 sm:p-6">

        {{-- جسم المودال الرئيسي مع التحكم بالارتفاع والسكرول الذكي --}}
        <div class="relative w-full max-w-4xl transform rounded-3xl bg-white dark:bg-slate-900 shadow-2xl transition-all border border-slate-100 dark:border-slate-800 flex flex-col max-h-[calc(100vh-2rem)] overflow-hidden">

            {{-- Header (ثابت) --}}
            <div class="shrink-0 px-6 py-5 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
                            تسوية فاتورة
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            اختيار الرصيد المناسب لتسديد الفاتورة واحتساب فروقات الصرف
                        </p>
                    </div>
                    <button id="closeSettlementModal" type="button" class="w-10 h-10 inline-flex items-center justify-center rounded-xl bg-slate-150 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 transition-colors duration-200 text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        ✕
                    </button>
                </div>
            </div>

            {{-- Body (منطقة السكرول الديناميكي) --}}
            <div class="flex-1 overflow-y-auto p-6 text-slate-700 dark:text-slate-300 custom-scrollbar">
                <form id="settlementForm" onsubmit="return false;">
                    @csrf

                    <input type="hidden" id="invoice_id" name="invoice_id">
                    <input type="hidden" id="client_id" name="client_id">

                    {{-- معلومات العميل والفاتورة --}}
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-800 dark:text-slate-200">
                                العميل
                            </label>
                            <input id="client_name" readonly class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 px-4 py-3 text-slate-600 dark:text-slate-400 font-medium focus:outline-none">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-slate-800 dark:text-slate-200">
                                رقم الفاتورة
                            </label>
                            <input id="invoice_number" readonly class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 px-4 py-3 text-slate-600 dark:text-slate-400 font-mono font-medium focus:outline-none">
                        </div>
                    </div>

                    {{-- بطاقات التحليل المالي الرقمية --}}
                    <div class="mt-6">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/60 p-4 border border-slate-100 dark:border-slate-700/50">
                                <div class="text-xs font-medium text-slate-500 dark:text-slate-400">الإجمالي</div>
                                <div id="invoice_total" class="font-bold text-lg mt-1.5 text-slate-900 dark:text-white"></div>
                            </div>

                            <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-950/20 p-4 border border-emerald-100/50 dark:border-emerald-900/30">
                                <div class="text-xs font-medium text-emerald-600 dark:text-emerald-400">المدفوع</div>
                                <div id="invoice_paid" class="font-bold text-lg mt-1.5 text-emerald-700 dark:text-emerald-400"></div>
                            </div>

                            <div class="rounded-2xl bg-rose-50 dark:bg-rose-950/20 p-4 border border-rose-100/50 dark:border-rose-900/30">
                                <div class="text-xs font-medium text-rose-600 dark:text-rose-400">المتبقي</div>
                                <div id="invoice_remaining" class="font-bold text-lg mt-1.5 text-rose-600 dark:text-rose-400"></div>
                            </div>

                            <div class="rounded-2xl bg-blue-50 dark:bg-blue-950/20 p-4 border border-blue-100/50 dark:border-blue-900/30">
                                <div class="text-xs font-medium text-blue-600 dark:text-blue-400">عملة الفاتورة</div>
                                <div id="invoice_currency" class="font-bold text-lg mt-1.5 text-blue-700 dark:text-blue-400"></div>
                            </div>
                        </div>
                    </div>

                    {{-- اختيار الرصيد --}}
                    <div class="mt-6">
                        <label for="source_currency_id" class="block mb-2 text-sm font-semibold text-slate-800 dark:text-slate-200">
                            اختر الرصيد المراد استخدامه للتسديد
                        </label>
                        <select id="source_currency_id" name="source_currency_id" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-slate-900 dark:text-white font-medium focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none appearance-none cursor-pointer">
                        </select>
                    </div>

                    {{-- شريط الرصيد المتاح --}}
                    <div id="balanceInfo" class="hidden mt-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 p-4 border border-indigo-100 dark:border-indigo-900/30 transition-all duration-300">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-medium text-indigo-700 dark:text-indigo-400 flex items-center gap-2">
                                💳 الرصيد المتاح لهذا الحساب:
                            </span>
                            <span id="available_balance" class="font-bold text-base text-indigo-900 dark:text-indigo-200"></span>
                        </div>
                    </div>

                    {{-- الصرف التحويلي الديناميكي --}}
                    <div id="exchangeBox" class="hidden mt-6 transition-all duration-300">
                        <div class="rounded-2xl bg-amber-50/60 dark:bg-amber-950/20 p-5 border border-amber-200/60 dark:border-amber-900/30">
                            <div class="font-bold text-sm text-amber-800 dark:text-amber-400 mb-4 flex items-center gap-2">
                                🔄 تفاصيل تحويل أسعار الصرف الآلي
                            </div>
                            <div class="grid md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block mb-1.5 text-xs font-semibold text-amber-700 dark:text-amber-500">سعر الصرف</label>
                                    <input id="exchange_rate" readonly class="w-full rounded-xl border border-amber-200 dark:border-amber-900/50 px-4 py-2.5 bg-white dark:bg-slate-800 font-mono font-bold text-amber-900 dark:text-amber-300 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-xs font-semibold text-amber-700 dark:text-amber-500">سيخصم من الرصيد</label>
                                    <input id="exchange_source_amount" readonly class="w-full rounded-xl border border-amber-200 dark:border-amber-900/50 px-4 py-2.5 bg-white dark:bg-slate-800 font-mono font-bold text-rose-600 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block mb-1.5 text-xs font-semibold text-amber-700 dark:text-amber-500">سيضاف لتسوية الفاتورة</label>
                                    <input id="exchange_target_amount" readonly class="w-full rounded-xl border border-amber-200 dark:border-amber-900/50 px-4 py-2.5 bg-white dark:bg-slate-800 font-mono font-bold text-emerald-600 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- مبلغ التسوية المطلوبة وعرض المعاينة --}}
                    <div class="grid md:grid-cols-2 gap-5 mt-6">
                        <div class="mt-6">

                            <label class="block mb-2 font-medium">

                                مبلغ التسوية
                                <span id="invoiceCurrencyLabel"></span>

                            </label>

                            <input
                                type="number"
                                step="0.01"
                                min="0.01"
                                id="amount"
                                name="amount"
                                class="w-full rounded-2xl border border-slate-300 dark:border-slate-700 px-4 py-3">

                            <div
                                id="amountHint"
                                class="text-xs text-slate-500 mt-2">
                            </div>

                        </div>

                        {{-- صندوق المعاينة الحية للمبلغ المعدل --}}
                        <div id="exchangePreview" class="hidden self-end">
                            <div class="rounded-2xl bg-slate-50 dark:bg-slate-800/80 p-3.5 border border-slate-200/60 dark:border-slate-700/80 grid grid-cols-3 gap-2 text-center text-xs">
                                <div>
                                    <div class="text-slate-400 font-medium">سعر الصرف</div>
                                    <div id="previewRate" class="font-bold font-mono mt-1 text-slate-700 dark:text-slate-300"></div>
                                </div>
                                <div>
                                    <div class="text-slate-400 font-medium">سيخصم</div>
                                    <div id="previewSource" class="font-bold font-mono mt-1 text-rose-600 dark:text-rose-400"></div>
                                </div>
                                <div>
                                    <div class="text-slate-400 font-medium">سيدخل للفاتورة</div>
                                    <div id="previewTarget" class="font-bold font-mono mt-1 text-emerald-600 dark:text-emerald-400"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ملاحظات السند والتسوية --}}
                    <div class="mt-6">
                        <label for="notes" class="block mb-2 text-sm font-semibold text-slate-800 dark:text-slate-200">
                            ملاحظات الإدارة
                        </label>
                        <textarea id="notes" name="notes" rows="3" class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 px-4 py-3 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none placeholder:text-slate-400" placeholder="اكتب تفاصيل إضافية أو أسباب التسوية هنا..."></textarea>
                    </div>
                </form>
            </div>

            {{-- Footer (ثابت) --}}
            <div class="shrink-0 px-6 py-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 backdrop-blur-md">
                <div class="flex flex-col sm:flex-row-reverse gap-3">
                    <button id="submitSettlement" type="button" class="flex-1 sm:flex-initial px-8 bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-2xl font-bold shadow-lg shadow-indigo-600/20 active:scale-[0.98] transition-all duration-150 text-center">
                        تنفيذ التسوية وحفظ
                    </button>
                    <button id="cancelSettlement" type="button" class="flex-1 sm:flex-initial px-6 bg-slate-200 hover:bg-slate-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 py-3.5 rounded-2xl font-semibold active:scale-[0.98] transition-all duration-150 text-center">
                        إلغاء الأمر
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- تنسيق شريط التمرير الأنيق --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgb(203 213 225);
        border-radius: 20px;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgb(71 85 105);
    }
</style>

<script>
    let maxSettlementAmount = 0;

    let currentInvoice = null;
    let currentBalances = [];

    document.addEventListener(
        'click',
        async function(e){

            const btn =
                e.target.closest(
                    '.openSettlementModal'
                );

            if(!btn){
                return;
            }

            const card =
                btn.closest(
                    '[data-invoice-card]'
                );

            currentInvoice = {

                id:
                btn.dataset.invoice,

                client_id:
                card.dataset.clientId,

                client_name:
                card.dataset.clientName,

                total:
                card.dataset.total,

                paid:
                card.dataset.paid,

                remaining:
                card.dataset.remaining,

                currency_id:
                card.dataset.currencyId,

                currency_code:
                card.dataset.currencyCode,

                balances:
                    JSON.parse(
                        card.dataset.balances
                    )
            };

            currentBalances =
                currentInvoice.balances;

            fillModal();

        }
    );

    function fillModal()
    {
        document
            .getElementById(
                'invoice_id'
            ).value =
            currentInvoice.id;

        document
            .getElementById(
                'client_id'
            ).value =
            currentInvoice.client_id;

        document
            .getElementById(
                'client_name'
            ).value =
            currentInvoice.client_name;

        document
            .getElementById(
                'invoice_number'
            ).value =
            currentInvoice.id;

        document
            .getElementById(
                'invoice_total'
            ).innerText =
            currentInvoice.total;

        document
            .getElementById(
                'invoice_paid'
            ).innerText =
            currentInvoice.paid;

        document
            .getElementById(
                'invoice_remaining'
            ).innerText =
            currentInvoice.remaining;

        document
            .getElementById(
                'invoice_currency'
            ).innerText =
            currentInvoice.currency_code;

        buildCurrencies();

        document
            .getElementById(
                'settlementModal'
            )
            .classList
            .remove('hidden');
    }

    function buildCurrencies()
    {
        const select =
            document.getElementById(
                'source_currency_id'
            );

        let html = '';

        currentBalances.forEach(
            balance => {

                html += `
            <option
                value="${balance.currency_id}"
                data-balance="${balance.balance}"
                data-code="${balance.currency_code}">

                ${balance.currency_code}
                -
                ${balance.balance}

            </option>
            `;

            }
        );

        select.innerHTML = html;


        updateBalanceInfo();
         autoFillSettlementAmount();
    }

    document
        .getElementById(
            'source_currency_id'
        )
        .addEventListener(
            'change',
            async function() {

                updateBalanceInfo();

                await autoFillSettlementAmount();
            }

            );

    function updateBalanceInfo()
    {
        const select =
            document.getElementById(
                'source_currency_id'
            );

        const option =
            select.options[
                select.selectedIndex
                ];

        if(!option){
            return;
        }

        document
            .getElementById(
                'balanceInfo'
            )
            .classList
            .remove('hidden');

        document
            .getElementById(
                'available_balance'
            )
            .innerText =
            option.dataset.balance
            +
            ' '
            +
            option.dataset.code;
    }

    document
        .getElementById(
            'closeSettlementModal'
        )
        .addEventListener(
            'click',
            closeSettlementModal
        );

    document
        .getElementById(
            'cancelSettlement'
        )
        .addEventListener(
            'click',
            closeSettlementModal
        );

    function closeSettlementModal()
    {
        document
            .getElementById(
                'settlementModal'
            )
            .classList
            .add('hidden');
    }

    document
        .getElementById(
            'submitSettlement'
        )
        .addEventListener(
            'click',
            submitSettlement
        );

    async function submitSettlement()
    {

        const form =
            document.getElementById(
                'settlementForm'
            );

        const formData =
            new FormData(form);

        try {

            const response =
                await fetch(

                    "{{ route('voucher-settlements.settle') }}",

                    {
                        method:'POST',


                        headers:{
                            'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .content
                        },

                        body:formData
                    }
                );

            const result =
                await response.json();



            if(!response.ok)
            {

                const text =
                    await response.text();

                console.log(text);


                alert(
                    result.message
                    ??
                    'فشل التنفيذ'
                );

                return;
            }

            alert(
                result.message
            );

            closeSettlementModal();

            location.reload();

        }
        catch(error){

            console.error(
                error
            );

            alert(
                'حدث خطأ غير متوقع'
            );
        }
    }

    document
        .getElementById(
            'amount'
        )
        .addEventListener(
            'input',
            calculateExchange
        );

    document
        .getElementById(
            'source_currency_id'
        )
        .addEventListener(
            'change',
            calculateExchange
        );

    async function calculateExchange()
    {
        const sourceCurrencyId =
            parseInt(
                document.getElementById(
                    'source_currency_id'
                ).value
            );


const invoiceCurrencyId =
    parseInt(
        currentInvoice.currency_id
    );

const amount =
    parseFloat(
        document.getElementById(
            'amount'
        ).value || 0
    );

if(!amount)
{
    return;
}

/*
|--------------------------------------------------------------------------
| نفس العملة
|--------------------------------------------------------------------------
*/

if(
    sourceCurrencyId ===
    invoiceCurrencyId
){

    document
        .getElementById(
            'exchangePreview'
        )
        .classList
        .add('hidden');

    return;
}

const response =
    await fetch(

        `/exchange-rates/find?from_currency_id=${sourceCurrencyId}&to_currency_id=${invoiceCurrencyId}`

    );

const result =
    await response.json();

if(!result.success)
{
    return;
}

const rate =
    parseFloat(
        result.rate
    );

let sourceAmount;

/*
|--------------------------------------------------------------------------
| amount هنا بعملة الفاتورة
|--------------------------------------------------------------------------
*/

if(
    result.direction === 'direct'
){

    sourceAmount =
        amount * rate;

}else{

    sourceAmount =
        amount / rate;
}

document
    .getElementById(
        'exchangePreview'
    )
    .classList
    .remove('hidden');

document
    .getElementById(
        'previewRate'
    )
    .innerText =
    rate;

document
    .getElementById(
        'previewSource'
    )
    .innerText =
    sourceAmount.toFixed(2)
    +
    ' '
    +
    document
        .getElementById(
            'source_currency_id'
        )
        .options[
            document
                .getElementById(
                    'source_currency_id'
                )
                .selectedIndex
        ]
        .dataset.code;

document
    .getElementById(
        'previewTarget'
    )
    .innerText =
    amount.toFixed(2)
    +
    ' '
    +
    currentInvoice.currency_code;


    }

    async function autoFillSettlementAmount()
    {

        const currencySelect =
            document.getElementById(
                'source_currency_id'
            );

        const option =
            currencySelect.options[
                currencySelect.selectedIndex
                ];

        if(!option){
            return;
        }

        const balance =
            parseFloat(
                option.dataset.balance
            );

        const sourceCurrencyId =
            parseInt(
                currencySelect.value
            );

        const invoiceCurrencyId =
            parseInt(
                currentInvoice.currency_id
            );

        const invoiceRemaining =
            parseFloat(
                currentInvoice.remaining
            );

        let maxAllowed =
            invoiceRemaining;

        /*
        |--------------------------------------------------------------------------
        | نفس العملة
        |--------------------------------------------------------------------------
        */

        if(
            sourceCurrencyId
            ===
            invoiceCurrencyId
        ){

            maxAllowed =
                Math.min(
                    balance,
                    invoiceRemaining
                );

            document
                .getElementById(
                    'exchangePreview'
                )
                .classList
                .add('hidden');
        }

        /*
        |--------------------------------------------------------------------------
        | عملة مختلفة
        |--------------------------------------------------------------------------
        */

        else{

            const response =
                await fetch(

                    `/exchange-rates/find?from_currency_id=${sourceCurrencyId}&to_currency_id=${invoiceCurrencyId}`

                );

            const result =
                await response.json();

            if(!result.success)
            {
                return;
            }

            const rate =
                parseFloat(
                    result.rate
                );

            let convertedBalance;

            if(
                result.direction === 'direct'
            ){
                convertedBalance =
                    balance / rate;
            }
            else
            {
                convertedBalance =
                    balance * rate;
            }

            maxAllowed =
                Math.min(
                    convertedBalance,
                    invoiceRemaining
                );

            document
                .getElementById(
                    'exchangePreview'
                )
                .classList
                .remove('hidden');

            document
                .getElementById(
                    'previewRate'
                )
                .innerText =
                rate;

            let sourceAmount;

            if(
                result.direction === 'direct'
            ){
                sourceAmount =
                    maxAllowed * rate;
            }
            else
            {
                sourceAmount =
                    maxAllowed / rate;
            }

            document
                .getElementById(
                    'previewSource'
                )
                .innerText =
                sourceAmount.toFixed(2)
                +
                ' '
                +
                option.dataset.code;

            document
                .getElementById(
                    'previewTarget'
                )
                .innerText =
                maxAllowed.toFixed(2)
                +
                ' '
                +
                currentInvoice.currency_code;
        }

        maxSettlementAmount =
            maxAllowed;

        const amountInput =
            document.getElementById(
                'amount'
            );

        amountInput.max =
            maxAllowed;

        amountInput.value =
            maxAllowed.toFixed(2);

        await calculateExchange();
        document
            .getElementById(
                'invoiceCurrencyLabel'
            )
            .innerText =
            '('
            +
            currentInvoice.currency_code
            +
            ')';

        document
            .getElementById(
                'amountHint'
            )
            .innerText =
            'الحد الأقصى المتاح: '
            +
            maxAllowed.toFixed(2)
            +
            ' '
            +
            currentInvoice.currency_code;
    }
    document
        .getElementById(
            'amount'
        )
        .addEventListener(
            'input',
            function(){

                const value =
                    parseFloat(
                        this.value || 0
                    );

                if(
                    value >
                    maxSettlementAmount
                ){

                    this.value =
                        maxSettlementAmount
                            .toFixed(2);
                }

                if(value < 0)
                {
                    this.value = '';
                }
            }
        );

</script>
