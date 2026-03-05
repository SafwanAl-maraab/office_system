<div id="financeModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    {{-- خلفية --}}
    <div class="absolute inset-0" onclick="closeFinanceModal()"></div>

    {{-- الصندوق --}}
    <div class="relative bg-white dark:bg-gray-800
                w-full max-w-3xl rounded-2xl shadow-2xl
                p-6 md:p-8 space-y-6">

        {{-- زر الإغلاق --}}
        <button onclick="closeFinanceModal()"
                class="absolute top-3 left-3 text-gray-400 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            الملخص المالي للفرع
        </h2>


        {{-- البطاقات --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- إجمالي المدفوعات --}}
            <div class="bg-green-50 dark:bg-green-900/40
                        p-4 rounded-xl text-center">

                <div class="text-sm text-gray-500">
                    إجمالي المدفوعات
                </div>

                <div class="text-xl font-bold text-green-600">
                    {{ number_format($totalPayments ?? 0 ,2) }}
                </div>

            </div>


            {{-- إجمالي المصروفات --}}
            <div class="bg-red-50 dark:bg-red-900/40
                        p-4 rounded-xl text-center">

                <div class="text-sm text-gray-500">
                    إجمالي المصروفات
                </div>

                <div class="text-xl font-bold text-red-600">
                    {{ number_format($totalExpenses ?? 0 ,2) }}
                </div>

            </div>


            {{-- صافي --}}
            <div class="bg-blue-50 dark:bg-blue-900/40
                        p-4 rounded-xl text-center">

                <div class="text-sm text-gray-500">
                    الصافي
                </div>

                <div class="text-xl font-bold text-blue-600">
                    {{ number_format(($totalPayments ?? 0) - ($totalExpenses ?? 0),2) }}
                </div>

            </div>

        </div>



        {{-- أرصدة الخزن --}}
        <div class="mt-6">

            <h3 class="font-bold mb-3 text-gray-700 dark:text-gray-200">
                أرصدة الخزن
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                @foreach($cashboxes ?? [] as $cash)

                    <div class="bg-gray-50 dark:bg-gray-900
                                p-4 rounded-xl">

                        <div class="text-sm text-gray-500">
                            {{ $cash->currency->name }}
                        </div>

                        <div class="text-lg font-bold">
                            {{ number_format($cash->balance ,2) }}
                            {{ $cash->currency->symbol }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- زر الإغلاق --}}
        <div class="flex justify-end pt-4">

            <button onclick="closeFinanceModal()"
                    class="px-4 py-2 rounded-xl bg-gray-400 hover:bg-gray-500 text-white">
                إغلاق
            </button>

        </div>

    </div>

</div>


<script>

    function openFinanceModal(){

        const modal = document.getElementById('financeModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }

    function closeFinanceModal(){

        const modal = document.getElementById('financeModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }

</script>
