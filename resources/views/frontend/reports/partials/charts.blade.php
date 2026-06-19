
<div class="grid
            grid-cols-1
            xl:grid-cols-2
            gap-6">

    {{-- الإيرادات والمصروفات --}}
    <div class="bg-white dark:bg-gray-900
                rounded-2xl
                shadow-sm
                border border-gray-100 dark:border-gray-800
                p-5">

        <h3 class="font-bold mb-4">

            الإيرادات مقابل المصروفات

        </h3>

        <canvas
            id="incomeExpenseChart"
            height="120">
        </canvas>

    </div>

    {{-- المبيعات والمقبوضات --}}
    <div class="bg-white dark:bg-gray-900
                rounded-2xl
                shadow-sm
                border border-gray-100 dark:border-gray-800
                p-5">

        <h3 class="font-bold mb-4">

            المبيعات مقابل المقبوضات

        </h3>

        <canvas
            id="salesPaymentsChart"
            height="120">
        </canvas>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    const incomeExpenseData =
        @json($incomeExpenseChart);

    new Chart(

        document.getElementById(
            'incomeExpenseChart'
        ),

        {

            type: 'line',

            data: {

                labels:
                    incomeExpenseData.map(
                        x => x.date
                    ),

                datasets: [

                    {

                        label: 'الإيرادات',

                        data:
                            incomeExpenseData.map(
                                x => x.income
                            ),

                        borderColor:
                            '#10b981',

                        backgroundColor:
                            '#10b981'

                    },

                    {

                        label: 'المصروفات',

                        data:
                            incomeExpenseData.map(
                                x => x.expense
                            ),

                        borderColor:
                            '#ef4444',

                        backgroundColor:
                            '#ef4444'

                    }

                ]
            }

        }

    );

    const salesPaymentsData =
        @json($salesPaymentsChart);

    new Chart(

        document.getElementById(
            'salesPaymentsChart'
        ),

        {

            type: 'bar',

            data: {

                labels:
                    salesPaymentsData.map(
                        x => x.date
                    ),

                datasets: [

                    {

                        label: 'المبيعات',

                        data:
                            salesPaymentsData.map(
                                x => x.sales
                            ),

                        backgroundColor:
                            '#3b82f6'

                    },

                    {

                        label: 'المقبوضات',

                        data:
                            salesPaymentsData.map(
                                x => x.payments
                            ),

                        backgroundColor:
                            '#22c55e'

                    }

                ]
            }

        }

    );

</script>
