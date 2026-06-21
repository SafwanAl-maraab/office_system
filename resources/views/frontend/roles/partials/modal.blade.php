<div id="roleModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all duration-300">

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-6xl max-h-[95vh] overflow-hidden flex flex-col scale-95 transition-transform duration-300" id="modalCard">

        <div class="border-b p-6 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <div>
                <h2 id="modalTitle" class="text-2xl font-black text-gray-800 dark:text-white">
                    إضافة دور جديد
                </h2>
                <p class="text-gray-500 text-sm mt-1">
                    إدارة صلاحيات المستخدمين داخل النظام
                </p>
            </div>
            <button type="button" onclick="closeRoleModal()" class="w-10 h-10 rounded-xl bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center font-bold">
                ✕
            </button>
        </div>

        <form id="roleForm" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf

            <div class="grid lg:grid-cols-3 gap-0 flex-1 overflow-hidden">

                <div class="lg:col-span-1 border-l dark:border-gray-700 p-6 bg-gray-50/30 dark:bg-gray-900/10 overflow-y-auto">
                    <div class="space-y-5">
                        <div>
                            <label class="font-bold block mb-2 text-gray-700 dark:text-gray-300">
                                اسم الدور
                            </label>
                            <input type="text" id="roleName" name="name" required class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <div>
                            <label class="font-bold block mb-2 text-gray-700 dark:text-gray-300">
                                بحث في الصلاحيات
                            </label>
                            <input type="text" id="permissionSearch" placeholder="ابحث عن صلاحية..." class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-950/40 rounded-2xl p-4 border border-blue-100 dark:border-blue-900/50 flex justify-between items-center">
                            <div class="text-sm font-bold text-blue-800 dark:text-blue-300">
                                الصلاحيات المختارة
                            </div>
                            <div id="selectedCount" class="text-3xl font-black text-blue-600 dark:text-blue-400">
                                0
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="selectAllPermissions()" class="w-full bg-green-100 hover:bg-green-200 text-green-700 dark:bg-green-900/30 dark:text-green-400 py-3 rounded-2xl font-bold transition">
                                تحديد الكل
                            </button>
                            <button type="button" onclick="unselectAllPermissions()" class="w-full bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900/30 dark:text-red-400 py-3 rounded-2xl font-bold transition">
                                إلغاء الكل
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 p-6 overflow-y-auto max-h-[65vh] dark:bg-gray-800">
                    <div class="space-y-4" id="permissionsContainer">
                        @foreach($permissionsMap as $groupName => $groupPermissions)
                            <div class="permission-group border dark:border-gray-700 rounded-3xl overflow-hidden bg-white dark:bg-gray-800 transition-all duration-200">

                                <button type="button" class="accordion-btn w-full flex justify-between items-center px-5 py-4 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600/80 transition text-gray-800 dark:text-white">
                                    <span class="font-black group-title">{{ $groupName }}</span>
                                    <div class="flex items-center gap-3">
                                        <span class="bg-gray-200 dark:bg-gray-600 px-2.5 py-0.5 rounded-full text-xs font-bold">
                                            {{ count($groupPermissions) }}
                                        </span>
                                        <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </button>

                                <div class="accordion-body hidden p-5 border-t dark:border-gray-700 bg-white dark:bg-gray-800/50">
                                    <div class="grid md:grid-cols-2 gap-3">
                                        @foreach($groupPermissions as $key => $label)
                                            @if($permissions->contains('name',$key))
                                                <label class="permission-item flex items-center gap-3 p-3 border dark:border-gray-700 rounded-2xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                                    <input type="checkbox" name="permissions[]" value="{{ $key }}" class="permission-checkbox w-5 h-5 rounded-lg text-blue-600 border-gray-300 focus:ring-blue-500">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 permission-label">
                                                        {{ $label }}
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="border-t dark:border-gray-700 p-6 flex gap-3 bg-gray-50 dark:bg-gray-800/50">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black shadow-lg shadow-blue-500/20 transition">
                    حفظ الدور
                </button>
                <button type="button" onclick="closeRoleModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white py-3 rounded-2xl font-black transition">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- 1. التحكم بفتح وإغلاق الـ Modal بتأثير سلس ---
    function openRoleModal() {
        const modal = document.getElementById('roleModal');
        const card = document.getElementById('modalCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }

    function closeRoleModal() {
        const modal = document.getElementById('roleModal');
        const card = document.getElementById('modalCard');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    document.addEventListener('DOMContentLoaded', function () {
        // --- 2. الـ Accordion الفعال ---
        const accordionBtns = document.querySelectorAll('.accordion-btn');

        accordionBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const body = this.nextElementSibling;
                const arrow = this.querySelector('svg');

                // فتح/إغلاق الحالي
                if (body.classList.contains('hidden')) {
                    body.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                } else {
                    body.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                }
            });
        });

        // --- 3. تحديث العداد الذكي وستايل الصلاحيات الفوري ---
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const selectedCountEl = document.getElementById('selectedCount');

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            selectedCountEl.textContent = checkedCount;

            // تغيير العداد إلى اللون الأخضر إذا كان هناك شيء محدد لتنبيه المستخدم بصرياً
            if(checkedCount > 0) {
                selectedCountEl.classList.remove('text-blue-600');
                selectedCountEl.classList.add('text-green-600');
            } else {
                selectedCountEl.classList.remove('text-green-600');
                selectedCountEl.classList.add('text-blue-600');
            }
        }

        function handleCheckboxStyle(checkbox) {
            const labelItem = checkbox.closest('.permission-item');
            if (checkbox.checked) {
                labelItem.classList.add('bg-blue-50', 'border-blue-400', 'dark:bg-blue-950/40', 'dark:border-blue-500');
            } else {
                labelItem.classList.remove('bg-blue-50', 'border-blue-400', 'dark:bg-blue-950/40', 'dark:border-blue-500');
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                handleCheckboxStyle(this);
                updateSelectedCount();
            });
        });

        // دوال تحديد وإلغاء الكل العالمية داخل النطاق
        window.selectAllPermissions = function() {
            checkboxes.forEach(cb => {
                // نحدد فقط الصلاحيات الظاهرة حالياً (إذا كان المستخدم يبحث)
                const item = cb.closest('.permission-item');
                const group = cb.closest('.permission-group');
                if (!group.classList.contains('hidden') && !item.classList.contains('hidden')) {
                    cb.checked = true;
                    handleCheckboxStyle(cb);
                }
            });
            updateSelectedCount();
        }

        window.unselectAllPermissions = function() {
            checkboxes.forEach(cb => {
                cb.checked = false;
                handleCheckboxStyle(cb);
            });
            updateSelectedCount();
        }

        // --- 4. محرك البحث الذكي والمباشر الفوري ---
        const searchInput = document.getElementById('permissionSearch');

        searchInput.addEventListener('input', function () {
            const query = this.value.trim().toLowerCase();
            const groups = document.querySelectorAll('.permission-group');

            groups.forEach(group => {
                const groupTitle = group.querySelector('.group-title').textContent.toLowerCase();
                const items = group.querySelectorAll('.permission-item');
                const body = group.querySelector('.accordion-body');
                const arrow = group.querySelector('.accordion-btn svg');

                let matchesInGroup = 0;

                items.forEach(item => {
                    const label = item.querySelector('.permission-label').textContent.toLowerCase();

                    // إذا كان اسم المجموعة أو اسم الصلاحية يطابق البحث
                    if (label.includes(query) || groupTitle.includes(query)) {
                        item.classList.remove('hidden');
                        matchesInGroup++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // إذا وجدنا تطابق، نظهر المجموعة ونفتح الـ Accordions تلقائياً لتسهيل الرؤية
                if (matchesInGroup > 0 || query === '') {
                    group.classList.remove('hidden');
                    if (query !== '') {
                        body.classList.remove('hidden');
                        arrow.classList.add('rotate-180');
                    } else {
                        // إعادة الوضع الافتراضي عند مسح البحث
                        body.classList.add('hidden');
                        arrow.classList.remove('rotate-180');
                    }
                } else {
                    group.classList.add('hidden');
                }
            });
        });
    });
</script>
