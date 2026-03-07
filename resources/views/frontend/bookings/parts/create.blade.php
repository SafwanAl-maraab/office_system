@extends('frontend.layouts.app')

@section('content')

    <div class="p-6 space-y-6">

        {{-- العنوان والزر --}}

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                الحجوزات
            </h1>

            <button
                onclick="openBookingModal()"
                class="bg-blue-600 hover:bg-blue-700 transition text-white px-5 py-2 rounded-xl shadow">

                + حجز جديد

            </button>

        </div>



        {{-- بطاقات الاحصائيات --}}

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">
                <div class="text-gray-500 text-sm">اجمالي الحجوزات</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $bookings->total() }}
                </div>
            </div>


            <div class="bg-green-100 dark:bg-green-900 p-5 rounded-xl shadow">
                <div class="text-green-700 dark:text-green-300 text-sm">المؤكدة</div>
                <div class="text-2xl font-bold">
                    {{ \App\Models\Booking::where('status','confirmed')->count() }}
                </div>
            </div>


            <div class="bg-yellow-100 dark:bg-yellow-900 p-5 rounded-xl shadow">
                <div class="text-yellow-700 dark:text-yellow-300 text-sm">قيد الانتظار</div>
                <div class="text-2xl font-bold">
                    {{ \App\Models\Booking::where('status','pending')->count() }}
                </div>
            </div>


            <div class="bg-red-100 dark:bg-red-900 p-5 rounded-xl shadow">
                <div class="text-red-700 dark:text-red-300 text-sm">الملغية</div>
                <div class="text-2xl font-bold">
                    {{ \App\Models\Booking::where('status','cancelled')->count() }}
                </div>
            </div>

        </div>



        {{-- البحث --}}

        <form method="GET" class="flex flex-col md:flex-row gap-3">

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="بحث باسم العميل..."
                class="border dark:border-gray-700 dark:bg-gray-800 dark:text-white px-4 py-2 rounded-lg w-full md:w-72">


            <button class="bg-gray-800 dark:bg-gray-700 text-white px-4 py-2 rounded-lg">
                بحث
            </button>

        </form>



        {{-- البطاقات --}}

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($bookings as $booking)

                <div class="bg-white dark:bg-gray-800 shadow hover:shadow-xl transition rounded-xl p-5 space-y-3">

                    {{-- اسم العميل --}}

                    <div class="flex justify-between items-center">

                        <h2 class="font-bold text-lg text-gray-800 dark:text-white">
                            {{ $booking->client->full_name }}
                        </h2>

                        <span class="text-xs px-3 py-1 rounded-full
@if($booking->status=='confirmed') bg-green-200 text-green-800
@elseif($booking->status=='pending') bg-yellow-200 text-yellow-800
@else bg-red-200 text-red-800
@endif
">

{{ $booking->status }}

</span>

                    </div>



                    {{-- معلومات الرحلة --}}

                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">

                        <div>
                            ✈ الرحلة:
                            <b>
                                {{ $booking->trip->from_city }}
                                →
                                {{ $booking->trip->to_city }}
                            </b>
                        </div>


                        <div>
                            🚌 الباص:
                            <b>
                                {{ $booking->trip->bus->plate_number }}
                            </b>
                        </div>


                        <div>
                            💰 السعر النهائي:
                            <b>
                                {{ $booking->final_price }}
                                {{ $booking->currency->symbol ?? '' }}
                            </b>
                        </div>

                    </div>



                    {{-- ازرار التحكم --}}

                    <div class="flex justify-between pt-3 border-t dark:border-gray-700">

                        <a
                            href="#"
                            class="text-blue-600 hover:underline text-sm">

                            عرض

                        </a>

                        <a
                            href="#"
                            class="text-green-600 hover:underline text-sm">

                            تعديل

                        </a>

                        <a
                            href="#"
                            class="text-red-600 hover:underline text-sm">

                            حذف

                        </a>

                    </div>

                </div>

            @endforeach

        </div>



        {{-- pagination --}}

        <div class="mt-8">

            {{ $bookings->links() }}

        </div>

    </div>



    @include('frontend.bookings.parts.create')

@endsection
