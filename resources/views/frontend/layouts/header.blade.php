<header
    x-data="{
        mobileMenu: false,
        profileMenu: false,
        notificationsMenu: false
    }"
    class="fixed top-0 right-0 left-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700 shadow-sm"
>
    <div class="h-16 px-6 flex items-center justify-between">

        <div class="flex items-center gap-4">

            <button
                @click="$dispatch('toggle-sidebar')"
                class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-3">
                @if(isset($info) && $info->logo)
                    <img
                        src="{{ asset('storage/'.$info->logo) }}"
                        class="h-10 w-10 rounded-2xl object-cover shadow-md"
                        alt="Logo"
                    >
                @else
                    <div class="h-10 w-10 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                        🏢
                    </div>
                @endif

                    <div class="flex flex-col leading-tight">
    <span class="text-base font-bold text-gray-800 dark:text-gray-100">
        {{ $info->office_name ?? 'اسم المكتب' }}
    </span>

                        <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1">
        📍 {{ $branchName ?? 'المركز ...' }}
    </span>
                    </div>
            </div>

        </div>

        <div class="hidden lg:flex flex-1 justify-center">
            <div class="relative w-full max-w-md">
                <input
                    type="text"
                    placeholder="بحث سريع..."
                    class="w-full pr-12 pl-4 py-2 rounded-2xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm shadow-sm text-gray-800 dark:text-gray-100"
                >
                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                    🔍
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">

            <button
                @click="toggleDark()"
                class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition duration-200 text-sm"
                title="تبديل المظهر"
            >
                <span x-show="!darkMode">🌙</span>
                <span x-show="darkMode">☀️</span>
            </button>

            <div class="relative">
                <button
                    @click="notificationsMenu = !notificationsMenu"
                    class="relative p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition text-sm"
                >
                    🔔
                    <span class="absolute -top-1 -left-1 bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5 font-bold">
                        3
                    </span>
                </button>

                <div
                    x-show="notificationsMenu"
                    @click.outside="notificationsMenu=false"
                    x-transition
                    class="absolute left-0 mt-3 w-72 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-4 space-y-3 hidden"
                    :class="{'hidden': !notificationsMenu}"
                >
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-100 border-b border-gray-100 dark:border-gray-700 pb-2">🔔 الإشعارات الواردة</p>
                    <div class="text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 p-1.5 rounded-lg">تم إضافة تأشيرة مستندية جديدة</div>
                    <div class="text-xs text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 p-1.5 rounded-lg">تم سداد دفعة مالية للخزنة</div>
                </div>
            </div>

            <div class="relative">
                <button
                    @click="profileMenu = !profileMenu"
                    class="flex items-center gap-2 p-1.5 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <div class="h-8 w-8 bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center rounded-xl text-xs font-bold uppercase">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <span class="hidden md:block text-sm font-semibold text-gray-700 dark:text-gray-200">
                        {{ auth()->user()->name ?? 'المستخدم' }}
                    </span>
                </button>

                <div
                    x-show="profileMenu"
                    @click.outside="profileMenu=false"
                    x-transition
                    class="absolute left-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-2 space-y-1 hidden"
                    :class="{'hidden': !profileMenu}"
                >
                    <a href="#" class="block px-3 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-xs text-gray-700 dark:text-gray-200">
                        👤 الملف الشخصي
                    </a>
                    <a href="#" class="block px-3 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-xs text-gray-700 dark:text-gray-200">
                        ⚙️ إعدادات الحساب
                    </a>
                    <hr class="border-gray-100 dark:border-gray-700 my-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-right px-3 py-2 rounded-xl hover:bg-red-50 dark:hover:bg-red-950/30 text-xs text-red-600 font-medium">
                            🚪 تسجيل الخروج
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</header>
