{{-- ================= PAYMENT MODAL ================= --}}
<div id="paymentModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    {{-- خلفية لإغلاق المودال --}}
    <div onclick="closePaymentModal()" class="absolute inset-0"></div>

    {{-- محتوى المودال --}}
    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-lg rounded-2xl shadow-2xl
                p-6 md:p-8 animate-scaleIn">

        {{-- زر إغلاق --}}
        <button onclick="closePaymentModal()"
                class="absolute top-4 left-4 text-gray-400 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            تسجيل دفعة جديدة
        </h2>

        @php
            $currency = $invoice->currency->symbol ?? '';
        @endphp

        <form method="post"

              action="{{ route('dashboard.addInvoice')}}"
              class="space-y-6"

            @csrf

            {{-- المبلغ --}}
            <div>
                <label class="block text-sm mb-2 text-gray-700 dark:text-gray-300">
                    المبلغ
                </label>
                @csrf
                <input type="hidden" value="{{$invoice->id}}" name="invId" >
                <div class="relative">

                    <input type="number"
                           name="amount"
                           id="paymentAmount"

                           max="{{ $invoice->remaining_amount }}"
                           class="w-full px-4 py-3 rounded-xl border
                                  dark:bg-gray-900 dark:border-gray-600
                                  focus:ring-2 focus:ring-green-500"
                           required>

                    <span class="absolute left-4 top-3 text-gray-400 text-sm">
                        {{ $currency }}
                    </span>
                </div>

                <p id="overAmountWarning"
                   class="hidden text-sm text-red-600 mt-2 font-semibold">
                    ⚠ المبلغ أكبر من المتبقي في الفاتورة
                </p>

            </div>

            {{-- طريقة الدفع --}}
            <div>
                <label class="block text-sm mb-2 text-gray-700 dark:text-gray-300">
                    طريقة الدفع
                </label>

                <select name="payment_method"
                        class="w-full px-4 py-3 rounded-xl border
                               dark:bg-gray-900 dark:border-gray-600
                               focus:ring-2 focus:ring-green-500"
                        required>
                    <option value="cash">نقدي</option>
                    <option value="transfer">تحويل بنكي</option>
                </select>
            </div>

            {{-- معلومات سريعة --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-4 text-sm space-y-1">

                <div class="flex justify-between">
                    <span>الإجمالي:</span>
                    <span>{{ number_format($invoice->total_amount,2) }} {{ $currency }}</span>
                </div>

                <div class="flex justify-between text-green-600">
                    <span>المدفوع:</span>
                    <span>{{ number_format($invoice->paid_amount,2) }} {{ $currency }}</span>
                </div>

                <div class="flex justify-between text-red-600 font-semibold">
                    <span>المتبقي:</span>
                    <span>{{ number_format($invoice->remaining_amount,2) }} {{ $currency }}</span>
                </div>

            </div>

            {{-- أزرار --}}
            <div class="flex flex-col md:flex-row gap-3 justify-end pt-4">

                <button type="button"
                        onclick="closePaymentModal()"
                        class="px-5 py-2 rounded-xl bg-gray-400 hover:bg-gray-500 text-white text-sm">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-5 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm">
                    حفظ الدفعة
                </button>

            </div>

        </form>

    </div>
</div>


{{-- Animation --}}
<style>
    @keyframes scaleIn {
        from { transform: scale(.95); opacity: 0 }
        to { transform: scale(1); opacity: 1 }
    }
    .animate-scaleIn {
        animation: scaleIn .2s ease-out;
    }
</style>


{{-- JS --}}
<script>

    const remainingAmount = {{ $invoice->remaining_amount }};
    const amountInput = document.getElementById('paymentAmount');
    const warning = document.getElementById('overAmountWarning');

    function openPaymentModal() {
        document.getElementById('paymentModal')
            .classList.replace('hidden','flex');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal')
            .classList.replace('flex','hidden');
    }

    amountInput?.addEventListener('input', function () {

        if (parseFloat(this.value) > remainingAmount) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }

    });

</script>
