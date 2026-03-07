<div id="bookingModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6 relative">

        <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">
            إنشاء حجز جديد
        </h2>


        <form method="POST" action="{{ route('bookings.store') }}">

            @csrf


            {{-- العميل --}}

            <div class="mb-4">

                <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">
                    العميل
                </label>

                <select
                    name="client_id"
                    required
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white">

                    <option value="">اختر العميل</option>

                    @foreach($clients as $client)

                        <option value="{{ $client->id }}">

                            {{ $client->full_name }}

                        </option>

                    @endforeach

                </select>

            </div>



            {{-- الرحلة --}}

            <div class="mb-4">

                <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">
                    الرحلة
                </label>

                <select
                    name="trip_id"
                    required
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white">

                    <option value="">اختر الرحلة</option>

                    @foreach($trips as $trip)

                        <option value="{{ $trip->id }}">

                            {{ $trip->from_city }} → {{ $trip->to_city }}

                        </option>

                    @endforeach

                </select>

            </div>



            {{-- العملة --}}

            <div class="mb-4">

                <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">
                    العملة
                </label>

                <select
                    name="currency_id"
                    required
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white">

                    <option value="">اختر العملة</option>

                    @foreach($currencies as $currency)

                        <option value="{{ $currency->id }}">

                            {{ $currency->name }}

                        </option>

                    @endforeach

                </select>

            </div>



            {{-- السعر النهائي --}}

            <div class="mb-4">

                <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">
                    السعر النهائي
                </label>

                <input
                    type="number"
                    name="final_price"
                    step="0.01"
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white">

            </div>



            {{-- حالة الحجز --}}

            <div class="mb-6">

                <label class="block text-sm mb-1 text-gray-600 dark:text-gray-300">
                    حالة الحجز
                </label>

                <select
                    name="status"
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 dark:text-white">

                    <option value="pending">قيد الانتظار</option>
                    <option value="confirmed">مؤكد</option>
                    <option value="cancelled">ملغي</option>

                </select>

            </div>



            {{-- الأزرار --}}

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeBookingModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg">

                    إلغاء

                </button>


                <button
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">

                    حفظ الحجز

                </button>

            </div>


        </form>

    </div>

</div>

<script>

    function openBookingModal() {

        document.getElementById('bookingModal').classList.remove('hidden');
        document.getElementById('bookingModal').classList.add('flex');

    }

    function closeBookingModal() {

        document.getElementById('bookingModal').classList.remove('flex');
        document.getElementById('bookingModal').classList.add('hidden');

    }

</script>
