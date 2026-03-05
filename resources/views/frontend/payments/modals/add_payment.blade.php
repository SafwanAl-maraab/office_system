<div id="paymentModal"
     class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">

    <div onclick="closePaymentModal()" class="absolute inset-0"></div>

    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-3xl rounded-2xl shadow-2xl
                p-6 md:p-8 space-y-6 animate-scaleIn">

        {{-- إغلاق --}}
        <button onclick="closePaymentModal()"
                class="absolute top-4 left-4 text-gray-400 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            إضافة دفعة جديدة
        </h2>

        <p class="text-sm text-gray-500">
            ابحث برقم الفاتورة أو اسم العميل ثم اختر الفاتورة المناسبة لإضافة دفعة.
        </p>

        {{-- بحث --}}
        <input type="text"
               id="invoiceSearch"
               placeholder="بحث باسم العميل أو رقم الفاتورة..."
               class="w-full px-4 py-3 rounded-xl border
                      dark:bg-gray-900 dark:border-gray-700">

        {{-- نتائج البحث --}}
        <div id="invoiceResults"
             class="max-h-48 overflow-y-auto space-y-2 mt-4">

            @foreach($invoices as $invoice)

                <div class="invoice-card cursor-pointer
                            border rounded-xl p-3
                            hover:bg-gray-100 dark:hover:bg-gray-700"
                     data-id="{{ $invoice->id }}"
                     data-client="{{ $invoice->client->full_name }}"
                     data-total="{{ $invoice->total_amount }}"
                     data-paid="{{ $invoice->paid_amount }}"
                     data-remaining="{{ $invoice->remaining_amount }}"
                     data-currency="{{ $invoice->currency->symbol ?? '' }}">

                    <div class="flex justify-between">
                        <span class="font-semibold">
                            #{{ $invoice->id }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ $invoice->client->full_name }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-500">
                        متبقي:
                        {{ number_format($invoice->remaining_amount,2) }}
                        {{ $invoice->currency->symbol }}
                    </div>

                </div>

            @endforeach

        </div>

        {{-- تفاصيل الفاتورة المختارة --}}
        <div id="selectedInvoiceBox"
             class="hidden bg-gray-50 dark:bg-gray-900
                    rounded-xl p-5 space-y-4">

            <div class="font-bold text-lg">
                تفاصيل الفاتورة المختارة
            </div>

            <div id="invoiceDetails" class="text-sm space-y-2"></div>

            {{-- شريط تقدم --}}
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span>نسبة السداد</span>
                    <span id="progressText"></span>
                </div>

                <div class="w-full bg-gray-300 dark:bg-gray-700 rounded-full h-2">
                    <div id="progressBar"
                         class="h-2 rounded-full bg-green-600"></div>
                </div>
            </div>

        </div>

        {{-- فورم الدفع --}}
        <form method="POST"
              action="{{ route('dashboard.payments.store') }}"
              class="space-y-4">

            @csrf

            <input type="hidden" name="invoice_id" id="selectedInvoiceId">

            <div>
                <label class="text-sm">المبلغ</label>
                <input type="number"
                       name="amount"
                       id="paymentAmount"
                       class="w-full px-4 py-3 rounded-xl border
                              dark:bg-gray-900 dark:border-gray-700"
                       required>

                <p id="amountWarning"
                   class="hidden text-red-600 text-sm mt-2">
                    المبلغ أكبر من المتبقي
                </p>
            </div>

            <div>
                <label class="text-sm">طريقة الدفع</label>
                <select name="payment_method"
                        class="w-full px-4 py-3 rounded-xl border
                               dark:bg-gray-900 dark:border-gray-700">
                    <option value="cash">نقدي</option>
                    <option value="transfer">تحويل</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                        onclick="closePaymentModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-xl">
                    إلغاء
                </button>

                <button class="px-4 py-2 bg-green-600 text-white rounded-xl">
                    حفظ الدفعة
                </button>
            </div>

        </form>

    </div>
</div>


<style>
    @keyframes scaleIn {
        from { transform: scale(.95); opacity:0 }
        to { transform: scale(1); opacity:1 }
    }
    .animate-scaleIn { animation: scaleIn .2s ease-out; }
</style>


<script>

    function openPaymentModal() {
        document.getElementById('paymentModal')
            .classList.replace('hidden','flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal')
            .classList.replace('flex','hidden');
    }

    // بحث مباشر
    document.getElementById('invoiceSearch')
        .addEventListener('input', function(){

            const search = this.value.toLowerCase();
            const cards = document.querySelectorAll('.invoice-card');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                card.style.display = text.includes(search) ? 'block' : 'none';
            });

        });

    // اختيار فاتورة
    document.querySelectorAll('.invoice-card').forEach(card => {

        card.addEventListener('click', function(){

            const id = this.dataset.id;
            const client = this.dataset.client;
            const total = parseFloat(this.dataset.total);
            const paid = parseFloat(this.dataset.paid);
            const remaining = parseFloat(this.dataset.remaining);
            const currency = this.dataset.currency;

            document.getElementById('selectedInvoiceId').value = id;

            const percent = total > 0 ? (paid/total)*100 : 0;

            document.getElementById('invoiceDetails').innerHTML = `
            <div>العميل: <strong>${client}</strong></div>
            <div>الإجمالي: ${total.toFixed(2)} ${currency}</div>
            <div class="text-green-600">المدفوع: ${paid.toFixed(2)} ${currency}</div>
            <div class="text-red-600">المتبقي: ${remaining.toFixed(2)} ${currency}</div>
        `;

            document.getElementById('progressBar')
                .style.width = percent + '%';

            document.getElementById('progressText')
                .innerText = Math.round(percent) + '%';

            document.getElementById('selectedInvoiceBox')
                .classList.remove('hidden');

        });

    });

    // تنبيه المبلغ
    document.getElementById('paymentAmount')
        .addEventListener('input', function(){

            const selected = document.getElementById('selectedInvoiceId').value;
            if(!selected) return;

            const card = document.querySelector(`[data-id='${selected}']`);
            const remaining = parseFloat(card.dataset.remaining);

            if(parseFloat(this.value) > remaining){
                document.getElementById('amountWarning')
                    .classList.remove('hidden');
            } else {
                document.getElementById('amountWarning')
                    .classList.add('hidden');
            }

        });

</script>
