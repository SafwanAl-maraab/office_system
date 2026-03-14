<div id="createBusModal"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-2xl p-6">

        <h2 class="font-bold text-lg mb-4">
            إضافة باص
        </h2>

        <form method="POST"
              action="{{ route('dashboard.buses.store') }}"
              class="space-y-4">

            @csrf


            <input
                name="plate_number"
                placeholder="رقم اللوحة"
                class="w-full border rounded-lg px-4 py-2"
                required>


            <input
                name="model"
                placeholder="الموديل"
                class="w-full border rounded-lg px-4 py-2">


            <input
                name="capacity"
                type="number"
                placeholder="عدد المقاعد"
                class="w-full border rounded-lg px-4 py-2">


            <select name="agent_id"
                    class="w-full border rounded-lg px-4 py-2">

                <option value="">اختر الوكيل</option>

                @foreach($agents as $agent)

                    <option value="{{ $agent->id }}">
                        {{ $agent->name }}
                    </option>

                @endforeach

            </select>


            <select name="status"
                    class="w-full border rounded-lg px-4 py-2">

                <option value="active">active</option>
                <option value="maintenance">maintenance</option>
                <option value="inactive">inactive</option>

            </select>


            <select name="drivers[]"
                    multiple
                    class="w-full border rounded-lg px-4 py-2">

                @foreach($drivers as $driver)

                    <option value="{{ $driver->id }}">
                        {{ $driver->name }}
                    </option>

                @endforeach

            </select>


            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeCreateBusModal()"
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
