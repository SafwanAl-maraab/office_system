@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-6">

        {{-- Header & Quick Stats --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">

            {{-- Title & Info --}}
            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    إدارة الطلبات
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    عرض ومتابعة طلبات الجوازات والبطاقات الشخصية.
                </p>
            </div>

            {{-- Quick Summary Stats (معلومات هامة ومختصرة لا تشتت المستخدم) --}}
            <div class="grid grid-cols-3 gap-4 flex-1 max-w-xl">
                {{-- طلبات قيد الانتظار --}}
                <div class="bg-amber-50 dark:bg-amber-950/30 p-3 rounded-lg border border-amber-100 dark:border-amber-900/50 text-center">
                    <span class="block text-xs text-amber-600 dark:text-amber-400 font-medium">قيد الانتظار</span>
                    <span class="text-lg font-bold text-amber-700 dark:text-amber-300">
                        {{ $pendingRequestsCount ?? 0 }}
                    </span>
                </div>
                {{-- طلبات مكتملة اليوم --}}
                <div class="bg-green-50 dark:bg-green-950/30 p-3 rounded-lg border border-green-100 dark:border-green-900/50 text-center">
                    <span class="block text-xs text-green-600 dark:text-green-400 font-medium">أُنجزت اليوم</span>
                    <span class="text-lg font-bold text-green-700 dark:text-green-300">
                        {{ $completedTodayCount ?? 0 }}
                    </span>
                </div>
                {{-- إجمالي طلبات النشطة --}}
                <div class="bg-blue-50 dark:bg-blue-950/30 p-3 rounded-lg border border-blue-100 dark:border-blue-900/50 text-center">
                    <span class="block text-xs text-blue-600 dark:text-blue-400 font-medium">إجمالي النشطة</span>
                    <span class="text-lg font-bold text-blue-700 dark:text-blue-300">
                        {{ $totalActiveRequestsCount ?? 0 }}
                    </span>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="flex items-center">
                <button onclick="toggleCreateModal()"
                        class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة طلب جديد
                </button>
            </div>

        </div>

        {{-- Filters --}}
        @include('frontend.requests.components.filters')

        {{-- Cards --}}
        @include('frontend.requests.components.cards')

    </div>


    {{-- Modals --}}
    @include('frontend.requests.modals.create')
    @include('frontend.requests.modals.edit')
    @include('frontend.requests.modals.change_status')
    @include('frontend.requests.modals.attach_travel')
    @include('frontend.requests.modals.delete')

@endsection
