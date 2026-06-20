@extends('frontend.layouts.app')

@section('content')

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute top-[-10%] rtl:right-[-5%] ltr:left-[-5%] w-96 h-96 bg-blue-500/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] rtl:left-[-5%] ltr:right-[-5%] w-96 h-96 bg-purple-500/20 rounded-full blur-[120px]"></div>
    </div>

    <div class="py-12 relative z-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <div class="flex items-center gap-4 mb-8 px-4 sm:px-0">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center shadow-lg text-white">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-wide">
                        الملف الشخصي
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        إدارة إعدادات حسابك، معلوماتك الشخصية، وكلمة المرور.
                    </p>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                
                <div class="relative p-8 sm:p-10 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-[2.5rem] shadow-xl transition-all duration-300 hover:shadow-2xl">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                
                <div class="relative p-8 sm:p-10 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200 dark:border-slate-800 rounded-[2.5rem] shadow-xl transition-all duration-300 hover:shadow-2xl">
                    <div class="max-w-2xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="relative group mt-16">
                <div class="flex items-center justify-center gap-4 mb-8">
                    <div class="h-px bg-slate-300 dark:bg-slate-700 w-1/4"></div>
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 tracking-wider uppercase">المنطقة الخطرة</span>
                    <div class="h-px bg-slate-300 dark:bg-slate-700 w-1/4"></div>
                </div>

                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-400 rounded-[2.5rem] blur opacity-10 group-hover:opacity-30 transition duration-500"></div>
                
                <div class="relative p-8 sm:p-10 bg-rose-50/50 dark:bg-rose-950/20 backdrop-blur-xl border border-rose-200 dark:border-rose-900/50 rounded-[2.5rem] shadow-lg transition-all duration-300 hover:shadow-xl">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection