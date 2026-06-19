<div id="driverModal" class="fixed inset-0 z-50 hidden">
    <div id="driverOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative w-full h-full flex items-end sm:items-center justify-center p-4">
        <div class="w-full sm:max-w-2xl bg-white dark:bg-gray-900 rounded-2xl sm:rounded-3xl shadow-2xl overflow-y-auto max-h-[90vh]">
            <div class="p-6 sm:p-8 space-y-6">

                <div class="flex justify-between items-center border-b dark:border-gray-800 pb-4">
                    <h3 id="driverModalTitle" class="text-xl font-bold text-gray-900 dark:text-white">
                        إضافة سائق جديد
                    </h3>
                    <button id="closeDriverModal" type="button" class="h-10 w-10 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                        ✕
                    </button>
                </div>

                <form id="driverForm" method="POST" action="{{ route('drivers.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <input type="hidden" name="_method" id="driverFormMethod" value="POST">

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">اسم السائق</label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">تصنيف ونوع السائق</label>
                        <select name="type" required class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 outline-none dark:text-white">
                            <option value="regular">سائق عادي (للرحلات الخارجية)</option>
                            <option value="agent_driver">سائق ووكيل (للرحلات الداخلية)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">رقم الهاتف</label>
                        <input type="text" name="phone" class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none dark:text-white">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">رقم الرخصة</label>
                        <input type="text" name="license_number" required class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">حالة السائق الحالية</label>
                        <select name="status" class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 outline-none dark:text-white">
                            <option value="active">نشط ومتاح</option>
                            <option value="inactive">غير نشط</option>
                            <option value="suspended">موقوف</option>
                            <option value="on_trip">في رحلة عمل جارية</option>
                            <option value="vacation">في إجازة رسمية</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 pt-3">
                        <button type="submit" class="w-full py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition">
                            حفظ البيانات والملف
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
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        function resetForm() {
            form.reset();
            form.action = "{{ route('drivers.store') }}";
            methodInput.value = "POST";
            title.innerText = "إضافة سائق جديد";
        }

        // إطلاق إضافة سائق
        document.querySelectorAll('[data-open-driver]').forEach(btn => {
            btn.addEventListener('click', function () {
                resetForm();
                openModal();
            });
        });

        // إطلاق تعديل السائق وتعبئة الحقول الجديدة تلقائياً
        document.querySelectorAll('[data-edit-driver]').forEach(btn => {
            btn.addEventListener('click', function () {
                const data = JSON.parse(this.dataset.driver);

                form.action = "/dashboard/drivers/" + data.id;
                methodInput.value = "PUT";
                title.innerText = "تعديل بيانات السائق: " + data.name;

                form.name.value = data.name;
                form.type.value = data.type || 'regular'; // القيمة الجديدة للنوع
                form.phone.value = data.phone || '';
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
