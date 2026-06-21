<div id="rateModal"
     class="fixed inset-0 z-50 hidden">

    <div id="rateOverlay"
         class="absolute inset-0 bg-black/60"></div>

    <div class="relative w-full h-full flex items-center justify-center p-4">

        <div class="w-full max-w-xl bg-white dark:bg-gray-900 rounded-3xl shadow-2xl">

            <form id="rateForm"
                  method="POST"
                  action="{{ route('exchange-rates.store') }}"
                  class="p-8 space-y-5">

                @csrf

                <input
                    type="hidden"
                    id="rateMethod"
                    name="_method"
                    value="POST">

                <h3 id="rateTitle"
                    class="text-xl font-bold">

                    إضافة سعر صرف

                </h3>

                <div>

                    <label class="block mb-2">

                        من عملة

                    </label>

                    <select
                        name="from_currency_id"
                        id="from_currency_id"
                        class="w-full px-4 py-3 rounded-2xl border">

                        @foreach($currencies as $currency)

                            <option value="{{ $currency->id }}">

                                {{ $currency->code }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block mb-2">

                        إلى عملة

                    </label>

                    <select
                        name="to_currency_id"
                        id="to_currency_id"
                        class="w-full px-4 py-3 rounded-2xl border">

                        @foreach($currencies as $currency)

                            <option value="{{ $currency->id }}">

                                {{ $currency->code }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="block mb-2">

                        سعر الصرف

                    </label>

                    <input
                        type="number"
                        step="0.000001"
                        name="rate"
                        id="rateInput"
                        class="w-full px-4 py-3 rounded-2xl border">

                </div>

                <div class="flex gap-3">

                    <button
                        class="flex-1 py-3 rounded-2xl bg-blue-600 text-white">

                        حفظ

                    </button>

                    <button
                        type="button"
                        id="closeRateModal"
                        class="flex-1 py-3 rounded-2xl bg-gray-200">

                        إلغاء

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

    document.addEventListener('DOMContentLoaded',function(){

        const modal =
            document.getElementById('rateModal');

        function openModal(){

            modal.classList.remove('hidden');

        }

        function closeModal(){

            modal.classList.add('hidden');

        }

        document
            .querySelector('[data-open-rate]')
            ?.addEventListener(
                'click',
                openModal
            );

        document
            .getElementById('closeRateModal')
            ?.addEventListener(
                'click',
                closeModal
            );

        document
            .getElementById('rateOverlay')
            ?.addEventListener(
                'click',
                closeModal
            );

        document
            .querySelectorAll('[data-edit-rate]')
            .forEach(btn=>{

                btn.addEventListener('click',function(){

                    const rate =
                        JSON.parse(
                            this.dataset.rate
                        );

                    document
                        .getElementById('rateTitle')
                        .innerText =
                        'تعديل سعر الصرف';

                    document
                        .getElementById('rateForm')
                        .action =
                        '/exchange-rates/'+rate.id;

                    document
                        .getElementById('rateMethod')
                        .value =
                        'PUT';

                    document
                        .getElementById('from_currency_id')
                        .value =
                        rate.from_currency_id;

                    document
                        .getElementById('to_currency_id')
                        .value =
                        rate.to_currency_id;

                    document
                        .getElementById('rateInput')
                        .value =
                        rate.rate;

                    openModal();

                });

            });

    });

</script>
