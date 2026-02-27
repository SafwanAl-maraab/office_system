<header
    x-data="{
        darkMode: localStorage.getItem('theme') === 'dark',
        mobileMenu: false,
        profileMenu: false,
        notificationsMenu: false
    }"
    x-init="
        if(darkMode){
            document.documentElement.classList.add('dark')
        }
    "
    class="fixed top-0 right-0 left-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700 shadow-sm"
>

    <!-- MAIN CONTAINER -->
    <div class="h-16 px-6 flex items-center justify-between">

        <!-- RIGHT SIDE -->
        <div class="flex items-center gap-4">

            <!-- HAMBURGER (MOBILE ONLY) -->
            <button
                @click="$dispatch('toggle-sidebar')"
                class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- LOGO -->
            <div class="flex items-center gap-3">
                @if(isset($info) && $info->logo)
                    <img
                        src="{{ asset('storage/'.$info->logo) }}"
                        class="h-10 w-10 rounded-2xl object-cover shadow-md  "
                        alt="Logo"
                    >
                @endif

                <!-- OFFICE NAME -->
                <div class="flex flex-col leading-tight">
                    <span class="text-base font-bold text-gray-800 dark:text-gray-100 ">
                        {{ $info->office_name ?? 'اسم المكتب' }}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        لوحة التحكم
                    </span>
                </div>
            </div>

        </div>

        <!-- CENTER (SEARCH - DESKTOP ONLY) -->
        <div class="hidden lg:flex flex-1 justify-center">
            <div class="relative w-full max-w-md">

                <input
                    type="text"
                    placeholder="بحث سريع..."
                    class="w-full pr-12 pl-4 py-2 rounded-2xl bg-gray-100  border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm    shadow-lg"
                >

                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                    🔍
                </div>

            </div>
        </div>

        <!-- LEFT SIDE -->
        <div class="flex items-center gap-4">

            <!-- DARK MODE -->
            <button
                @click="
                    darkMode = !darkMode;
                    if(darkMode){
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme','dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme','light');
                    }
                "
                class="p-2 rounded-xl bg-gray-300 dark:bg-gray-400 hover:bg-gray-400  dark:hover:bg-gray-100 transition duration-200"
            >
                <span x-show="!darkMode">🌙</span>
                <span x-show="darkMode">☀️</span>
            </button>

            <!-- NOTIFICATIONS -->
            <div class="relative">

                <button
                    @click="notificationsMenu = !notificationsMenu"
                    class="relative p-2 rounded-xl bg-gray-300 dark:bg-gray-400 hover:bg-gray-400  dark:hover:bg-gray-100 transition"
                >
                    🔔

                    <!-- Badge -->
                    <span class="absolute -top-1 -left-1 bg-red-500 text-white text-xs rounded-full px-1">
                        3
                    </span>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="notificationsMenu"
                    @click.outside="notificationsMenu=false"
                    class="absolute left-0 mt-3 w-72 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-4 space-y-3"
                >
                    <p class="text-sm font-semibold">الإشعارات</p>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        تم إضافة تأشيرة جديدة
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        تم دفع فاتورة
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300">
                        عميل جديد
                    </div>
                </div>

            </div>

            <!-- USER PROFILE -->
            <div class="relative">

                <button
                    @click="profileMenu = !profileMenu"
                    class="flex items-center gap-2 p-2 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <div class="h-8 w-8 bg-blue-500 text-white flex items-center justify-center rounded-xl text-sm">
                        {{ substr(auth()->user()->name ?? 'A',0,1) }}
                    </div>

                    <span class="hidden md:block text-sm font-medium">
                        {{ auth()->user()->name ?? 'المستخدم' }}
                    </span>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="profileMenu"
                    @click.outside="profileMenu=false"
                    class="absolute left-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-3 space-y-2"
                >

                    <a href="#" class="block px-3 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                        الملف الشخصي
                    </a>

                    <a href="#" class="block px-3 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                        الإعدادات
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-right px-3 py-2 rounded-xl hover:bg-red-100 dark:hover:bg-red-900 text-sm text-red-600">
                            تسجيل الخروج
                        </button>
                    </form>

                </div>

            </div>

        </div>

    </div>

</header>
