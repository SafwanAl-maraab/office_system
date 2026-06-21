@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    تفاصيل الرحلة
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    تاريخ الانطلاق: <strong class="text-gray-700 dark:text-gray-300">{{ $travel->travel_date }}</strong>
                </p>
            </div>

            <div class="flex gap-3">

                {{-- تعطيل الرحلة إذا تجاوز التاريخ أو مكتملة --}}
                @if(
                    $travel->status === 'active' &&
                    now()->toDateString() >= $travel->travel_date
                )
                    <form method="POST" action="{{ route('dashboard.travels.update', $travel->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-xl text-sm transition shadow-sm">
                            إنهاء الرحلة
                        </button>
                    </form>
                @endif


                {{-- حذف فقط إذا لا يوجد طلبات --}}
                @if($travel->requests->count() == 0)
                    <form method="POST" action="{{ route('dashboard.travels.destroy', $travel->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الرحلة نهائياً؟')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm transition shadow-sm">
                            حذف الرحلة
                        </button>
                    </form>
                @endif

                <a href="{{ route('dashboard.travels.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-xl text-sm transition shadow-sm flex items-center justify-center">
                    رجوع
                </a>
            </div>

        </div>


        {{-- Card معلومات الرحلة المطور --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

                <div>
                    <p class="text-xs text-gray-500 mb-1">المسار المعتمد</p>
                    <p class="font-bold text-lg text-gray-800 dark:text-gray-100">
                        {{ $travel->from_location }} &rarr; {{ $travel->to_location }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">الكابتن / السائق</p>
                    <p class="font-bold text-gray-800 dark:text-gray-200">
                        {{ $travel->driver->name ?? '-' }}
                    </p>
                </div>

                {{-- الحقل المالي الجديد المضاف للتفاصيل --}}
                <div>
                    <p class="text-xs text-gray-500 mb-1">أجرة السائق المقيدة</p>
                    <p class="font-bold text-lg text-blue-600 dark:text-blue-400">
                        {{ number_format($travel->driver_cost, 2) }} <span class="text-xs font-normal text-gray-500">{{ $travel->currency->code ?? '' }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">حالة التشغيل</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold mt-1
                        @if($travel->status === 'active')
                            bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400
                        @elseif($travel->status === 'completed')
                            bg-blue-100 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400
                        @else
                            bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400
                        @endif
                    ">
                        @if($travel->status === 'active') نشطة @elseif($travel->status === 'completed') مكتملة @else ملغية @endif
                    </span>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-6 pt-5 border-t dark:border-gray-700">

                <div>
                    <p class="text-xs text-gray-500 mb-1">السعة الإجمالية</p>
                    <p class="font-bold text-xl text-gray-800 dark:text-gray-100">{{ $travel->capacity }} مقعد</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">المقاعد المحجوزة</p>
                    <p class="font-bold text-xl text-amber-600 dark:text-amber-400">
                        {{ $travel->requests->count() }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 mb-1">المقاعد المتاحة</p>
                    <p class="font-bold text-xl text-emerald-600 dark:text-emerald-400">
                        {{ $travel->capacity - $travel->requests->count() }}
                    </p>
                </div>

            </div>

            @if($travel->notes)
                <div class="pt-4 border-t dark:border-gray-700">
                    <p class="text-xs text-gray-500 mb-1">ملاحظات إدارية</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                        {{ $travel->notes }}
                    </p>
                </div>
            @endif

        </div>


        {{-- الطلبات المرتبطة --}}
        <div>

            <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">
                الطلبات المرتبطة بهذه الرحلة
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                @forelse($travel->requests as $request)

                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-5 space-y-3 transition hover:shadow-md">

                        <div class="flex justify-between items-center">
                            <div class="font-bold text-blue-600 dark:text-blue-400 font-mono">
                                #{{ $request->request_number }}
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                {{ $request->status }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-600 dark:text-gray-300">
                            العميل: <strong class="text-gray-800 dark:text-gray-100">{{ $request->client->full_name ?? '-' }}</strong>
                        </div>

                    </div>

                @empty

                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-12 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                        لا توجد طلبات حجز أو شحن مقيدة على هذه الرحلة حالياً.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

@endsection
