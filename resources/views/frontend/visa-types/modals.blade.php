{{-- ===========================
    CREATE / EDIT MODAL
=========================== --}}

<div id="visaTypeModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-900 w-full max-w-2xl rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700">

        {{-- HEADER --}}
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 id="modalTitle"
                class="text-xl font-bold text-gray-800 dark:text-white">
                إضافة نوع تأشيرة
            </h2>

            <button onclick="closeVisaTypeModal()"
                    class="text-gray-400 hover:text-red-500 text-xl">
                ✕
            </button>
        </div>

        {{-- FORM --}}
        <form id="visaTypeForm"
              method="POST"
              action="{{ route('visa-types.store') }}"
              class="p-6 space-y-6">

            @csrf

            <input type="hidden" name="_method" id="formMethod" value="POST">

            {{-- NAME --}}
            <div>
                <label class="block text-sm mb-2 text-gray-600 dark:text-gray-300">
                    اسم النوع *
                </label>
                <input type="text"
                       name="name"
                       id="typeName"
                       required
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                              focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- CATEGORY --}}
            <div>
                <label class="block text-sm mb-2 text-gray-600 dark:text-gray-300">
                    التصنيف
                </label>
                <input type="text"
                       name="category"
                       id="typeCategory"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                              focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- DURATION --}}
            <div>
                <label class="block text-sm mb-2 text-gray-600 dark:text-gray-300">
                    المدة الافتراضية (بالأيام)
                </label>
                <input type="number"
                       name="default_duration_days"
                       id="typeDuration"
                       min="1"
                       class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                              bg-white dark:bg-gray-800 text-gray-800 dark:text-white
                              focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            {{-- REQUIRES PACKAGE --}}
            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 p-4 rounded-2xl">

                <span class="text-sm text-gray-700 dark:text-gray-300">
                    يتطلب باقة؟
                </span>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="requires_package"
                           value="1"
                           id="typeRequiresPackage"
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-blue-500
                                dark:bg-gray-600 rounded-full peer
                                peer-checked:bg-blue-600 transition"></div>
                </label>

                <input type="hidden" name="requires_package" value="0">

            </div>

            {{-- STATUS --}}
            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 p-4 rounded-2xl">

                <span class="text-sm text-gray-700 dark:text-gray-300">
                    الحالة
                </span>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           name="status"
                           value="1"
                           id="typeStatus"
                           class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:ring-2 peer-focus:ring-green-500
                                dark:bg-gray-600 rounded-full peer
                                peer-checked:bg-green-600 transition"></div>
                </label>

                <input type="hidden" name="status" value="1">

            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-4 pt-4">

                <button type="button"
                        onclick="closeVisaTypeModal()"
                        class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-xl">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                    حفظ
                </button>

            </div>

        </form>

    </div>
</div>

{{-- ===========================
    JAVASCRIPT
=========================== --}}

<script>

function openCreateModal(){
    document.getElementById('modalTitle').innerText='إضافة نوع تأشيرة';
    document.getElementById('visaTypeForm').action="{{ route('visa-types.store') }}";
    document.getElementById('formMethod').value='POST';
    document.getElementById('visaTypeForm').reset();
    document.getElementById('visaTypeModal').classList.remove('hidden');
}

function editType(type){
    document.getElementById('modalTitle').innerText='تعديل نوع التأشيرة';
    document.getElementById('visaTypeForm').action='/dashboard/visa-types/'+type.id;
    document.getElementById('formMethod').value='PUT';

    document.getElementById('typeName').value=type.name;
    document.getElementById('typeCategory').value=type.category ?? '';
    document.getElementById('typeDuration').value=type.default_duration_days ?? '';

    document.getElementById('typeRequiresPackage').checked=type.requires_package;
    document.getElementById('typeStatus').checked=type.status;

    document.getElementById('visaTypeModal').classList.remove('hidden');
}

function closeVisaTypeModal(){
    document.getElementById('visaTypeModal').classList.add('hidden');
}

window.addEventListener('click',function(e){
    if(e.target.id==='visaTypeModal'){
        closeVisaTypeModal();
    }
});

</script>