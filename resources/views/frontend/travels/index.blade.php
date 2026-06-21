@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                إدارة الرحلات
            </h1>
            <button onclick="openCreateModal()"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow transition">
                + إنشاء رحلة
            </button>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 p-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 p-3 rounded-xl text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($travels as $travel)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 space-y-4 transition hover:shadow-xl">

                    <div class="flex justify-between items-center">
                        <div class="font-bold text-lg text-gray-800 dark:text-gray-100">
                            {{ $travel->from_location }} → {{ $travel->to_location }}
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-600 px-3 py-1 rounded-full">
                            {{ $travel->travel_date }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <div>
                            السائق: <strong>{{ $travel->driver->name ?? '-' }}</strong>
                        </div>
                        {{-- الحقول الجديدة المضافة للكارت --}}
                        <div>
                            أجرة السائق: <strong class="text-blue-600 dark:text-blue-400">{{ number_format($travel->driver_cost, 2) }} {{ $travel->currency->code ?? '' }}</strong>
                        </div>
                        <div>
                            السعة: {{ $travel->capacity }} | المستخدم: {{ $travel->requests_count }}
                        </div>
                        <div>
                            المتبقي: <strong>{{ $travel->capacity - $travel->requests_count }}</strong>
                        </div>

                        @if($travel->requests_count >= $travel->capacity)
                            <div class="text-red-600 font-bold text-xs mt-1">
                                الرحلة ممتلئة
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2 pt-3">
                        <a href="{{ route('dashboard.travels.show', $travel->id) }}"
                           class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl text-sm transition">
                            عرض
                        </a>
                        {{-- زر التعديل المطور الذي يمرر البيانات --}}
                        <button type="button"
                                data-travel="{{ json_encode($travel) }}"
                                class="flex-1 edit-travel-btn bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-xl text-sm transition">
                            تعديل
                        </button>
                    </div>

                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-12">
                    لا توجد رحلات حالياً
                </div>
            @endforelse

        </div>
    </div>

    {{-- تضمين المودالات --}}
    @include('frontend.travels.modals.create')
    @include('frontend.travels.modals.edit')

@endsection
