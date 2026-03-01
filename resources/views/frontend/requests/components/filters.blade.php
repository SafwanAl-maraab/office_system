<div class="bg-white dark:bg-gray-800 p-4 rounded-2xl shadow border border-gray-200 dark:border-gray-700">

    <form method="GET" action="{{ route('dashboard.requests.index') }}">

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

            {{-- Search --}}
            <div class="md:col-span-2">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="بحث باسم العميل أو رقم الطلب..."
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-900
                              text-gray-700 dark:text-gray-200
                              focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- Status --}}
            <div>
                <select name="status"
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900
                               text-gray-700 dark:text-gray-200">

                    <option value="">كل الحالات</option>

                    @php
                        $statuses = [
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

                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}"
                            {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- Date --}}
            <div>
                <input type="date"
                       name="date"
                       value="{{ request('date') }}"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-900
                              text-gray-700 dark:text-gray-200">
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition">
                    تصفية
                </button>

                <a href="{{ route('dashboard.requests.index') }}"
                   class="w-full text-center bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-xl transition">
                    إعادة
                </a>

            </div>

        </div>

    </form>

</div>
