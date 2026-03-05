@extends('frontend.layouts.app')

@section('content')

    <div class="p-6 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    إدارة الخزنة
                </h1>

                <p class="text-sm text-gray-500">
                    إدارة العملات والأرصدة الخاصة بالفرع
                </p>
            </div>

            <button onclick="openCreateCurrencyModal()"
                    class="bg-blue-600 hover:bg-blue-700
                       text-white px-4 py-2 rounded-xl text-sm">

                + إضافة عملة

            </button>

        </div>


        {{-- البحث --}}
        <form method="GET"
              class="bg-white dark:bg-gray-800
                 p-4 rounded-2xl shadow">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="بحث باسم العملة أو الرمز"

                   class="w-full px-4 py-2 rounded-xl
                      border border-gray-300
                      dark:border-gray-600
                      bg-white dark:bg-gray-900">

        </form>


        {{-- بطاقات العملات --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($cashboxes as $cashbox)

                <div class="bg-white dark:bg-gray-800
            rounded-2xl shadow-lg
            border border-gray-200 dark:border-gray-700
            p-6 space-y-6
            transition hover:shadow-xl">

                    {{-- رأس البطاقة --}}
                    <div class="flex justify-between items-center">

                        <div>

                            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                                {{ $cashbox->currency->name }}
                            </h2>

                            <p class="text-sm text-gray-500">
                                {{ $cashbox->currency->symbol }}
                            </p>

                        </div>

                        {{-- حالة العملة --}}
                        @if($cashbox->currency->status)

                            <span class="text-xs px-3 py-1 rounded-full
                     bg-green-100 text-green-700">

            مفعلة

        </span>

                        @else

                            <span class="text-xs px-3 py-1 rounded-full
                     bg-red-100 text-red-700">

            غير مفعلة

        </span>

                        @endif

                    </div>


                    {{-- الرصيد --}}
                    <div class="bg-gray-50 dark:bg-gray-900
                p-4 rounded-xl text-center">

                        <div class="text-sm text-gray-500">
                            الرصيد الحالي
                        </div>

                        <div class="text-3xl font-bold text-green-600 mt-1">

                            {{ number_format($cashbox->balance,2) }}

                            <span class="text-lg">
                {{ $cashbox->currency->symbol }}
            </span>

                        </div>

                    </div>


                    {{-- أزرار التحكم --}}
                    <div class="flex justify-end">

                        <button
                            onclick="openEditCurrencyModal(
                {{ $cashbox->currency->id }},
                '{{ $cashbox->currency->name }}',
                '{{ $cashbox->currency->symbol }}',
                {{ $cashbox->currency->status }}
            )"

                            class="px-4 py-2 rounded-xl
                   bg-blue-600 hover:bg-blue-700
                   text-white text-sm">

                            تعديل العملة

                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 py-16">
                    لا توجد خزائن
                </div>

            @endforelse
        </div>

    </div>


    @include('frontend.cashboxes.modals.create')
    @include('frontend.cashboxes.modals.edit')

@endsection
