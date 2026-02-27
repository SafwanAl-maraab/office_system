@extends('frontend.layouts.app')

@section('title','الموظفين')
@section('subtitle','إدارة موظفي الفرع')

@section('content')

    <div class="max-w-7xl mx-auto space-y-12">

        <!-- HEADER STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-3xl p-8 shadow-xl">
                <h4 class="text-sm opacity-80">إجمالي الموظفين</h4>
                <p class="text-3xl font-bold mt-2">{{ $employees->total() }}</p>
            </div>

            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-8 shadow-xl">
                <h4 class="text-sm text-gray-500">النشطين</h4>
                <p class="text-3xl font-bold mt-2 text-green-600">
                    {{ $employees->where('status',1)->count() }}
                </p>
            </div>

            <div
                class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-8 shadow-xl">
                <h4 class="text-sm text-gray-500">إجمالي الرواتب</h4>
                <p class="text-3xl font-bold mt-2 text-indigo-600">
                    {{ number_format($employees->sum('salary')) }}
                </p>
            </div>

        </div>

        <!-- TOP BAR -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

            <form method="GET" class="relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="بحث باسم أو رقم..."
                       class="w-72 px-5 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus:ring-4 focus:ring-blue-200 outline-none shadow-sm">
            </form>

            <button type="button"
                    data-open-employee
                    class="px-6 py-3 rounded-2xl bg-blue-600 text-white">
                + إضافة موظف
            </button>

        </div>

        <!-- EMPLOYEE CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            @forelse($employees as $employee)

                <div
                    class="group bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">

                    <!-- TOP -->
                    <div class="flex items-center justify-between">

                        <div
                            class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-500 text-white flex items-center justify-center text-xl font-bold shadow-md">
                            {{ strtoupper(substr($employee->full_name,0,1)) }}
                        </div>

                        <span class="px-3 py-1 text-xs rounded-full
                    {{ $employee->status ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400' : 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400' }}">
                    {{ $employee->status ? 'نشط' : 'موقوف' }}
                </span>

                    </div>

                    <!-- BODY -->
                    <div class="mt-5 space-y-2">

                        <h3 class="text-lg font-semibold">
                            {{ $employee->full_name }}
                        </h3>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            📱 {{ $employee->phone }}
                        </p>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            🧾 الدور: {{ $employee->role->name ?? '-' }}
                        </p>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            💰 الراتب: {{ number_format($employee->salary) }}
                        </p>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            📊 العمولة: {{ $employee->commission_percentage }}%
                        </p>

                    </div>

                    <!-- ACTIONS -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-800 flex justify-between">

                        <button type="button"
                                data-edit-employee
                                data-employee='@json($employee)'
                                class="text-xs px-3 py-1 bg-yellow-100 text-yellow-600 rounded-xl">
                            تعديل
                        </button>

                        {{--                        <form method="POST" action="{{ route('employees.destroy',$employee->id) }}">--}}
                        {{--                            @csrf--}}
                        {{--                            @method('DELETE')--}}
                        {{--                            <button onclick="confirmDelete({{ $employee->id }})"--}}
                        {{--                                    class="text-xs px-3 py-1 rounded-xl bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400">--}}
                        {{--                                حذف--}}
                        {{--                            </button>--}}
                        {{--                        </form>--}}


                        <button type="button"
                                onclick="confirmDelete({{ $employee->id }})"
                                class="text-xs px-3 py-1 rounded-xl bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400">
                            حذف
                        </button>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-20 text-gray-400">
                    لا يوجد موظفين حالياً
                </div>

            @endforelse

        </div>

        <!-- PAGINATION -->
        <div>
            {{ $employees->links() }}
        </div>

    </div>


    @include('frontend.clients.partials.modal')
    <!-- DELETE CONFIRM MODAL -->
    <div id="deleteModal"
         class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden items-center justify-center z-50 p-4">

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-md p-8 text-center">

            <h3 class="text-xl font-bold mb-4">تأكيد الحذف</h3>

            <p class="text-gray-500 dark:text-gray-400 mb-6">
                هل أنت متأكد أنك تريد حذف هذا الموظف؟
            </p>

            <div class="flex justify-center gap-4">

                <button onclick="closeDeleteModal()"
                        class="px-6 py-2 rounded-2xl bg-gray-200 dark:bg-gray-700">
                    إلغاء
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-6 py-2 rounded-2xl bg-red-600 text-white">
                        نعم، حذف
                    </button>
                </form>

            </div>

        </div>
    </div>


    <script>
        function confirmDelete(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');

            form.action = '/dashboard/employees/' + id;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

@endsection
