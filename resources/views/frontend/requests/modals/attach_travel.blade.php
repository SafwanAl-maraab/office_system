<div id="travelModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl p-6 relative">

        <button onclick="closeTravelModal()"
                class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            ربط الطلب برحلة
        </h2>

        <form method="POST" id="travelForm" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    اختر الرحلة
                </label>

                <select name="travel_id"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>

                    <option value="">اختر رحلة</option>

                    @foreach($travels as $travel)
                        <option value="{{ $travel->id }}">
                            {{ $travel->from_location }} → {{ $travel->to_location }}
                            ({{ $travel->travel_date }})
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    رقم المقعد (اختياري)
                </label>

                <input type="text"
                       name="seat_number"
                       class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                              bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200">
            </div>

            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeTravelModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white">
                    حفظ
                </button>

            </div>

        </form>

    </div>
</div>


<script>
    function openTravelModal(id) {

        const modal = document.getElementById('travelModal');
        const form = document.getElementById('travelForm');

        form.action = '/dashboard/requests/' + id + '/attach-travel';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTravelModal() {
        const modal = document.getElementById('travelModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
