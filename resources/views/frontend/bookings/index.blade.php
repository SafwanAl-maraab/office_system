@extends('frontend.layouts.app')

@section('content')

    <div x-data="{dark: localStorage.getItem('dark')==='true'}"
         x-init="$watch('dark', val => localStorage.setItem('dark', val));
     if(localStorage.getItem('dark')==='true'){document.documentElement.classList.add('dark')}"
         :class="dark ? 'dark' : ''">

        <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-8 transition-all">

            {{-- الترويسة الرئيسية --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-100 dark:border-gray-700/50 pb-5">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 dark:text-white flex items-center gap-2">
                        <span>🎫</span> إدارة وحجوزات المسافرين
                    </h1>
                    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
                        تابع حالة الرحلات، الحجوزات، والمدفوعات القييدية لعملائك من مكان واحد.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- زر إضافة حجز جديد --}}
                    {{-- زر إضافة حجز جديد المعدل --}}
                    <button
                        type="button"
                        onclick="openBookingModal()"
                        class="w-full sm:w-auto px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 active:scale-95 transition-all shadow-md shadow-blue-600/10 flex items-center justify-center gap-1.5">
                        <span>+</span> حجز رحلة جديدة
                    </button>

                </div>
            </div>


            {{-- لوحة المؤشرات والإحصائيات الحيوية --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- إجمالي الحجوزات --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700/50 rounded-2xl p-4 md:p-5 hover:shadow-md transition">
                    <p class="text-xs md:text-sm font-medium text-gray-400 dark:text-gray-500">إجمالي الحجوزات</p>
                    <p class="text-2xl md:text-3xl font-black text-gray-800 dark:text-white mt-1 font-mono">
                        {{ $stats['total'] }}
                    </p>
                </div>

                {{-- الحجوزات المؤكدة --}}
                <div class="bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 shadow-sm rounded-2xl p-4 md:p-5 hover:shadow-md transition">
                    <p class="text-xs md:text-sm font-medium text-emerald-600 dark:text-emerald-400">مؤكدة ومقبولة</p>
                    <p class="text-2xl md:text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1 font-mono">
                        {{ $stats['confirmed'] }}
                    </p>
                </div>

                {{-- الحجوزات المعلقة --}}
                <div class="bg-amber-50/60 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 shadow-sm rounded-2xl p-4 md:p-5 hover:shadow-md transition">
                    <p class="text-xs md:text-sm font-medium text-amber-600 dark:text-amber-400">قيد الانتظار / معلقة</p>
                    <p class="text-2xl md:text-3xl font-black text-amber-600 dark:text-amber-400 mt-1 font-mono">
                        {{ $stats['pending'] }}
                    </p>
                </div>

                {{-- الحجوزات الملغية --}}
                <div class="bg-rose-50/60 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 shadow-sm rounded-2xl p-4 md:p-5 hover:shadow-md transition">
                    <p class="text-xs md:text-sm font-medium text-rose-600 dark:text-rose-400">ملغية / مسترجعة</p>
                    <p class="text-2xl md:text-3xl font-black text-rose-600 dark:text-rose-400 mt-1 font-mono">
                        {{ $stats['cancelled'] }}
                    </p>
                </div>
            </div>


            {{-- شريط البحث والفلترة --}}
            <form method="GET" class="max-w-md">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="البحث برقم الحجز أو اسم العميل..."
                        class="w-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-white rounded-xl pr-4 pl-10 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 focus:outline-none transition text-sm">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        🔍
                    </div>
                </div>
            </form>


            {{-- شبكة عرض بطاقات الحجوزات المحدثة --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach($bookings as $booking)

                    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700/60 rounded-2xl p-5 space-y-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between">

                        <div class="space-y-3.5">
                            {{-- رأس البطاقة (العميل والحالة المعربة) --}}
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="font-bold text-gray-800 dark:text-white text-base line-clamp-1">
                                    {{ $booking->client->full_name }}
                                </h3>

                                <span class="text-[11px] px-2.5 py-1 rounded-full font-bold whitespace-nowrap
                                    @if($booking->status == 'confirmed')
                                        bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400
                                    @elseif($booking->status == 'pending')
                                        bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400
                                    @else
                                        bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400
                                    @endif">
                                    @if($booking->status == 'confirmed')
                                        مؤكد
                                    @elseif($booking->status == 'pending')
                                        قيد الانتظار
                                    @elseif($booking->status == 'issued')
                                       تم اصدار التاشيرة
                                    @else
                                        ملغي
                                    @endif
                                </span>
                            </div>

                            {{-- تفاصيل خط سير الرحلة والمقاعد --}}
                            <div class="p-3 bg-gray-50 dark:bg-gray-900/40 rounded-xl space-y-2 border border-gray-100/50 dark:border-gray-700/20 text-xs text-gray-600 dark:text-gray-300">
                                <p class="flex items-center gap-1.5 font-medium">
                                    <span class="text-sm">🚌</span>
                                    <span class="text-gray-400">الرحلة:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">
                                        {{ $booking->trip->from_city }} ← {{ $booking->trip->to_city }}
                                    </span>
                                </p>

                                <p class="flex items-center gap-1.5">
                                    <span class="text-sm">💺</span>
                                    <span class="text-gray-400">المقعد المخصص:</span>
                                    <span class="font-mono font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 px-2 py-0.5 rounded border dark:border-gray-700">
                                        {{ $booking->seat_number }}
                                    </span>
                                </p>
                            </div>

                            {{-- التفاصيل المالية للفاتورة الملحقة بالحجز --}}
                            <div class="border-t border-dashed border-gray-100 dark:border-gray-700/60 pt-3 space-y-1.5 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-400">💰 سعر البيع الإجمالي:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-100 font-mono">
                                        {{ number_format($booking->sale_price, 2) }} {{ $booking->currency->code ?? '' }}
                                    </span>
                                </div>

                                @if($booking->invoice)
                                    <div class="flex justify-between items-center text-[11px]">
                                        <span class="text-emerald-600 dark:text-emerald-400">🟢 المدفوع: {{ number_format($booking->invoice->paid_amount) }}</span>
                                        <span class="text-rose-600 dark:text-rose-400">🔴 المتبقي: {{ number_format($booking->invoice->remaining_amount) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- أزرار التحكم والإجراءات التفاعلية السفلية --}}
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700/50 gap-2">
                            <a href="{{ route('bookings.show', $booking->id) }}"
                               class="flex-1 text-center bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 dark:hover:bg-blue-950/60 py-1.5 rounded-lg text-xs font-bold transition-all">
                                عرض التفاصيل
                            </a>

                            <button
                                class="changeStatusBtn"

                                data-booking-id="{{ $booking->id }}"

                                data-current-status="{{ $booking->status }}"

                                data-invoice-id="{{ $booking->invoice?->id }}"

                                data-paid-amount="{{ $booking->invoice?->paid_amount ?? 0 }}"

                                data-currency="{{ $booking->currency?->code }}">
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
                                    "currency_id" => optional($booking->currency)->code ?? "",
                                    "discount_percent" => $booking->discount_percent,
                                    "invoice" => [
                                        "total_amount" => optional($booking->invoice)->total_amount ?? 0,
                                        "paid_amount" => optional($booking->invoice)->paid_amount ?? 0,
                                        "remaining_amount" => optional($booking->invoice)->remaining_amount ?? 0,
                                    ]
                                ];
                            @endphp

                            <button
                                type="button"
                                class="editBookingBtn flex-1 text-center bg-amber-50 hover:bg-amber-100 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 dark:hover:bg-amber-950/60 py-1.5 rounded-lg text-xs font-bold transition-all"
                                data-booking='@json($bookingData)'>
                                تعديل البيانات
                            </button>
                        </div>

                    </div>

                @endforeach

            </div>


            {{-- الترقيم وصفحات التنقل --}}
            <div class="pt-4">
                {{ $bookings->links() }}
            </div>

        </div>

        {{-- تضمين الأجزاء والمودالات التكميلية لملف الحجوزات --}}
        @include('frontend.bookings.parts.create')

        @include('frontend.bookings.parts.edit')

        @include('frontend.bookings.parts.status')

    </div>

@endsection
