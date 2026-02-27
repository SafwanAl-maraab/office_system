@extends('frontend.layouts.app')

@section('title','الإعدادات')
@section('subtitle','تخصيص بيانات المكتب')

@section('content')

    <div class="max-w-6xl mx-auto space-y-12">

        <!-- HEADER SECTION -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 p-10 text-white shadow-2xl">

            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2">إعدادات المكتب</h2>
                <p class="text-white/80 text-sm">
                    قم بتحديث معلومات المكتب التي تظهر في النظام والفواتير.
                </p>
            </div>

            <!-- Glow -->
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

        </div>

        <!-- SETTINGS CARD -->
        <form method="POST"
              action="{{ route('settings.update') }}"
              enctype="multipart/form-data"
              class="space-y-10">

            @csrf

            <!-- GLASS CARD -->
            <div class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-xl border border-gray-200 dark:border-gray-800 rounded-3xl shadow-xl p-10 space-y-10">

                <!-- LOGO SECTION -->
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

                        <!-- Hover Overlay -->
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
                            يفضل أن يكون الشعار مربع بدقة جيدة.
                        </p>
                    </div>

                </div>

                <!-- GRID INFO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            اسم المكتب
                        </label>
                        <input type="text"
                               name="office_name"
                               value="{{ $info->office_name ?? '' }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            البريد الإلكتروني
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ $info->email ?? '' }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            رقم المكتب الأول
                        </label>
                        <input type="text"
                               name="primary_phone"
                               value="{{ $info->primary_phone ?? '' }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            رقم المكتب الثاني
                        </label>
                        <input type="text"
                               name="secondary_phone"
                               value="{{ $info->secondary_phone ?? '' }}"
                               class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                    </div>

                </div>

                <!-- ADDRESS -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        العنوان
                    </label>
                    <input type="text"
                           name="address"
                           value="{{ $info->address ?? '' }}"
                           class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">
                </div>

                <!-- DESCRIPTION -->
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-600 dark:text-gray-400">
                        وصف قصير
                    </label>
                    <textarea name="short_description"
                              rows="4"
                              class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 transition outline-none shadow-sm">{{ $info->short_description ?? '' }}</textarea>
                </div>

                <!-- SOCIAL LINKS -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <input type="text"
                           name="facebook"
                           placeholder="رابط فيسبوك"
                           value="{{ $info->facebook ?? '' }}"
                           class="px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 transition outline-none shadow-sm">

                    <input type="text"
                           name="whatsapp"
                           placeholder="رقم واتساب"
                           value="{{ $info->whatsapp ?? '' }}"
                           class="px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 transition outline-none shadow-sm">

                    <input type="text"
                           name="website"
                           placeholder="الموقع الإلكتروني"
                           value="{{ $info->website ?? '' }}"
                           class="px-5 py-4 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 focus:ring-4 focus:ring-blue-200 transition outline-none shadow-sm">

                </div>

            </div>

            <!-- SAVE BUTTON -->
            <div class="flex justify-end">
                <button type="submit"
                        class="px-10 py-4 rounded-3xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold shadow-xl hover:scale-[1.02] active:scale-95 transition-all duration-200">
                    حفظ التعديلات
                </button>
            </div>

        </form>

    </div>

@endsection
