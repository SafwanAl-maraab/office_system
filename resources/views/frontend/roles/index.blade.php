@extends('frontend.layouts.app')

@section('title', 'إدارة الأدوار والصلاحيات')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        @php
            $permissionsMap = [
                '🏠 لوحة التحكم' => [
                    'view.dashboard' => 'عرض لوحة التحكم',
                ],
                '👥 العملاء' => [
                    'view.clients' => 'عرض العملاء',
                    'create.clients' => 'إضافة عميل',
                    'update.clients' => 'تعديل عميل',
                    'delete.clients' => 'حذف عميل',
                    'statement.clients' => 'كشف حساب العميل',
                ],
                '📄 الطلبات والجوازات' => [
                    'view.requests' => 'عرض الطلبات',
                    'create.requests' => 'إنشاء طلب',
                    'update.requests' => 'تعديل طلب',
                    'delete.requests' => 'حذف طلب',
                    'change-status.requests' => 'تغيير حالة الطلب',
                    'attach-travel.requests' => 'ربط الطلب برحلة',
                ],
                '🛂 التأشيرات' => [
                    'view.visas' => 'عرض التأشيرات',
                    'create.visas' => 'إضافة تأشيرة',
                    'update.visas' => 'تعديل تأشيرة',
                    'delete.visas' => 'حذف تأشيرة',
                    'change-status.visas' => 'تغيير الحالة',
                    'attach-trip-group.visas' => 'ربط بحملة',
                    'attach-package.visas' => 'ربط بباقة',
                    'payment.visas' => 'تسجيل دفعة',
                ],
                '✈️ الرحلات' => [
                    'view.trips' => 'عرض الرحلات',
                    'create.trips' => 'إضافة رحلة',
                    'update.trips' => 'تعديل رحلة',
                    'delete.trips' => 'حذف رحلة',
                ],
                '🕋 الحملات والمجموعات' => [
                    'view.trip-groups' => 'عرض الحملات',
                    'create.trip-groups' => 'إضافة حملة',
                    'update.trip-groups' => 'تعديل حملة',
                    'delete.trip-groups' => 'حذف حملة',
                    'attach-bus.trip-groups' => 'ربط باص بالحملة',
                ],
                '🎫 الحجوزات' => [
                    'view.bookings' => 'عرض الحجوزات',
                    'create.bookings' => 'إنشاء حجز',
                    'update.bookings' => 'تعديل حجز',
                    'delete.bookings' => 'حذف حجز',
                    'change-status.bookings' => 'تغيير الحالة',
                    'payment.bookings' => 'تسجيل دفعة',
                ],
                '🚌 الباصات' => [
                    'view.buses' => 'عرض الباصات',
                    'create.buses' => 'إضافة باص',
                    'update.buses' => 'تعديل باص',
                    'delete.buses' => 'حذف باص',
                ],
                '👨‍✈️ السائقون' => [
                    'view.drivers' => 'عرض السائقين',
                    'create.drivers' => 'إضافة سائق',
                    'update.drivers' => 'تعديل سائق',
                    'delete.drivers' => 'حذف سائق',
                ],
                '🔄 توزيع الباصات' => [
                    'view.bus-assignments' => 'عرض التوزيعات',
                    'create.bus-assignments' => 'إنشاء توزيع',
                    'update.bus-assignments' => 'تعديل توزيع',
                    'delete.bus-assignments' => 'حذف توزيع',
                ],
                '💰 الخزائن' => [
                    'view.cashboxes' => 'عرض الخزائن',
                    'create.cashboxes' => 'إنشاء خزنة',
                    'update.cashboxes' => 'تعديل خزنة',
                    'transactions.cashboxes' => 'عرض الحركات',
                ],
                '💱 أسعار الصرف' => [
                    'view.exchange-rates' => 'عرض الأسعار',
                    'create.exchange-rates' => 'إضافة سعر',
                    'update.exchange-rates' => 'تعديل سعر',
                ],
                '📥 الإيرادات' => [
                    'view.incomes' => 'عرض الإيرادات',
                    'create.incomes' => 'إضافة إيراد',
                ],
                '📤 المصروفات' => [
                    'view.expenses' => 'عرض المصروفات',
                    'create.expenses' => 'إضافة مصروف',
                ],
                '🧾 الفواتير' => [
                    'view.invoices' => 'عرض الفواتير',
                    'refund.invoices' => 'إنشاء مرتجع',
                    'cancel.invoices' => 'إلغاء فاتورة',
                    'pdf.invoices' => 'طباعة PDF',
                ],
                '💳 المدفوعات' => [
                    'view.payments' => 'عرض المدفوعات',
                    'create.payments' => 'إضافة دفعة',
                    'delete.payments' => 'حذف دفعة',
                ],
                '🤝 الوكلاء' => [
                    'view.agents' => 'عرض الوكلاء',
                    'create.agents' => 'إضافة وكيل',
                    'update.agents' => 'تعديل وكيل',
                    'delete.agents' => 'حذف وكيل',
                    'payment.agents' => 'دفع للوكيل',
                    'statement.agents' => 'كشف حساب',
                    'export.agents' => 'تصدير التقارير',
                ],
                '📊 التقارير' => [
                    'view.financial-reports' => 'عرض التقارير المالية',
                    'export.financial-reports' => 'تصدير التقارير',
                    'view.profit-analysis' => 'تحليل الأرباح',
                    'export.profit-analysis' => 'تصدير تحليل الأرباح',
                ],
                '⚙️ الإعدادات' => [
                    'view.settings' => 'عرض الإعدادات',
                    'update.settings' => 'تعديل الإعدادات',
                ],
                '👨‍💼 المستخدمون' => [
                    'view.users' => 'عرض المستخدمين',
                    'create.users' => 'إضافة مستخدم',
                    'update.users' => 'تعديل مستخدم',
                    'delete.users' => 'حذف مستخدم',
                ],
                '👔 الموظفون' => [
                    'view.employees' => 'عرض الموظفين',
                    'create.employees' => 'إضافة موظف',
                    'update.employees' => 'تعديل موظف',
                    'delete.employees' => 'حذف موظف',
                ],
                '🔐 الملف الشخصي' => [
                    'view.profile' => 'عرض الملف الشخصي',
                    'update.profile' => 'تعديل الملف الشخصي',
                    'delete.profile' => 'حذف الحساب',
                ],
            ];

            // تحويل المصفوفة الشجرية إلى مصفوفة مسطحة ومباشرة لتسريع عملية جلب الاسم العربي لاحقاً
            $flatPermissions = [];
            foreach ($permissionsMap as $group => $items) {
                foreach ($items as $enKey => $arLabel) {
                    $flatPermissions[$enKey] = $arLabel;
                }
            }
        @endphp

        <div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-black text-gray-800 dark:text-white">
                    إدارة الأدوار والصلاحيات
                </h1>
                <p class="text-gray-500 mt-2">
                    التحكم الكامل في صلاحيات المستخدمين داخل النظام
                </p>
            </div>

            <button
                onclick="triggerCreateMode()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl shadow-lg font-bold transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-plus text-lg"></i>
                إضافة دور جديد
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">إجمالي الأدوار</p>
                        <h2 class="text-3xl font-black mt-2 text-gray-800 dark:text-white">{{ $roles->count() }}</h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">إجمالي الصلاحيات</p>
                        <h2 class="text-3xl font-black mt-2 text-gray-800 dark:text-white">{{ $permissions->count() }}</h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                        <i class="fa-solid fa-key text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">المستخدمون</p>
                        <h2 class="text-3xl font-black mt-2 text-gray-800 dark:text-white">{{ $usersCount ?? 0 }}</h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                        <i class="fa-solid fa-users text-orange-600 dark:text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">الأدوار النشطة</p>
                        <h2 class="text-3xl font-black mt-2 text-gray-800 dark:text-white">{{ $roles->count() }}</h2>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($roles as $role)
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition duration-300 overflow-hidden flex flex-col justify-between">

                    <div class="p-6 border-b dark:border-gray-700">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <h3 class="text-xl font-black text-blue-600 dark:text-blue-400">
                                    {{ $role->name }}
                                </h3>
                                <p class="text-gray-400 text-xs mt-1 font-mono">
                                    معرف الدور #{{ $role->id }}
                                </p>
                            </div>
                            <span class="bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs px-3 py-1.5 rounded-xl font-bold shrink-0">
                                {{ $role->permissions->count() }} صلاحية
                            </span>
                        </div>
                    </div>

                    <div class="p-6 flex-1">
                        <div class="flex flex-wrap gap-1.5 min-h-[90px] content-start">
                            @foreach($role->permissions->take(6) as $permission)
                                <span class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs px-3 py-1.5 rounded-xl border dark:border-gray-600 font-medium shadow-sm">
                                    {{ $flatPermissions[$permission->name] ?? $permission->name }}
                                </span>
                            @endforeach

                            @if($role->permissions->count() > 6)
                                <span class="bg-blue-100 text-blue-800 dark:bg-blue-950/50 dark:text-blue-300 text-xs px-3 py-1.5 rounded-xl font-bold">
                                    + {{ $role->permissions->count() - 6 }} أخرى
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t dark:border-gray-700 p-4 bg-gray-50/50 dark:bg-gray-900/10 flex gap-3">
                        <button
                            onclick="triggerEditMode({{ $role->id }}, '{{ $role->name }}', {{ json_encode($role->permissions->pluck('name')) }})"
                            class="flex-1 bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-600 dark:hover:text-white py-3 rounded-xl font-bold text-sm transition shadow-sm">
                            تعديل
                        </button>

                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="flex-1" onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور نهائياً؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-50 hover:bg-red-600 text-red-700 hover:text-white dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white py-3 rounded-xl font-bold text-sm transition shadow-sm">
                                حذف
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-16 text-center border shadow-sm">
                        <i class="fa-solid fa-user-shield text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <h3 class="text-xl font-black mb-2 dark:text-white">لا توجد أدوار مسجلة</h3>
                        <p class="text-gray-500 dark:text-gray-400">قم بإضافة أول دور وتعيين الصلاحيات له للبدء</p>
                    </div>
                </div>
            @endempty
        </div>

    </div>

    @include('frontend.roles.partials.modal')

    <script>
        // دالة تحضير الـ Modal لعملية "إنشاء دور جديد"
        function triggerCreateMode() {
            const form = document.getElementById('roleForm');
            const title = document.getElementById('modalTitle');
            const nameInput = document.getElementById('roleName');

            // تعيين عنوان ومسار الإرسال
            title.textContent = "إضافة دور جديد";
            form.setAttribute('action', "{{ route('roles.store') }}");

            // إزالة حقل الـ _method الخاص بالتحديث إن وُجد سابقاً
            const methodInput = form.querySelector('input[name="_method"]');
            if(methodInput) methodInput.remove();

            // تفريغ المدخلات وإلغاء تحديد كل الـ Checkboxes
            nameInput.value = "";
            if (typeof window.unselectAllPermissions === "function") {
                window.unselectAllPermissions();
            }

            openRoleModal(); // فتح الـ Modal
        }

        // دالة تحضير الـ Modal لعملية "التعديل" الفورية بالـ JavaScript
        function triggerEditMode(roleId, roleName, assignedPermissions) {
            const form = document.getElementById('roleForm');
            const title = document.getElementById('modalTitle');
            const nameInput = document.getElementById('roleName');

            // تعيين عنوان ومسار الإرسال للتعديل بشكل ديناميكي
            title.textContent = "تعديل بيانات الدور: " + roleName;

            // بناء المسار البرمجي لـ Update ديناميكياً
            let updateRoute = "{{ route('roles.update', ':id') }}";
            form.setAttribute('action', updateRoute.replace(':id', roleId));

            // التأكد من إضافة حقل الـ PUT ليتوافق مع لارافيل
            let methodInput = form.querySelector('input[name="_method"]');
            if(!methodInput) {
                methodInput = document.createElement('input');
                methodInput.setAttribute('type', 'hidden');
                methodInput.setAttribute('name', '_method');
                methodInput.setAttribute('value', 'PUT');
                form.appendChild(methodInput);
            }

            // تعيين اسم الدور الحالي في حقل الإدخال
            nameInput.value = roleName;

            // تصفير كل الخانات أولاً قبل البدء بالتحديد الجديد
            if (typeof window.unselectAllPermissions === "function") {
                window.unselectAllPermissions();
            }

            // تحديد الـ Checkboxes المطابقة للصلاحيات الممررة
            const checkboxes = document.querySelectorAll('.permission-checkbox');
            checkboxes.forEach(cb => {
                if(assignedPermissions.includes(cb.value)) {
                    cb.checked = true;
                    // تفعيل الستايل البصري النشط للـ Item (إذا تم استخدام سكربت التحسين السابق)
                    const labelItem = cb.closest('.permission-item');
                    if (labelItem) {
                        labelItem.classList.add('bg-blue-50', 'border-blue-400', 'dark:bg-blue-950/40', 'dark:border-blue-500');
                    }
                }
            });

            // تحديث عداد الصلاحيات المختارة الإجمالي
            const selectedCountEl = document.getElementById('selectedCount');
            if (selectedCountEl) {
                selectedCountEl.textContent = assignedPermissions.length;
            }

            openRoleModal(); // فتح الـ Modal بالتأثير الإنسيابي
        }
    </script>

@endsection
