<aside
    x-data="{
        collapsed:false,
        mobile:false,
        openMenu:null
    }"
    x-init="
        window.addEventListener('toggle-sidebar',()=>{ mobile=!mobile })
    "
    :class="mobile ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
    class="fixed lg:static top-0 right-0 z-40 h-screen w-72 bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 shadow-2xl lg:shadow-none transition-all duration-300 transform overflow-hidden"
>

    <!-- TOP HEADER -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-gray-200 dark:border-gray-800">

        <div class="flex items-center gap-3">
{{--            @if(isset($info) && $info->logo)--}}
{{--                <img src="{{ asset('storage/'.$info->logo) }}" class="h-10 w-10 rounded-2xl object-cover shadow-md">--}}
{{--            @endif--}}
            <span class="font-bold text-gray-800 dark:text-gray-100 text-sm">
                {{ $info->office_name ?? 'المكتب' }}
            </span>
        </div>

        <!-- Collapse Button Desktop -->
        <button @click="collapsed=!collapsed"
                class="hidden lg:flex p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            ⇔
        </button>

        <!-- Close Mobile -->
        <button @click="mobile=false"
                class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            ✕
        </button>
    </div>

    <!-- SCROLL AREA -->
    <div class="h-[calc(100vh-4rem)] overflow-y-auto px-4 py-6 space-y-3">

        <!-- DASHBOARD -->
    <a href="{{ route('dashboard') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition
   {{ request()->routeIs('dashboard')
        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
        : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300'
   }}">

    <span>🏠</span>

    <span x-show="!collapsed" class="text-sm font-medium">
        الرئيسية
    </span>

</a>
        <!-- VISAS MENU -->

        <!-- VISAS MENU -->
@php
    $visasActive = request()->routeIs('visas.*') || request()->routeIs('trip-groups.*');
@endphp

<!-- VISAS MENU -->
<div>

    <!-- زر القسم -->
    <button
        @click="openMenu === 'visas' ? openMenu=null : openMenu='visas'"
        class="w-full flex items-center justify-between px-4 py-3 rounded-2xl transition
        {{ $visasActive
            ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
            : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300'
        }}"
    >
        <div class="flex items-center gap-3">
            <span>🛂</span>
            <span x-show="!collapsed" class="text-sm font-medium">
                التأشيرات
            </span>
        </div>

        <span x-show="!collapsed"
              class="transition transform"
              :class="openMenu === 'visas' ? 'rotate-180' : ''">
            ⌄
        </span>
    </button>

    <!-- الروابط -->
    <div x-show="openMenu === 'visas' || {{ $visasActive ? 'true' : 'false' }}"
         x-transition
         class="mt-2 space-y-1 pr-8">

        <a href="{{ route('visas.index') }}"
           class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('visas.index')
                ? 'bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
           }}">
            عرض التأشيرات
        </a>

        <a href="{{ route('trip-groups.index') }}"
           class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('trip-groups.*')
                ? 'bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
           }}">
            الحملات
        </a>

        <a href="{{ route('visa-types.index') }}"
   class="block px-3 py-2 rounded-xl text-sm transition
   {{ request()->routeIs('visa-types.*')
        ? 'bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300'
        : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
   }}">

    أنواع التأشيرات

</a>


        <a href="{{ route('trips.index') }}"
           class="block px-3 py-2 rounded-xl text-sm transition
   {{ request()->routeIs('trips.*')
        ? 'bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300'
        : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
   }}">

        الرحللات الخارجية

        </a>


        <a href="{{ route('dashboard.bookings.index') }}"
           class="block px-3 py-2 rounded-xl text-sm transition
   {{ request()->routeIs('bookings.*')
        ? 'bg-blue-100 dark:bg-blue-800/40 text-blue-700 dark:text-blue-300'
        : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400'
   }}">

        الحجوزات
        </a>


    </div>

