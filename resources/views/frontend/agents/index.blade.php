@extends('frontend.layouts.app')

@section('title', 'إدارة الوكلاء')

@section('content')

    <div class="space-y-6 sm:space-y-8">

        <!-- HEADER -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-800 dark:text-white">
                    إدارة الوكلاء
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    إدارة الحسابات الجغرافية للوكلاء ومراقبة الأرصدة والمدفوعات المالية
                </p>
            </div>

            <div class="flex gap-3 w-full sm:w-auto">
                <button
                    onclick="triggerCreateAgent()"
                    class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-3 rounded-2xl shadow-lg font-bold transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>إضافة وكيل</span>
                </button>

                <a
                    href="{{ route('agents.export', request()->query()) }}"
                    class="flex-1 sm:flex-none bg-purple-600 hover:bg-purple-700 text-white px-4 sm:px-6 py-3 rounded-2xl shadow-lg font-bold transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>تصدير PDF</span>
                </a>
            </div>
        </div>

        <!-- FINANCIAL CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <!-- DUE -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-bold">إجمالي المستحقات</p>
                    <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                        <i class="fa-solid fa-money-bill-trend-up text-red-600 dark:text-red-400 text-sm"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($stats as $stat)
                        <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700/50 pb-1 last:border-0 last:pb-0">
                            <span class="font-bold text-sm text-gray-700 dark:text-gray-300">{{ $stat->currency->code }}</span>
                            <span class="font-black text-lg text-red-600 dark:text-red-400">{{ number_format($stat->total_due, 2) }}</span>
                        </div>
                    @empty
                        <div class="text-gray-400 text-sm">0.00</div>
                    @endforelse
                </div>
            </div>

            <!-- PAYMENTS -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-bold">إجمالي المدفوعات</p>
                    <div class="w-9 h-9 rounded-xl bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                        <i class="fa-solid fa-hand-holding-dollar text-green-600 dark:text-green-400 text-sm"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($stats as $stat)
                        <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700/50 pb-1 last:border-0 last:pb-0">
                            <span class="font-bold text-sm text-gray-700 dark:text-gray-300">{{ $stat->currency->code }}</span>
                            <span class="font-black text-lg text-green-600 dark:text-green-400">{{ number_format(abs($stat->total_payment), 2) }}</span>
                        </div>
                    @empty
                        <div class="text-gray-400 text-sm">0.00</div>
                    @endforelse
                </div>
            </div>

            <!-- BALANCE -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm font-bold">الرصيد الصافي الحالي</p>
                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                        <i class="fa-solid fa-scale-balanced text-blue-600 dark:text-blue-400 text-sm"></i>
                    </div>
                </div>
                <div class="space-y-2">
                    @forelse($stats as $stat)
                        <div class="flex justify-between items-center border-b border-gray-50 dark:border-gray-700/50 pb-1 last:border-0 last:pb-0">
                            <span class="font-bold text-sm text-gray-700 dark:text-gray-300">{{ $stat->currency->code }}</span>
                            <span class="font-black text-lg {{ $stat->balance >= 0 ? 'text-green-600 dark:text-green-400':'text-red-600 dark:text-red-400' }}">
                            {{ number_format($stat->balance, 2) }}
                        </span>
                        </div>
                    @empty
                        <div class="text-gray-400 text-sm">0.00</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- SEARCH & FILTER FORM -->
        <form method="GET" class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1.5">ابحث عن وكيل</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم الوكيل أو الهاتف..." class="w-full border dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-2xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1.5">من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full border dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-2xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 mb-1.5">إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full border dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-2xl p-3 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl p-3.5 text-sm transition flex items-center justify-center gap-2 shadow-md shadow-blue-500/10">
                <i class="fa-solid fa-magnifying-glass"></i><span>تطبيق الفلترة</span>
            </button>
        </form>

        <!-- 📱 1. MOBILE LAYOUT (بطاقات ذكية تظهر على الجوال وتختفي على الشاشات الكبيرة) -->
        <div class="block md:hidden space-y-4">
            @forelse($agents as $agent)
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 space-y-4">
                    <!-- الوكيل والاسم -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center font-black text-blue-600 dark:text-blue-400 text-sm shrink-0">
                                {{ mb_substr($agent->name, 0, 1, 'utf-8') }}
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 dark:text-white text-base">{{ $agent->name }}</h3>
                                <p class="text-xs text-gray-400 font-medium font-mono mt-0.5" dir="ltr">{{ $agent->phone ?? 'لا يوجد هاتف' }}</p>
                            </div>
                        </div>
                        <!-- نطاق جغرافي -->
                        <span class="text-xs bg-gray-50 dark:bg-gray-900 px-3 py-1.5 rounded-xl text-gray-600 dark:text-gray-300 font-bold">
                        {{ $agent->country ?? '—' }} {{ $agent->city ? "({$agent->city})" : '' }}
                    </span>
                    </div>

                    <!-- الرصيد المالي -->
                    <div class="flex items-center justify-between bg-gray-50/50 dark:bg-gray-900/40 p-3 rounded-2xl">
                        <span class="text-xs font-bold text-gray-400">حالة صافي الرصيد:</span>
                        <span class="font-black text-base {{ $agent->balance >= 0 ? 'text-green-600 dark:text-green-400':'text-red-600 dark:text-red-400' }}">
                        {{ number_format($agent->balance, 2) }}
                    </span>
                    </div>

                    <!-- أزرار الإجراءات السريعة مريحة للمس -->
                    <div class="grid grid-cols-4 gap-2 pt-1">
                        <a href="{{ route('agents.show', $agent->id) }}"
                           class="bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white dark:bg-blue-900/20 dark:text-blue-400 px-2 py-2.5 rounded-xl text-xs font-bold transition flex flex-col items-center gap-1">
                            <i class="fa-solid fa-eye text-sm"></i> كشف
                        </a>
                        <button onclick="triggerEditAgent({{ $agent->id }}, '{{ $agent->name }}', '{{ $agent->phone }}', '{{ $agent->country }}', '{{ $agent->city }}')"
                                class="bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white dark:bg-amber-900/20 dark:text-amber-400 px-2 py-2.5 rounded-xl text-xs font-bold transition flex flex-col items-center gap-1">
                            <i class="fa-solid fa-pen-to-square text-sm"></i> تعديل
                        </button>
                        <button onclick="openPaymentModal({{ $agent->id }})"
                                class="bg-green-50 hover:bg-green-600 text-green-700 hover:text-white dark:bg-green-900/20 dark:text-green-400 px-2 py-2.5 rounded-xl text-xs font-bold transition flex flex-col items-center gap-1">
                            <i class="fa-solid fa-receipt text-sm"></i> دفع
                        </button>
                        <form method="POST" action="{{ route('agents.destroy', $agent->id) }}"
                              onsubmit="return confirm('هل أنت متأكد من حذف حساب هذا الوكيل نهائياً؟');" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button class="w-full bg-red-50 hover:bg-red-600 text-red-700 hover:text-white dark:bg-red-900/20 dark:text-red-400 px-2 py-2.5 rounded-xl text-xs font-bold transition flex flex-col items-center gap-1">
                                <i class="fa-solid fa-trash-can text-sm"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl text-center text-gray-400 border">
                    لا يوجد وكلاء مسجلين حالياً.
                </div>
            @endforelse
        </div>

        <!-- 💻 2. DESKTOP LAYOUT (الجدول التقليدي الفاخر يظهر فقط في الشاشات الكبيرة) -->
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <table class="w-full text-sm text-right whitespace-nowrap">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold border-b dark:border-gray-600">
                <tr>
                    <th class="p-4">الوكيل</th>
                    <th class="p-4">الهاتف</th>
                    <th class="p-4">النطاق الجغرافي</th>
                    <th class="p-4">حالة الرصيد</th>
                    <th class="p-4 text-center">الإجراءات والعمليات</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($agents as $agent)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center font-black text-blue-600 dark:text-blue-400 text-xs">
                                    {{ mb_substr($agent->name, 0, 1, 'utf-8') }}
                                </div>
                                <div class="font-bold text-gray-800 dark:text-white">{{ $agent->name }}</div>
                            </div>
                        </td>
                        <td class="p-4 font-medium font-mono text-gray-600 dark:text-gray-300" dir="ltr">
                            {{ $agent->phone ?? '—' }}
                        </td>
                        <td class="p-4 text-gray-500 dark:text-gray-400">
                            <span class="font-bold text-gray-700 dark:text-gray-200">{{ $agent->country ?? '—' }}</span>
                            @if($agent->city) <span class="text-xs text-gray-400">({{ $agent->city }})</span> @endif
                        </td>
                        <td class="p-4 font-black text-base {{ $agent->balance >= 0 ? 'text-green-600 dark:text-green-400':'text-red-600 dark:text-red-400' }}">
                            {{ number_format($agent->balance, 2) }}
                        </td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('agents.show', $agent->id) }}" class="bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white dark:bg-blue-900/20 dark:text-blue-400 px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <i class="fa-solid fa-eye"></i> كشف
                                </a>
                                <button onclick="triggerEditAgent({{ $agent->id }}, '{{ $agent->name }}', '{{ $agent->phone }}', '{{ $agent->country }}', '{{ $agent->city }}')" class="bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white dark:bg-amber-900/20 dark:text-amber-400 px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <i class="fa-solid fa-pen-to-square"></i> تعديل
                                </button>
                                <button onclick="openPaymentModal({{ $agent->id }})" class="bg-green-50 hover:bg-green-600 text-green-700 hover:text-white dark:bg-green-900/20 dark:text-green-400 px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1">
                                    <i class="fa-solid fa-receipt"></i> دفع
                                </button>
                                <form method="POST" action="{{ route('agents.destroy', $agent->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف حساب هذا الوكيل نهائياً؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-50 hover:bg-red-600 text-red-700 hover:text-white dark:bg-red-900/20 dark:text-red-400 px-2.5 py-1.5 rounded-xl text-xs font-bold transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-12 text-gray-400">
                            لا يوجد وكلاء مسجلين حالياً.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="pt-2">
            {{ $agents->links() }}
        </div>

    </div>

    <!-- MODALS ROOT INCLUSIONS -->
    @include('frontend.agents.partials.create_modal')

    <!-- PAYMENT MODAL -->
    <div id="paymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all duration-300">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden scale-95 transition-transform duration-150" id="paymentCard">
            <div class="border-b dark:border-gray-700 p-5 bg-gray-50/50 dark:bg-gray-800/50 flex justify-between items-center">
                <h3 class="font-black text-base text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-green-600"></i> تسجيل دفعة مالية للوكيل
                </h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white font-bold">✕</button>
            </div>
            <form method="POST" id="paymentForm" class="p-5 sm:p-6 space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-bold block mb-1.5 text-gray-500">قيمة المبلغ المالي</label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="text-xs font-bold block mb-1.5 text-gray-500">ملاحظات الدفعة</label>
                    <textarea name="notes" placeholder="اكتب تفاصيل الدفعة هنا..." class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-500 h-20 resize-none"></textarea>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl text-sm font-bold shadow-lg transition">تأكيد الحفظ</button>
                    <button type="button" onclick="closePaymentModal()" class="flex-1 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-white py-2.5 rounded-xl text-sm font-bold transition">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPaymentModal(id){
            const modal = document.getElementById('paymentModal');
            const card = document.getElementById('paymentCard');
            modal.classList.remove('hidden');
            setTimeout(() => { card.classList.replace('scale-95', 'scale-100'); }, 10);
            document.getElementById('paymentForm').setAttribute('action', "/dashboard/agents/" + id + "/payment");
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            const card = document.getElementById('paymentCard');
            card.classList.replace('scale-100', 'scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 150);
        }
    </script>

@endsection
