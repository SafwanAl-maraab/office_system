{{-- ================= CREATE TRAVEL MODAL ================= --}}
<div id="createTravelModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <!-- Background Click -->
    <div onclick="closeCreateModal()"
         class="absolute inset-0"></div>

    <!-- Modal Box -->
    <div id="createTravelBox"
         class="relative bg-white dark:bg-gray-800
                w-full max-w-2xl
                max-h-[90vh] overflow-y-auto
                rounded-2xl shadow-2xl
                p-5 sm:p-6
                transform scale-95 opacity-0
                transition-all duration-200">

        <h2 class="text-lg sm:text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            إنشاء رحلة جديدة
        </h2>

        <form method="POST"
              action="{{ route('dashboard.travels.store') }}"
              class="space-y-5">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- تاريخ -->
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        تاريخ الرحلة
                    </label>
                    <input type="date"
                           name="travel_date"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-2 rounded-xl border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-900 dark:text-white"
                           required>
                </div>

                <!-- السائق -->
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        السائق
                    </label>

                    <select name="driver_id"
                            class="w-full px-3 py-2 rounded-xl border
                                   border-gray-300 dark:border-gray-600
                                   dark:bg-gray-900 dark:text-white"
                            required>

                        <option value="">اختر السائق</option>

                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <!-- من -->
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        من
                    </label>
                    <input type="text"
                           name="from_location"
                           required
                           class="w-full px-3 py-2 rounded-xl border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-900 dark:text-white">
                </div>

                <!-- إلى -->
                <div>
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        إلى
                    </label>
                    <input type="text"
                           name="to_location"
                           required
                           class="w-full px-3 py-2 rounded-xl border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-900 dark:text-white">
                </div>

                <!-- المقاعد -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        عدد المقاعد
                    </label>
                    <input type="number"
                           name="capacity"
                           value="20"
                           min="1"
                           required
                           class="w-full px-3 py-2 rounded-xl border
                                  border-gray-300 dark:border-gray-600
                                  dark:bg-gray-900 dark:text-white">
                </div>

                <!-- ملاحظات -->
                <div class="md:col-span-2">
                    <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                        ملاحظات
                    </label>
                    <textarea name="notes"
                              rows="3"
                              class="w-full px-3 py-2 rounded-xl border
                                     border-gray-300 dark:border-gray-600
                                     dark:bg-gray-900 dark:text-white"></textarea>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-5">

                <button type="button"
                        onclick="closeCreateModal()"
                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="w-full sm:w-auto px-4 py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white">
                    حفظ الرحلة
                </button>

            </div>

        </form>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('createTravelModal');
        const box = document.getElementById('createTravelBox');

        window.openCreateModal = function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                box.classList.remove('scale-95', 'opacity-0');
            }, 10);
        };

        window.closeCreateModal = function () {
            box.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 200);
        };

        // اغلاق بالضغط خارج الصندوق
        modal.addEventListener('click', function(e){
            if(e.target === modal){
                closeCreateModal();
            }
        });

        // اغلاق بزر ESC
        document.addEventListener('keydown', function(e){
            if(e.key === "Escape"){
                closeCreateModal();
            }
        });

    });
</script>
