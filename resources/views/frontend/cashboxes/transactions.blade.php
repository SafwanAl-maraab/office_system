@extends('frontend.layouts.app')

@section('title', 'حركات الخزنة')

@section('content')

    <div class="p-4 sm:p-6 space-y-6">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm print:hidden">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    📦 حركة خزنة: {{ $currency->name }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    جميع القيود والحركات المالية التفصيلية الخاصة بهذه العملة
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                {{-- زر الطباعة الفوري --}}
                <button onclick="window.print()"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-sm">
                    🖨️ طباعة الحركة
                </button>

                <a href="{{ route('dashboard.cashboxes.index') }}"
                   class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold transition shadow-sm">
                    رجوع للخزائن
                </a>
            </div>
        </div>

        {{-- هيدر مخصص يظهر فقط في ورقة الطباعة الرسمية --}}
        <div class="hidden print:block text-center border-b-2 border-gray-800 pb-4 mb-6">
            <h1 class="text-2xl font-bold text-black">سجل حركات الخزنة المستندي الرسمي</h1>
            <p class="text-sm text-gray-600 mt-1">الملعة: {{ $currency->name }} ({{ $currency->code }}) | تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 print:grid-cols-3 print:gap-2">
            {{-- الرصيد الحالي --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">الرصيد الدفتري الحالي</div>
                <div class="mt-2 text-2xl font-black text-green-600 dark:text-green-400 font-mono print:text-green-700">
                    {{ number_format($balance, 2) }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-bold">
                    {{ $currency->code }}
                </div>
            </div>

            {{-- عدد الحركات --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">عدد الحركات المستخرجة</div>
                <div class="mt-2 text-2xl font-black text-blue-600 dark:text-blue-400 font-mono print:text-blue-700">
                    {{ $transactions->count() }}
                    <span class="text-xs font-normal print:text-black"> قيد حركي</span>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">مسجلة بالنظام</div>
            </div>

            {{-- رمز العملة --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">رمز الحساب المنشأ</div>
                <div class="mt-2 text-2xl font-black text-purple-600 dark:text-purple-400 font-mono print:text-purple-700">
                    {{ $currency->symbol ?: $currency->code }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">معرف السداد</div>
            </div>


        </div>

        {{-- Summary Cards المطورة لتشمل الرصيد الافتتاحي والملخص المالي --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">

            {{-- الرصيد الافتتاحي --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">الرصيد الافتتاحي للمدة</div>
                <div class="mt-1 text-xl font-black font-mono text-gray-700 dark:text-gray-300">
                    {{ number_format($openingBalance, 2) }}
                </div>
                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">ما قبل تاريخ: {{ $dateFrom ?? 'البداية' }}</div>
            </div>

            {{-- إجمالي الوارد --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">إجمالي المقبوضات (الوارد)</div>
                <div class="mt-1 text-xl font-black font-mono text-emerald-600 dark:text-emerald-400">
                    +{{ number_format($totalIn, 2) }}
                </div>
                <div class="text-[10px] text-emerald-500/80 mt-1">خلال الفترة المحددة</div>
            </div>

            {{-- إجمالي المنصرف --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">إجمالي المدفوعات (المنصرف)</div>
                <div class="mt-1 text-xl font-black font-mono text-red-600 dark:text-red-400">
                    -{{ number_format($totalOut, 2) }}
                </div>
                <div class="text-[10px] text-red-500/80 mt-1">خلال الفترة المحددة</div>
            </div>

            {{-- الرصيد الدفتري الحالي الكلي --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-4 shadow-sm print:border-gray-300 bg-gradient-to-br from-indigo-50/30 to-transparent dark:from-indigo-950/10">
                <div class="text-xs text-indigo-500 dark:text-indigo-400 font-bold print:text-gray-700">الرصيد الفعلي الحالي الكلي</div>
                <div class="mt-1 text-xl font-black font-mono text-indigo-600 dark:text-indigo-400">
                    {{ number_format($balance, 2) }}
                </div>
                <div class="text-[10px] text-gray-500 dark:text-gray-400 mt-1 font-bold">{{ $currency->code }}</div>
            </div>

        </div>


        {{-- 🔍 فلاتر البحث والفرز النطاقي بالتاريخ (تختفي تلقائياً عند الطباعة) --}}
        <form method="GET" class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm space-y-4 print:hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 items-end">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">تاريخ من</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">تاريخ إلى</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                </div>
                <div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-xl text-sm transition shadow-sm">
                        🔍 تصفية الحركات
                    </button>
                </div>
            </div>
        </form>

        {{-- Transactions Ledger --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden print:border-gray-400 print:shadow-none">

            <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30 print:bg-white print:p-2">
                <h2 class="font-bold text-gray-800 dark:text-gray-100 text-lg print:text-sm">
                    📊 كشف القيود والأرصدة التتابعية
                </h2>
            </div>

            {{-- 🖥️ عرض الجدول للشاشات الكبيرة وعند الطباعة --}}
            <div class="hidden md:block print:block overflow-x-auto">
                <table class="w-full text-right border-collapse print:text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">#</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">التاريخ</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">النوع</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">الرصيد قبل الحركة</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">المبلغ</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">الرصيد بعد الحركة</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">المرجع</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">الموظف</th>
                        <th class="p-4 border-b dark:border-gray-700 print:p-2 print:border-gray-300">الملاحظات</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-200 text-sm divide-y divide-gray-100 dark:divide-gray-700 print:divide-gray-300">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/40 transition print:hover:bg-transparent">
                            <td class="p-4 font-mono font-bold text-gray-400 dark:text-gray-500 print:p-2 print:text-black">
                                #{{ $transaction->id }}
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap print:p-2 print:text-black">
                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-4 whitespace-nowrap print:p-2">
                                @php
                                    $isSuccess = in_array($transaction->type, ['deposit', 'income', 'exchange_in']);
                                @endphp
                                @if($isSuccess)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400 print:bg-transparent print:text-green-700 print:p-0">
                                            {{ $transaction->type_label }}
                                        </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400 print:bg-transparent print:text-red-700 print:p-0">
                                            {{ $transaction->type_label }}
                                        </span>
                                @endif
                            </td>

                            <td class="p-4 font-mono whitespace-nowrap print:p-2">
                                    <span class="font-medium text-gray-600 dark:text-gray-400 print:text-black">
                                        {{ number_format($transaction->balance_before ?? 0, 2) }}
                                    </span>
                            </td>

                            <td class="p-4 font-mono font-bold whitespace-nowrap print:p-2">
                                @if($transaction->amount > 0)
                                    <span class="text-green-600 dark:text-green-400 print:text-green-700">+{{ number_format($transaction->amount, 2) }}</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400 print:text-red-700">{{ number_format($transaction->amount, 2) }}</span>
                                @endif
                            </td>

                            <td class="p-4 font-mono whitespace-nowrap print:p-2">
                                    <span class="font-black text-indigo-600 dark:text-indigo-400 print:text-black">
                                        {{ number_format($transaction->running_balance ?? 0, 2) }}
                                    </span>
                            </td>

                            <td class="p-4 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap font-medium print:p-2 print:text-black">
                                @php
                                    $referenceLabel = match($transaction->reference_type){
                                        'invoice'      => 'فاتورة',
                                        'payment'      => 'دفعة',
                                        'exchange',
                                        'cashbox_exchange' => 'مصارفة',
                                        'expense'      => 'مصروف',
                                        'refund'       => 'استرجاع',
                                        default        => $transaction->reference_type ?? '-'
                                    };
                                @endphp
                                {{ $referenceLabel }}
                                @if($transaction->reference_id)
                                    <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-700 dark:text-gray-300 print:bg-transparent print:p-0 print:text-black">#{{ $transaction->reference_id }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-xs text-gray-600 dark:text-gray-300 whitespace-nowrap print:p-2 print:text-black">
                                {{ $transaction->employee->full_name ?? '-' }}
                            </td>
                            <td class="p-4 text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate print:max-w-none print:whitespace-normal print:p-2 print:text-black" title="{{ $transaction->notes }}">
                                {{ $transaction->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-12 text-center text-gray-400 dark:text-gray-500">
                                لا توجد حركات مسجلة تطابق التواريخ المحددة.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 📱 عرض كبطاقات للجوال متجاوب (يختفي تماماً عند الطباعة) --}}
            <div class="block md:hidden print:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                    <div class="p-4 space-y-3 text-sm text-gray-700 dark:text-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="font-mono font-bold text-gray-400">#{{ $transaction->id }}</span>
                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500">{{ $transaction->created_at->format('Y-m-d H:i') }}</span>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl space-y-2">
                            <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 pb-1.5 border-b border-gray-200/60 dark:border-gray-700/50">
                                <span>الرصيد قبل الحركة:</span>
                                <span class="font-mono font-medium">{{ number_format($transaction->balance_before ?? 0, 2) }}</span>
                            </div>

                            <div class="flex justify-between items-center py-1">
                                <div>
                                    @if(in_array($transaction->type, ['deposit', 'income', 'exchange_in']))
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400">
                                            {{ $transaction->type_label }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400">
                                            {{ $transaction->type_label }}
                                        </span>
                                    @endif
                                </div>
                                <div class="font-mono font-bold text-base">
                                    @if($transaction->amount > 0)
                                        <span class="text-green-600 dark:text-green-400">+{{ number_format($transaction->amount, 2) }}</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400">{{ number_format($transaction->amount, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex justify-between items-center pt-1.5 border-t border-gray-200/60 dark:border-gray-700/50 text-xs">
                                <span class="text-gray-400 font-semibold">الرصيد بعد الحركة:</span>
                                <span class="font-mono font-black text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($transaction->running_balance ?? 0, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 dark:text-gray-400 pt-1">
                            <div>
                                <span class="block text-[10px] text-gray-400">نوع المستند المرجعي:</span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    @php
                                        $referenceLabelMobile = match($transaction->reference_type){
                                            'invoice'      => 'فاتورة',
                                            'payment'      => 'دفعة',
                                            'exchange',
                                            'cashbox_exchange' => 'مصارفة',
                                            'expense'      => 'مصروف',
                                            'refund'       => 'استرجاع',
                                            default        => $transaction->reference_type ?? '-'
                                        };
                                    @endphp
                                    {{ $referenceLabelMobile }}
                                    @if($transaction->reference_id) <b class="font-mono text-xs">#{{ $transaction->reference_id }}</b> @endif
                                </span>
                            </div>
                            <div class="text-left">
                                <span class="block text-[10px] text-gray-400">بواسطة الموظف:</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $transaction->employee->full_name ?? '-' }}</span>
                            </div>
                        </div>

                        @if($transaction->notes)
                            <div class="bg-gray-50/50 dark:bg-gray-900/20 rounded-lg p-2 text-xs border border-gray-100/50 dark:border-gray-700/30 text-gray-500 dark:text-gray-400">
                                <strong class="text-[10px] text-gray-400 block mb-0.5">ملاحظات الحركة:</strong>
                                {{ $transaction->notes }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                        لا توجد حركات مسجلة تطابق التواريخ المحددة.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
