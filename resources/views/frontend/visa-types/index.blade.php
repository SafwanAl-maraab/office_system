@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-wrap justify-between items-center gap-4">

        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            إدارة أنواع التأشيرات
        </h1>

        <button onclick="openCreateModal()"
            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow">
            + إضافة نوع
        </button>

    </div>

    {{-- SEARCH --}}
    <form method="GET" class="max-w-md">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="بحث باسم النوع..."
               class="w-full border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-800
                      text-gray-800 dark:text-white
                      rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">
    </form>

    {{-- CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($visaTypes as $type)

        <div class="bg-white dark:bg-gray-900 p-6 rounded-3xl shadow hover:shadow-lg transition">

            <div class="flex justify-between items-start">

                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ $type->name }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        {{ $type->category ?? 'غير محدد' }}
                    </p>
                </div>

                <span class="px-3 py-1 text-xs rounded-full
                    {{ $type->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $type->status ? 'نشط' : 'غير مفعل' }}
                </span>

            </div>

            <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">

                <p>يتطلب باقة:
                    <strong>{{ $type->requires_package ? 'نعم' : 'لا' }}</strong>
                </p>

                <p>المدة الافتراضية:
                    <strong>{{ $type->default_duration_days ?? '-' }} يوم</strong>
                </p>

            </div>

            <div class="flex gap-3 mt-6">

                <button onclick="editType({{ $type }})"
                    class="flex-1 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl">
                    تعديل
                </button>

                <form method="POST"
                      action="{{ route('visa-types.destroy',$type->id) }}"
                      class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl">
                        حذف
                    </button>
                </form>

            </div>

        </div>

        @endforeach

    </div>

    <div>
        {{ $visaTypes->links() }}
    </div>

</div>

@include('frontend.visa-types.modals')

@endsection