@extends('frontend.layouts.app')

@section('title','السائقين')
@section('subtitle','إدارة السائقين')

@section('content')

    <div class="space-y-10">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-3xl p-6 shadow-xl">
                <div class="text-sm opacity-80">إجمالي السائقين</div>
                <div class="text-4xl font-bold mt-3">{{ $totalDrivers }}</div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-sm text-gray-500">السائقين النشطين</div>
                <div class="text-4xl font-bold mt-3 text-green-600">{{ $activeDrivers }}</div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-sm text-gray-500">غير النشطين والموقوفين</div>
                <div class="text-4xl font-bold mt-3 text-red-500">{{ $suspendedDrivers + $inactiveDrivers }}</div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-800">
                <div class="text-sm text-gray-500">في رحلات جارية</div>
                <div class="text-4xl font-bold mt-3 text-blue-500">{{ $drivers->where('status', 'on_trip')->count() }}</div>
            </div>

        </div>

        <div class="flex flex-col lg:flex-row gap-4 justify-between items-center">
            <form method="GET" class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="بحث باسم السائق أو الهاتف أو الرخصة..."
                        class="w-full lg:w-96 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-4 focus:ring-blue-200 outline-none">
                </div>

                <select name="type" onchange="this.form.submit()" class="px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 outline-none">
                    <option value="">كل الأنواع</option>
                    <option value="regular" {{ request('type') == 'regular' ? 'selected' : '' }}>سائق عادي (خارجي)</option>
                    <option value="agent_driver" {{ request('type') == 'agent_driver' ? 'selected' : '' }}>سائق ووكيل (داخلي)</option>
                </select>
            </form>

            <button
                type="button"
                data-open-driver
                class="w-full lg:w-auto px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
                + إضافة سائق جديد
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($drivers as $driver)
                <div class="group bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 p-6">

                    <div class="flex items-center justify-between">
                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ strtoupper(substr($driver->name,0,1)) }}
                        </div>

                        <div class="flex flex-col items-end gap-2">
                            @if($driver->status == 'active')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">نشط</span>
                            @elseif($driver->status == 'inactive')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400">غير نشط</span>
                            @elseif($driver->status == 'suspended')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">موقوف</span>
                            @elseif($driver->status == 'on_trip')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">🏁 في رحلة</span>
                            @elseif($driver->status == 'vacation')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">🌴 في إجازة</span>
                            @endif

                            <span class="text-[11px] px-2 py-0.5 rounded-md font-medium {{ $driver->type == 'agent_driver' ? 'bg-purple-50 text-purple-600 dark:bg-purple-950/40 dark:text-purple-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400' }}">
                                {{ $driver->type == 'agent_driver' ? '💼 سائق ووكيل (داخلي)' : '🚌 سائق عادي (خارجي)' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors">
                            {{ $driver->name }}
                        </h3>
                        <div class="text-sm text-gray-500 dark:text-gray-400">📱 {{ $driver->phone ?: 'لا يوجد هاتف' }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 break-all">🪪 رقم الرخصة: <b>{{ $driver->license_number }}</b></div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                        <button
                            type="button"
                            data-edit-driver
                            data-driver='@json($driver)'
                            class="px-4 py-2 rounded-xl bg-yellow-50 text-yellow-700 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 text-sm font-semibold transition">
                            تعديل
                        </button>

                        <button
                            type="button"
                            onclick="confirmDelete({{ $driver->id }})"
                            class="px-4 py-2 rounded-xl bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 text-sm font-semibold transition">
                            حذف
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-900 rounded-3xl p-16 text-center border border-dashed border-gray-300 dark:border-gray-700">
                        <div class="text-5xl mb-4">🚖</div>
                        <h3 class="text-xl font-bold mb-2">لا يوجد سائقين مطابقين</h3>
                        <p class="text-gray-500">قم بتعديل البحث أو إضافة سائق جديد.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($drivers->count())
            <div class="pt-4">
                {{ $drivers->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    @include('frontend.drivers.partials.modal')
    @include('frontend.drivers.partials.delete-modal')

@endsection
