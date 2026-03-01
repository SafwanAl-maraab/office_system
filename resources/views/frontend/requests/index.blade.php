@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    إدارة الطلبات
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    عرض ومتابعة طلبات الجوازات والبطاقات
                </p>
            </div>

            <div>
                <button onclick="toggleCreateModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                    + إضافة طلب جديد
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
