@extends('frontend.layouts.app')

@section('content')

    <div class="p-4 space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                أنواع الطلبات
            </h1>

            <button onclick="openCreateModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow">
                + إضافة نوع
            </button>
        </div>

{{--        --}}{{-- Success Message --}}
{{--        @if(session('success'))--}}
{{--            <div class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300--}}
{{--            p-3 rounded-xl text-sm">--}}
{{--                {{ session('success') }}--}}
{{--            </div>--}}
{{--        @endif--}}

        {{-- Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

            @forelse($types as $type)

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow
                        border border-gray-200 dark:border-gray-700
                        p-5 space-y-4 transition hover:shadow-lg">

                    <div class="flex justify-between items-center">
                        <h2 class="font-bold text-gray-800 dark:text-gray-100">
                            {{ $type->name }}
                        </h2>

                        <span class="text-xs px-3 py-1 rounded-full
                        {{ $type->service_category === 'passport'
                            ? 'bg-blue-100 text-blue-600'
                            : 'bg-purple-100 text-purple-600' }}">
                        {{ $type->service_category }}
                    </span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 space-y-1">
                        <div>السعر: <strong>{{ $type->price }}</strong></div>
                        <div>العملة: <strong>{{ $type->currency->code }}</strong></div>
                    </div>

                    <div class="flex gap-2 pt-3">

                        <button
                            onclick="openEditModal(
                            {{ $type->id }},
                            '{{ addslashes($type->name) }}',
                            '{{ $type->service_category }}',
                            {{ $type->price }},
                            {{ $type->currency_id }}
                        )"
                            class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-2 rounded-xl">
                            تعديل
                        </button>

                        <button
                            onclick="openDeleteModal({{ $type->id }})"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded-xl">
                            حذف
                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-10">
                    لا يوجد أنواع طلبات حالياً
                </div>

            @endforelse

        </div>

    </div>

    {{-- ================== MODALS ================== --}}

    {{-- Create --}}
    <div id="createTypeModal"
         class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl p-6 relative">

            <button onclick="closeCreateModal()"
                    class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">✕</button>

            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-100">
                إضافة نوع طلب
            </h2>

            <form method="POST"
                  action="{{ route('dashboard.request-types.store') }}"
                  class="space-y-4">

                @csrf

                <input type="text" name="name" placeholder="اسم النوع"
                       class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                       required>

                <select name="service_category"
                        class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                        required>
                    <option value="passport">جواز</option>
                    <option value="card">بطاقة</option>
                </select>

                <input type="number" name="price" placeholder="السعر"
                       class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                       required>

                <select name="currency_id"
                        class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                        required>

                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">
                            {{ $currency->code }}
                        </option>
                    @endforeach

                </select>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button"
                            onclick="closeCreateModal()"
                            class="px-4 py-2 rounded-xl bg-gray-400 text-white">
                        إلغاء
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white">
                        حفظ
                    </button>
                </div>

            </form>

        </div>
    </div>


    {{-- Edit --}}
    <div id="editTypeModal"
         class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-xl p-6 relative">

            <button onclick="closeEditModal()"
                    class="absolute top-3 left-3 text-gray-500 hover:text-red-500 text-xl">✕</button>

            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-gray-100">
                تعديل النوع
            </h2>

            <form method="POST" id="editForm" class="space-y-4">
                @csrf
                @method('PUT')

                <input type="text" name="name" id="editName"
                       class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                       required>

                <select name="service_category" id="editCategory"
                        class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                        required>
                    <option value="passport">جواز</option>
                    <option value="card">بطاقة</option>
                </select>

                <input type="number" name="price" id="editPrice"
                       class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                       required>

                <select name="currency_id" id="editCurrency"
                        class="w-full px-3 py-2 rounded-xl border dark:bg-gray-900 dark:border-gray-600"
                        required>
                    @foreach($currencies as $currency)
                        <option value="{{ $currency->id }}">
                            {{ $currency->code }}
                        </option>
                    @endforeach
                </select>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button"
                            onclick="closeEditModal()"
                            class="px-4 py-2 rounded-xl bg-gray-400 text-white">
                        إلغاء
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white">
                        تحديث
                    </button>
                </div>
            </form>

        </div>
    </div>


    {{-- Delete --}}
    <div id="deleteTypeModal"
         class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white dark:bg-gray-800 w-full max-w-sm rounded-2xl shadow-xl p-6 text-center">

            <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                تأكيد الحذف
            </h2>

            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')

                <div class="flex justify-center gap-4">
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="px-4 py-2 rounded-xl bg-gray-400 text-white">
                        إلغاء
                    </button>

                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white">
                        حذف
                    </button>
                </div>
            </form>

        </div>
    </div>


    <script>
        function openCreateModal() {
            document.getElementById('createTypeModal').classList.replace('hidden','flex');
        }
        function closeCreateModal() {
            document.getElementById('createTypeModal').classList.replace('flex','hidden');
        }

        function openEditModal(id,name,category,price,currency) {
            const form = document.getElementById('editForm');
            form.action = '/dashboard/request-types/' + id;

            document.getElementById('editName').value = name;
            document.getElementById('editCategory').value = category;
            document.getElementById('editPrice').value = price;
            document.getElementById('editCurrency').value = currency;

            document.getElementById('editTypeModal').classList.replace('hidden','flex');
        }
        function closeEditModal() {
            document.getElementById('editTypeModal').classList.replace('flex','hidden');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action =
                '/dashboard/request-types/' + id;

            document.getElementById('deleteTypeModal').classList.replace('hidden','flex');
        }
        function closeDeleteModal() {
            document.getElementById('deleteTypeModal').classList.replace('flex','hidden');
        }
    </script>

@endsection
