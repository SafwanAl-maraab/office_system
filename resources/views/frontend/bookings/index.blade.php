@extends('frontend.layouts.app')

@section('content')

    <div class="p-6">

        <!-- العنوان -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                الحجوزات
            </h1>

            <!-- البحث -->
            <form method="GET" action="{{ route('bookings.index') }}" class="flex gap-2">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="بحث باسم العميل..."
                    class="border rounded-lg px-4 py-2 w-64
                dark:bg-gray-800 dark:border-gray-700 dark:text-white"
                >

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

                    بحث

                </button>

            </form>

        </div>



        <!-- بطاقات الحجوزات -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse($bookings as $booking)

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                    <!-- العميل -->
                    <div class="mb-3">

                        <h2 class="text-lg font-bold text-gray-800 dark:text-white">

                            {{ $booking->client->full_name }}

                        </h2>

                        <p class="text-sm text-gray-500">

                            {{ $booking->client->phone }}

                        </p>

                    </div>


                    <!-- الرحلة -->

                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">

                        <div>

                            الرحلة :
                            <span class="font-semibold">

                        {{ $booking->trip->from_city ?? '-' }}
                        →
                        {{ $booking->trip->to_city ?? '-' }}

                    </span>

                        </div>

                        <div>

                            السعر :
                            <span class="font-semibold text-green-600">

                        {{ number_format($booking->final_price) }}

                                {{ $booking->currency->symbol ?? '' }}

                    </span>

                        </div>

                        <div>

                            التاريخ :

                            {{ $booking->created_at->format('Y-m-d') }}

                        </div>

                    </div>


                    <!-- الحالة -->

                    <div class="mt-3">

                        @if($booking->status == 'confirmed')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">

                    مؤكد

                </span>

                        @else

                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">

                    {{ $booking->status }}

                </span>

                        @endif

                    </div>


                    <!-- زر التفاصيل -->

                    <div class="mt-4">

                        <a
                            href="{{ route('bookings.show', $booking->id) }}"
                            class="text-blue-600 hover:underline text-sm">

                            عرض التفاصيل

                        </a>

                    </div>

                </div>

            @empty

                <div class="col-span-3 text-center text-gray-500">

                    لا يوجد حجوزات

                </div>

            @endforelse

        </div>



        <!-- Pagination -->

        <div class="mt-8">

            {{ $bookings->links() }}

        </div>

    </div>

@endsection
