<!DOCTYPE html>
<html lang="ar" dir="rtl" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-ar="Travel ERP | إنشاء حساب جديد" data-en="Travel ERP | Create New Account">Travel ERP | إنشاء حساب جديد</title>

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

        /* توهج زمردي يتناسب مع التسجيل */
        .glow-effect { box-shadow: 0 0 80px -20px rgba(16, 185, 129, 0.3); }
        .dark .glow-effect { box-shadow: 0 0 80px -20px rgba(16, 185, 129, 0.5); }

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

    <div class="fixed top-6 left-6 right-6 z-50 flex justify-between items-center px-4">
        <div class="flex gap-3">
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-emerald-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
            <button onclick="toggleLang()" class="px-4 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-emerald-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <span id="lang-text">EN</span>
            </button>
        </div>
    </div>

    <div class="fixed inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1464037866556-6812c9d1c72e?q=80&w=2070&auto=format&fit=crop" alt="Background" class="w-full h-full object-cover opacity-60 dark:opacity-30 scale-105 transition-transform duration-[20s] hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-tl from-white/95 via-slate-100/90 to-emerald-50/80 dark:from-slate-950/95 dark:via-slate-900/90 dark:to-emerald-950/80 backdrop-blur-[4px] transition-colors duration-500"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 rounded-[2.5rem] overflow-hidden bg-white/60 dark:bg-white/5 backdrop-blur-2xl border border-white/40 dark:border-white/10 shadow-2xl dark:shadow-none animate-enter glow-effect transition-colors duration-500">

        <div class="lg:col-span-5 flex flex-col justify-center bg-white/80 dark:bg-slate-950/60 p-8 sm:p-10 lg:p-12 relative z-20 transition-colors duration-500">
            
            <div class="mb-6 text-center lg:text-start rtl:lg:text-right ltr:lg:text-left">
                <h1 class="text-3xl font-bold text-slate-800 dark:text-white tracking-wide mb-2 transition-colors" data-ar="إنشاء حساب جديد" data-en="Create New Account">إنشاء حساب جديد</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-light transition-colors" data-ar="أدخل بياناتك لتسجيل حساب جديد في النظام." data-en="Enter your details to register a new account in the system.">أدخل بياناتك لتسجيل حساب جديد في النظام.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="الاسم الكامل" data-en="Full Name">الاسم الكامل</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="أحمد محمد" data-ar-ph="أحمد محمد" data-en-ph="John Doe">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500 text-xs" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="البريد الإلكتروني" data-en="Email Address">البريد الإلكتروني</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="user@example.com" data-ar-ph="user@example.com" data-en-ph="user@example.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="كلمة المرور" data-en="Password">كلمة المرور</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-3 ltr:pl-3 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                   class="w-full rtl:pr-10 rtl:pl-4 ltr:pl-10 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                                   placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors" data-ar="تأكيد كلمة المرور" data-en="Confirm Password">تأكيد كلمة المرور</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-3 ltr:pl-3 pointer-events-none text-slate-400 group-focus-within:text-emerald-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                   class="w-full rtl:pr-10 rtl:pl-4 ltr:pl-10 ltr:pr-4 py-3 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                                   placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500 text-xs" />
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-4">
                    <a href="{{ route('login') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium transition-colors" data-ar="لديك حساب بالفعل؟ تسجيل الدخول" data-en="Already registered? Log in">
                        لديك حساب بالفعل؟ تسجيل الدخول
                    </a>

                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transform hover:-translate-y-1 shadow-[0_10px_20px_-10px_rgba(16,185,129,0.5)] transition-all duration-300 flex justify-center items-center gap-3">
                        <span class="text-base tracking-wide" data-ar="إنشاء حساب" data-en="Register">إنشاء حساب</span>
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-14 rtl:border-r ltr:border-l border-slate-200 dark:border-white/10 transition-colors duration-500">
            <div class="absolute inset-0 bg-gradient-to-tr from-emerald-50/50 dark:from-emerald-900/20 to-transparent pointer-events-none transition-colors duration-500"></div>

            <div class="relative z-10 mt-6">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/20 text-emerald-600 dark:text-emerald-300 text-sm font-medium mb-6 backdrop-blur-md transition-colors">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span data-ar="انطلاقة جديدة" data-en="New Journey">انطلاقة جديدة</span>
                </div>
                
                <h2 class="text-4xl xl:text-5xl font-extrabold text-slate-800 dark:text-white mb-6 leading-tight drop-shadow-sm dark:drop-shadow-lg transition-colors">
                    <span data-ar="ابدأ رحلة النجاح" data-en="Start the Journey of Success">ابدأ رحلة النجاح</span> <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-teal-500 transition-colors" data-ar="مع أفضل نظام لإدارة السفر" data-en="With the Best Travel System">مع أفضل نظام لإدارة السفر</span>
                </h2>
                
                <p class="text-lg text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed mb-10 transition-colors" data-ar="انضم إلى المئات من وكالات السفر التي تعتمد على نظامنا لتسهيل حجوزاتها وإدارة بياناتها بكل أمان وسهولة." data-en="Join hundreds of travel agencies that rely on our system to facilitate their bookings and manage their data securely and easily.">
                    انضم إلى المئات من وكالات السفر التي تعتمد على نظامنا لتسهيل حجوزاتها وإدارة بياناتها بكل أمان وسهولة.
                </p>

                <div class="grid grid-cols-2 gap-6 max-w-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="سهولة الاستخدام" data-en="Ease of Use">سهولة الاستخدام</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="واجهة مستخدم بسيطة" data-en="Simple user interface">واجهة مستخدم بسيطة</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="دعم مستمر" data-en="Continuous Support">دعم مستمر</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="نحن هنا لمساعدتك دائماً" data-en="We are always here to help">نحن هنا لمساعدتك دائماً</p>
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

            // تبديل النصوص
            document.querySelectorAll('[data-ar]').forEach(el => {
                el.innerHTML = el.getAttribute(`data-${currentLang}`);
            });

            // تبديل الـ Placeholders
            document.querySelectorAll('input').forEach(el => {
                if (el.hasAttribute(`data-${currentLang}-ph`)) {
                    el.placeholder = el.getAttribute(`data-${currentLang}-ph`);
                }
            });
        }
    </script>
</body>
</html>