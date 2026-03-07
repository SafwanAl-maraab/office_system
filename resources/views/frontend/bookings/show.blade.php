@extends('frontend.layouts.app')

@section('content')

    <div class="p-6 space-y-6">

        {{-- HEADER --}}

        <div class="flex justify-between items-center">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">

                تفاصيل الحجز

            </h1>


            <a
                href="{{ route('bookings.index') }}"
                class="bg-gray-700 text-white px-4 py-2 rounded">

                رجوع

            </a>

        </div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- CLIENT CARD --}}

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">

                <h2 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">
                    بيانات العميل
                </h2>

                <div class="space-y-2 text-sm">

                    <div>
                        الاسم:
                        <strong>{{ $booking->client->full_name }}</strong>
                    </div>

                    <div>
                        الهاتف:
                        {{ $booking->client->phone }}
                    </div>

                    <div>
                        الجواز:
                        {{ $booking->client->passport_number }}
                    </div>

                    <div>
                        الهوية:
                        {{ $booking->client->national_id }}
                    </div>

                </div>

            </div>



            {{-- TRIP CARD --}}

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">

                <h2 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">

                    بيانات الرحلة

                </h2>

                <div class="space-y-2 text-sm">

                    <div>

                        المسار:

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

                        التاريخ:

                        {{ $booking->trip->trip_date }}

                    </div>


                    <div>

                        الوقت:

                        {{ $booking->trip->trip_time }}

                    </div>

                </div>

            </div>



            {{-- BOOKING INFO --}}

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">

                <h2 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">

                    بيانات الحجز

                </h2>

                <div class="space-y-2 text-sm">

                    <div>

                        سعر الشراء:

                        {{ $booking->purchase_price }}

                        {{ $booking->currency->symbol ?? '' }}

                    </div>


                    <div>

                        سعر البيع:

                        {{ $booking->sale_price }}

                        {{ $booking->currency->symbol ?? '' }}

                    </div>


                    <div>

                        الخصم:

                        {{ $booking->discount_amount }}

                    </div>


                    <div>

                        السعر النهائي:

                        <strong>

                            {{ $booking->final_price }}

                            {{ $booking->currency->symbol ?? '' }}

                        </strong>

                    </div>


                    <div>

                        الحالة:

                        <span class="px-2 py-1 rounded bg-green-100 text-green-700">

{{ $booking->status }}

</span>

                    </div>

                </div>

            </div>

        </div>



        {{-- INVOICE --}}

        @if($booking->invoice)

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">

                <h2 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">

                    الفاتورة

                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">

                    <div>

                        الإجمالي

                        <div class="font-bold">

                            {{ $booking->invoice->total_amount }}

                        </div>

                    </div>


                    <div>

                        المدفوع

                        <div class="font-bold text-green-600">

                            {{ $booking->invoice->paid_amount }}

                        </div>

                    </div>


                    <div>

                        المتبقي

                        <div class="font-bold text-red-600">

                            {{ $booking->invoice->remaining_amount }}

                        </div>

                    </div>


                    <div>

                        الحالة

                        <div class="font-bold">

                            {{ $booking->invoice->status }}

                        </div>

                    </div>

                </div>

            </div>

        @endif



        {{-- PAYMENTS --}}

        @if($booking->invoice && $booking->invoice->payments->count())

            <div class="bg-white dark:bg-gray-800 p-5 rounded-xl shadow">

                <h2 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">

                    المدفوعات

                </h2>

                <div class="space-y-2">

                    @foreach($booking->invoice->payments as $payment)

                        <div class="flex justify-between border-b pb-2 text-sm">

                            <div>

                                {{ $payment->payment_method }}

                            </div>

                            <div class="font-bold">

                                {{ $payment->amount }}

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif



    </div>

@endsection
