@extends('frontend.layouts.app')

@section('title','السائقين')
@section('subtitle','إدارة السائقين')

@section('content')

    <div class="space-y-10">

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">
                    إجمالي السائقين
                </div>

                <div class="text-4xl font-bold mt-3">
                    {{ $totalDrivers }}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">

                <div class="text-sm text-gray-500">
                    السائقين النشطين
                </div>

                <div class="text-4xl font-bold mt-3 text-green-600">
                    {{ $activeDrivers }}
                </div>

            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">

                <div class="text-sm text-gray-500">
                    غير النشطين
                </div>

                <div class="text-4xl font-bold mt-3 text-yellow-500">
                    {{ $inactiveDrivers }}
                </div>

            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">

                <div class="text-sm text-gray-500">
                    الموقوفين
                </div>

                <div class="text-4xl font-bold mt-3 text-red-600">
                    {{ $suspendedDrivers }}
                </div>

            </div>

        </div>

        <!-- TOP BAR -->

        <div class="flex flex-col lg:flex-row gap-4 justify-between items-center">

            <form method="GET" class="w-full lg:w-auto">

                <div class="relative">

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="بحث باسم السائق أو الهاتف أو الرخصة..."
                        class="w-full lg:w-96 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-4 focus:ring-blue-200 outline-none">

                </div>

            </form>

            <button
                type="button"
                data-open-driver
                class="w-full lg:w-auto px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg">

                + إضافة سائق

            </button>

        </div>

        <!-- DRIVERS -->

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($drivers as $driver)

                <div
                    class="group bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 p-6">

                    <div class="flex items-center justify-between">

                        <div
                            class="h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">

                            {{ strtoupper(substr($driver->name,0,1)) }}

                        </div>

                        @if($driver->status == 'active')

                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            نشط
                        </span>

                        @elseif($driver->status == 'inactive')

                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                            غير نشط
                        </span>

                        @else

                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                            موقوف
                        </span>

                        @endif

                    </div>

                    <div class="mt-5 space-y-3">

                        <h3 class="text-xl font-bold">
                            {{ $driver->name }}
                        </h3>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            📱 {{ $driver->phone ?: 'لا يوجد رقم هاتف' }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400 break-all">
                            🪪 {{ $driver->license_number }}
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            📅 {{ \Carbon\Carbon::parse($driver->created_at)->format('Y-m-d') }}
                        </div>

                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between">

                        <button
                            type="button"
                            data-edit-driver
                            data-driver='@json($driver)'
                            class="px-4 py-2 rounded-xl bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 text-sm font-semibold">

                            تعديل

                        </button>

                        <button
                            type="button"
                            onclick="confirmDelete({{ $driver->id }})"
                            class="px-4 py-2 rounded-xl bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-sm font-semibold">

                            حذف

                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full">

                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-16 text-center border border-dashed border-gray-300 dark:border-gray-700">

                        <div class="text-5xl mb-4">
                            🚖
                        </div>

                        <h3 class="text-xl font-bold mb-2">
                            لا يوجد سائقين
                        </h3>

                        <p class="text-gray-500">
                            قم بإضافة أول سائق للفرع.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

        <!-- PAGINATION -->

        @if($drivers->count())

            <div class="pt-4">
                {{ $drivers->links() }}
            </div>

        @endif

    </div>

    @include('frontend.drivers.partials.modal')
    @include('frontend.drivers.partials.delete-modal')

@endsection
