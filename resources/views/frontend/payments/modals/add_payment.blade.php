<div id="paymentModal"
     class="fixed inset-0 bg-gray-950/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4 transition-all duration-300">

    {{-- الخلفية لغلق المودال عند الضغط خارجاً --}}
    <div onclick="closePaymentModal()" class="absolute inset-0"></div>

    {{-- جسم المودال بارتفاع أقصى محدد ومحكم لمنع الخروج عن الشاشة --}}
    <div class="relative bg-white dark:bg-gray-900 w-full max-w-2xl rounded-3xl shadow-2xl p-5 md:p-7 space-y-4 border border-gray-100 dark:border-gray-800/80 animate-scaleIn max-h-[90vh] flex flex-col">

        {{-- زر الإغلاق --}}
        <button onclick="closePaymentModal()"
                class="absolute top-5 left-5 text-gray-400 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-800 w-8 h-8 rounded-full flex items-center justify-center transition-colors text-lg font-bold print:hidden">
            ✕
        </button>

        {{-- الترويسة ثابته --}}
        <div class="space-y-1 flex-shrink-0">
            <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                💵 إضافة دفعة مالية جديدة
            </h2>
            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                ابحث برقم الفاتورة أو اسم العميل، ثم اختر الفاتورة لتسجيل السداد النقدي.
            </p>
        </div>

        {{-- حقل البحث الذكي ثابت --}}
        <div class="relative flex-shrink-0">
            <input type="text"
                   id="invoiceSearch"
                   placeholder="🔍 ابدأ بكتابة اسم العميل أو رقم الفاتورة..."
                   class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 text-xs text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all">
        </div>

        {{-- مساحة تمرير مرنة تحتوي على قائمة النتائج وتفاصيل الفاتورة المختارة --}}
        <div class="flex-1 overflow-y-auto space-y-4 pr-1 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-800 pl-1">

            {{-- قائمة الفواتير المقيدة بـ سكرول داخلي صغير ومحكم --}}
            <div id="invoiceResults" class="max-h-40 overflow-y-auto space-y-2 border border-gray-50 dark:border-gray-800/50 rounded-2xl p-1 bg-gray-50/30">
                @foreach($invoices as $invoice)
                    <div class="invoice-card cursor-pointer border border-gray-100 dark:border-gray-800 rounded-xl p-3 bg-white dark:bg-gray-950/40 hover:border-green-500 dark:hover:border-green-500 hover:shadow-sm transition-all duration-150"
                         data-id="{{ $invoice->id }}"
                         data-client="{{ $invoice->client->full_name }}"
                         data-total="{{ $invoice->total_amount }}"
                         data-paid="{{ $invoice->paid_amount }}"
                         data-remaining="{{ $invoice->remaining_amount }}"
                         data-currency="{{ $invoice->currency->code ?? $invoice->currency->symbol ?? '' }}">

                        <div class="flex justify-between items-center text-xs">
                            <span class="font-mono font-black text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 border dark:border-gray-700 px-2 py-0.5 rounded-md text-[10px]">
                                #{{ $invoice->id }}
                            </span>
                            <span class="font-bold text-gray-700 dark:text-gray-300">
                                {{ $invoice->client->full_name }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-50 dark:border-gray-800 text-[10px]">
                            <span class="text-gray-400">المبلغ المتبقي:</span>
                            <span class="font-mono font-bold text-red-600 dark:text-red-400">
                                {{ number_format($invoice->remaining_amount, 2) }} {{ $invoice->currency->code ?? $invoice->currency->symbol }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- بوكس تفاصيل الفاتورة المختارة يظهر تحت السكرول بسلاسة --}}
            <div id="selectedInvoiceBox"
                 class="hidden bg-emerald-50/40 dark:bg-emerald-950/10 border border-emerald-100/50 dark:border-emerald-900/20 rounded-2xl p-4 space-y-3 animate-scaleIn">

                <div class="font-black text-xs text-emerald-800 dark:text-emerald-400 flex items-center gap-1">
                    📌 تفاصيل بيانات القيد المالي المحدد
                </div>

                <div id="invoiceDetails" class="grid grid-cols-2 gap-2 text-[11px] text-gray-700 dark:text-gray-300"></div>

                {{-- شريط نسبة السداد --}}
                <div class="pt-2 border-t border-emerald-100/30 dark:border-emerald-900/10">
                    <div class="flex justify-between text-[10px] font-bold mb-1">
                        <span class="text-gray-400">نسبة تغطية وسداد الفاتورة:</span>
                        <span id="progressText" class="text-emerald-600 dark:text-emerald-400 font-mono">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
                        <div id="progressBar"
                             class="h-1.5 rounded-full bg-emerald-500 w-0 transition-all duration-500 ease-out"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- فورم الدفع المقيد ثابت دائمًا في الأسفل لسهولة الضغط والحفظ --}}
        <form method="POST"
              action="{{ route('dashboard.payments.store') }}"
              class="space-y-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex-shrink-0">
            @csrf

            <input type="hidden" name="invoice_id" id="selectedInvoiceId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">المبلغ المطلوب قيده</label>
                    <input type="number"
                           step="0.01"
                           name="amount"
                           id="paymentAmount"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 font-mono font-bold text-xs text-gray-800 dark:text-white focus:ring-2 focus:ring-green-500 focus:outline-none"
                           required>
                    <p id="amountWarning"
                       class="hidden text-rose-600 dark:text-rose-400 font-medium text-[10px] mt-1.5 bg-rose-50 dark:bg-rose-950/20 p-2 rounded-lg border border-rose-100 dark:border-rose-900/30">
                        ⚠️ خطأ محاسبي: المبلغ تجاوز القيمة المتبقية بالفاتورة!
                    </p>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">قناة / طريقة السداد</label>
                    <select name="payment_method"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-green-500 focus:outline-none font-medium">
                        <option value="cash">💵 نقدي (صندوق الكاش)</option>
                        <option value="transfer">🏦 تحويل بنكي / حساب مصرفي</option>
                    </select>
                </div>
            </div>

            {{-- أزرار التحكم السفلى --}}
            <div class="flex justify-end gap-2 pt-2">
                <button type="button"
                        onclick="closePaymentModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 text-xs font-bold rounded-xl transition-all">
                    إلغاء الأمر
                </button>

                <button id="submitPaymentBtn" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-xl shadow-md shadow-green-600/10 transition-all active:scale-95">
                    تأكيد وحفظ الدفعة
                </button>
            </div>
        </form>

    </div>
