@extends('frontend.layouts.app')

@section('content')

    <style>
        @media print {

            body {
                background: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-table {
                display: block !important;
            }

            .expense-card {
                display: none !important;
            }

        }

        .print-table {
            display: none;
        }
    </style>

    <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                    إدارة المصروفات
                </h1>
                <p class="text-sm text-gray-500">
                    عرض وتسجيل جميع المصروفات
                </p>
            </div>

            <div class="flex gap-3 no-print">

                <button onclick="window.print()"
                        class="bg-blue-600 hover:bg-blue-700
                           text-white px-4 py-2 rounded-xl">
                    طباعة
                </button>

                <button onclick="openExpenseModal()"
                        class="bg-red-600 hover:bg-red-700
                           text-white px-4 py-2 rounded-xl">
                    + إضافة مصروف
                </button>

            </div>

        </div>


        {{-- فلترة --}}
        <form method="GET"
              class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6
                 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 no-print">

            <input type="text"
                   name="description"
                   value="{{ request('description') }}"
                   placeholder="بحث في الوصف"
                   class="px-4 py-2 rounded-xl border dark:bg-gray-900">

            <select name="currency_id"
                    class="px-4 py-2 rounded-xl border dark:bg-gray-900">
                <option value="">كل العملات</option>
                @foreach($currencies as $currency)
                    <option value="{{ $currency->id }}"
                        {{ request('currency_id')==$currency->id?'selected':'' }}>
                        {{ $currency->name }}
                    </option>
                @endforeach
            </select>

            <input type="date"
                   name="date_from"
                   value="{{ request('date_from') }}"
                   class="px-4 py-2 rounded-xl border dark:bg-gray-900">

            <input type="date"
                   name="date_to"
                   value="{{ request('date_to') }}"
                   class="px-4 py-2 rounded-xl border dark:bg-gray-900">

            <button class="bg-green-600 text-white rounded-xl px-4 py-2">
                بحث
            </button>

        </form>


        {{-- بطاقات العرض --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($expenses as $expense)

                <div class="expense-card bg-white dark:bg-gray-800
                        rounded-2xl shadow-lg
                        border border-red-200 dark:border-red-700
                        p-6 space-y-4">

                    <div class="flex justify-between">
                        <div class="text-sm text-gray-500">
                            {{ $expense->created_at->format('Y-m-d') }}
                        </div>

                        <div class="text-xs bg-red-100 text-red-700
                                px-3 py-1 rounded-full">
                            مصروف
                        </div>
                    </div>

                    <div class="text-xl font-bold text-red-600">
                        - {{ number_format($expense->amount,2) }}
                        {{ $expense->currency->symbol ?? '' }}
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $expense->description }}
                    </div>

                    <div class="text-xs text-gray-500">
                        بواسطة:
                        {{ $expense->employee->full_name ?? '-' }}
                    </div>

                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 py-16">
                    لا توجد نتائج
                </div>

            @endforelse

        </div>


        {{-- جدول الطباعة --}}
        <div class="print-table mt-10">

            <h2 class="text-xl font-bold mb-4">
                تقرير المصروفات
            </h2>

            <table class="w-full border border-gray-300 text-sm">

                <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">التاريخ</th>
                    <th class="border p-2">الوصف</th>
                    <th class="border p-2">المبلغ</th>
                    <th class="border p-2">العملة</th>
                    <th class="border p-2">الموظف</th>
                </tr>
                </thead>

                <tbody>

                @foreach($expenses as $expense)

                    <tr>
                        <td class="border p-2">
                            {{ $expense->created_at->format('Y-m-d') }}
                        </td>
                        <td class="border p-2">
                            {{ $expense->description }}
                        </td>
                        <td class="border p-2 text-red-600">
                            {{ number_format($expense->amount,2) }}
                        </td>
                        <td class="border p-2">
                            {{ $expense->currency->symbol ?? '' }}
                        </td>
                        <td class="border p-2">
                            {{ $expense->employee->full_name ?? '-' }}
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>


        <div class="no-print">
            {{ $expenses->links() }}
        </div>

    </div>

    @include('frontend.expenses.modals.create')

@endsection
