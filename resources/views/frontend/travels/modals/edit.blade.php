{{-- ================= EDIT TRAVEL MODAL ================= --}}
<div id="editTravelModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">
    <div onclick="closeEditModal()" class="absolute inset-0"></div>

    <div id="editTravelBox" class="relative bg-white dark:bg-gray-800 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl p-5 sm:p-6 transform scale-95 opacity-0 transition-all duration-200">
        <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">تعديل بيانات الرحلة</h2>

        <form method="POST" id="editTravelForm" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">تاريخ الرحلة</label>
                    <input type="date" name="travel_date" id="edit_travel_date" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">السائق</label>
                    <select name="driver_id" id="edit_driver_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
                        <option value="">اختر السائق</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">أجرة السائق</label>
                    <input type="number" step="0.01" name="driver_cost" id="edit_driver_cost" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">العملة</label>
                    <select name="currency_id" id="edit_currency_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
                        <option value="">اختر العملة</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency->id }}">{{ $currency->code }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">من</label>
                    <input type="text" name="from_location" id="edit_from_location" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">إلى</label>
                    <input type="text" name="to_location" id="edit_to_location" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">عدد المقاعد</label>
                    <input type="number" name="capacity" id="edit_capacity" min="1" required class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">ملاحظات</label>
                    <textarea name="notes" id="edit_notes" rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5">
                <button type="button" onclick="closeEditModal()" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-gray-400 hover:bg-gray-500 text-white">إلغاء</button>
                <button type="submit" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white">تعديل الرحلة</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editModal = document.getElementById('editTravelModal');
        const editBox = document.getElementById('editTravelBox');

        window.openEditModal = function () {
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            setTimeout(() => { editBox.classList.remove('scale-95', 'opacity-0'); }, 10);
        };

        window.closeEditModal = function () {
            editBox.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { editModal.classList.remove('flex'); editModal.classList.add('hidden'); }, 200);
        };

        // لقطة الجافا سكريبت لتعبئة الحقول فورياً عند الضغط على زر التعديل
        document.querySelectorAll('.edit-travel-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const travel = JSON.parse(this.dataset.travel);

                // تغيير آكشن الـ Form ليتوجه لرابط الـ Update الخاص بالرحلة المحددة
                document.getElementById('editTravelForm').action = `/dashboard/travels/${travel.id}`;

                // ضخ البيانات داخل المدخلات
                document.getElementById('edit_travel_date').value = travel.travel_date;
                document.getElementById('edit_driver_id').value = travel.driver_id ?? '';
                document.getElementById('edit_driver_cost').value = travel.driver_cost;
                document.getElementById('edit_currency_id').value = travel.currency_id ?? '';
                document.getElementById('edit_from_location').value = travel.from_location;
                document.getElementById('edit_to_location').value = travel.to_location;
                document.getElementById('edit_capacity').value = travel.capacity;
                document.getElementById('edit_notes').value = travel.notes ?? '';

                openEditModal();
            });
        });
    });
</script>
