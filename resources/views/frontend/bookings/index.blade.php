@extends('frontend.layouts.app')

@section('content')

    <div class="p-6 space-y-6">

        <!-- العنوان -->

        <div class="flex flex-col md:flex-row justify-between gap-4">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                الحجوزات
            </h1>

            <button onclick="openBookingModal()"
                    class="px-5 py-2 rounded-lg bg-black text-white
hover:bg-gray-800 dark:bg-white dark:text-black">

                إضافة حجز

            </button>

        </div>



        <!-- البحث -->

        <form method="GET">

            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="بحث باسم العميل..."

                   class="modern-input max-w-md">

        </form>



        <!-- البطاقات -->

        <div class="grid gap-5
grid-cols-1
sm:grid-cols-2
lg:grid-cols-3
xl:grid-cols-4">


            @foreach($bookings as $booking)

                <div class="bg-white dark:bg-gray-900
border border-gray-200 dark:border-gray-700
rounded-xl p-5 shadow-sm hover:shadow-lg
transition">

                    <!-- العميل -->

                    <div class="flex justify-between items-center mb-3">

                        <h3 class="font-semibold text-gray-800 dark:text-white">

                            {{ $booking->client->full_name }}

                        </h3>

                        <span class="text-xs px-2 py-1 rounded
bg-green-100 text-green-700
dark:bg-green-900 dark:text-green-300">

{{ $booking->status }}

</span>

                    </div>



                    <!-- الرحلة -->

                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">

                        🚍

                        {{ $booking->trip->from_city }}
                        →
                        {{ $booking->trip->to_city }}

                    </div>



                    <!-- الباص -->

                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">

                        🚌 الباص :

                        {{ $booking->trip->bus->plate_number }}

                    </div>



                    <!-- السعر -->

                    <div class="text-lg font-bold text-gray-900 dark:text-white mb-3">

                        {{ $booking->final_price }}

                        {{ $booking->currency->name }}

                    </div>



                    <!-- التاريخ -->

                    <div class="text-xs text-gray-500 mb-4">

                        {{ $booking->created_at->format('Y-m-d') }}

                    </div>



                    <!-- العمليات -->

                    <div class="flex justify-between gap-2">

                        <button class="text-sm px-3 py-1 rounded border
hover:bg-gray-100 dark:hover:bg-gray-800">

                            عرض

                        </button>

                        <button class="text-sm px-3 py-1 rounded border
hover:bg-gray-100 dark:hover:bg-gray-800">

                            تعديل

                        </button>

                        <button class="text-sm px-3 py-1 rounded
bg-red-500 text-white hover:bg-red-600">

                            حذف

                        </button>

                    </div>


                </div>

            @endforeach


        </div>



        <!-- pagination -->

        <div class="pt-6">

            {{ $bookings->links() }}

        </div>


    </div>



    @include('frontend.bookings.parts.create')

@endsection
