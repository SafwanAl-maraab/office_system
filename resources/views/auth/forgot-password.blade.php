<!DOCTYPE html>
<html lang="ar" dir="rtl" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-ar="Travel ERP | استعادة كلمة المرور" data-en="Travel ERP | Forgot Password">Travel ERP | استعادة كلمة المرور</title>

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

        /* توهج بنفسجي يتناسب مع الاستعادة */
        .glow-effect { box-shadow: 0 0 80px -20px rgba(99, 102, 241, 0.3); }
        .dark .glow-effect { box-shadow: 0 0 80px -20px rgba(99, 102, 241, 0.5); }

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
<body class="min-h-screen relative flex items-center justify-center p-4 sm:p-6 lg:p-12 overflow-hidden bg-slate-50 text-slate-800 dark:bg-slate-950 dark:text-slate-200 transition-colors duration-500">

    <!-- أزرار التحكم -->
    <div class="fixed top-6 left-6 right-6 z-50 flex justify-between items-center px-4">
        <div class="flex gap-3">
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            <button onclick="toggleLang()" class="px-4 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-indigo-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <span id="lang-text">EN</span>
            </button>
        </div>
    </div>

    <!-- الخلفية -->
    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1557683316-973673baf926?q=80&w=2029&auto=format&fit=crop" alt="Background" class="w-full h-full object-cover opacity-60 dark:opacity-30 scale-105 transition-transform duration-[20s] hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-tl from-white/95 via-slate-100/90 to-indigo-50/80 dark:from-slate-950/95 dark:via-slate-900/90 dark:to-indigo-950/80 backdrop-blur-[4px] transition-colors duration-500"></div>
    </div>

    <!-- البطاقة المركزية -->
    <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 rounded-[2.5rem] overflow-hidden bg-white/60 dark:bg-white/5 backdrop-blur-2xl border border-white/40 dark:border-white/10 shadow-2xl dark:shadow-none animate-enter glow-effect transition-colors duration-500">

        <!-- القسم الأيمن: نموذج استعادة كلمة المرور -->
        <div class="lg:col-span-5 flex flex-col justify-center bg-white/80 dark:bg-slate-950/60 p-8 sm:p-12 lg:p-14 relative z-20 transition-colors duration-500">
            
            <div class="mb-8 text-center lg:text-start rtl:lg:text-right ltr:lg:text-left">
                <!-- أيقونة البريد/الاستعادة -->
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-500 flex items-center justify-center mx-auto lg:mx-0 mb-4 shadow-lg text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                
                <h1 class="text-3xl font-bold text-slate-800 dark:text-white tracking-wide mb-3 transition-colors" data-ar="نسيت كلمة المرور؟" data-en="Forgot Password?">نسيت كلمة المرور؟</h1>
                
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed transition-colors" data-ar="لا مشكلة. فقط أخبرنا بعنوان بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور الذي سيسمح لك باختيار كلمة مرور جديدة." data-en="No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.">
                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                </p>
            </div>

            <!-- رسالة النجاح (Session Status) -->
            @if (session('status'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm font-medium flex items-start gap-3 transition-colors">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- نموذج البريد الإلكتروني -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2 transition-colors" data-ar="البريد الإلكتروني" data-en="Email Address">البريد الإلكتروني</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3.5 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="user@example.com">
                    </div>
                    
                    <!-- رسائل الخطأ -->
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <!-- أزرار الإجراءات -->
                <div class="flex flex-col-reverse sm:flex-row items-center gap-4 pt-2">
                    <!-- رابط العودة للدخول -->
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-4 rounded-2xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all duration-300 text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span data-ar="رجوع" data-en="Back">رجوع</span>
                    </a>
                    
                    <!-- زر الإرسال -->
                    <button type="submit"
                            class="w-full sm:flex-1 py-4 rounded-2xl font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-500 hover:from-indigo-700 hover:to-purple-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 transform hover:-translate-y-1 shadow-[0_10px_20px_-10px_rgba(99,102,241,0.5)] transition-all duration-300 flex justify-center items-center gap-3">
                        <span class="text-base tracking-wide" data-ar="إرسال رابط الاستعادة" data-en="Email Password Reset Link">إرسال رابط الاستعادة</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- القسم الأيسر: الإعلاني / التوجيهي -->
        <div class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-14 rtl:border-r ltr:border-l border-slate-200 dark:border-white/10 transition-colors duration-500">
            <div class="absolute inset-0 bg-gradient-to-tr from-indigo-50/50 dark:from-indigo-900/20 to-transparent pointer-events-none transition-colors duration-500"></div>

            <div class="relative z-10 mt-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/20 text-indigo-600 dark:text-indigo-300 text-sm font-medium mb-6 backdrop-blur-md transition-colors">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    <span data-ar="استعادة الحساب" data-en="Account Recovery">استعادة الحساب</span>
                </div>
                
                <h2 class="text-4xl xl:text-5xl font-extrabold text-slate-800 dark:text-white mb-6 leading-tight drop-shadow-sm dark:drop-shadow-lg transition-colors">
                    <span data-ar="خطوة واحدة لتعود إلى" data-en="One step to get back to">خطوة واحدة لتعود إلى</span> <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-500 transition-colors" data-ar="لوحة التحكم الخاصة بك" data-en="Your Dashboard">لوحة التحكم الخاصة بك</span>
                </h2>
                
                <p class="text-lg text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed mb-10 transition-colors" data-ar="نحن نضمن أن تظل بياناتك آمنة، سنقوم بإرسال رابط صالح لمرة واحدة لإعادة تعيين كلمة المرور بكل سهولة." data-en="We ensure your data remains secure, we will send a one-time valid link to easily reset your password.">
                    نحن نضمن أن تظل بياناتك آمنة، سنقوم بإرسال رابط صالح لمرة واحدة لإعادة تعيين كلمة المرور بكل سهولة.
                </p>

                <div class="grid grid-cols-2 gap-6 max-w-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="إرسال فوري" data-en="Instant Delivery">إرسال فوري</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="يصلك الرابط فوراً" data-en="Link arrives instantly">يصلك الرابط فوراً</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="رابط آمن ومؤقت" data-en="Secure & Temp Link">رابط آمن ومؤقت</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="صلاحية محددة للحماية" data-en="Limited validity for safety">صلاحية محددة للحماية</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الجافاسكريبت للتبديل -->
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
                if (el.tagName === 'INPUT' && el.type === 'email') {
                    // Placeholder input
                } else {
                    el.innerHTML = el.getAttribute(`data-${currentLang}`);
                }
            });
        }
    </script>
</body>
</html>