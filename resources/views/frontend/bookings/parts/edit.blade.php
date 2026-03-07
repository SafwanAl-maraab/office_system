<div id="editModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-xl rounded-xl p-6">

        <div class="flex justify-between items-center mb-4">

            <h2 class="text-lg font-bold dark:text-white">
                تعديل الرحلة
            </h2>

            <button onclick="closeEditModal()" class="text-gray-500 text-xl">✕</button>

        </div>

        <form id="editForm" method="POST">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">

                <div>
                    <label class="text-sm dark:text-gray-300">من</label>
                    <input id="edit_from_city" name="from_city" required
                           class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label class="text-sm dark:text-gray-300">إلى</label>
                    <input id="edit_to_city" name="to_city" required
                           class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">
                </div>

            </div>


            <div class="mt-4">

                <label class="text-sm dark:text-gray-300">الباص</label>

                <select id="edit_bus_id" name="bus_id"
                        class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">

                    @foreach($buses as $bus)

                        <option value="{{ $bus->id }}">
                            {{ $bus->model }} - {{ $bus->plate_number }}
                        </option>

                    @endforeach

                </select>

            </div>



            <div class="grid grid-cols-2 gap-4 mt-4">

                <input type="date" id="edit_trip_date" name="trip_date"
                       class="p-2 border rounded dark:bg-gray-700 dark:text-white">

                <input type="time" id="edit_trip_time" name="trip_time"
                       class="p-2 border rounded dark:bg-gray-700 dark:text-white">

            </div>



            <div class="grid grid-cols-2 gap-4 mt-4">

                <input type="number" step="0.01" id="edit_purchase_price"
                       name="purchase_price"
                       class="p-2 border rounded dark:bg-gray-700 dark:text-white">

                <input type="number" step="0.01" id="edit_sale_price"
                       name="sale_price"
                       class="p-2 border rounded dark:bg-gray-700 dark:text-white">

            </div>



            <div class="mt-4">

                <select id="edit_currency_id" name="currency_id"
                        class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">

                    @foreach($currencies as $currency)

                        <option value="{{ $currency->id }}">
                            {{ $currency->name }}
                        </option>

                    @endforeach

                </select>

            </div>



            <div class="mt-4">

                <select id="edit_status" name="status"
                        class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white">

                    <option value="scheduled">scheduled</option>
                    <option value="in_progress">in_progress</option>
                    <option value="completed">completed</option>
                    <option value="cancelled">cancelled</option>

                </select>

            </div>



            <div class="mt-4">

<textarea id="edit_notes" name="notes"
          class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"></textarea>

            </div>



            <div class="flex justify-end gap-3 mt-6">

                <button type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 rounded">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                    تحديث
                </button>

            </div>

        </form>

    </div>
</div>
