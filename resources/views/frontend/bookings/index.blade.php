@extends('frontend.layouts.app')

@section('content')

    <div class="p-6 space-y-6">

        {{-- العنوان --}}

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                الحجوزات
            </h1>

            <button
                onclick="openBookingModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow transition">

                + حجز جديد

            </button>

        </div>


        {{-- الاحصائيات --}}

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5">
                <div class="text-gray-500 text-sm">اجمالي الحجوزات</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $stats['total'] ?? 0 }}
                </div>
            </div>


            <div class="bg-green-100 dark:bg-green-900 rounded-xl p-5">
                <div class="text-green-700 dark:text-green-300 text-sm">المؤكدة</div>
                <div class="text-2xl font-bold">
                    {{ $stats['confirmed'] ?? 0 }}
                </div>
            </div>


            <div class="bg-yellow-100 dark:bg-yellow-900 rounded-xl p-5">
                <div class="text-yellow-700 dark:text-yellow-300 text-sm">قيد الانتظار</div>
                <div class="text-2xl font-bold">
                    {{ $stats['pending'] ?? 0 }}
                </div>
            </div>


            <div class="bg-red-100 dark:bg-red-900 rounded-xl p-5">
                <div class="text-red-700 dark:text-red-300 text-sm">الملغية</div>
                <div class="text-2xl font-bold">
                    {{ $stats['cancelled'] ?? 0 }}
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

            <button
                class="bg-gray-800 dark:bg-gray-700 text-white px-4 py-2 rounded-lg">

                بحث

            </button>

        </form>



        {{-- بطاقات الحجوزات --}}

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($bookings as $booking)

                <div class="bg-white dark:bg-gray-800 shadow hover:shadow-xl transition rounded-xl p-5 space-y-3">


                    {{-- اسم العميل والحالة --}}

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

                            الرحلة :

                            <b>

                                {{ $booking->trip->from_city }}

                                →

                                {{ $booking->trip->to_city }}

                            </b>

                        </div>


                        <div>

                            الباص :

                            <b>

                                {{ $booking->trip->bus->plate_number }}

                            </b>

                        </div>


                        <div>

                            السعر :

                            <b>

                                {{ $booking->final_price }}

                                {{ $booking->currency->symbol ?? '' }}

                            </b>

                        </div>

                    </div>



                    {{-- الازرار --}}

                    <div class="flex justify-between pt-3 border-t dark:border-gray-700">

                        <a
                            href="{{ route('bookings.show',$booking->id) }}"
                            class="text-blue-600 hover:underline text-sm">

                            عرض

                        </a>


                        <a
                            href="{{ route('bookings.edit',$booking->id) }}"
                            class="text-green-600 hover:underline text-sm">

                            تعديل

                        </a>


                        <form
                            method="POST"
                            action="{{ route('bookings.destroy',$booking->id) }}"
                            onsubmit="return confirm('هل تريد حذف الحجز ؟')">

                            @csrf
                            @method('DELETE')

                            <button
                                class="text-red-600 hover:underline text-sm">

                                حذف

                            </button>

                        </form>

                    </div>

                </div>

            @empty


                <div class="col-span-3 text-center text-gray-500">

                    لا يوجد حجوزات

                </div>

            @endforelse

        </div>



        {{-- pagination --}}

        <div class="mt-6">

            {{ $bookings->links() }}

        </div>

    </div>

@endsection