</div>

<style>
    @keyframes scaleIn {
        from { transform: scale(.98); opacity: 0 }
        to { transform: scale(1); opacity: 1 }
    }
    .animate-scaleIn { animation: scaleIn .15s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    /* ستايل شريط التمرير الداخلي اللطيف لعدم تشويه المنظر */
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .dark .scrollbar-thin::-webkit-scrollbar-thumb { background: #374151; }
</style>

<script>
    function openPaymentModal() {
        const modal = document.getElementById('paymentModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => document.getElementById('invoiceSearch').focus(), 100);
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');

        document.getElementById('invoiceSearch').value = '';
        document.getElementById('selectedInvoiceBox').classList.add('hidden');
        document.getElementById('paymentAmount').value = '';
        document.getElementById('paymentAmount').classList.remove('border-rose-500', 'focus:ring-rose-500');
        document.getElementById('amountWarning').classList.add('hidden');
        document.getElementById('submitPaymentBtn').disabled = false;

        document.querySelectorAll('.invoice-card').forEach(c => c.style.display = 'block');
    }

    // البحث السريع المباشر
    document.getElementById('invoiceSearch').addEventListener('input', function(){
        const search = this.value.toLowerCase();
        const cards = document.querySelectorAll('.invoice-card');

        cards.forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(search) ? 'block' : 'none';
        });
    });

    // اختيار الفاتورة وتنسيق العرض والتمرير
    document.querySelectorAll('.invoice-card').forEach(card => {
        card.addEventListener('click', function(){
            document.querySelectorAll('.invoice-card').forEach(c => c.classList.remove('border-green-500', 'bg-white', 'dark:bg-gray-900'));
            this.classList.add('border-green-500', 'bg-white', 'dark:bg-gray-900');

            const id = this.dataset.id;
            const client = this.dataset.client;
            const total = parseFloat(this.dataset.total);
            const paid = parseFloat(this.dataset.paid);
            const remaining = parseFloat(this.dataset.remaining);
            const currency = this.dataset.currency;

            document.getElementById('selectedInvoiceId').value = id;

            const amountInput = document.getElementById('paymentAmount');
            amountInput.value = remaining.toFixed(2);
            amountInput.focus();

            validateAmount(remaining, remaining);

            const percent = total > 0 ? (paid / total) * 100 : 0;

            document.getElementById('invoiceDetails').innerHTML = `
                <div>👤 العميل: <strong class="text-gray-900 dark:text-white">${client}</strong></div>
                <div>💰 الإجمالي: <span class="font-mono font-bold">${total.toFixed(2)} ${currency}</span></div>
                <div class="text-emerald-600 dark:text-emerald-400">✅ المدفوع: <span class="font-mono font-bold">${paid.toFixed(2)} ${currency}</span></div>
                <div class="text-rose-600 dark:text-rose-400">⏳ المتبقي: <span class="font-mono font-bold">${remaining.toFixed(2)} ${currency}</span></div>
            `;

            document.getElementById('progressBar').style.width = percent + '%';
            document.getElementById('progressText').innerText = Math.round(percent) + '%';

            const progressBar = document.getElementById('progressBar');
            if(percent >= 75) {
                progressBar.className = "h-1.5 rounded-full bg-emerald-500 transition-all duration-500";
            } else if(percent >= 40) {
                progressBar.className = "h-1.5 rounded-full bg-amber-500 transition-all duration-500";
            } else {
                progressBar.className = "h-1.5 rounded-full bg-blue-500 transition-all duration-500";
            }

            document.getElementById('selectedInvoiceBox').classList.remove('hidden');
        });
    });

    document.getElementById('paymentAmount').addEventListener('input', function(){
        const selected = document.getElementById('selectedInvoiceId').value;
        if(!selected) return;

        const card = document.querySelector(`[data-id='${selected}']`);
        const remaining = parseFloat(card.dataset.remaining);
        const currentVal = parseFloat(this.value) || 0;

        validateAmount(currentVal, remaining);
    });

    function validateAmount(inputVal, remainingAmt) {
        const warning = document.getElementById('amountWarning');
        const inputField = document.getElementById('paymentAmount');
        const submitBtn = document.getElementById('submitPaymentBtn');

        if(inputVal > remainingAmt) {
            warning.classList.remove('hidden');
            inputField.classList.add('border-rose-500', 'focus:ring-rose-500', 'text-rose-600');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-40', 'cursor-not-allowed');
        } else {
            warning.classList.add('hidden');
            inputField.classList.remove('border-rose-500', 'focus:ring-rose-500', 'text-rose-600');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-40', 'cursor-not-allowed');
        }
    }
</script>
