<div id="driverModal" class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="driverOverlay"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm">
    </div>

    <!-- Wrapper -->
    <div class="relative w-full h-full flex items-end sm:items-center justify-center">

        <!-- Card -->
        <div class="w-full sm:max-w-2xl h-full sm:h-auto bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-y-auto">

            <div class="p-6 sm:p-8 space-y-6">

                <!-- Header -->
                <div class="flex justify-between items-center">

                    <h3 id="driverModalTitle"
                        class="text-xl font-bold">
                        إضافة سائق جديد
                    </h3>

                    <button id="closeDriverModal"
                            type="button"
                            class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                        ✕
                    </button>

                </div>

                <!-- Form -->

                <form id="driverForm"
                      method="POST"
                      action="{{ route('drivers.store') }}"
                      class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    @csrf

                    <input type="hidden"
                           name="_method"
                           id="driverFormMethod"
                           value="POST">

                    <!-- Name -->

                    <div class="md:col-span-2">

                        <label class="block mb-2 text-sm font-medium">
                            اسم السائق
                        </label>

                        <input type="text"
                               name="name"
                               required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    </div>

                    <!-- Phone -->

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            رقم الهاتف
                        </label>

                        <input type="text"
                               name="phone"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    </div>

                    <!-- License -->

                    <div>

                        <label class="block mb-2 text-sm font-medium">
                            رقم الرخصة
                        </label>

                        <input type="text"
                               name="license_number"
                               required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    </div>

                    <!-- Status -->

                    <div class="md:col-span-2">

                        <label class="block mb-2 text-sm font-medium">
                            الحالة
                        </label>

                        <select name="status"
                                class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                            <option value="active">
                                نشط
                            </option>

                            <option value="inactive">
                                غير نشط
                            </option>

                            <option value="suspended">
                                موقوف
                            </option>

                        </select>

                    </div>

                    <!-- Submit -->

                    <div class="md:col-span-2 pt-3">

                        <button type="submit"
                                class="w-full py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">

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

        const modal = document.getElementById('driverModal');
        const overlay = document.getElementById('driverOverlay');

        const closeBtn = document.getElementById('closeDriverModal');

        const form = document.getElementById('driverForm');

        const methodInput = document.getElementById('driverFormMethod');

        const title = document.getElementById('driverModalTitle');

        function openModal() {

            modal.classList.remove('hidden');

            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {

            modal.classList.add('hidden');

            document.body.classList.remove('overflow-hidden');
        }

        function resetForm() {

            form.reset();

            form.action = "{{ route('drivers.store') }}";

            methodInput.value = "POST";

            title.innerText = "إضافة سائق جديد";
        }

        // ADD

        document.querySelectorAll('[data-open-driver]')
            .forEach(btn => {

                btn.addEventListener('click', function () {

                    resetForm();

                    openModal();
                });

            });

        // EDIT

        document.querySelectorAll('[data-edit-driver]')
            .forEach(btn => {

                btn.addEventListener('click', function () {

                    const data = JSON.parse(this.dataset.driver);

                    form.action = "/dashboard/drivers/" + data.id;

                    methodInput.value = "PUT";

                    title.innerText = "تعديل بيانات السائق";

                    form.name.value = data.name;
                    form.phone.value = data.phone;
                    form.license_number.value = data.license_number;
                    form.status.value = data.status;

                    openModal();

                });

            });

        closeBtn.addEventListener('click', closeModal);

        overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', function(e){

            if(e.key === 'Escape'){

                closeModal();
            }

        });

    });
</script>
