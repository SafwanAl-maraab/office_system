@if($requests->count())

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

        @foreach($requests as $request)

            @php
                $statusColors = [
                    'new' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                    'under_review' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300',
                    'preparing' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300',
                    'sent_to_south' => 'bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300',
                    'received_south' => 'bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-300',
                    'ready' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                    'delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300',
                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                    'rejected' => 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                ];

                $statusLabels = [
                    'new' => 'جديد',
                    'under_review' => 'قيد المراجعة',
                    'preparing' => 'قيد التجهيز',
                    'sent_to_south' => 'تم الإرسال',
                    'received_south' => 'تم الاستلام',
                    'ready' => 'جاهز',
                    'delivered' => 'تم التسليم',
                    'cancelled' => 'ملغي',
                    'rejected' => 'مرفوض',
                ];
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-5 space-y-4">

                {{-- Header --}}
                <div class="flex justify-between items-start">

                    <div>
                        <h2 class="font-bold text-gray-800 dark:text-gray-100">
                            {{ $request->request_number }}
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $request->client->full_name ?? '-' }}
                        </p>
                    </div>

                    <span class="px-3 py-1 text-xs rounded-full {{ $statusColors[$request->status] ?? '' }}">
                        {{ $statusLabels[$request->status] ?? '-' }}
                    </span>

                </div>

                {{-- Body --}}
                <div class="text-sm space-y-2 text-gray-600 dark:text-gray-300">

                    <div class="flex justify-between">
                        <span>نوع الخدمة:</span>
                        <span class="font-medium">
                            {{ $request->requestType->name ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>تاريخ الطلب:</span>
                        <span>{{ $request->request_date }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span>الموظف:</span>
                        <span>{{ $request->employee->full_name ?? '-' }}</span>
                    </div>

                    @if($request->travels->count())
                        <div class="flex justify-between">
                            <span>الرحلة:</span>
                            <span class="text-green-600 font-medium">
            {{ $request->travels->first()->from_location }}
            →
            {{ $request->travels->first()->to_location }}
        </span>
                        </div>
                    @endif

                </div>

                {{-- Actions --}}
                <div class="grid grid-cols-2 gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">

                    <a href="{{ route('dashboard.requests.show', $request->id) }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm py-2 rounded-lg text-center">
                        عرض
                    </a>

                    <button onclick="openEditModal(
    {{ $request->id }},
    {{ $request->client_id }},
    {{ $request->request_type_id }},
    '{{ addslashes($request->notes) }}'
)"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-2 rounded-lg">
                        تعديل
                    </button>

                    <button onclick="openStatusModal({{ $request->id }})"
                            class="bg-purple-600 hover:bg-purple-700 text-white text-sm py-2 rounded-lg">
                        تغيير الحالة
                    </button>


                    <button onclick="openTravelModal({{ $request->id }})"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm py-2 rounded-lg">
                        ربط رحلة
                    </button>

                    @if($request->travels->count())
                        <form method="POST"
                              action="{{ route('dashboard.requests.detachTravel', $request->id) }}">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded-lg w-full">
                                فك الربط
                            </button>
                        </form>
                    @endif


                    <button onclick="openDeleteModal({{ $request->id }})"
                            class="bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded-lg">
                        حذف
                    </button>

                </div>

            </div>

        @endforeach

    </div>

    {{-- Pagination --}}
    <div class="pt-6">
        {{ $requests->links() }}
    </div>

@else

    {{-- Empty State --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-10 text-center">

        <div class="text-5xl mb-4">📭</div>

        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            لا توجد طلبات حالياً
        </h2>

        <p class="text-gray-500 dark:text-gray-400 mt-2">
            قم بإضافة طلب جديد للبدء
        </p>

    </div>

@endif