</div>

        <!-- PASSPORTS -->
        <div>

            @php
                $isPassportsActive =
                    request()->routeIs('dashboard.requests.*') ||
                   request()->routeIs('dashboard.travels.*') ||
                    request()->routeIs('dashboard.request-types.*');
            @endphp

            <button
                @click="openMenu === 'passports' ? openMenu=null : openMenu='passports'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-2xl transition
        {{ $isPassportsActive
            ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300'
            : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}"
            >

                <div class="flex items-center gap-3">
                    <span>🪪</span>
                    <span x-show="!collapsed" class="text-sm font-medium">
                الجوازات
            </span>
                </div>

                <span x-show="!collapsed">⌄</span>

            </button>

            <div x-show="openMenu==='passports' || {{ $isPassportsActive ? 'true' : 'false' }}"
                 class="mt-2 space-y-1 pr-8">
 <!-- الحجوزات -->


                <!-- الطلبات -->
                <a href="{{ route('dashboard.requests.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('dashboard.requests.*')
                ? 'bg-blue-500 text-white'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    الطلبات
                </a>

                <!-- أنواع الطلبات -->
                <a href="{{ route('dashboard.request-types.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('dashboard.request-types.*')
                ? 'bg-blue-500 text-white'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    أنواع الطلبات
                </a>
{{--                //الرحلات--}}

                <a href="{{ route('dashboard.travels.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('dashboard.travels.*')
                ? 'bg-blue-500 text-white'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                   الرحلات البرية
                </a>

            </div>

        </div>

        @php
    $bookingsActive = request()->routeIs('bookings.*');
@endphp

{{--<a href="{{ route('bookings.index') }}"--}}
{{--   class="relative flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200--}}
{{--   {{ $bookingsActive--}}
{{--        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm'--}}
{{--        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'--}}
{{--   }}">--}}

{{--    <!-- Active Indicator -->--}}
{{--    @if($bookingsActive)--}}
{{--        <span class="absolute right-0 top-2 bottom-2 w-1 bg-blue-600 rounded-l-full"></span>--}}
{{--    @endif--}}

{{--    <!-- Icon -->--}}
{{--    <span class="text-lg">🎟</span>--}}

{{--    <!-- Title -->--}}
{{--    <span class="text-sm font-medium">--}}
{{--        الحجوزات--}}
{{--    </span>--}}

{{--</a>--}}
        <!-- FINANCE -->
        @php
            $financeActive = request()->routeIs('dashboard.invoices.*')
                            || request()->routeIs('dashboard.payments.*')
                            || request()->routeIs('dashboard.expenses.*')
               || request()->routeIs('dashboard.cashboxes.*');
        @endphp

        <div>

            <button
                @click="openMenu === 'finance' ? openMenu=null : openMenu='finance'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-2xl transition
            {{ $financeActive ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}"
            >
                <div class="flex items-center gap-3">
                    <span>💰</span>
                    <span x-show="!collapsed" class="text-sm font-medium">
                المالية
            </span>
                </div>

                <span x-show="!collapsed">⌄</span>
            </button>

            <div
                x-show="openMenu==='finance' || {{ $financeActive ? 'true' : 'false' }}"
                class="mt-2 space-y-1 pr-8"
            >

                {{-- الفواتير --}}
                <a href="{{ route('dashboard.invoices.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('dashboard.invoices.*')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    الفواتير
                </a>

                {{-- المدفوعات --}}
                <a href="{{ route('dashboard.payments.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('dashboard.payments.*')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    المدفوعات
                </a>

                {{-- المصاريف --}}
                <a href="{{ route('dashboard.expenses.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
           {{ request()->routeIs('dashboard.expenses.*')
                ? 'bg-blue-600 text-white'
                : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    المصاريف
                </a>


                {{-- إدارة الخزنة --}}
                <a href="{{ route('dashboard.cashboxes.index') }}"
                   class="block px-3 py-2 rounded-xl text-sm transition
   {{ request()->routeIs('dashboard.cashboxes.*')
        ? 'bg-blue-600 text-white'
        : 'hover:bg-gray-100 dark:hover:bg-gray-800' }}">

                    إدارة الخزنة

                </a>


            </div>

        </div>
        <!-- CLIENTS -->
        @php
            $clientsActive = request()->routeIs('clients.*');
        @endphp

        <a href="{{ route('clients.index') }}"
           class="relative flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
   {{ $clientsActive
        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm'
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
   }}">

            <!-- Active Indicator -->
            @if($clientsActive)
                <span class="absolute right-0 top-2 bottom-2 w-1 bg-blue-600 rounded-l-full"></span>
            @endif

            <!-- Icon -->
            <span class="text-lg">👥</span>

            <!-- Title -->
            <span class="text-sm font-medium">
        العملاء
    </span>
        </a>
@php
$agentsActive = request()->routeIs('agents.*');
@endphp

<a href="{{ route('agents.index') }}"
   class="relative flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
   {{ $agentsActive
        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm'
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
   }}">

    <!-- Active Indicator -->
    @if($agentsActive)
        <span class="absolute right-0 top-2 bottom-2 w-1 bg-blue-600 rounded-l-full"></span>
    @endif

    <!-- Icon -->
    <span class="text-lg">🏢</span>

    <!-- Title -->
    <span x-show="!collapsed" class="text-sm font-medium">
        الوكلاء
    </span>

</a>

        <!-- EMPLOYEES -->
        @php
            $employeesActive = request()->routeIs('employees.*');
        @endphp

        <a href="{{ route('employees.index') }}"
           class="relative flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
   {{ $employeesActive
        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm'
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
   }}">

            <!-- Active Indicator -->
            @if($employeesActive)
                <span class="absolute right-0 top-2 bottom-2 w-1 bg-blue-600 rounded-l-full"></span>
            @endif

            <!-- Icon -->
            <span class="text-lg">👨‍💼</span>

            <!-- Title -->
            <span class="text-sm font-medium">
        الموظفين
    </span>

        </a>


        @php
            $busActive = request()->routeIs('bus-assignments.*');
        @endphp

        <a href="{{ route('dashboard.bus_assignments.index') }}"
           class="relative flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
   {{ $busActive
        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 shadow-sm'
        : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'
   }}">

            <!-- Active Indicator -->
            @if($busActive)
                <span class="absolute right-0 top-2 bottom-2 w-1 bg-blue-600 rounded-l-full"></span>
            @endif

            <!-- Icon -->
            <span class="text-lg">🚌</span>

            <!-- Title -->
            <span class="text-sm font-medium">
       أدارة الباصات
    </span>

        </a>


        <!-- BUSES -->
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            <span>🚌</span>
            <span x-show="!collapsed" class="text-sm">الباصات</span>
        </a>

        <!-- DRIVERS -->
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
            <span>👨‍✈️</span>
            <span x-show="!collapsed" class="text-sm">السائقين</span>
        </a>

        <!-- SETTINGS -->

        @php
            $active = request()->routeIs('settings.*');
        @endphp

        <a href="{{ route('settings.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-2xl transition
   {{ $active
        ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 shadow-sm'
        : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300'
   }}">

            ⚙️
            <span>الإعدادات</span>
        </a>


        <!-- SPACER -->
        <div class="pt-10"></div>

        <!-- FOOTER INFO -->
        <div x-show="!collapsed" class="text-xs text-gray-400 text-center">
            © {{ date('Y') }} {{ $info->office_name ?? '' }}
        </div>

    </div>

</aside>
