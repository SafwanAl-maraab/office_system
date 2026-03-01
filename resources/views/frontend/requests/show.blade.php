@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    تفاصيل الطلب
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $request->request_number }}
                </p>
            </div>

            <a href="{{ route('dashboard.requests.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                رجوع
            </a>

        </div>

        {{-- معلومات الطلب --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- بيانات الطلب --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border border-gray-200 dark:border-gray-700 space-y-3">

                <h2 class="font-bold text-gray-800 dark:text-gray-100 mb-3">
                    بيانات الطلب
                </h2>

                <div class="flex justify-between">
                    <span>رقم الطلب:</span>
                    <span>{{ $request->request_number }}</span>
                </div>

                <div class="flex justify-between">
                    <span>نوع الخدمة:</span>
                    <span>{{ $request->requestType->name }}</span>
                </div>

                <div class="flex justify-between">
                    <span>تاريخ الطلب:</span>
                    <span>{{ $request->request_date }}</span>
                </div>

                <div class="flex justify-between">
                    <span>الحالة:</span>
                    <span>{{ $request->status }}</span>
                </div>

                <div class="flex justify-between">
                    <span>الموظف:</span>
                    <span>{{ $request->employee->full_name }}</span>
                </div>

            </div>

            @if($request->travels->count())

                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border border-gray-200 dark:border-gray-700">

                    <h2 class="font-bold text-gray-800 dark:text-gray-100 mb-4">
                        معلومات الرحلة
                    </h2>

                    @php $travel = $request->travels->first(); @endphp

                    <div class="flex justify-between mb-2">
                        <span>من:</span>
                        <span>{{ $travel->from_location }}</span>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span>إلى:</span>
                        <span>{{ $travel->to_location }}</span>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span>التاريخ:</span>
                        <span>{{ $travel->travel_date }}</span>
                    </div>

                </div>

            @endif

            {{-- بيانات العميل --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border border-gray-200 dark:border-gray-700 space-y-3">

                <h2 class="font-bold text-gray-800 dark:text-gray-100 mb-3">
                    بيانات العميل
                </h2>

                <div class="flex justify-between">
                    <span>الاسم:</span>
                    <span>{{ $request->client->full_name }}</span>
                </div>

                <div class="flex justify-between">
                    <span>الهاتف:</span>
                    <span>{{ $request->client->phone }}</span>
                </div>

                <div class="flex justify-between">
                    <span>الرقم الوطني:</span>
                    <span>{{ $request->client->national_id }}</span>
                </div>

            </div>

        </div>

        {{-- الفاتورة --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border border-gray-200 dark:border-gray-700">

            <h2 class="font-bold text-gray-800 dark:text-gray-100 mb-4">
                الفاتورة
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <p class="text-sm text-gray-500">الإجمالي</p>
                    <p class="font-bold">{{ $request->invoice->total_amount }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">المدفوع</p>
                    <p class="font-bold text-green-600">
                        {{ $request->invoice->paid_amount }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">المتبقي</p>
                    <p class="font-bold text-red-600">
                        {{ $request->invoice->remaining_amount }}
                    </p>
                </div>

            </div>

        </div>

        {{-- سجل الحالات --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border border-gray-200 dark:border-gray-700">

            <h2 class="font-bold text-gray-800 dark:text-gray-100 mb-4">
                سجل تغيير الحالات
            </h2>

            @forelse($request->statusHistories as $history)

                <div class="border-b border-gray-200 dark:border-gray-700 py-3 text-sm">

                    <div class="flex justify-between">
                    <span>
                        {{ $history->old_status }} → {{ $history->new_status }}
                    </span>
                        <span>{{ $history->created_at }}</span>
                    </div>

                    <div class="text-gray-500">
                        بواسطة: {{ $history->employee->full_name ?? '-' }}
                    </div>

                </div>

            @empty

                <p class="text-gray-500">لا يوجد سجل تغييرات</p>

            @endforelse

        </div>

    </div>

@endsection
