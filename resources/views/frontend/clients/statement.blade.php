@extends('frontend.layouts.app')

@section('title', 'كشف حساب العميل المحاسبي')
@section('subtitle', 'سجل الحركات والأرصدة التفصيلية')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 antialiased text-sm md:text-base">

        {{-- 1. كارت الهيدر والتحكم العلوى (تختفي عند الطباعة) --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-5 md:p-6 print:hidden">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 md:gap-6">
                <div class="flex items-center gap-4 w-full lg:w-auto">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-2xl shadow-sm flex-shrink-0">
                        👤
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white truncate">
                            {{ $client->full_name }}
                        </h2>
                        <div class="text-sm font-mono text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                            <span>📞</span> <span class="truncate">{{ $client->phone }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-row items-center gap-3 w-full lg:w-auto flex-shrink-0">
                    <a href="{{ route('clients.index') }}"
                       class="flex-1 lg:flex-none text-center px-5 py-3 rounded-xl md:rounded-2xl bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 font-bold text-sm transition-all duration-200">
                        ⬅️ رجوع
                    </a>

                    <button onclick="window.print()"
                            class="flex-1 lg:flex-none text-center px-5 py-3 rounded-xl md:rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-lg shadow-indigo-600/20 active:scale-95 transition-all duration-200 flex items-center justify-center gap-2">
                        🖨️ طباعة وتصدير PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- الترويسة الخاصة بالطباعة فقط --}}
        <div class="hidden print:block border-b-2 border-gray-900 pb-6 mb-8 text-right">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black text-gray-900">{{ config('app.name', 'ستار ويب للتكنولوجيا') }}</h1>
                    <p class="text-sm text-gray-500 mt-1">قسم التدقيق والمحاسبة المالية المعتمدة</p>
                </div>
                <div class="text-left">
                    <h2 class="text-xl font-bold text-gray-900">كشف حساب رسمي ومفصل</h2>
                    <p class="text-sm text-gray-500 font-mono mt-1">تاريخ الاستخراج: {{ date('Y-m-d H:i') }}</p>
                </div>
            </div>
            <div class="mt-6 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                <p class="text-base"><strong>اسم العميل:</strong> {{ $client->full_name }}</p>
                <p class="text-base font-mono mt-1"><strong>رقم الهاتف المعتمد:</strong> {{ $client->phone }}</p>
            </div>
        </div>

        {{-- 2. بطاقات الأرصدة والملخص المالي المطور (محمية ومكبرة ومتوافقة تماماً) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            @foreach($summary as $item)

                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden">

                    {{-- Header --}}

                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-black">

                                {{ $item['currency']->name }}

                            </h3>

                            <p class="text-sm text-gray-500">

                                {{ $item['currency']->code }}

                            </p>

                        </div>

                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-2xl">

                            💰

                        </div>

                    </div>

                    {{-- Stats --}}

                    <div class="p-6">

                        <div class="grid grid-cols-3 gap-4">

                            <div class="bg-blue-50 dark:bg-blue-950/20 rounded-2xl p-4">

                                <div class="text-xs text-blue-600 font-bold">

                                    الرصيد النقدي

                                </div>

                                <div class="mt-2 text-2xl font-black text-blue-700 dark:text-blue-400 font-mono">

                                    {{ number_format($item['balance'],2) }}

                                </div>

                            </div>

                            <div class="bg-rose-50 dark:bg-rose-950/20 rounded-2xl p-4">

                                <div class="text-xs text-rose-600 font-bold">

                                    الذمم المستحقة

                                </div>

                                <div class="mt-2 text-2xl font-black text-rose-700 dark:text-rose-400 font-mono">

                                    {{ number_format($item['receivable'],2) }}

                                </div>

                            </div>

                            <div class="
                rounded-2xl p-4

                @if($item['net'] > 0)
                    bg-emerald-50 dark:bg-emerald-950/20
                @elseif($item['net'] < 0)
                    bg-amber-50 dark:bg-amber-950/20
                @else
                    bg-gray-50 dark:bg-gray-800
                @endif
            ">

                                <div class="text-xs font-bold">

                                    صافي الموقف

                                </div>

                                <div class="mt-2 text-2xl font-black font-mono

                    @if($item['net'] > 0)
                        text-emerald-600
                    @elseif($item['net'] < 0)
                        text-amber-600
                    @else
                        text-gray-500
                    @endif">

                                    {{ number_format(abs($item['net']),2) }}

                                </div>

                            </div>

                        </div>

                        {{-- حالة الحساب --}}

                        <div class="mt-5">

                            @if($item['net'] > 0)

                                <div class="flex items-center justify-between bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl px-4 py-3">

                    <span class="font-bold text-emerald-700">

                        رصيد متاح للتسوية

                    </span>

                                    <span class="font-mono font-black text-emerald-700">

{{--                        {{ number_format(min($item['balance'],$item['receivable']),2) }}--}}
                                        @if($item['net'] > 0)

                                            {{ number_format($item['net'],2) }}

                                        @endif
                    </span>

                                </div>

                            @elseif($item['net'] < 0)

                                <div class="flex items-center justify-between bg-amber-50 dark:bg-amber-950/20 rounded-2xl px-4 py-3">

                    <span class="font-bold text-amber-700">

                        مبلغ مستحق على العميل

                    </span>

                                    <span class="font-mono font-black text-amber-700">

                        {{ number_format(abs($item['net']),2) }}

                    </span>

                                </div>

                            @else

                                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl px-4 py-3 text-center font-bold text-gray-600">

                                    الحساب متوازن بالكامل

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        {{-- 3. شريط الفلاتر والبحث المطور (مرن بالكامل لجميع الشاشات) --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800/60 p-4 print:hidden">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-7 gap-4 items-center">
                <div class="md:col-span-3">
                    <select name="currency_id" class="w-full px-4 py-3 text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">🌍 عرض جميع العملات</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" @selected(request('currency_id') == $currency->id)>
                                {{ $currency->code }} - ({{ $currency->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3">
                    <select name="type" class="w-full px-4 py-3 text-sm rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="">📋 فلترة حسب نوع الحركة</option>
                        <option value="receipt" @selected(request('type') == 'receipt')>📥 سند قبض مالي</option>
                        <option value="payment" @selected(request('type') == 'payment')>📤 سند صرف مالي</option>
                        <option value="settlement" @selected(request('type') == 'settlement')>⚙️ تسوية حساب</option>
                        <option value="exchange_in" @selected(request('type') == 'exchange_in')>💱 تحويل صرف داخلي (In)</option>
                        <option value="exchange_out" @selected(request('type') == 'exchange_out')>💱 تحويل صرف خارجي (Out)</option>
                        <option value="refund" @selected(request('type') == 'refund')>🔁 استرجاع مالي</option>
                    </select>
                </div>

                <div class="md:col-span-1 flex items-center justify-between gap-3 w-full">
                    <button class="w-full text-center px-4 py-3 bg-gray-900 hover:bg-black dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white font-bold text-sm rounded-xl transition-all duration-150 shadow-sm whitespace-nowrap">
                        تطبيق
                    </button>
                    @if(request()->has('currency_id') || request()->has('type'))
                        <a href="{{ request()->url() }}" class="text-sm font-bold text-rose-600 hover:underline whitespace-nowrap">تصفير</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- 4. جدول استعراض كشف الحساب الاحترافي --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl md:rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden print:border-none print:shadow-none">
            <div class="p-5 md:p-6 border-b border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 print:pb-3">
                <h2 class="text-base md:text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                    📰 دفتر أستاذ وحركات الحساب المالي المعتمدة
                </h2>
                <span class="text-xs font-mono bg-gray-50 dark:bg-gray-950 px-3 py-1.5 rounded-xl border dark:border-gray-800 text-gray-500 dark:text-gray-400 print:hidden">
                    إجمالي العمليات: {{ $logs->count() }}
                </span>
            </div>

            <div class="w-full overflow-x-auto scrolling-touch">
                <table class="w-full text-right text-sm min-w-[1000px]">
                    <thead class="bg-gray-50 dark:bg-gray-800/80 text-gray-500 dark:text-gray-400 font-bold text-xs uppercase">
                    <tr>
                        <th class="p-4 whitespace-nowrap">التاريخ والوقت</th>
                        <th class="p-4 whitespace-nowrap">نوع التدقيق</th>
                        <th class="p-4 whitespace-nowrap">العملية والشرط</th>
                        <th class="p-4 whitespace-nowrap">رقم السند </th>
                        <th class="p-4 text-left whitespace-nowrap">مدين (-)</th>
                        <th class="p-4 text-left whitespace-nowrap">دائن (+)</th>
                        <th class="p-4 text-left whitespace-nowrap">الرصيد المتتابع</th>
                        <th class="p-4 text-center whitespace-nowrap">العملة</th>
                        <th class="p-4 print:hidden whitespace-nowrap">الموظف</th>
                        <th class="p-4 min-w-[240px]">البيان / الملاحظات</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 font-medium text-gray-800 dark:text-gray-200">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors duration-100">
                            <td class="p-4 font-mono text-xs whitespace-nowrap text-gray-500 dark:text-gray-400">
                                {{ $log->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold shadow-sm inline-block
                                        @if(in_array($log->type, ['receipt', 'exchange_in', 'refund'])) bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400
                                        @elseif(in_array($log->type, ['payment', 'settlement', 'exchange_out'])) bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400
                                        @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 @endif">
                                        {{ $log->type_label ?? $log->type }}
                                    </span>
                            </td>

                            <td class="p-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900 dark:text-white text-sm">
                                    {{ $log->operation_title }}
                                </div>
                                @if(!empty($log->operation_details))
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1 font-sans">
                                        {{ $log->operation_details }}
                                    </div>
                                @endif
                            </td>

                            <td class="p-4 font-mono text-xs font-black text-gray-400 dark:text-gray-500 whitespace-nowrap">
                                @if($log->id)
                                    <span class="bg-gray-50 dark:bg-gray-950 border dark:border-gray-800 px-2 py-0.5 rounded">#{{ $log->id }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="p-4 text-left font-mono font-bold text-base text-rose-600 dark:text-rose-400 whitespace-nowrap">
                                @if($log->amount < 0)
                                    {{ number_format(abs($log->amount), 2) }}
                                @else
                                    <span class="text-gray-300 dark:text-gray-700">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-left font-mono font-bold text-base text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                @if($log->amount > 0)
                                    {{ number_format($log->amount, 2) }}
                                @else
                                    <span class="text-gray-300 dark:text-gray-700">-</span>
                                @endif
                            </td>

                            <td class="p-4 text-left font-mono font-black text-base whitespace-nowrap">
                                @if($log->running_balance > 0)
                                    <span class="text-emerald-600 dark:text-emerald-400">
                                            {{ number_format(abs($log->running_balance), 2) }}
                                        </span>
                                @elseif($log->running_balance < 0)
                                    <span class="text-rose-600 dark:text-rose-400">
                                            {{ number_format(abs($log->running_balance), 2) }}
                                        </span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-600">0.00</span>
                                @endif
                            </td>

                            <td class="p-4 text-center font-bold text-sm text-gray-600 dark:text-gray-300 font-mono whitespace-nowrap">
                                {{ $log->currency->code }}
                            </td>

                            <td class="p-4 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap print:hidden">
                                {{ $log->employee->full_name ?? 'النظام الرئيسي' }}
                            </td>

                            <td class="p-4 text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-sm break-words font-sans leading-relaxed">
                                {{ $log->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-16 text-center text-gray-400 dark:text-gray-600 font-bold text-base">
                                🔔 لا توجد قيود أو حركات مالية مسجلة مطابقة لهذه الفلترة.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- تذييل المستند عند الطباعة --}}
            <div class="hidden print:grid grid-cols-2 gap-6 text-center text-sm mt-12 pt-8 border-t border-dashed border-gray-300">
                <div>
                    <p class="text-gray-400 font-bold">توقيع واعتماد المحاسب المالي</p>
                    <div class="h-16"></div>
                    <p>.........................................</p>
                </div>
                <div>
                    <p class="text-gray-400 font-bold">الختم الرسمي للمؤسسة</p>
                    <div class="h-16"></div>
                    <p class="font-black text-indigo-600">{{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .scrolling-touch {
            -webkit-overflow-scrolling: touch;
        }
        @media print {
            body {
                background: white !important;
                color: black !important;
                font-size: 12px !important;
            }
            .print\:hidden, nav, sidebar, header, form, button, a {
                display: none !important;
            }
            .max-w-7xl {
                max-w: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                min-width: 100% !important;
            }
            th, td {
                padding: 8px 6px !important;
                border-bottom: 1px solid #e5e7eb !important;
            }
        }
    </style>

@endsection
