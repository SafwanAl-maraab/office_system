@extends('frontend.layouts.app')

@section('title', 'الإيرادات')

@section('content')

    <div class="p-4 sm:p-6 space-y-6">

        {{-- الترويسة الرسمية التي تظهر فقط في ورقة الطباعة --}}
        <div class="hidden print:block text-center border-b-2 border-gray-800 pb-4 mb-6">
            <h1 class="text-2xl font-bold text-black">كشف تقرير الإيرادات التفصيلي للفرع</h1>
            <p class="text-xs text-gray-600 mt-1">تاريخ استخراج التقرير: {{ now()->format('Y-m-d H:i') }} | نظام إدارة النظام المالي</p>
        </div>

        {{-- Header Section (يختفي عند الطباعة) --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm print:hidden">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    💰 إدارة الإيرادات
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    متابعة ورصد الإيرادات النقدية والإضافية المحصلة في الفرع
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                {{-- زر الطباعة الفوري --}}
                <button onclick="window.print()"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition shadow-sm">
                    🖨️ طباعة التقرير
                </button>

                {{-- زر فتح مودال إضافة إيراد جديد --}}
                <button onclick="openCreateIncomeModal()"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow-sm transition flex items-center justify-center gap-1">
                    <span>+</span> إضافة إيراد
                </button>
            </div>
        </div>

        {{-- الإحصائيات (Summary Cards) --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 print:grid-cols-3 print:gap-2">
            {{-- إيرادات اليوم --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">إيرادات اليوم</div>
                <div class="mt-2 text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono">
                    {{ number_format($todayIncome, 2) }}
                </div>
                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">الحصيلة النقدية لليوم</div>
            </div>

            {{-- إجمالي الإيرادات --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">إجمالي الإيرادات</div>
                <div class="mt-2 text-2xl font-black text-blue-600 dark:text-blue-400 font-mono">
                    {{ number_format($totalIncome, 2) }}
                </div>
                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">التراكمي الكلي المسجل</div>
            </div>

            {{-- عدد الحركات --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm print:border-gray-300">
                <div class="text-xs text-gray-400 dark:text-gray-500 font-medium print:text-gray-700">عدد قيود الإيرادات</div>
                <div class="mt-2 text-2xl font-black text-purple-600 dark:text-purple-400 font-mono">
                    {{ $incomeCount }} <span class="text-xs font-normal text-gray-500 dark:text-gray-400">حركة</span>
                </div>
                <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5">إجمالي القيود الدفترية</div>
            </div>
        </div>

        {{-- محرك البحث والفلاتر المتقدم المطور (يختفي عند الطباعة) --}}
        <form method="GET" class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm space-y-4 print:hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                {{-- بحث بالوصف --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">البحث بالوصف</label>
                    <input type="text" name="description" value="{{ request('description') }}" placeholder="اكتب كلمة من الوصف..."
                           class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                {{-- تصفية بالعملة --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">العملة</label>
                    <select name="currency_id" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                        <option value="">جميع العملات المعرفة</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}" @selected(request('currency_id') == $currency->id)>
                                {{ $currency->name }} ({{ $currency->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- تاريخ من --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">تاريخ من</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>

                {{-- تاريخ إلى --}}
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-semibold text-gray-600 dark:text-gray-400">تاريخ إلى</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                </div>
            </div>

            <div class="flex justify-start">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-xl text-sm transition shadow-sm">
                    🔍 تطبيق تصفية البحث
                </button>
            </div>
        </form>

        {{-- جدول الحركات وعرض البطاقات --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden print:border-gray-400 print:shadow-none">

            {{-- الجدول للشاشات الكبيرة وعند الطباعة --}}
            <div class="hidden md:block print:block overflow-x-auto">
                <table class="w-full text-right border-collapse print:text-xs">
                    <thead class="bg-gray-50 dark:bg-gray-900/60 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider print:bg-gray-100 print:text-black border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="p-4 print:p-2">#</th>
                        <th class="p-4 print:p-2">التاريخ</th>
                        <th class="p-4 print:p-2">المبلغ</th>
                        <th class="p-4 print:p-2">العملة</th>
                        <th class="p-4 print:p-2">الوصف والبيان</th>
                        <th class="p-4 print:p-2">الموظف المستلم</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-200 text-sm divide-y divide-gray-100 dark:divide-gray-700/50 print:divide-gray-300">
                    @forelse($incomes as $income)
                        <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-900/20 transition print:hover:bg-transparent">
                            <td class="p-4 font-mono font-bold text-gray-400 dark:text-gray-500 print:p-2 print:text-black">
                                #{{ $income->id }}
                            </td>
                            <td class="p-4 font-mono text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap print:p-2 print:text-black">
                                {{ $income->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="p-4 font-mono font-bold whitespace-nowrap print:p-2 text-emerald-600 dark:text-emerald-400">
                                +{{ number_format($income->amount, 2) }}
                            </td>
                            <td class="p-4 whitespace-nowrap print:p-2">
                                    <span class="font-bold bg-gray-100 dark:bg-gray-900 px-2 py-0.5 rounded text-xs dark:text-gray-300 print:bg-transparent print:p-0 print:text-black">
                                        {{ $income->currency->code }}
                                    </span>
                            </td>
                            <td class="p-4 text-xs font-medium max-w-xs break-words print:max-w-none print:whitespace-normal print:p-2 print:text-black">
                                {{ $income->description }}
                            </td>
                            <td class="p-4 text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap print:p-2 print:text-black">
                                {{ $income->employee->full_name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400 dark:text-gray-500">
                                لا توجد قيود إيرادات مطابقة للبحث حالياً.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- بطاقات متجاوبة تظهر للجوال والتابلت فقط (وتختفي عند الطباعة) --}}
            <div class="block md:hidden print:hidden divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($incomes as $income)
                    <div class="p-4 space-y-3 text-sm text-gray-700 dark:text-gray-200">
                        {{-- معرف الحركة والتاريخ --}}
                        <div class="flex justify-between items-center">
                            <span class="font-mono font-bold text-gray-400">#{{ $income->id }}</span>
                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500">{{ $income->created_at->format('Y-m-d H:i') }}</span>
                        </div>

                        {{-- صندوق المبالغ والعملة --}}
                        <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-900/50 p-2.5 rounded-xl">
                            <span class="text-xs text-gray-400">المبلغ المحصل:</span>
                            <div class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-base">
                                +{{ number_format($income->amount, 2) }} <span class="text-xs bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-1.5 py-0.5 rounded font-sans">{{ $income->currency->code }}</span>
                            </div>
                        </div>

                        {{-- الموظف --}}
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            <span class="text-gray-400">بواسطة الموظف:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $income->employee->full_name ?? '-' }}</span>
                        </div>

                        {{-- البيان والوصف --}}
                        <div class="bg-gray-50/50 dark:bg-gray-900/20 rounded-lg p-2.5 text-xs border border-gray-100/50 dark:border-gray-700/30 text-gray-600 dark:text-gray-400 break-words">
                            <strong class="text-[10px] text-gray-400 block mb-1">البيان / الوصف:</strong>
                            {{ $income->description }}
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400 dark:text-gray-500 text-sm">
                        لا توجد قيود إيرادات مطابقة للبحث حالياً.
                    </div>
                @endforelse
            </div>

            {{-- أزرار التقسيم والتنقل (Paging) --}}
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/10 print:hidden">
                {{ $incomes->links() }}
            </div>

        </div>

        {{-- تذييل ورقة الطباعة للتوقيع الرسمي المعتمد --}}
        <div class="hidden print:grid grid-cols-2 gap-6 mt-12 text-center text-xs text-black">
            <div>
                <p class="font-bold">توقيع الموظف المستلم</p>
                <p class="mt-8">............................</p>
            </div>
            <div>
                <p class="font-bold">اعتماد إدارة الفرع</p>
                <p class="mt-8">............................</p>
            </div>
        </div>

    </div>

    @include('frontend.incomes.modals.create')

@endsection
