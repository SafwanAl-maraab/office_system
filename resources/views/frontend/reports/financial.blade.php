@extends('frontend.layouts.app')

@section('title','التقرير المالي')

@section('content')

    <div class="p-4 md:p-6 space-y-6">

        {{-- Header --}}
        <div class="bg-white dark:bg-gray-900
                rounded-2xl
                border border-gray-100 dark:border-gray-800
                p-6 shadow-sm">

            <div class="flex flex-col lg:flex-row
                    lg:items-center
                    lg:justify-between
                    gap-4">

                <div>

                    <h1 class="text-2xl font-bold
                           text-gray-800
                           dark:text-gray-100">

                        التقرير المالي

                    </h1>

                    <p class="text-sm text-gray-500">

                        تحليل الأداء المالي والخزائن
                        للفترة المحددة

                    </p>

                </div>

                <div class="flex gap-3 flex-wrap">

                    <button
                        onclick="window.print()"
                        class="px-4 py-2 rounded-xl
                           bg-blue-600 text-white">

                        طباعة

                 </button>

{{--                    <a--}}
{{--                        href="{{ route('financial-report.excel',request()->all()) }}"--}}
{{--                        class="px-4 py-2 rounded-xl bg-green-600 text-white">--}}

{{--                        Excel--}}

{{--                    </a>--}}

                    <a
                        href="{{ route('financial-report.pdf',request()->all()) }}"
                        target="_blank"
                        class="px-4 py-2 rounded-xl bg-red-600 text-white">

                        PDF

                    </a>

                </div>

            </div>

        </div>

        @include('frontend.reports.partials.filter')

        @include('frontend.reports.partials.kpi-cards')

        @include('frontend.reports.partials.charts')

        @include('frontend.reports.partials.cashboxes')

        @include('frontend.reports.partials.latest-operations')

    </div>

@endsection
