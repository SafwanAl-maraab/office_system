@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto space-y-10">

        <h2 class="text-3xl font-bold ">لوحة التحكم</h2>

        <!-- Grid Balanced -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-lg shadow-gray-200/40 transition hover:-translate-y-1">
                <p class="text-gray-500 text-sm">تأشيرات اليوم</p>
                <p class="text-4xl font-bold mt-3">25</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-lg shadow-gray-200/40 transition hover:-translate-y-1">
                <p class="text-gray-500 text-sm">طلبات اليوم</p>
                <p class="text-4xl font-bold mt-3">12</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-lg shadow-gray-200/40 transition hover:-translate-y-1">
                <p class="text-gray-500 text-sm">الإيرادات</p>
                <p class="text-4xl font-bold mt-3">150,000</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-lg shadow-gray-200/40 transition hover:-translate-y-1">
                <p class="text-gray-500 text-sm">المتبقي</p>
                <p class="text-4xl font-bold mt-3">30,000</p>
            </div>

        </div>

    </div>

@endsection
