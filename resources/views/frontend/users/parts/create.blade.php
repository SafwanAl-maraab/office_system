<div id="createUserModal"
     class="fixed inset-0 z-50 hidden">

```
<!-- Overlay -->
<div
    onclick="closeCreateUserModal()"
    class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm">
</div>

<!-- Modal -->
<div class="relative flex items-center justify-center min-h-screen p-4">

    <div class="bg-white dark:bg-gray-800 w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-l from-blue-600 to-indigo-700 px-8 py-6">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-2xl font-black text-white">
                        إضافة مستخدم جديد
                    </h2>

                    <p class="text-blue-100 text-sm mt-1">
                        إنشاء حساب مستخدم جديد داخل النظام
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closeCreateUserModal()"
                    class="w-10 h-10 rounded-full bg-white/20 text-white hover:bg-red-500 transition">

                    ✕

                </button>

            </div>

        </div>

        <!-- Form -->
        <form
            method="POST"
            action="{{ route('users.store') }}">

            @csrf

            <div class="p-8 max-h-[75vh] overflow-y-auto">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Employee -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            الموظف
                        </label>

                        <select
                            id="employee_id"
                            name="employee_id"
                            required
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                            <option value="">
                                اختر الموظف
                            </option>

                            @foreach($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    data-name="{{ $employee->full_name }}">

                                    {{ $employee->full_name }}
                                    -
                                    {{ $employee->branch->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Username -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            اسم المستخدم
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                    </div>

                    <!-- Email -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            البريد الإلكتروني
                        </label>

                        <input
                            type="email"
                            name="email"
                            required
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                    </div>

                    <!-- Role -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            الدور
                        </label>

                        <select
                            name="role"
                            required
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                            @foreach($roles as $role)

                                <option value="{{ $role->name }}">
                                    {{ $role->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <!-- Password -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            كلمة المرور
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                        <div
                            id="passwordStrength"
                            class="mt-2 text-xs font-bold">
                        </div>

                    </div>

                    <!-- Confirm -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            تأكيد كلمة المرور
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-900 px-8 py-5 border-t border-gray-100 dark:border-gray-700">

                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-bold">

                        حفظ المستخدم

                    </button>

                    <button
                        type="button"
                        onclick="closeCreateUserModal()"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 dark:text-white py-3 rounded-2xl font-bold">

                        إلغاء

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
```

</div>

<script>

function openCreateUserModal()
{
    document
        .getElementById('createUserModal')
        .classList.remove('hidden');
}

function closeCreateUserModal()
{
    document
        .getElementById('createUserModal')
        .classList.add('hidden');
}

/*
|--------------------------------------------------------------------------
| تعبئة اسم المستخدم تلقائياً
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    let employeeSelect =
        document.getElementById('employee_id');

    if(employeeSelect){

        employeeSelect.addEventListener('change', function(){

            let selected =
                this.options[this.selectedIndex];

            document
                .getElementById('name')
                .value =
                selected.dataset.name ?? '';

        });

    }

});

/*
|--------------------------------------------------------------------------
| فحص قوة كلمة المرور
|--------------------------------------------------------------------------
*/

document
.getElementById('password')
.addEventListener('keyup', function(){

    let value = this.value;

    let box =
        document.getElementById('passwordStrength');

    if(value.length < 8){

        box.innerHTML =
        '<span class="text-red-500">كلمة المرور ضعيفة</span>';

        return;
    }

    if(
        /[A-Z]/.test(value)
        &&
        /[a-z]/.test(value)
        &&
        /[0-9]/.test(value)
        &&
        /[^A-Za-z0-9]/.test(value)
    ){

        box.innerHTML =
        '<span class="text-green-600">كلمة المرور قوية جداً</span>';

    }
    else{

        box.innerHTML =
        '<span class="text-yellow-500">كلمة المرور متوسطة</span>';

    }

});

</script>
