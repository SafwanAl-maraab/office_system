@extends('frontend.layouts.app')

@section('title', 'مصارفة الخزائن')

@section('content')
    <div class="p-4 space-y-6">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    🔄 مصارفة الخزائن
                </h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                    تحويل وتحديث الأرصدة بين العملات المختلفة داخل الخزائن تلقائياً.
                </p>
            </div>

            <button onclick="openExchangeModal()"
                    class="w-full sm:w-auto px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition flex items-center justify-center gap-2 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                إنشاء مصارفة
            </button>
        </div>

        {{-- 📊 البطاقات المالية الإحصائية العلوية الحقيقية --}}
        {{-- البطاقات العلوية --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- عدد العمليات اليوم --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">

                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center
                        bg-blue-50 text-blue-600
                        dark:bg-blue-950/30 dark:text-blue-400">

                        📊

                    </div>

                    <div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">

                            عمليات المصارفة اليوم

                        </div>

                        <div class="text-3xl font-black text-gray-800 dark:text-gray-100">

                            {{ $todayExchangesCount }}

                        </div>

                    </div>

                </div>

            </div>

            {{-- آخر عملية --}}
            <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">

                <div class="flex items-center gap-4">

                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center
                        bg-emerald-50 text-emerald-600
                        dark:bg-emerald-950/30 dark:text-emerald-400">

                        🔄

                    </div>

                    <div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">

                            آخر عملية مصارفة

                        </div>

                        <div class="text-sm font-bold text-gray-800 dark:text-gray-100">

                            @if($lastExchange)

                                {{ $lastExchange->fromCurrency->code }}

                                →

                                {{ $lastExchange->toCurrency->code }}

                            @else

                                لا يوجد

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Data Container (Table) --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm font-semibold">
                    <tr>
                        <th class="p-4 border-b dark:border-gray-700">
                            #
                        </th>
                        <th class="p-4 border-b dark:border-gray-700">التاريخ</th>
                        <th class="p-4 border-b dark:border-gray-700">من عملة</th>
                        <th class="p-4 border-b dark:border-gray-700">إلى عملة</th>
                        <th class="p-4 border-b dark:border-gray-700">المبلغ الخارج (-)</th>
                        <th class="p-4 border-b dark:border-gray-700">المبلغ الداخل (+)</th>
                        <th class="p-4 border-b dark:border-gray-700">سعر الصرف البنيوي</th>
                        <th class="p-4 border-b dark:border-gray-700">الموظف</th>
                        <th class="p-4 border-b dark:border-gray-700">الحالة</th>
                        <th class="p-4 border-b dark:border-gray-700">
                            الإجراءات
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-200 text-sm divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($exchanges as $exchange)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition">
                            <td class="p-4 font-black text-indigo-600">
                                #{{ $exchange->id }}
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-500 dark:text-gray-400">
                                {{ $exchange->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-4"><span class="font-bold bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">{{ $exchange->fromCurrency->code }}</span></td>
                            <td class="p-4"><span class="font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 px-2 py-1 rounded">{{ $exchange->toCurrency->code }}</span></td>
                            <td class="p-4 text-red-600 dark:text-red-400 font-bold font-mono">-{{ number_format($exchange->from_amount, 2) }}</td>
                            <td class="p-4 text-green-600 dark:text-green-400 font-bold font-mono">+{{ number_format($exchange->to_amount, 2) }}</td>
                            <td class="p-4 font-mono font-medium"><div class="font-mono text-xs">

                                    {{ number_format($exchange->rate,4) }}

                                </div>

                                <div class="text-[11px] text-gray-500">

                                    {{ $exchange->fromCurrency->code }}

                                    →

                                    {{ $exchange->toCurrency->code }}

                                </div></td>
                            <td class="p-4 text-xs text-gray-600 dark:text-gray-300">{{ $exchange->creator->full_name ?? '-' }}</td>
                            <td class="p-4">
                                @if(!$exchange->is_reversed)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400 border border-green-100 dark:border-green-900/30">نشطة</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 border border-red-100 dark:border-red-900/30">معكوسة</span>
                                @endif
                            </td>
                            <td class="p-4">

                                @if(!$exchange->is_reversed)

                                    <form
                                        method="POST"
                                        action="{{ route('cashbox-exchanges.reverse',$exchange) }}"
                                        onsubmit="return confirm('هل تريد عكس المصارفة؟')">

                                        @csrf

                                        <button
                                            class="px-3 py-2 rounded-lg
                       bg-red-600 hover:bg-red-700
                       text-white text-xs">

                                            عكس العملية

                                        </button>

                                    </form>

                                @else

                                    <span class="text-xs text-gray-400">

            تم العكس

        </span>

                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-gray-400 dark:text-gray-500">لا توجد عمليات مصارفة مسجلة حالياً.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                <div class="p-5 border-t border-gray-100 dark:border-gray-800">

                    {{ $exchanges->links() }}

                </div>
            </div>
        </div>
    </div>

    @include('frontend.cashbox-exchanges.create')
@endsection
