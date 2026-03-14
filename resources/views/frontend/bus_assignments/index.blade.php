@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-4 space-y-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                إدارة سائقين الباصات
            </h1>

            <div class="flex gap-3">

                <form method="GET">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="بحث بالباص أو السائق"
                        class="border rounded-xl px-4 py-2 dark:bg-gray-800 dark:text-white">

                </form>

                <button
                    data-open-create
                    class="bg-blue-600 text-white px-4 py-2 rounded-xl">

                    إضافة سائق

                </button>

            </div>

        </div>


        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

            @foreach($records as $record)

                @include('frontend.bus_assignments.parts.card')

            @endforeach

        </div>

    </div>


    @include('frontend.bus_assignments.parts.create')

    @include('frontend.bus_assignments.parts.edit')

    @include('frontend.bus_assignments.parts.scripts')

@endsection
