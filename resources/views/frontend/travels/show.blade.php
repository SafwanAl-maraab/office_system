@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    تفاصيل الرحلة
                </h1>

                <p class="text-sm text-gray-500">
                    {{ $travel->travel_date }}
                </p>
            </div>

            <div class="flex gap-3">

                {{-- تعطيل الرحلة إذا تجاوز التاريخ أو مكتملة --}}
                @if(
                    $travel->status === 'active' &&
                    now()->toDateString() >= $travel->travel_date
                )
                    <form method="POST"
                          action="{{ route('dashboard.travels.update', $travel->id) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="status" value="completed">

                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm">
                            إنهاء الرحلة
                        </button>
                    </form>
                @endif


                {{-- حذف فقط إذا لا يوجد طلبات --}}
                @if($travel->requests->count() == 0)

                    <form method="POST"
                          action="{{ route('dashboard.travels.destroy', $travel->id) }}">
                        @csrf
                        @method('DELETE')

                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm">
                            حذف الرحلة
                        </button>
                    </form>

                @endif

                <a href="{{ route('dashboard.travels.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    رجوع
                </a>
            </div>

        </div>


        {{-- Card معلومات الرحلة --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg
                border border-gray-200 dark:border-gray-700
                p-6 space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div>
                    <p class="text-xs text-gray-500">المسار</p>
                    <p class="font-bold text-lg">
                        {{ $travel->from_location }} → {{ $travel->to_location }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">السائق</p>
                    <p class="font-medium">
                        {{ $travel->driver->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">الحالة</p>

                    <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if($travel->status === 'active')
                        bg-green-100 text-green-700
                    @elseif($travel->status === 'completed')
                        bg-blue-100 text-blue-700
                    @else
                        bg-red-100 text-red-700
                    @endif
                ">
                    {{ $travel->status }}
                </span>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-6 pt-4 border-t dark:border-gray-700">

                <div>
                    <p class="text-xs text-gray-500">السعة</p>
                    <p class="font-bold">{{ $travel->capacity }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">المستخدم</p>
                    <p class="font-bold">
                        {{ $travel->requests->count() }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500">المتبقي</p>
                    <p class="font-bold">
                        {{ $travel->capacity - $travel->requests->count() }}
                    </p>
                </div>

            </div>

        </div>


        {{-- الطلبات المرتبطة --}}
        <div>

            <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">
                الطلبات المرتبطة
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                @forelse($travel->requests as $request)

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 space-y-2">

                        <div class="font-bold text-gray-800 dark:text-gray-100">
                            {{ $request->request_number }}
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            العميل: {{ $request->client->full_name }}
                        </div>

                        <div class="text-sm">
                            الحالة:
                            <span class="font-semibold">
                            {{ $request->status }}
                        </span>
                        </div>

                    </div>

                @empty

                    <div class="col-span-full text-center text-gray-500 py-10">
                        لا توجد طلبات مرتبطة بهذه الرحلة
                    </div>

                @endforelse

            </div>

        </div>

    </div>

@endsection
