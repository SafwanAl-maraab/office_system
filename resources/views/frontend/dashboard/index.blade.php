@extends('frontend.layouts.app')

@section('title','لوحة التحكم')

@section('content')

    <div class="p-4 md:p-6 space-y-6">

        @include('frontend.dashboard.partials.hero')

        @include('frontend.dashboard.partials.kpis')

        @include('frontend.dashboard.partials.financial-summary')
        @include(
        'frontend.dashboard.partials.profit-cards'
        )
        @include('frontend.dashboard.partials.cashboxes')

        @include('frontend.dashboard.partials.receivables')

        @include('frontend.dashboard.partials.timeline')

        @include('frontend.dashboard.partials.alerts')

        @include('frontend.dashboard.partials.charts')

    </div>

@endsection
