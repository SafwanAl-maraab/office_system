@extends('frontend.layouts.app')

@section('title','تحليل الأرباح')

@section('content')

    <div class="p-4 md:p-6 space-y-6">

        {{-- Header --}}

        <div
            class="bg-white dark:bg-gray-900
               rounded-2xl
               border border-gray-100
               dark:border-gray-800
               shadow-sm
               p-6">

            <div
                class="flex flex-col
                   lg:flex-row
                   lg:justify-between
                   lg:items-center
                   gap-4">

                <div>

                    <h1
                        class="text-2xl
                           font-bold
                           text-gray-800
                           dark:text-gray-100">

                        تحليل الأرباح

                    </h1>

                    <p
                        class="text-sm
                           text-gray-500">

                        تحليل الأرباح المتوقعة والمؤكدة
                        حسب النشاط

                    </p>

                </div>

                <div class="flex gap-3">

                    <a
                        href="{{ route(
        'reports.profit-analysis.pdf',
        request()->query()
    ) }}"
                        target="_blank"
                        class="px-4 py-2 rounded-xl
           bg-red-600 hover:bg-red-700
           text-white">

                        PDF

                    </a>

                </div>

            </div>

        </div>

        @include(
            'frontend.reports.profit.partials.filter'
        )

        @include(
            'frontend.reports.profit.partials.kpi'
        )

        @include(
            'frontend.reports.profit.partials.activities'
        )

        @include(
            'frontend.reports.profit.partials.charts'
        )

        @include(
            'frontend.reports.profit.partials.table'
        )

    </div>

@endsection
