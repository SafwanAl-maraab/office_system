<!DOCTYPE html>
<html lang="ar" dir="rtl" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-ar="Travel ERP | تعيين كلمة مرور جديدة" data-en="Travel ERP | Reset Password">Travel ERP | تعيين كلمة مرور جديدة</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap');

        body { font-family: 'Tajawal', sans-serif; }

        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-enter { animation: fadeSlideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        /* توهج وردي/روبي يتناسب مع إعادة التعيين */
        .glow-effect { box-shadow: 0 0 80px -20px rgba(244, 63, 94, 0.3); }
        .dark .glow-effect { box-shadow: 0 0 80px -20px rgba(244, 63, 94, 0.5); }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #f8fafc inset !important;
            -webkit-text-fill-color: #0f172a !important;
            border-color: rgba(0,0,0,0.1) !important;
        }
        .dark input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
            -webkit-text-fill-color: #f8fafc !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
    </style>
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 sm:p-6 lg:p-10 overflow-hidden bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 transition-colors duration-500">

    <!-- أزرار التحكم -->
    <div class="fixed top-6 left-6 right-6 z-50 flex justify-between items-center px-4">
        <div class="flex gap-3">
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-rose-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            <button onclick="toggleLang()" class="px-4 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-rose-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <span id="lang-text">EN</span>
            </button>
        </div>
    </div>

    <!-- الخلفية -->
    <div class="fixed inset-0 z-0">
        <!-- صورة تعبر عن الأمان والتجديد -->
        <img src="https://images.unsplash.com/photo-1614064641913-6b70a1a3fcb8?q=80&w=2070&auto=format&fit=crop" alt="Background" class="w-full h-full object-cover opacity-60 dark:opacity-30 scale-105 transition-transform duration-[20s] hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-tl from-white/95 via-slate-100/90 to-rose-50/80 dark:from-slate-950/95 dark:via-slate-900/90 dark:to-rose-950/80 backdrop-blur-[4px] transition-colors duration-500"></div>
    </div>

    <!-- البطاقة المركزية -->
    <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 rounded-[2.5rem] overflow-hidden bg-white/60 dark:bg-white/5 backdrop-blur-2xl border border-white/40 dark:border-white/10 shadow-2xl dark:shadow-none animate-enter glow-effect transition-colors duration-500">

        <!-- القسم الأيمن: نموذج إعادة تعيين كلمة المرور -->
        <div class="lg:col-span-5 flex flex-col justify-center bg-white/80 dark:bg-slate-950/60 p-8 sm:p-10 lg:p-12 relative z-20 transition-colors duration-500">
            
            <div class="mb-6 text-center lg:text-start rtl:lg:text-right ltr:lg:text-left">
                <!-- أيقونة تعبر عن القفل/المفتاح -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center mx-auto lg:mx-0 mb-4 shadow-lg text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>

                <h1 class="text-3xl font-bold text-slate-800 dark:text-white tracking-wide mb-2 transition-colors" data-ar="كلمة مرور جديدة" data-en="New Password">كلمة مرور جديدة</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-light transition-colors" data-ar="قم بتعيين كلمة مرور قوية لتأمين حسابك." data-en="Set a strong password to secure your account.">قم بتعيين كلمة مرور قوية لتأمين حسابك.</p>
            </div>

            <!-- نموذج إعادة التعيين -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- توكن استعادة كلمة المرور المخفي -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- حقل البريد الإلكتروني -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="البريد الإلكتروني" data-en="Email Address">البريد الإلكتروني</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <!-- غالباً يأتي البريد الإلكتروني معبأ مسبقاً من الرابط -->
                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="user@example.com" data-ar-ph="user@example.com" data-en-ph="user@example.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                </div>

                <!-- حقل كلمة المرور الجديدة -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="كلمة المرور الجديدة" data-en="New Password">كلمة المرور الجديدة</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
                </div>

                <!-- حقل تأكيد كلمة المرور -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="تأكيد كلمة المرور" data-en="Confirm Password">تأكيد كلمة المرور</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full py-4 rounded-2xl font-bold text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 focus:outline-none focus:ring-4 focus:ring-rose-500/30 transform hover:-translate-y-1 shadow-[0_10px_20px_-10px_rgba(244,63,94,0.5)] transition-all duration-300 flex justify-center items-center gap-3">
                        <span class="text-base tracking-wide" data-ar="إعادة تعيين كلمة المرور" data-en="Reset Password">إعادة تعيين كلمة المرور</span>
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- القسم الأيسر: الإعلاني / التوجيهي -->
        <div class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-14 rtl:border-r ltr:border-l border-slate-200 dark:border-white/10 transition-colors duration-500">
            <div class="absolute inset-0 bg-gradient-to-tr from-rose-50/50 dark:from-rose-900/20 to-transparent pointer-events-none transition-colors duration-500"></div>

            <div class="relative z-10 mt-6">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/20 text-rose-600 dark:text-rose-300 text-sm font-medium mb-6 backdrop-blur-md transition-colors">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span data-ar="استعادة آمنة" data-en="Secure Recovery">استعادة آمنة</span>
                </div>
                
                <h2 class="text-4xl xl:text-5xl font-extrabold text-slate-800 dark:text-white mb-6 leading-tight drop-shadow-sm dark:drop-shadow-lg transition-colors">
                    <span data-ar="كلمة مرور جديدة" data-en="A New Password">كلمة مرور جديدة</span> <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-pink-500 transition-colors" data-ar="بداية آمنة جديدة" data-en="A Secure Fresh Start">بداية آمنة جديدة</span>
                </h2>
                
                <p class="text-lg text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed mb-10 transition-colors" data-ar="احرص على إنشاء كلمة مرور قوية تتضمن أحرفاً وأرقاماً ورموزاً لضمان أقصى درجات الحماية لحسابك وبيانات السفر الخاصة بك." data-en="Make sure to create a strong password containing letters, numbers, and symbols to ensure maximum protection for your account and travel data.">
                    احرص على إنشاء كلمة مرور قوية تتضمن أحرفاً وأرقاماً ورموزاً لضمان أقصى درجات الحماية لحسابك وبيانات السفر الخاصة بك.
                </p>

                <div class="grid grid-cols-2 gap-6 max-w-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="حماية قوية" data-en="Strong Protection">حماية قوية</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="تشفير بيانات الاعتماد" data-en="Credentials encryption">تشفير بيانات الاعتماد</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
        }

        let currentLang = 'ar';
        const htmlElement = document.documentElement;
        const langTextElement = document.getElementById('lang-text');
        
        function toggleLang() {
            currentLang = currentLang === 'ar' ? 'en' : 'ar';
            htmlElement.setAttribute('lang', currentLang);
            htmlElement.setAttribute('dir', currentLang === 'ar' ? 'rtl' : 'ltr');
            langTextElement.innerText = currentLang === 'ar' ? 'EN' : 'عربي';

            document.querySelectorAll('[data-ar]').forEach(el => {
                el.innerHTML = el.getAttribute(`data-${currentLang}`);
            });

            document.querySelectorAll('input').forEach(el => {
                if (el.hasAttribute(`data-${currentLang}-ph`)) {
                    el.placeholder = el.getAttribute(`data-${currentLang}-ph`);
                }
            });
        }
    </script>
</body>
</html>