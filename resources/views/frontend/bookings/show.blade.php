@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto p-4">

        <div class="flex justify-between mb-4">

            <a href="{{ route('dashboard.bookings.index') }}"
               class="text-gray-600 hover:underline">

                ← رجوع

            </a>

            <button onclick="window.print()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg">

                طباعة التذكرة

            </button>

        </div>


        {{-- منطقة الطباعة --}}

        <div class="print-area bg-white shadow-lg rounded-xl p-6">


            {{-- رأس التذكرة --}}

            <div class="flex justify-between items-center border-b pb-3">

                <div>

                    <h2 class="text-lg font-bold">

                        تذكرة سفر

                    </h2>

                    <p class="text-sm text-gray-500">

                        {{ config('app.name') }}

                    </p>

                </div>

                <div class="text-right">

                    <p class="text-sm">

                        رقم الحجز

                    </p>

                    <p class="font-bold">

                        #{{ $booking->id }}

                    </p>

                </div>

            </div>



            {{-- بيانات العميل --}}

            <div class="grid grid-cols-2 gap-4 mt-4">

                <div>

                    <p class="text-gray-500 text-sm">اسم المسافر</p>

                    <p class="font-semibold">

                        {{ $booking->client->full_name }}

                    </p>

                </div>


                <div>

                    <p class="text-gray-500 text-sm">رقم الجواز</p>

                    <p class="font-semibold">

                        {{ $booking->client->passport_number }}

                    </p>

                </div>

            </div>



            {{-- بيانات الرحلة --}}

            <div class="grid grid-cols-3 gap-4 mt-6">

                <div>

                    <p class="text-gray-500 text-sm">

                        من

                    </p>

                    <p class="font-semibold">

                        {{ $booking->trip->from_city }}

                    </p>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        إلى

                    </p>

                    <p class="font-semibold">

                        {{ $booking->trip->to_city }}

                    </p>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        المقعد

                    </p>

                    <p class="font-semibold">

                        {{ $booking->seat_number }}

                    </p>

                </div>

            </div>



            {{-- تاريخ الرحلة --}}

            <div class="grid grid-cols-2 gap-4 mt-6">

                <div>

                    <p class="text-gray-500 text-sm">

                        تاريخ الرحلة

                    </p>

                    <p class="font-semibold">

                        {{ $booking->trip->trip_date }}

                    </p>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        وقت الرحلة

                    </p>

                    <p class="font-semibold">

                        {{ $booking->trip->trip_time }}

                    </p>

                </div>

            </div>



            {{-- الفاتورة --}}

            <div class="border-t mt-6 pt-4">

                <h3 class="font-semibold mb-2">

                    تفاصيل الفاتورة

                </h3>

                <div class="grid grid-cols-3 gap-4">

                    <div>

                        <p class="text-gray-500 text-sm">

                            السعر النهائي

                        </p>

                        <p class="font-semibold">

                            {{ $booking->invoice->total_amount }}

                            {{ $booking->invoice->currency->code }}

                        </p>

                    </div>


                    <div>

                        <p class="text-gray-500 text-sm">

                            المدفوع

                        </p>

                        <p class="font-semibold text-green-600">

                            {{ $booking->invoice->paid_amount }}

                        </p>

                    </div>


                    <div>

                        <p class="text-gray-500 text-sm">

                            المتبقي

                        </p>

                        <p class="font-semibold text-red-600">

                            {{ $booking->invoice->remaining_amount }}

                        </p>

                    </div>

                </div>

            </div>



            {{-- جدول الدفعات --}}

            @if($booking->invoice->payments->count())

                <div class="border-t mt-6 pt-4">

                    <h3 class="font-semibold mb-3">

                        سجل الدفعات

                    </h3>

                    <table class="w-full text-sm">

                        <thead>

                        <tr class="text-gray-500">

                            <th class="text-left">المبلغ</th>

                            <th class="text-left">العملة</th>

                            <th class="text-left">الموظف</th>

                            <th class="text-left">التاريخ</th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($booking->invoice->payments as $payment)

                            <tr class="border-t">

                                <td>

                                    {{ $payment->amount }}

                                </td>

                                <td>

                                    {{ $payment->currency->code }}

                                </td>

                                <td>

                                    {{ $payment->employee->full_name }}

                                </td>

                                <td>

                                    {{ $payment->created_at->format('Y-m-d') }}

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @endif



            {{-- الحالة --}}

            <div class="border-t mt-6 pt-4 flex justify-between">

                <div>

                    <p class="text-gray-500 text-sm">

                        الحالة

                    </p>

                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

               {{ $booking->status }}

             </span>

                </div>

                <div>

                    <p class="text-gray-500 text-sm">

                        تاريخ الحجز

                    </p>

                    <p class="font-semibold">

                        {{ $booking->created_at->format('Y-m-d') }}

                    </p>

                </div>


            </div>


           </div>

          </div>

@endsection



<style>

    @media print {

        body * {

            visibility:hidden;

        }

        .print-area, .print-area * {

            visibility:visible;

        }

        .print-area {

            position:absolute;

            left:0;

            top:0;

            width:100%;

            background:white;

        }

        button{

            display:none;

        }

    }

</style>
