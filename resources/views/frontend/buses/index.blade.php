@extends('frontend.layouts.app')

@section('title','الحافلات')
@section('subtitle','إدارة الحافلات')

@section('content')

    <div class="space-y-10">

        <!-- STATS -->

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">
                    إجمالي الحافلات
                </div>

                <div class="text-4xl font-bold mt-3">
                    {{ $totalBuses }}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">

                <div class="text-sm text-gray-500">
                    الحافلات النشطة
                </div>

                <div class="text-4xl font-bold mt-3 text-green-600">
                    {{ $activeBuses }}
                </div>

            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">

                <div class="text-sm text-gray-500">
                    بالصيانة
                </div>

                <div class="text-4xl font-bold mt-3 text-yellow-500">
                    {{ $maintenanceBuses }}
                </div>

            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">

                <div class="text-sm text-gray-500">
                    متوقفة
                </div>

                <div class="text-4xl font-bold mt-3 text-red-600">
                    {{ $inactiveBuses }}
                </div>

            </div>

        </div>

        <!-- SEARCH -->

        <div class="flex flex-col lg:flex-row gap-4 justify-between items-center">

            <form method="GET" class="w-full lg:w-auto">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="بحث برقم اللوحة أو الموديل..."
                    class="w-full lg:w-96 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">

            </form>

            <button
                type="button"
                data-open-bus
                class="w-full lg:w-auto px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                + إضافة حافلة

            </button>

        </div>

        <!-- BUSES -->

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($buses as $bus)

                <div class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-xl p-6">

                    <div class="flex justify-between items-center">

                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl shadow-lg">

                            🚌

                        </div>

                        @if($bus->status == 'active')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs">
                            نشطة
                        </span>

                        @elseif($bus->status == 'maintenance')

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs">
                            صيانة
                        </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs">
                            متوقفة
                        </span>

                        @endif

                    </div>

                    <div class="mt-5 space-y-3">

                        <h3 class="text-xl font-bold">
                            {{ $bus->plate_number }}
                        </h3>

                        <div class="text-sm text-gray-500">
                            🚍 الموديل :
                            {{ $bus->model ?: '-' }}
                        </div>

                        <div class="text-sm text-gray-500">
                            💺 المقاعد :
                            {{ $bus->capacity }}
                        </div>

                        <div class="text-sm text-gray-500">
                            🏢 الوكيل :
                            {{ $bus->agent->name ?? 'غير محدد' }}
                        </div>

                        <div class="text-sm text-gray-500">
                            👨‍✈️ السائقين :
                            {{ $bus->drivers_count }}
                        </div>

                        <div class="text-sm text-gray-500">
                            🛣 الرحلات :
                            {{ $bus->trips_count }}
                        </div>

                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between">

                        <button
                            type="button"
                            data-edit-bus
                            data-bus='@json($bus)'
                            class="px-4 py-2 rounded-xl bg-yellow-100 text-yellow-700 text-sm">

                            تعديل

                        </button>

                        <button
                            type="button"
                            onclick="confirmDelete({{ $bus->id }})"
                            class="px-4 py-2 rounded-xl bg-red-100 text-red-700 text-sm">

                            حذف

                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full">

                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-16 text-center">

                        <div class="text-6xl mb-4">
                            🚌
                        </div>

                        <h3 class="text-xl font-bold">
                            لا توجد حافلات
                        </h3>

                    </div>

                </div>

            @endforelse

        </div>

        <div>

            {{ $buses->links() }}

        </div>

    </div>

    @include('frontend.buses.partials.modal')
    @include('frontend.buses.partials.delete-modal')

@endsection
