@extends('frontend.layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4 md:p-6 space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg">

        <div class="text-gray-500">
            إجمالي المستخدمين
        </div>

        <div class="text-4xl font-black mt-3 text-blue-600">
            {{ $totalUsers }}
        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg">

        <div class="text-gray-500">
            المدراء
        </div>

        <div class="text-4xl font-black mt-3 text-green-600">
            {{ $totalAdmins }}
        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg">

        <div class="text-gray-500">
            مدراء الفروع
        </div>

        <div class="text-4xl font-black mt-3 text-amber-600">
            {{ $totalManagers }}
        </div>

    </div>

    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg">

        <div class="text-gray-500">
            الحسابات النشطة
        </div>

        <div class="text-4xl font-black mt-3 text-purple-600">
            {{ $totalEmployees }}
        </div>

    </div>

</div>
<div class="bg-white dark:bg-gray-800 rounded-3xl p-5 shadow-lg">

    <div class="flex flex-col lg:flex-row gap-4">

        <form method="GET" class="flex-1">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="ابحث باسم المستخدم أو البريد..."
                class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white px-5 py-4">

        </form>

        <button
            onclick="openCreateUserModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-2xl font-bold">

            + إضافة مستخدم

        </button>

    </div>

</div>
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @foreach($users as $user)

<div class="bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">

        <div class="flex justify-between items-center">

            <div>

                <h2 class="font-black text-xl">
                    {{ $user->name }}
                </h2>

                <p class="text-blue-100">
                    {{ $user->email }}
                </p>

            </div>

            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-black">

                {{ strtoupper(substr($user->name,0,1)) }}

            </div>

        </div>

    </div>

    <div class="p-6 space-y-3">

        <div class="flex justify-between">

            <span class="text-gray-500">
                الموظف
            </span>

            <span class="font-bold dark:text-white">

                {{ $user->employee?->full_name }}

            </span>

        </div>

        <div class="flex justify-between">

            <span class="text-gray-500">
                الفرع
            </span>

            <span class="font-bold dark:text-white">

                {{ $user->employee?->branch?->name }}

            </span>

        </div>

        <div class="flex justify-between">

            <span class="text-gray-500">
                الدور
            </span>

            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

                {{ $user->roles->first()?->name }}

            </span>

        </div>

    </div>

    <div class="border-t border-gray-100 dark:border-gray-700 p-4 flex gap-2">
<button
onclick="openEditUserModal(
'{{ $user->id }}',
'{{ $user->employee?->full_name }}',
'{{ $user->name }}',
'{{ $user->email }}',
'{{ $user->roles->first()?->name }}'
)"
class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-3 rounded-xl font-bold">

تعديل

</button>

        <form
            action="{{ route('users.destroy',$user->id) }}"
            method="POST"
            class="flex-1">

            @csrf
            @method('DELETE')

            <button
                onclick="return confirm('حذف المستخدم؟')"
                class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl">

                حذف

            </button>

        </form>

    </div>

</div>

@endforeach
<div>

    {{ $users->links() }}

</div>
@include('frontend.users.parts.create')

@include('frontend.users.parts.edit')

@endsection