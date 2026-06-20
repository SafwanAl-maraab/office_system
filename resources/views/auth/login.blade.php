<!DOCTYPE html>
<html lang="ar" dir="rtl" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-ar="Travel ERP | بوابة الدخول الاحترافية" data-en="Travel ERP | Professional Login Portal">Travel ERP | بوابة الدخول الاحترافية</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap');

        body {
            font-family: 'Tajawal', sans-serif;
        }

        @keyframes fadeSlideUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-enter {
            animation: fadeSlideUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .glow-effect {
            box-shadow: 0 0 80px -20px rgba(6, 182, 212, 0.3);
        }
        .dark .glow-effect {
            box-shadow: 0 0 80px -20px rgba(6, 182, 212, 0.5);
        }

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

    <div class="fixed top-6 left-6 right-6 z-50 flex justify-between items-center px-4">
        <div class="flex gap-3">
            <button onclick="toggleTheme()" class="w-10 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-cyan-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <svg id="sun-icon" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <svg id="moon-icon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>

            <button onclick="toggleLang()" class="px-4 h-10 rounded-full bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-cyan-400 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-all shadow-sm">
                <span id="lang-text">EN</span>
            </button>
        </div>
    </div>

    <div class="fixed inset-0 z-0">
        <img src="{{ asset('images/login-travel.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-60 dark:opacity-40 scale-105 transition-transform duration-[20s] hover:scale-110">
        <div class="absolute inset-0 bg-gradient-to-tl from-white/95 via-slate-100/90 to-blue-50/80 dark:from-slate-950/95 dark:via-slate-900/90 dark:to-blue-950/80 backdrop-blur-[4px] transition-colors duration-500"></div>
    </div>

    <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-12 rounded-[2.5rem] overflow-hidden bg-white/60 dark:bg-white/5 backdrop-blur-2xl border border-white/40 dark:border-white/10 shadow-2xl dark:shadow-none animate-enter glow-effect transition-colors duration-500">

        <div class="lg:col-span-5 flex flex-col justify-center bg-white/80 dark:bg-slate-950/60 p-8 sm:p-12 lg:p-14 relative z-20 transition-colors duration-500">
            
            <div class="mb-10 text-center lg:text-start rtl:lg:text-right ltr:lg:text-left">
                <img src="{{ asset('images/logo.png') }}" alt="Travel ERP Logo" class="w-36 lg:w-44 mx-auto lg:mx-0 object-contain drop-shadow-xl mb-4">
                <h1 class="text-3xl font-bold text-slate-800 dark:text-white tracking-wide mb-2 transition-colors" data-ar="أهلاً بك مجدداً" data-en="Welcome Back">أهلاً بك مجدداً</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-light transition-colors" data-ar="أدخل بيانات الاعتماد للوصول إلى لوحة التحكم." data-en="Enter your credentials to access the dashboard.">أدخل بيانات الاعتماد للوصول إلى لوحة التحكم.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-sm font-medium flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2 transition-colors" data-ar="البريد الإلكتروني" data-en="Email Address">البريد الإلكتروني</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-cyan-500 dark:group-focus-within:text-cyan-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3.5 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-cyan-500 dark:focus:border-cyan-400 focus:ring-1 focus:ring-cyan-500 dark:focus:ring-cyan-400 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="admin@travelerp.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors" data-ar="كلمة المرور" data-en="Password">كلمة المرور</label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 dark:text-cyan-400 hover:text-blue-700 dark:hover:text-cyan-300 font-medium transition-colors" data-ar="نسيت كلمة المرور؟" data-en="Forgot Password?">
                                نسيت كلمة المرور؟
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 ltr:left-0 rtl:right-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-slate-400 group-focus-within:text-cyan-500 dark:group-focus-within:text-cyan-400 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="w-full rtl:pr-12 rtl:pl-4 ltr:pl-12 ltr:pr-4 py-3.5 rounded-2xl bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:bg-white dark:focus:bg-white/10 focus:border-cyan-500 dark:focus:border-cyan-400 focus:ring-1 focus:ring-cyan-500 dark:focus:ring-cyan-400 transition-all duration-300 outline-none shadow-sm dark:shadow-none"
                               placeholder="••••••••">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center w-5 h-5 rounded border border-slate-300 dark:border-slate-500 bg-white dark:bg-white/5 group-hover:border-cyan-500 transition-colors">
                            <input type="checkbox" name="remember" id="remember_me" class="peer absolute w-full h-full opacity-0 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-cyan-500 dark:text-cyan-400 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="rtl:ms-3 ltr:mr-3 text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors" data-ar="البقاء قيد تسجيل الدخول" data-en="Remember me">البقاء قيد تسجيل الدخول</span>
                    </label>
                    
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-xs text-slate-500 hover:text-cyan-500 transition-colors" data-ar="إنشاء حساب جديد" data-en="Create an account">إنشاء حساب جديد</a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full py-4 mt-2 rounded-2xl font-bold text-white bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 dark:hover:from-blue-500 dark:hover:to-cyan-400 focus:outline-none focus:ring-4 focus:ring-cyan-500/30 transform hover:-translate-y-1 shadow-[0_10px_20px_-10px_rgba(6,182,212,0.5)] transition-all duration-300 flex justify-center items-center gap-3">
                    <span class="text-base tracking-wide" data-ar="دخول إلى النظام" data-en="Login to System">دخول إلى النظام</span>
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </button>
            </form>

            <div class="mt-8 text-center lg:text-start rtl:lg:text-right ltr:lg:text-left text-slate-400 dark:text-slate-500 text-xs">
                © {{ date('Y') }} Travel ERP V2.0.
            </div>
        </div>

        <div class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-14 rtl:border-r ltr:border-l border-slate-200 dark:border-white/10 transition-colors duration-500">
            <div class="absolute inset-0 bg-gradient-to-tr from-cyan-50/50 dark:from-cyan-900/20 to-transparent pointer-events-none transition-colors duration-500"></div>

            <div class="relative z-10 mt-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/20 text-cyan-600 dark:text-cyan-300 text-sm font-medium mb-6 backdrop-blur-md transition-colors">
                    <span class="w-2 h-2 rounded-full bg-cyan-500 dark:bg-cyan-400 animate-pulse"></span>
                    <span data-ar="الإصدار الأحدث من النظام" data-en="Latest System Version">الإصدار الأحدث من النظام</span>
                </div>
                
                <h2 class="text-4xl xl:text-5xl font-extrabold text-slate-800 dark:text-white mb-6 leading-tight drop-shadow-sm dark:drop-shadow-lg transition-colors">
                    <span data-ar="إدارة شاملة لرحلاتك" data-en="Comprehensive Travel">إدارة شاملة لرحلاتك</span> <br> 
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500 dark:from-cyan-400 dark:to-blue-400 transition-colors" data-ar="في منصة واحدة ذكية" data-en="Management in One Smart Platform">في منصة واحدة ذكية</span>
                </h2>
                
                <p class="text-lg text-slate-600 dark:text-slate-300 max-w-lg leading-relaxed mb-10 transition-colors" data-ar="تحكم كامل في الحجوزات، التأشيرات، الحسابات المالية، والموارد البشرية بكل سهولة وبأعلى معايير الأمان المعتمدة عالمياً." data-en="Full control over bookings, visas, financial accounts, and HR with ease and the highest globally certified security standards.">
                    تحكم كامل في الحجوزات، التأشيرات، الحسابات المالية، والموارد البشرية بكل سهولة وبأعلى معايير الأمان المعتمدة عالمياً.
                </p>

                <div class="grid grid-cols-2 gap-6 max-w-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-cyan-500 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="أمان عالي" data-en="High Security">أمان عالي</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="تشفير متقدم لبياناتك" data-en="Advanced data encryption">تشفير متقدم لبياناتك</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 flex items-center justify-center border border-slate-200 dark:border-white/10 shrink-0 shadow-sm dark:shadow-none transition-colors">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-medium transition-colors" data-ar="سرعة وأداء" data-en="Speed & Performance">سرعة وأداء</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors" data-ar="معالجة فورية للبيانات" data-en="Real-time data processing">معالجة فورية للبيانات</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 flex items-center gap-4 mt-auto">
                <div class="flex rtl:-space-x-3 rtl:space-x-reverse ltr:-space-x-3">
                    <div class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs shadow-sm transition-colors">👨‍💻</div>
                    <div class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs shadow-sm transition-colors">👩‍💼</div>
                </div>
                <div>
                    <p class="text-sm text-slate-700 dark:text-white font-medium transition-colors" data-ar="هل تواجه مشكلة؟" data-en="Facing any issues?">هل تواجه مشكلة؟</p>
                    <a href="#" class="text-xs text-blue-600 dark:text-cyan-400 hover:text-blue-800 dark:hover:text-cyan-300 transition-colors" data-ar="تواصل مع الدعم الفني" data-en="Contact Technical Support">تواصل مع الدعم الفني</a>
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

            document.getElementById('email').placeholder = currentLang === 'ar' ? 'admin@travelerp.com' : 'admin@travelerp.com';
            document.getElementById('password').placeholder = currentLang === 'ar' ? '••••••••' : '••••••••';
        }
    </script>
</body>
</html>          