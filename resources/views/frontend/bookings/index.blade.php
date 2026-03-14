@extends('frontend.layouts.app')

@section('content')

    <div x-data="{dark: localStorage.getItem('dark')==='true'}"
         x-init="$watch('dark', val => localStorage.setItem('dark', val));
     if(localStorage.getItem('dark')==='true'){document.documentElement.classList.add('dark')}"
         :class="dark ? 'dark' : ''">

        <div class="max-w-7xl mx-auto p-4 space-y-6 transition-all">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    🎫 إدارة الحجوزات
                </h1>

                <div class="flex gap-3">

                    {{-- Dark mode --}}

                    {{-- Add booking --}}
                    <button
                        data-open-booking
                        class="px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 hover:scale-105 transition shadow">

                        + حجز جديد

                    </button>

                </div>
            </div>


            {{-- Statistics --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4 hover:shadow-lg transition">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        إجمالي الحجوزات
                    </p>

                    <p class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['total'] }}
                    </p>

                </div>


                <div class="bg-green-50 dark:bg-green-900 shadow rounded-xl p-4 hover:shadow-lg transition">

                    <p class="text-sm text-gray-500 dark:text-gray-300">
                        مؤكدة
                    </p>

                    <p class="text-2xl font-bold text-green-600">
                        {{ $stats['confirmed'] }}
                    </p>

                </div>


                <div class="bg-yellow-50 dark:bg-yellow-900 shadow rounded-xl p-4 hover:shadow-lg transition">

                    <p class="text-sm text-gray-500 dark:text-gray-300">
                        معلقة
                    </p>

                    <p class="text-2xl font-bold text-yellow-600">
                        {{ $stats['pending'] }}
                    </p>

                </div>


                <div class="bg-red-50 dark:bg-red-900 shadow rounded-xl p-4 hover:shadow-lg transition">

                    <p class="text-sm text-gray-500 dark:text-gray-300">
                        ملغية
                    </p>

                    <p class="text-2xl font-bold text-red-600">
                        {{ $stats['cancelled'] }}
                    </p>

                </div>

            </div>


            {{-- Search --}}
            <form method="GET" class="max-w-md">

                <div class="relative">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="🔍 بحث باسم العميل..."
                        class="w-full border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 dark:text-white rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition">

                </div>

            </form>


            {{-- Bookings Grid --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">

                @foreach($bookings as $booking)

                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-5 space-y-3 hover:shadow-xl hover:-translate-y-1 transition duration-200">

                        <div class="flex justify-between items-center">

                            <h3 class="font-semibold text-gray-800 dark:text-white">

                                {{ $booking->client->full_name }}

                            </h3>

                            <span class="text-xs px-3 py-1 rounded-full

@if($booking->status=='confirmed')
bg-green-100 text-green-600 dark:bg-green-900
@elseif($booking->status=='pending')
bg-yellow-100 text-yellow-600 dark:bg-yellow-900
@else
bg-red-100 text-red-600 dark:bg-red-900
@endif">

{{ $booking->status }}

</span>

                        </div>


                        <p class="text-sm text-gray-500 dark:text-gray-400">

                            🚌 الرحلة :
                            {{ $booking->trip->from_city }}
                            →
                            {{ $booking->trip->to_city }}

                        </p>


                        <p class="text-sm text-gray-500 dark:text-gray-400">

                            💺 المقعد : {{ $booking->seat_number }}

                        </p>


                        <p class="text-sm text-gray-500 dark:text-gray-400">

                            💰 السعر :

                            {{ number_format($booking->sale_price) }}

                            {{ $booking->currency->code ?? '' }}

                        </p>


                        <div class="flex justify-between pt-3">

                            <a href="{{ route('bookings.show',$booking->id) }}"
                               class="text-blue-600 text-sm font-semibold hover:underline">

                                عرض

                            </a>


                            <button
                                type="button"
                                class="changeStatusBtn text-green-600 text-sm font-semibold hover:underline"

                                data-booking-id="{{ $booking->id }}"
                                data-current-status="{{ $booking->status }}">

                                تغيير الحالة

                            </button>


                            @php
                                $bookingData = [
                                "id" => $booking->id,
                                "client_id" => $booking->client_id,
                                "trip_id" => $booking->trip_id,
                                "seat_number" => $booking->seat_number,
                                "purchase_price" => $booking->purchase_price,
                                "sale_price" => $booking->sale_price,
                                "currency_id" =>optional($booking->currency )->code?? "",
                                "discount_percent" => $booking->discount_percent,
                                "invoice" => [
                                "total_amount" => optional($booking->invoice)->total_amount ?? 0,
                                "paid_amount" => optional($booking->invoice)->paid_amount ?? 0,
                                "remaining_amount" => optional($booking->invoice)->remaining_amount ?? 0,
                                ]
                                ];
                            @endphp


                            <button
                                class="editBookingBtn text-yellow-600 text-sm hover:underline"
                                data-booking='@json($bookingData)'>

                                تعديل

                            </button>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}
            <div class="pt-6">

                {{ $bookings->links() }}

            </div>

        </div>


        @include('frontend.bookings.parts.create')

        @include('frontend.bookings.parts.edit')

        @include('frontend.bookings.parts.status')

    </div>

@endsection
