<div
    class="grid
           grid-cols-1
           xl:grid-cols-2
           gap-6">

    {{-- حالة التأشيرات --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-black
                       text-xl">

                حالة التأشيرات

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-blue-100
                       dark:bg-blue-900/30
                       flex items-center justify-center">

                🛂

            </div>

        </div>

        <canvas
            id="visaChartCanvas"
            height="120">
        </canvas>

    </div>

    {{-- توزيع العمليات --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-black
                       text-xl">

                توزيع العمليات

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-purple-100
                       dark:bg-purple-900/30
                       flex items-center justify-center">

                📊

            </div>

        </div>

        <canvas
            id="operationsChartCanvas"
            height="120">
        </canvas>

    </div>

</div>


<div
    class="grid
           grid-cols-1
           xl:grid-cols-2
           gap-6
           mt-6">

    {{-- التحصيلات الشهرية --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-black
                       text-xl">

                التحصيلات الشهرية

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-green-100
                       dark:bg-green-900/30
                       flex items-center justify-center">

                💰

            </div>

        </div>

        <canvas
            id="monthlyCollectionsChart"
            height="120">
        </canvas>

    </div>

    {{-- الأرباح الشهرية --}}
    <div
        class="bg-white dark:bg-gray-900
               rounded-3xl
               shadow-sm
               border border-gray-100
               dark:border-gray-800
               p-6">

        <div
            class="flex items-center justify-between mb-6">

            <h2
                class="font-black
                       text-xl">

                الأرباح المؤكدة

            </h2>

            <div
                class="w-12 h-12
                       rounded-2xl
                       bg-amber-100
                       dark:bg-amber-900/30
                       flex items-center justify-center">

                📈

            </div>

        </div>

        <canvas
            id="monthlyProfitChart"
            height="120">
        </canvas>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    /*
    |--------------------------------------------------------------------------
    | Visa Chart
    |--------------------------------------------------------------------------
    */

    new Chart(

        document.getElementById(
            'visaChartCanvas'
        ),

        {

            type:'doughnut',

            data:{

                labels:[
                    'قيد المعالجة',
                    'صادرة',
                    'ملغية'
                ],

                datasets:[{

                    data:[

                        {{ $visaChart['pending'] }},

                        {{ $visaChart['issued'] }},

                        {{ $visaChart['cancelled'] }}

                    ]

                }]
            },

            options:{

                responsive:true,

                plugins:{

                    legend:{

                        position:'bottom'
                    }
                }
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Operations Chart
    |--------------------------------------------------------------------------
    */

    new Chart(

        document.getElementById(
            'operationsChartCanvas'
        ),

        {

            type:'polarArea',

            data:{

                labels:[

                    'التأشيرات',

                    'الحجوزات',

                    'الطلبات'

                ],

                datasets:[{

                    data:[

                        {{ $operationsChart['visas'] }},

                        {{ $operationsChart['bookings'] }},

                        {{ $operationsChart['requests'] }}

                    ]

                }]
            },

            options:{

                responsive:true,

                plugins:{

                    legend:{

                        position:'bottom'
                    }
                }
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Monthly Collections
    |--------------------------------------------------------------------------
    */

    new Chart(

        document.getElementById(
            'monthlyCollectionsChart'
        ),

        {

            type:'line',

            data:{

                labels:[

                    @foreach(
                        $monthlyCollections as $month
                    )

                        '{{ $month['month'] }}',

                    @endforeach

                ],

                datasets:[{

                    label:'التحصيلات',

                    data:[

                        @foreach(
                            $monthlyCollections as $month
                        )

                            {{ $month['total'] }},

                        @endforeach

                    ],

                    tension:0.4

                }]
            },

            options:{

                responsive:true,

                plugins:{

                    legend:{

                        display:true
                    }
                }
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | Monthly Profit
    |--------------------------------------------------------------------------
    */

    new Chart(

        document.getElementById(
            'monthlyProfitChart'
        ),

        {

            type:'bar',

            data:{

                labels:[

                    @foreach(
                        $monthlyProfit as $month
                    )

                        '{{ $month['month'] }}',

                    @endforeach

                ],

                datasets:[{

                    label:'الأرباح المؤكدة',

                    data:[

                        @foreach(
                            $monthlyProfit as $month
                        )

                            {{ $month['total'] }},

                        @endforeach

                    ]

                }]
            },

            options:{

                responsive:true,

                plugins:{

                    legend:{

                        display:true
                    }
                }
            }
        }
    );

</script>
