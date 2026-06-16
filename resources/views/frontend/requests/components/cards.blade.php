@if($requests->count())

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @foreach($requests as $request)

            @php
                $statusColors = [
                    'new' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                    'under_review' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300',
                    'preparing' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                    'sent_to_south' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                    'received_south' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300',
                    'ready' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                    'delivered' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                    'rejected' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
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

                // حساب المتبقي للفاتورة للتنبيه المالي
                $remaining = $request->invoice->remaining_amount ?? 0;
            @endphp

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 space-y-4 hover:shadow-md transition relative flex flex-col justify-between">

                <div>
                    {{-- Header --}}
                    <div class="flex justify-between items-start gap-2">
                        <div>
                            <span class="text-xs font-mono text-gray-400 dark:text-gray-500 block">#{{ $request->request_number }}</span>
                            <h2 class="font-bold text-gray-800 dark:text-gray-100 text-base mt-0.5">
                                {{ $request->client->full_name ?? '-' }}
                            </h2>
                        </div>

                        <div class="flex flex-col items-end gap-1.5">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $statusColors[$request->status] ?? '' }}">
                                {{ $statusLabels[$request->status] ?? '-' }}
                            </span>
                            {{-- مؤشر مالي ذكي ومختصر --}}
                            @if($remaining > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded bg-red-50 text-red-600 dark:bg-red-950/20 dark:text-red-400 border border-red-100 dark:border-red-900/30">
                                    متبقي: {{ number_format($remaining, 2) }}
                                </span>
                            @else
                                <span class="text-[10px] px-2 py-0.5 rounded bg-green-50 text-green-600 dark:bg-green-950/20 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                    مدفوع بالكامل
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-b border-gray-100 dark:border-gray-700/50 my-3"></div>

                    {{-- Body --}}
                    <div class="text-sm space-y-2.5 text-gray-600 dark:text-gray-300">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 dark:text-gray-500 flex items-center gap-1.5 text-xs">
                                💼 نوع الخدمة:
                            </span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200">
                                {{ $request->requestType->name ?? '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 dark:text-gray-500 flex items-center gap-1.5 text-xs">
                                📅 تاريخ الطلب:
                            </span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $request->request_date }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 dark:text-gray-500 flex items-center gap-1.5 text-xs">
                                👤 الموظف المستلم:
                            </span>
                            <span class="text-gray-700 dark:text-gray-200 text-xs">{{ $request->employee->full_name ?? '-' }}</span>
                        </div>

                        @if($request->travels->count())
                            <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-900/40 p-2 rounded-lg border border-gray-100 dark:border-gray-700/50">
                                <span class="text-xs text-gray-500">🚌 خط سيرة الحركة:</span>
                                <span class="text-xs text-green-600 dark:text-green-400 font-bold flex items-center gap-1">
                                    {{ $request->travels->first()->from_location }}
                                    <span class="text-gray-400">←</span>
                                    {{ $request->travels->first()->to_location }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions Container --}}
                <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700/50 mt-2">

                    {{-- الأزرار الرئيسية الواضحة والمباشرة --}}
                    <a href="{{ route('dashboard.requests.show', $request->id) }}"
                       class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-medium py-2 rounded-lg text-center transition">
                        عرض التفاصيل
                    </a>

                    <button onclick="openStatusModal({{ $request->id }}, '{{ $request->status }}', {{ $remaining }})"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium py-2 rounded-lg transition">
                        تغيير الحالة
                    </button>

                    {{-- زر القائمة المنسدلة للعمليات الإضافية والفرعية (Dropdown) --}}
                    <div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                                class="bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-500 dark:text-gray-300 p-2 rounded-lg border border-gray-200 dark:border-gray-600 transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0-6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 12c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                        </button>

                        {{-- عناصر القائمة المنسدلة بقوانين تصفية أنيقة --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             class="absolute left-0 mt-2 w-44 rounded-xl shadow-lg bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 z-30 overflow-hidden"
                             style="display: none;">
                            <div class="py-1">
                                <button onclick="openEditModal({{ $request->id }}, {{ $request->client_id }}, {{ $request->request_type_id }}, '{{ addslashes($request->notes) }}')"
                                        class="flex w-full items-center px-4 py-2 text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 text-right">
                                    ✏️ تعديل البيانات
                                </button>

                                @if(!$request->travels->count())
                                    <button onclick="openTravelModal({{ $request->id }})"
                                            class="flex w-full items-center px-4 py-2 text-xs text-green-600 dark:text-green-400 hover:bg-gray-50 dark:hover:bg-gray-600 text-right">
                                        🔗 ربط حركة رحلة
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('dashboard.requests.detachTravel', $request->id) }}" class="w-full">
                                        @csrf @method('DELETE')
                                        <button class="flex w-full items-center px-4 py-2 text-xs text-amber-600 dark:text-amber-400 hover:bg-gray-50 dark:hover:bg-gray-600 text-right">
                                            🔓 فك ربط الرحلة
                                        </button>
                                    </form>
                                @endif

                                <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>

                                <button onclick="openDeleteModal({{ $request->id }})"
                                        class="flex w-full items-center px-4 py-2 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-gray-600 text-right font-medium">
                                    🗑️ حذف الطلب
                                </button>
                            </div>
                        </div>
                    </div>

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
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center max-w-md mx-auto my-10">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
            📭
        </div>
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100">
            لا توجد طلبات حالياً
        </h2>
        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">
            لم نجد أي طلبات مطابقة للفلترة، أو يمكنك إضافة طلب جديد للبدء فوراً.
        </p>
    </div>

@endif
