<div id="employeeModal" class="fixed inset-0 z-50 hidden">

    <!-- Overlay -->
    <div id="employeeOverlay"
         class="absolute inset-0 bg-black/60 backdrop-blur-sm">
    </div>

    <!-- Wrapper -->
    <div class="relative w-full h-full flex items-end sm:items-center justify-center">

        <!-- Card -->
        <div class="w-full sm:max-w-2xl h-full sm:h-auto bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-y-auto">

            <div class="p-6 sm:p-8 space-y-6">

                <!-- Header -->
                <div class="flex justify-between items-center">

                    <h3 id="employeeModalTitle" class="text-lg sm:text-xl font-bold">
                        إضافة موظف جديد
                    </h3>

                    <button id="closeEmployeeModal"
                            class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                        ✕
                    </button>

                </div>

                <!-- Form -->
                <form id="employeeForm"
                      method="POST"
                      action="{{ route('employees.store') }}"
                      class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    @csrf
                    <input type="hidden" name="_method" id="employeeFormMethod" value="POST">

                    <div class="sm:col-span-2">
                        <input type="text" name="full_name"
                               placeholder="الاسم الكامل"
                               class="w-full px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    </div>

                    <input type="text" name="phone"
                           placeholder="رقم الهاتف"
                           class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                    <select name="role_id"
                            class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>

                    <input type="number" name="salary"
                           placeholder="الراتب"
                           class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                    <input type="number" name="commission_percentage"
                           placeholder="نسبة العمولة %"
                           class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">

                    <select name="status"
                            class="px-4 py-3 rounded-2xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <option value="1">نشط</option>
                        <option value="0">موقوف</option>
                    </select>

                    <button type="submit"
                            class="sm:col-span-2 py-3 rounded-2xl bg-blue-600 text-white font-semibold">
                        حفظ
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const modal = document.getElementById('employeeModal');
        const overlay = document.getElementById('employeeOverlay');
        const closeBtn = document.getElementById('closeEmployeeModal');
        const form = document.getElementById('employeeForm');
        const methodInput = document.getElementById('employeeFormMethod');
        const title = document.getElementById('employeeModalTitle');

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
            form.action = "{{ route('employees.store') }}";
            methodInput.value = "POST";
            title.innerText = "إضافة موظف جديد";
        }

        document.querySelectorAll('[data-open-employee]').forEach(btn => {
            btn.addEventListener('click', function () {
                resetForm();
                openModal();
            });
        });

        document.querySelectorAll('[data-edit-employee]').forEach(btn => {
            btn.addEventListener('click', function () {

                const data = JSON.parse(this.dataset.employee);

                form.action = "/dashboard/employees/" + data.id;
                methodInput.value = "PUT";
                title.innerText = "تعديل الموظف";

                form.full_name.value = data.full_name;
                form.phone.value = data.phone;
                form.role_id.value = data.role_id;
                form.salary.value = data.salary;
                form.commission_percentage.value = data.commission_percentage;
                form.status.value = data.status;

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
