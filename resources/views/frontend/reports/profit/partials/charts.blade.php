<div
    class="bg-white dark:bg-gray-900
           rounded-3xl
           border border-gray-100
           dark:border-gray-800
           shadow-sm
           p-6">

    <div
        class="flex items-center justify-between
               mb-6">

        <div>

            <h3
                class="text-xl font-bold
                       text-gray-800
                       dark:text-gray-100">

                مقارنة الأرباح

            </h3>

            <p
                class="text-sm
                       text-gray-500">

                الربح المتوقع مقابل الربح المؤكد

            </p>

        </div>

    </div>

    <div class="h-[450px]">

        <canvas
            id="profitChart">
        </canvas>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function()
        {

            const darkMode =
                document.documentElement
                    .classList
                    .contains('dark');

            const ctx =
                document
                    .getElementById(
                        'profitChart'
                    );

            new Chart(
                ctx,
                {

                    type:'bar',

                    data:{

                        labels:[

                            'التأشيرات',

                            'الحجوزات',

                            'الطلبات'

                        ],

                        datasets:[

                            {

                                label:
                                    'الربح المتوقع',

                                data:[

                                    {{ $analysis['visas']['expected_profit'] }},

                                    {{ $analysis['bookings']['expected_profit'] }},

                                    {{ $analysis['services']['expected_profit'] }}

                                ],

                                backgroundColor:
                                    '#4f46e5',

                                borderRadius:8

                            },

                            {

                                label:
                                    'الربح المؤكد',

                                data:[

                                    {{ $analysis['visas']['confirmed_profit'] }},

                                    {{ $analysis['bookings']['confirmed_profit'] }},

                                    {{ $analysis['services']['confirmed_profit'] }}

                                ],

                                backgroundColor:
                                    '#16a34a',

                                borderRadius:8

                            }

                        ]

                    },

                    options:{

                        responsive:true,

                        maintainAspectRatio:false,

                        interaction:{
                            mode:'index',
                            intersect:false
                        },

                        plugins:{

                            legend:{

                                labels:{

                                    color:
                                        darkMode
                                            ? '#fff'
                                            : '#111'

                                }

                            }

                        },

                        scales:{

                            x:{

                                ticks:{

                                    color:
                                        darkMode
                                            ? '#ddd'
                                            : '#444'

                                },

                                grid:{

                                    color:
                                        darkMode
                                            ? '#333'
                                            : '#eee'

                                }

                            },

                            y:{

                                beginAtZero:true,

                                ticks:{

                                    color:
                                        darkMode
                                            ? '#ddd'
                                            : '#444'

                                },

                                grid:{

                                    color:
                                        darkMode
                                            ? '#333'
                                            : '#eee'

                                }

                            }

                        }

                    }

                }
            );

        });

</script>
