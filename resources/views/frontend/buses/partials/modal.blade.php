<div id="busModal" class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="busOverlay"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm">
    </div>

    <!-- Wrapper -->
    <div class="relative w-full h-full flex items-end sm:items-center justify-center">

        <!-- Card -->
        <div class="w-full sm:max-w-3xl h-full sm:h-auto bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-y-auto">

            <div class="p-6 sm:p-8 space-y-6">

                <!-- Header -->
                <div class="flex justify-between items-center">

                    <h3 id="busModalTitle"
                        class="text-xl font-bold">
                        إضافة حافلة جديدة
                    </h3>

                    <button id="closeBusModal"
                            type="button"
                            class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                        ✕
                    </button>

                </div>

                <!-- Form -->

                <form id="busForm"
                      method="POST"
                      action="{{ route('buses.store') }}"
                      class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    @csrf

                    <input type="hidden"
                           name="_method"
                           id="busFormMethod"
                           value="POST">

                    <!-- Plate Number -->

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            رقم اللوحة
                        </label>

                        <input type="text"
                               name="plate_number"
                               required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    </div>

                    <!-- Model -->

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            موديل الحافلة
                        </label>

                        <input type="text"
                               name="model"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    </div>

                    <!-- Capacity -->

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            عدد المقاعد
                        </label>

                        <input type="number"
                               min="1"
                               name="capacity"
                               required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    </div>

                    <!-- Agent -->

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            الوكيل
                        </label>

                        <select name="agent_id"
                                class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                            <option value="">
                                بدون وكيل
                            </option>

                            @foreach($agents as $agent)

                                <option value="{{ $agent->id }}">
                                    {{ $agent->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Status -->

                    <div class="md:col-span-2">

                        <label class="block mb-2 text-sm font-medium">
                            حالة الحافلة
                        </label>

                        <select name="status"
                                class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                            <option value="active">
                                نشطة
                            </option>

                            <option value="maintenance">
                                بالصيانة
                            </option>

                            <option value="inactive">
                                متوقفة
                            </option>

                        </select>

                    </div>

                    <!-- Submit -->

                    <div class="md:col-span-2 pt-3">

                        <button type="submit"
                                class="w-full py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition shadow-lg">

                            حفظ البيانات

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('busModal');
        const overlay = document.getElementById('busOverlay');

        const closeBtn = document.getElementById('closeBusModal');

        const form = document.getElementById('busForm');

        const methodInput = document.getElementById('busFormMethod');

        const title = document.getElementById('busModalTitle');

        function openModal()
        {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal()
        {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function resetForm()
        {
            form.reset();

            form.action = "{{ route('buses.store') }}";

            methodInput.value = "POST";

            title.innerText = "إضافة حافلة جديدة";
        }

        // Add

        document.querySelectorAll('[data-open-bus]')
            .forEach(btn => {

                btn.addEventListener('click', function(){

                    resetForm();

                    openModal();

                });

            });

        // Edit

        document.querySelectorAll('[data-edit-bus]')
            .forEach(btn => {

                btn.addEventListener('click', function(){

                    const data = JSON.parse(this.dataset.bus);

                    form.action = "/dashboard/buses/" + data.id;

                    methodInput.value = "PUT";

                    title.innerText = "تعديل الحافلة";

                    form.plate_number.value = data.plate_number ?? '';
                    form.model.value = data.model ?? '';
                    form.capacity.value = data.capacity ?? '';
                    form.agent_id.value = data.agent_id ?? '';
                    form.status.value = data.status ?? 'active';

                    openModal();

                });

            });

        closeBtn.addEventListener('click', closeModal);

        overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', function(e){

            if(e.key === 'Escape')
            {
                closeModal();
            }

        });

    });
</script>
