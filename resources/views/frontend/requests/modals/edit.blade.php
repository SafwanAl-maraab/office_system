<div id="editModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-xl p-6 relative">

        <button onclick="closeEditModal()"
                class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">
            ✕
        </button>

        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-6">
            تعديل الطلب
        </h2>

        <form method="POST" id="editForm" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- العميل --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    العميل
                </label>
                <select name="client_id"
                        id="editClient"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>

                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->full_name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- نوع الطلب --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    نوع الطلب
                </label>

                <select name="request_type_id"
                        id="editRequestType"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                               bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"
                        required>

                    @foreach($requestTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- ملاحظات --}}
            <div>
                <label class="block text-sm mb-1 text-gray-700 dark:text-gray-300">
                    ملاحظات
                </label>
                <textarea name="notes"
                          id="editNotes"
                          rows="3"
                          class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600
                                 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4">

                <button type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 rounded-lg bg-gray-400 hover:bg-gray-500 text-white">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white">
                    حفظ التعديل
                </button>

            </div>

        </form>

    </div>
</div>


<script>
    function openEditModal(id, clientId, typeId, notes) {

        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');

        form.action = '/dashboard/requests/' + id;

        document.getElementById('editClient').value = clientId;
        document.getElementById('editRequestType').value = typeId;
        document.getElementById('editNotes').value = notes ?? '';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
