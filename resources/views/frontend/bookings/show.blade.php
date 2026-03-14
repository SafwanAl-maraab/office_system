@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto p-4">

        {{-- actions --}}
        <div class="flex justify-between items-center mb-6 print:hidden">

            <a href="{{ route('dashboard.bookings.index') }}"
               class="text-gray-600 hover:text-black">

                ← رجوع

            </a>

            <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">

                طباعة التذكرة

            </button>

        </div>


        {{-- التذكرة --}}
        <div class="print-area bg-white border rounded-2xl shadow-lg p-8 space-y-6">


            {{-- header --}}
            <div class="flex justify-between items-center border-b pb-4">

                <div>

                    <h2 class="text-xl font-bold">
                        🎫 تذكرة سفر
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ config('app.name') }}
                    </p>

                </div>

                <div class="text-right">

                    <p class="text-sm text-gray-500">
                        رقم الحجز
                    </p>

                    <p class="text-xl font-bold">
                        #{{ $booking->id }}
                    </p>

                </div>

            </div>



            {{-- passenger --}}
            <div>

                <h3 class="font-semibold mb-3 text-gray-700">
                    بيانات المسافر
                </h3>

                <div class="grid grid-cols-2 gap-6">

                    <div>

                        <p class="text-sm text-gray-500">
                            الاسم
                        </p>

                        <p class="font-semibold text-lg">
                            {{ $booking->client->full_name }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            رقم الجواز
                        </p>

                        <p class="font-semibold text-lg">
                            {{ $booking->client->passport_number }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- trip --}}
            <div>

                <h3 class="font-semibold mb-3 text-gray-700">
                    تفاصيل الرحلة
                </h3>

                <div class="grid grid-cols-3 gap-6 text-center">

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            من
                        </p>

                        <p class="font-bold text-lg">
                            {{ $booking->trip->from_city }}
                        </p>

                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            إلى
                        </p>

                        <p class="font-bold text-lg">
                            {{ $booking->trip->to_city }}
                        </p>

                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">

                        <p class="text-sm text-gray-500">
                            المقعد
                        </p>

                        <p class="font-bold text-lg">
                            {{ $booking->seat_number }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- date --}}
            <div class="grid grid-cols-2 gap-6">

                <div class="bg-gray-50 rounded-lg p-4">

                    <p class="text-sm text-gray-500">
                        تاريخ الرحلة
                    </p>

                    <p class="font-semibold">
                        {{ $booking->trip->trip_date }}
                    </p>

                </div>

                <div class="bg-gray-50 rounded-lg p-4">

                    <p class="text-sm text-gray-500">
                        وقت الرحلة
                    </p>

                    <p class="font-semibold">
                        {{ $booking->trip->trip_time }}
                    </p>

                </div>

            </div>



            {{-- invoice --}}
            <div class="border-t pt-6">

                <h3 class="font-semibold mb-4">
                    تفاصيل الفاتورة
                </h3>

                <div class="grid grid-cols-3 gap-6 text-center">

                    <div>

                        <p class="text-sm text-gray-500">
                            السعر النهائي
                        </p>

                        <p class="font-bold text-lg">
                            {{ $booking->invoice->total_amount }}
                            {{ $booking->invoice->currency->code }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            المدفوع
                        </p>

                        <p class="font-bold text-green-600 text-lg">
                            {{ $booking->invoice->paid_amount }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-gray-500">
                            المتبقي
                        </p>

                        <p class="font-bold text-red-600 text-lg">
                            {{ $booking->invoice->remaining_amount }}
                        </p>

                    </div>

                </div>

            </div>



            {{-- payments --}}
            @if($booking->invoice->payments->count())

                <div class="border-t pt-6">

                    <h3 class="font-semibold mb-3">
                        سجل الدفعات
                    </h3>

                    <table class="w-full text-sm border rounded-lg overflow-hidden">

                        <thead class="bg-gray-100">

                        <tr>

                            <th class="p-2 text-left">المبلغ</th>
                            <th class="p-2 text-left">العملة</th>
                            <th class="p-2 text-left">الموظف</th>
                            <th class="p-2 text-left">التاريخ</th>

                        </tr>

                        </thead>

                        <tbody>

                        @foreach($booking->invoice->payments as $payment)

                            <tr class="border-t">

                                <td class="p-2">{{ $payment->amount }}</td>
                                <td class="p-2">{{ $payment->currency->code }}</td>
                                <td class="p-2">{{ $payment->employee->full_name }}</td>
                                <td class="p-2">{{ $payment->created_at->format('Y-m-d') }}</td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @endif



            {{-- footer --}}
            <div class="border-t pt-4 flex justify-between items-center">

                <div>

                    <p class="text-sm text-gray-500">
                        الحالة
                    </p>

                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm">

                    {{ $booking->status }}

                </span>

                </div>

                <div>

                    <p class="text-sm text-gray-500">
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
            box-shadow:none;
            border:none;
        }

        .print\:hidden{
            display:none;
        }

    }

</style>
