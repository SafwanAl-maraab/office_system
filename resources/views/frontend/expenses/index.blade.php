@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-10">

        {{-- Header --}}
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                    إدارة المصروفات
                </h1>
                <p class="text-sm text-gray-500">
                    تسجيل ومتابعة جميع مصروفات الفرع
                </p>
            </div>

            <button onclick="openExpenseModal()"
                    class="bg-red-600 hover:bg-red-700
                       text-white px-5 py-2 rounded-xl">
                + إضافة مصروف
            </button>

        </div>


        {{-- فلترة --}}
        <form method="GET"
              class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5
                 grid grid-cols-1 md:grid-cols-3 gap-4">

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

            <button class="bg-blue-600 text-white rounded-xl px-4 py-2">
                بحث
            </button>

        </form>


        {{-- بطاقات المصروفات --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($expenses as $expense)

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
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

                    <div class="text-lg font-bold text-red-600">
                        - {{ number_format($expense->amount,2) }}
                        {{ $expense->currency->symbol ?? '' }}
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        {{ $expense->description }}
                    </div>

                    <div class="text-xs text-gray-500">
                        أنشئ بواسطة:
                        {{ $expense->employee->full_name ?? '-' }}
                    </div>

                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 py-16">
                    لا توجد مصروفات
                </div>

            @endforelse

        </div>

        {{ $expenses->links() }}

    </div>


    @include('frontend.expenses.modals.create')

@endsection
