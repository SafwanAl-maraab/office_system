<!-- =========================
     CLIENT MODAL COMPONENT
========================== -->

<div id="clientModal" class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="clientOverlay"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity duration-300">
    </div>

    <!-- Wrapper -->
    <div class="relative w-full h-full flex items-end sm:items-center justify-center">

        <!-- Card -->
        <div class="w-full sm:max-w-2xl h-full sm:h-auto bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-y-auto">

            <div class="p-6 sm:p-8 space-y-6">

                <!-- Header -->
                <div class="flex justify-between items-center">

                    <h3 id="clientModalTitle" class="text-lg sm:text-xl font-bold">
                        إضافة عميل جديد
                    </h3>

                    <button id="closeClientModal"
                            class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        ✕
                    </button>

                </div>

                <!-- Form -->
                <form id="clientForm"
                      method="POST"
                      action="{{ route('clients.store') }}"
                      class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    @csrf
                    <input type="hidden" name="_method" id="clientFormMethod" value="POST">

                    <!-- FULL NAME -->
                    <div class="sm:col-span-2">
                        <input type="text" name="full_name"
                               placeholder="الاسم الكامل"
                               required
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">
                    </div>

                    <!-- PHONE -->
                    <input type="text" name="phone"
                           placeholder="رقم الهاتف"
                           required
                           class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 outline-none">

                    <!-- PASSPORT -->
                    <input type="text" name="passport_number"
                           placeholder="رقم الجواز"
                           class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                    <!-- NATIONAL ID -->
                    <input type="text" name="national_id"
                           placeholder="رقم الهوية"
                           class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                    <!-- ADDRESS -->
                    <div class="sm:col-span-2">
                        <input type="text" name="address"
                               placeholder="العنوان"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    </div>

                    <!-- NOTES -->
                    <div class="sm:col-span-2">
                        <textarea name="notes"
                                  rows="3"
                                  placeholder="ملاحظات"
                                  class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 resize-none">
                        </textarea>
                    </div>

                    <!-- STATUS -->
                    <select name="status"
                            class="sm:col-span-2 px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <option value="1">نشط</option>
                        <option value="0">موقوف</option>
                    </select>

                    <!-- SUBMIT -->
                    <button type="submit"
                            class="sm:col-span-2 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow hover:scale-[1.02] transition">
                        حفظ
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

<!-- =========================
     CLIENT MODAL SCRIPT
========================== -->

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('clientModal');
        const overlay = document.getElementById('clientOverlay');
        const closeBtn = document.getElementById('closeClientModal');
        const form = document.getElementById('clientForm');
        const methodInput = document.getElementById('clientFormMethod');
        const title = document.getElementById('clientModalTitle');

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
            form.action = "{{ route('clients.store') }}";
            methodInput.value = "POST";
            title.innerText = "إضافة عميل جديد";
        }

        // زر إضافة
        document.querySelectorAll('[data-open-client]').forEach(btn => {
            btn.addEventListener('click', function () {
                resetForm();
                openModal();
            });
        });

        // زر تعديل
        document.querySelectorAll('[data-edit-client]').forEach(btn => {
            btn.addEventListener('click', function () {

                const data = JSON.parse(this.dataset.client);

                form.action = "/dashboard/clients/" + data.id;
                methodInput.value = "PUT";
                title.innerText = "تعديل العميل";

                form.full_name.value = data.full_name ?? '';
                form.phone.value = data.phone ?? '';
                form.passport_number.value = data.passport_number ?? '';
                form.national_id.value = data.national_id ?? '';
                form.address.value = data.address ?? '';
                form.notes.value = data.notes ?? '';
                form.status.value = data.status ?? 1;

                openModal();
            });
        });

        closeBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') closeModal();
        });

    });
</script>
