<div id="agentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all duration-300">

    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden scale-95 transition-transform duration-300" id="agentModalCard">

        <div class="border-b dark:border-gray-700 p-6 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <div>
                <h2 id="agentModalTitle" class="text-xl font-black text-gray-800 dark:text-white">
                    إضافة وكيل جديد
                </h2>
                <p class="text-gray-500 text-xs mt-1">
                    إدخال بيانات الوكيل وتحديد النطاق الجغرافي له
                </p>
            </div>
            <button type="button" onclick="closeAgentModal()" class="w-9 h-9 rounded-xl bg-red-100 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white transition flex items-center justify-center font-bold">
                ✕
            </button>
        </div>

        <form id="agentForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="text-sm font-bold block mb-2 text-gray-700 dark:text-gray-300">
                    اسم الوكيل <span class="text-red-500">*</span>
                </label>
                <input type="text" id="agentName" name="name" required placeholder="مثال: شركة النجم للسياحة"
                       class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition placeholder:text-gray-400 text-sm">
            </div>

            <div>
                <label class="text-sm font-bold block mb-2 text-gray-700 dark:text-gray-300">
                    رقم الهاتف
                </label>
                <input type="tel" id="agentPhone" name="phone" placeholder="مثال: 777XXXXXX"
                       class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition placeholder:text-gray-400 text-sm text-left" dir="ltr">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-bold block mb-2 text-gray-700 dark:text-gray-300">
                        الدولة
                    </label>
                    <input type="text" id="agentCountry" name="country" placeholder="اليمن"
                           class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition placeholder:text-gray-400 text-sm">
                </div>
                <div>
                    <label class="text-sm font-bold block mb-2 text-gray-700 dark:text-gray-300">
                        المدينة
                    </label>
                    <input type="text" id="agentCity" name="city" placeholder="صنعاء"
                           class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition placeholder:text-gray-400 text-sm">
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t dark:border-gray-700 mt-6">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-black shadow-lg shadow-blue-500/20 transition text-sm">
                    حفظ البيانات
                </button>
                <button type="button" onclick="closeAgentModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white py-3 rounded-2xl font-black transition text-sm">
                    إلغاء
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // فتح الـ Modal بتأثير سلس
    function openAgentModal() {
        const modal = document.getElementById('agentModal');
        const card = document.getElementById('agentModalCard');
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
        }, 10);
    }

    // إغلاق الـ Modal بتأثير سلس
    function closeAgentModal() {
        const modal = document.getElementById('agentModal');
        const card = document.getElementById('agentModalCard');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 150);
    }

    // تهيئة الـ Modal لعملية "إضافة وكيل جديد"
    function triggerCreateAgent() {
        const form = document.getElementById('agentForm');
        const title = document.getElementById('agentModalTitle');

        title.textContent = "إضافة وكيل جديد";
        form.setAttribute('action', "{{ route('agents.store') }}");

        // إزالة حقل الـ PUT الخاص بالتحديث إذا كان موجوداً من عملية تعديل سابقة
        const methodInput = form.querySelector('input[name="_method"]');
        if(methodInput) methodInput.remove();

        // إعادة تعيين وتفريغ الحقول
        form.reset();

        openAgentModal();
    }

    // تهيئة الـ Modal لعملية "تعديل بيانات وكيل" الحالي تلقائياً
    function triggerEditAgent(agentId, name, phone, country, city) {
        const form = document.getElementById('agentForm');
        const title = document.getElementById('agentModalTitle');

        title.textContent = "تعديل بيانات الوكيل: " + name;

        // تجهيز مسار الـ Update الخاص بلارافيل ديناميكياً
        let updateRoute = "{{ route('agents.update', ':id') }}";
        form.setAttribute('action', updateRoute.replace(':id', agentId));

        // إضافة حقل الـ PUT ليتوافق مع الـ Controller في لارافيل
        let methodInput = form.querySelector('input[name="_method"]');
        if(!methodInput) {
            methodInput = document.createElement('input');
            methodInput.setAttribute('type', 'hidden');
            methodInput.setAttribute('name', '_method');
            methodInput.setAttribute('value', 'PUT');
            form.appendChild(methodInput);
        }

        // تعبئة الحقول بالبيانات الممررة فوراً
        document.getElementById('agentName').value = name ?? '';
        document.getElementById('agentPhone').value = phone ?? '';
        document.getElementById('agentCountry').value = country ?? '';
        document.getElementById('agentCity').value = city ?? '';

        openAgentModal();
    }
</script>
