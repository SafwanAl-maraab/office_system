<div id="editUserModal"
     class="fixed inset-0 z-50 hidden">

```
<!-- Overlay -->
<div
    onclick="closeEditUserModal()"
    class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm">
</div>

<!-- Modal -->
<div class="relative flex items-center justify-center min-h-screen p-4">

    <div class="bg-white dark:bg-gray-800 w-full max-w-4xl rounded-3xl shadow-2xl overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-l from-amber-500 to-orange-600 px-8 py-6">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-2xl font-black text-white">
                        تعديل المستخدم
                    </h2>

                    <p class="text-orange-100 text-sm mt-1">
                        تعديل بيانات المستخدم والصلاحيات
                    </p>

                </div>

                <button
                    type="button"
                    onclick="closeEditUserModal()"
                    class="w-10 h-10 rounded-full bg-white/20 text-white hover:bg-red-500 transition">

                    ✕

                </button>

            </div>

        </div>

        <form id="editUserForm"
              method="POST">

            @csrf
            @method('PUT')

            <div class="p-8 max-h-[75vh] overflow-y-auto">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Employee -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            الموظف
                        </label>

                        <input
                            id="edit_employee"
                            readonly
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900 dark:text-white px-4 py-3">

                    </div>

                    <!-- Username -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            اسم المستخدم
                        </label>

                        <input
                            type="text"
                            id="edit_name"
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
                            id="edit_email"
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
                            id="edit_role"
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
                            كلمة المرور الجديدة
                        </label>

                        <input
                            type="password"
                            id="edit_password"
                            name="password"
                            placeholder="اتركها فارغة إذا لم ترد تغييرها"
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                    </div>

                    <!-- Confirm Password -->
                    <div>

                        <label class="block mb-2 font-bold text-gray-700 dark:text-gray-300">
                            تأكيد كلمة المرور
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-4 py-3">

                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="bg-gray-50 dark:bg-gray-900 px-8 py-5 border-t border-gray-100 dark:border-gray-700">

                <div class="flex gap-3">

                    <button
                        type="submit"
                        class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-2xl font-bold">

                        حفظ التعديلات

                    </button>

                    <button
                        type="button"
                        onclick="closeEditUserModal()"
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

function openEditUserModal(
    id,
    employee,
    name,
    email,
    role
){

    document
        .getElementById('editUserModal')
        .classList.remove('hidden');

    document
        .getElementById('edit_employee')
        .value = employee;

    document
        .getElementById('edit_name')
        .value = name;

    document
        .getElementById('edit_email')
        .value = email;

    document
        .getElementById('edit_role')
        .value = role;

    document
        .getElementById('editUserForm')
        .action = '/users/' + id;

}

function closeEditUserModal()
{
    document
        .getElementById('editUserModal')
        .classList.add('hidden');
}

</script>
