@extends('frontend.layouts.app')

@section('title','الإعدادات')
@section('subtitle','تخصيص بيانات المكتب')

@section('content')

    <div class="max-w-6xl mx-auto space-y-12">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 p-10 text-white shadow-2xl">
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2">إعدادات المكتب</h2>
                <p class="text-white/80 text-sm">
                    قم بتحديث معلومات المكتب التي تظهر في النظام والفواتير.
                </p>
            </div>
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 font-medium flex items-center gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST"
              action="{{ route('settings.update') }}"
              enctype="multipart/form-data"
              class="space-y-10">

            @csrf

            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xl p-10 space-y-10">

                <div class="flex flex-col md:flex-row items-center gap-10">

                    <div class="relative group">
                        @if(isset($info) && $info->logo)
                            <img src="{{ asset('storage/'.$info->logo) }}"
                                 class="h-32 w-32 rounded-3xl object-cover shadow-2xl border border-white dark:border-gray-700">
                        @else
                            <div class="h-32 w-32 rounded-3xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center text-gray-500 text-sm shadow-inner">
                                لا يوجد شعار
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition rounded-3xl flex items-center justify-center text-white text-sm">
                            تغيير الشعار
                        </div>
                    </div>

                    <div class="flex-1 space-y-3">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            رفع شعار جديد
                        </label>

                        <input type="file"
                               name="logo"
                               class="block w-full text-sm file:mr-4 file:py-2 file:px-4
                           file:rounded-2xl file:border-0
                           file:text-sm file:font-semibold
                           file:bg-blue-50 file:text-blue-600
                           hover:file:bg-blue-100
                           dark:file:bg-gray-800 dark:file:text-gray-300">

                        <p class="text-xs text-gray-500">
                            يفضل أن يكون الشعار مربع بدقة جيدة (الحد الأقصى 2MB).
                        </p>
                        
                        @error('logo')
                            <p class="text-sm text-red-500 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            اسم المكتب <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="office_name"
                               value="{{ old('office_name', $info->office_name ?? '') }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                        @error('office_name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            البريد الإلكتروني
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', $info->email ?? '') }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            رقم المكتب الأول
                        </label>
                        <input type="text"
                               name="primary_phone"
                               value="{{ old('primary_phone', $info->primary_phone ?? '') }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                        @error('primary_phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            رقم المكتب الثاني
                        </label>
                        <input type="text"
                               name="secondary_phone"
                               value="{{ old('secondary_phone', $info->secondary_phone ?? '') }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                    </div>

                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        العنوان
                    </label>
                    <input type="text"
                           name="address"
                           value="{{ old('address', $info->address ?? '') }}"
                           class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        وصف قصير
                    </label>
                    <textarea name="short_description"
                              rows="4"
                              class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">{{ old('short_description', $info->short_description ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <input type="text"
                           name="facebook"
                           placeholder="رابط فيسبوك"
                           value="{{ old('facebook', $info->facebook ?? '') }}"
                           class="px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 transition outline-none shadow-sm">

                    <input type="text"
                           name="whatsapp"
                           placeholder="رقم واتساب"
                           value="{{ old('whatsapp', $info->whatsapp ?? '') }}"
                           class="px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 transition outline-none shadow-sm">

                    <input type="text"
                           name="website"
                           placeholder="الموقع الإلكتروني"
                           value="{{ old('website', $info->website ?? '') }}"
                           class="px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 transition outline-none shadow-sm">

                </div>

            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-10 py-4 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-xl hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    حفظ التعديلات
                </button>
            </div>

        </form>

    </div>

@endsection