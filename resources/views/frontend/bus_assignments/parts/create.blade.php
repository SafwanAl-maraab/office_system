<div id="createModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-xl p-6">

        <h2 class="font-bold text-lg mb-4 dark:text-white">
            إضافة سائق
        </h2>

        <form method="POST"
              action="{{ route('dashboard.bus_assignments.store') }}"
              class="space-y-4">

            @csrf

            <input type="hidden" name="bus_id" id="create_bus">


            <select name="driver_id"
                    required
                    class="w-full border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white">

                <option value="">اختر السائق</option>

                @foreach($drivers as $driver)

                    <option value="{{ $driver->id }}">
                        {{ $driver->name }}
                    </option>

                @endforeach

            </select>


            <div class="grid grid-cols-2 gap-3">

                <input
                    type="time"
                    name="start_at"
                    required
                    class="border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white">

                <input
                    type="time"
                    name="end_at"
                    class="border rounded-lg px-4 py-2 dark:bg-gray-700 dark:text-white">

            </div>


            <div class="flex justify-end gap-3 pt-4">

                <button
                    type="button"
                    onclick="closeCreate()"
                    class="bg-gray-400 text-white px-4 py-2 rounded">

                    إلغاء

                </button>

                <button
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                    حفظ

                </button>

            </div>

        </form>

    </div>

</div>
