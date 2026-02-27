<!DOCTYPE html>
<html lang="ar"
      dir="rtl"
      x-data="layout()"
      x-init="init()"
      :class="{ 'dark': darkMode }"
      class="h-full bg-gray-100 dark:bg-gray-950"
>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $info->office_name ?? 'Office System' }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        body { font-family: system-ui, sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb { background: #374151; }
    </style>
</head>

<body class="h-full text-gray-800 dark:text-gray-200 overflow-hidden">

<div class="flex h-screen w-full">

    <!-- SIDEBAR -->
    @include('frontend.layouts.sidebar')

    <!-- CONTENT AREA -->
    <div class="flex flex-col flex-1 relative">

        <!-- HEADER -->
        @include('frontend.layouts.header')

        <!-- MAIN SCROLL AREA -->
        <main class="flex-1 overflow-y-auto pt-16  px-8 pb-10">

            <!-- PAGE CONTAINER -->
            <div class="max-w-7xl mx-auto">

                <!-- BREADCRUMB -->
                <div class="flex items-center justify-between mb-6">
                    <div>
{{--                        <h1 class="text-2xl font-bold">--}}
{{--                            @yield('title', 'لوحة التحكم')--}}
{{--                        </h1>--}}
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @yield('subtitle', '')
                        </p>
                    </div>
                </div>

                <!-- FLASH MESSAGES -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-2xl bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- PAGE CONTENT -->
                @yield('content')

            </div>

        </main>

    </div>

</div>

<!-- GLOBAL MODAL ROOT -->
<div x-show="modalOpen"
     x-transition
     class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50"
     @click.self="modalOpen = false">

    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 w-full max-w-lg shadow-2xl">
        <h2 class="text-lg font-bold mb-4">عنوان</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            محتوى المودال
        </p>
        <button @click="modalOpen = false"
                class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
            إغلاق
        </button>
    </div>

</div>


<!-- TOAST NOTIFICATION -->
<div
    x-data="toastHandler()"
    x-init="init()"
    class="fixed top-6 left-6 z-[9999] space-y-4"
>

    <!-- SUCCESS -->
    @if(session('success'))
        <div x-show="visible"
             x-transition
             class="relative w-80 bg-white dark:bg-gray-900 border border-green-200 dark:border-green-700 rounded-2xl shadow-2xl overflow-hidden">

            <!-- Progress Bar -->
            <div class="absolute top-0 right-0 h-1 bg-green-500 transition-all"
                 :style="'width: '+progress+'%'"></div>

            <div class="p-5 flex items-start gap-3">

                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400">
                        ✓
                    </div>
                </div>

                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        نجاح العملية
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ session('success') }}
                    </p>
                </div>

                <button @click="close()" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>

            </div>
        </div>
    @endif

    <!-- ERROR -->
    @if(session('error'))
        <div x-show="visible"
             x-transition
             class="relative w-80 bg-white dark:bg-gray-900 border border-red-200 dark:border-red-700 rounded-2xl shadow-2xl overflow-hidden">

            <div class="absolute top-0 right-0 h-1 bg-red-500 transition-all"
                 :style="'width: '+progress+'%'"></div>

            <div class="p-5 flex items-start gap-3">

                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl bg-red-100 dark:bg-red-900 flex items-center justify-center text-red-600 dark:text-red-400">
                        !
                    </div>
                </div>

                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        خطأ
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ session('error') }}
                    </p>
                </div>

                <button @click="close()" class="text-gray-400 hover:text-gray-600">
                    ✕
                </button>

            </div>
        </div>
    @endif

</div>

<script>
    function toastHandler(){
        return {
            visible:true,
            progress:100,
            timer:null,

            init(){
                this.startTimer()
            },

            startTimer(){
                let duration = 4000;
                let step = 100 / (duration / 100);

                this.timer = setInterval(()=>{
                    this.progress -= step;
                    if(this.progress <= 0){
                        this.close();
                    }
                },100);
            },

            close(){
                this.visible = false;
                clearInterval(this.timer);
            }
        }
    }
</script>

<script>
    function layout(){
        return {
            darkMode: localStorage.getItem('theme') === 'dark',
            modalOpen:false,

            init(){
                if(this.darkMode){
                    document.documentElement.classList.add('dark')
                }
            },

            toggleDark(){
                this.darkMode = !this.darkMode
                if(this.darkMode){
                    document.documentElement.classList.add('dark')
                    localStorage.setItem('theme','dark')
                }else{
                    document.documentElement.classList.remove('dark')
                    localStorage.setItem('theme','light')
                }
            }
        }
    }
</script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
