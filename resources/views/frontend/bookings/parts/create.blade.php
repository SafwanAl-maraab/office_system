@extends('frontend.layouts.app')

@section('content')

    <div class="p-6 space-y-6">

        {{-- HEADER --}}

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                إدارة الحجوزات
            </h1>

            <button
                onclick="openBookingModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">

                حجز جديد

            </button>

        </div>



        {{-- STATISTICS --}}

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

                <div class="text-sm text-gray-500 dark:text-gray-400">
                    إجمالي الحجوزات
                </div>

                <div class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $stats['total'] }}
                </div>

            </div>



            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl shadow">

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    المؤكدة
                </div>

                <div class="text-2xl font-bold text-green-700">
                    {{ $stats['confirmed'] }}
                </div>

            </div>



            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl shadow">

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    المعلقة
                </div>

                <div class="text-2xl font-bold text-yellow-700">
                    {{ $stats['pending'] }}
                </div>

            </div>



            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl shadow">

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    الملغية
                </div>

                <div class="text-2xl font-bold text-red-700">
                    {{ $stats['cancelled'] }}
                </div>

            </div>



            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl shadow">

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    حجوزات اليوم
                </div>

{{--                <div class="text-2xl font-bold text-blue-700">--}}
{{--                    {{ $stats['today'] }}--}}
{{--                </div>--}}

            </div>

        </div>



        {{-- SEARCH + FILTER --}}

        <form method="GET"
              class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow flex flex-col md:flex-row gap-4">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="بحث باسم العميل..."
                class="border px-3 py-2 rounded w-full md:w-72
dark:bg-gray-700 dark:border-gray-600 dark:text-white">



            <select
                name="status"
                class="border px-3 py-2 rounded w-full md:w-52
dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                <option value="">كل الحالات</option>

                <option value="confirmed"
                    {{ request('status')=='confirmed'?'selected':'' }}>
                    مؤكد
                </option>

                <option value="pending"
                    {{ request('status')=='pending'?'selected':'' }}>
                    معلق
                </option>

                <option value="cancelled"
                    {{ request('status')=='cancelled'?'selected':'' }}>
                    ملغي
                </option>

            </select>



            <button
                class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded">

                بحث

            </button>

        </form>



        {{-- BOOKINGS CARDS --}}

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($bookings as $booking)

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:shadow-lg transition">

                    <div class="flex justify-between items-start mb-3">

                        <div>

                            <h2 class="font-bold text-lg text-gray-800 dark:text-white">
                                {{ $booking->client->full_name }}
                            </h2>

                            <p class="text-sm text-gray-500">
                                {{ $booking->client->phone }}
                            </p>

                        </div>

                        <span
                            class="text-xs px-2 py-1 rounded

@if($booking->status=='confirmed') bg-green-100 text-green-700
@elseif($booking->status=='pending') bg-yellow-100 text-yellow-700
@else bg-red-100 text-red-700
@endif

">

{{ $booking->status }}

</span>

                    </div>



                    <div class="space-y-1 text-sm text-gray-600 dark:text-gray-300">

                        <div>
                            الرحلة:
                            <strong>
                                {{ $booking->trip->from_city }}
                                →
                                {{ $booking->trip->to_city }}
                            </strong>
                        </div>

                        <div>
                            الباص:
                            {{ $booking->trip->bus->plate_number }}
                        </div>

                        <div>
                            السعر:
                            <strong>
                                {{ $booking->final_price }}
                                {{ $booking->currency->symbol ?? '' }}
                            </strong>
                        </div>

                    </div>



                    <div class="flex justify-end gap-2 mt-4">

                        <a
                            href="{{ route('bookings.show',$booking->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">

                            عرض

                        </a>

                        <button
                            onclick="openEditModal({{ $booking->id }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

                            تعديل

                        </button>

                        <button
                            onclick="openDeleteModal({{ $booking->id }})"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">

                            حذف

                        </button>

                    </div>

                </div>

            @endforeach

        </div>



        {{-- PAGINATION --}}

        <div class="mt-6">

            {{ $bookings->links() }}

        </div>

    </div>



    @include('frontend.bookings.parts.create')
    @include('frontend.bookings.parts.edit')
    @include('frontend.bookings.parts.delete')

@endsection
