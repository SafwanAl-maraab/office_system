@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto p-4 md:p-6 space-y-6">

        {{-- لوحة أزرار التحكم - مخفية عند الطباعة --}}
        <div class="flex justify-between items-center print:hidden bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-sm">
            <a href="{{ route('dashboard.bookings.index') }}"
               class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 text-sm font-bold transition-all flex items-center gap-1.5">
                ⬅️ رجوع للحجوزات
            </a>

            <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold text-sm shadow-md shadow-blue-600/10 active:scale-[0.98] transition-all flex items-center gap-1.5">
                🖨️ طباعة التذكرة الفورية
            </button>
        </div>


        {{-- حاوية التذكرة الرسمية --}}
        <div class="print-area bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/80 rounded-3xl shadow-xl p-6 md:p-10 space-y-8 relative overflow-hidden">

            {{-- ترويسة التذكرة (Header) --}}
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-700 pb-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🎫</span>
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                            تذكرة صعود مسافر
                        </h2>
                    </div>
                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 mt-1 uppercase font-mono">
                        {{ config('app.name', 'نظام إدارة السفر') }}
                    </p>
                </div>

                <div class="text-left font-mono">
                    <p class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        رقم تأكيد الحجز
                    </p>
                    <p class="text-xl md:text-2xl font-black text-gray-900 dark:text-white text-blue-600">
                        #{{ $booking->id }}
                    </p>
                </div>
            </div>

            {{-- كتل بيانات المسافر الأساسية (Passenger Details) --}}
            <div class="bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl">
                <h3 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 tracking-wider mb-3 flex items-center gap-1">
                    👤 بيانات شخصية للمسافر
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">الاسم الكامل للمسافر:</p>
                        <p class="font-extrabold text-lg text-gray-800 dark:text-gray-100 mt-0.5">
                            {{ $booking->client->full_name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">رقم جواز السفر / الهوية:</p>
                        <p class="font-bold text-lg text-gray-800 dark:text-gray-100 font-mono mt-0.5">
                            {{ $booking->client->passport_number ?? 'غير مسجل' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- تفاصيل وخط سير الرحلة (Trip Visual Design) --}}
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 tracking-wider flex items-center gap-1">
                    🗺️ تفاصيل خط سير وجهة الرحلة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center text-center">
                    <div class="bg-blue-50/60 dark:bg-blue-950/20 border border-blue-100/50 dark:border-blue-900/30 rounded-2xl p-4">
                        <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">محطة الانطلاق (من)</p>
                        <p class="font-black text-xl text-gray-800 dark:text-white mt-1">
                            {{ $booking->trip->from_city }}
                        </p>
                    </div>

                    {{-- فاصل اتجاه حركي --}}
                    <div class="hidden md:flex flex-col items-center justify-center text-gray-300 dark:text-gray-600 font-mono">
                        <span class="text-sm font-bold text-gray-400 dark:text-gray-500 mb-1">اتفاقية رحلة</span>
                        <div class="w-full flex items-center justify-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            <div class="h-[2px] w-20 bg-dashed border-t-2 border-gray-300 dark:border-gray-600"></div>
                            <span class="text-lg">➔</span>
                        </div>
                    </div>

                    <div class="bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-100/50 dark:border-emerald-900/30 rounded-2xl p-4">
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">محطة الوصول (إلى)</p>
                        <p class="font-black text-xl text-gray-800 dark:text-white mt-1">
                            {{ $booking->trip->to_city }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- توقيت ومقعد الرحلة --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 font-mono">
                <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-sans">📅 تاريخ الرحلة</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200 mt-1 text-sm md:text-base">
                        {{ $booking->trip->trip_date }}
                    </p>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/40 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-sans">⏰ وقت المغادرة</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200 mt-1 text-sm md:text-base">
                        {{ $booking->trip->trip_time }}
                    </p>
                </div>

                <div class="bg-amber-50 dark:bg-amber-950/20 p-4 rounded-xl border border-amber-100 dark:border-amber-900/30 text-center">
                    <p class="text-xs text-amber-700 dark:text-amber-400 font-sans">💺 رقم المقعد المحدد</p>
                    <p class="font-black text-amber-600 dark:text-amber-400 mt-0.5 text-base md:text-lg">
                        {{ $booking->seat_number }}
                    </p>
                </div>
            </div>

            {{-- تفاصيل التسوية المالية للفاتورة --}}
            @if($booking->invoice)
                <div class="border-t border-gray-100 dark:border-gray-700 pt-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 tracking-wider">
                        💳 التسوية المالية للفاتورة الملحقة رقم #{{ $booking->invoice->id }}
                    </h3>

                    <div class="grid grid-cols-3 gap-4 text-center bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl font-mono">
                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-sans">السعر النهائي</p>
                            <p class="font-extrabold text-base md:text-lg text-gray-800 dark:text-white mt-1">
                                {{ number_format($booking->invoice->total_amount, 2) }} <span class="text-[10px] font-normal text-gray-400">{{ $booking->invoice->currency->code }}</span>
                            </p>
                        </div>

                        <div class="border-x border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-sans">المبلغ المدفوع</p>
                            <p class="font-extrabold text-base md:text-lg text-emerald-600 mt-1">
                                {{ number_format($booking->invoice->paid_amount, 2) }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-sans">المتبقي المطلوب</p>
                            <p class="font-extrabold text-base md:text-lg text-rose-600 mt-1">
                                {{ number_format($booking->invoice->remaining_amount, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- جدول سجل المدفوعات الدائنة والمدينة --}}
            @if($booking->invoice && $booking->invoice->payments->count())
                <div class="border-t border-gray-100 dark:border-gray-700 pt-6 space-y-3">
                    <h3 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 tracking-wider">
                        📊 كشف حركة سندات الدفع المقيّدة
                    </h3>

                    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-gray-700">
                        <table class="w-full text-sm text-right">
                            <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 text-xs font-bold uppercase">
                            <tr>
                                <th class="p-3 text-center">المبلغ المستلم</th>
                                <th class="p-3 text-center">العملة</th>
                                <th class="p-3">الموظف المسؤول</th>
                                <th class="p-3 font-mono text-center">التاريخ والوقت</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                            @foreach($booking->invoice->payments as $payment)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/30 transition-colors">
                                    <td class="p-3 font-bold font-mono text-emerald-600 text-center">
                                        {{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="p-3 text-center font-semibold text-xs text-gray-500">
                                        {{ $payment->currency->code ?? '' }}
                                    </td>
                                    <td class="p-3 font-medium">
                                        {{ $payment->employee->full_name ?? 'المركز الرئيسي' }}
                                    </td>
                                    <td class="p-3 font-mono text-xs text-gray-400 text-center">
                                        {{ $payment->created_at->format('Y-m-d') }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- التذييل السفلي (Footer) --}}
            <div class="border-t border-gray-100 dark:border-gray-700 pt-5 flex justify-between items-center text-xs md:text-sm">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500">حالة تأكيد الحجز الحالية</p>
                    <div class="mt-1">
                        <span class="px-3 py-1 rounded-full font-bold text-xs
                            @if($booking->status == 'confirmed')
                                bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400
                            @elseif($booking->status == 'pending')
                                bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400
                            @else
                                bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400
                            @endif">
                            @if($booking->status == 'confirmed')
                                مؤكد بالكامل
                            @elseif($booking->status == 'pending')
                                قيد الانتظار
                            @else
                                ملغي
                            @endif
                        </span>
                    </div>
                </div>

                <div class="text-left font-mono">
                    <p class="text-xs text-gray-400 dark:text-gray-500 font-sans">تاريخ حجز القيد الفعلي</p>
                    <p class="font-bold text-gray-600 dark:text-gray-300 mt-1">
                        {{ $booking->created_at->format('Y-m-d') }}
                    </p>
                </div>
            </div>

        </div>
    </div>

@endsection

{{-- كود أنماط التخصيص الكامل للطباعة الاحترافية --}}
<style>
    @media print {
        body {
            background: white !important;
            color: black !important;
        }

        body * {
            visibility: hidden;
        }

        .print-area, .print-area * {
            visibility: visible;
        }

        .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .print\:hidden {
            display: none !important;
        }
    }
</style>
