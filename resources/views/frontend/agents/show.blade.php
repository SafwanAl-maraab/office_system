@extends('frontend.layouts.app')

@section('title', $agent->name)

@section('content')

    <div class="max-w-[1500px] mx-auto px-4 sm:px-6 space-y-8 antialiased text-gray-900 dark:text-gray-100 font-sans selection:bg-blue-500/10">

        <!-- ─── 1. SAAS HERO HEADER WITH BACK BUTTON ─── -->
        <!-- ─── 1. SAAS HERO HEADER WITH BACK BUTTON (SVG VERSION) ─── -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-gray-100 dark:border-gray-800/80">
            <div class="flex items-center gap-4">
                <!-- Back Button Tool (SVG) -->
                <a href="{{ route('agents.index') }}"
                   class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/70 hover:bg-gray-50 dark:hover:bg-gray-700/80 flex items-center justify-center text-gray-500 dark:text-gray-300 transition-all duration-200 shadow-sm shrink-0 group"
                   title="الرجوع لصفحة إدارة الوكلاء">
                    <!-- أيقونة سهم للرجوع SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 group-hover:translate-x-1 transition-transform">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6.75 6.75-6.75 6.75M3.75 12h16.5" />
                    </svg>
                </a>

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-600/5 dark:bg-blue-500/10 border border-blue-500/10 flex items-center justify-center font-black text-blue-600 dark:text-blue-400 text-base shadow-sm">
                        {{ mb_substr($agent->name, 0, 1, 'utf-8') }}
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight flex items-center gap-2">
                            <span>{{ $agent->name }}</span>
                            <span class="text-[11px] bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-medium px-2 py-0.5 rounded-full border border-emerald-500/10">نشط</span>
                        </h1>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 font-normal">
                            المعرّف الحسابي الفريد للوكيل: <span class="font-mono bg-gray-50 dark:bg-gray-900 px-1.5 py-0.5 rounded text-gray-500">#AG-{{ str_pad($agent->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- SaaS Action Triggers (SVG) -->
            <div class="flex gap-2.5 w-full md:w-auto">
                <button
                    onclick="openSaaSPaymentModal()"
                    class="flex-1 md:flex-none bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl text-xs font-semibold tracking-wide shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center gap-2">
                    <!-- أيقونة زائد SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>تسجيل دفعة مالية</span>
                </button>

                <a
                    href="{{ route('agents.statement.pdf', $agent->id) }}"
                    class="flex-1 md:flex-none bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/60 text-gray-700 dark:text-gray-200 px-5 py-3 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-2 shadow-sm">
                    <!-- أيقونة تصدير/تحميل SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>تصدير البيانات</span>
                </a>
            </div>
        </div>

        <!-- ─── 2. CONTEXT PROFILE INFO ─── -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 p-2 bg-gray-50/50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800/60 rounded-2xl">
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800/40">
                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 block uppercase tracking-wider">قناة الاتصال</span>
                <span class="font-mono font-bold text-sm text-gray-700 dark:text-gray-200 block mt-1" dir="ltr">{{ $agent->phone ?? '—' }}</span>
            </div>
            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800/40">
                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 block uppercase tracking-wider">الدولة المعتمدة</span>
                <span class="font-bold text-sm text-gray-700 dark:text-gray-200 block mt-1">{{ $agent->country ?? '—' }}</span>
            </div>
            <div class="col-span-2 sm:col-span-1 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800/40">
                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 block uppercase tracking-wider">المدينة المرجعية</span>
                <span class="font-bold text-sm text-gray-700 dark:text-gray-200 block mt-1">{{ $agent->city ?? '—' }}</span>
            </div>
        </div>

        <!-- ─── 3. LARGE FINANCIAL STATS GRID (MAGNIFIED CARDS) ─── -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Due Block -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-800/80 rounded-2xl p-6 sm:p-7 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b dark:border-gray-700/50 pb-3">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">إجمالي المستحقات المترتبة</span>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700/30">
                    @foreach($financialStats as $stat)
                        <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                            <span class="text-xs font-black font-mono bg-gray-50 dark:bg-gray-900 text-gray-500 px-2 py-1 rounded-md">{{ $stat->currency->code }}</span>
                            <span class="font-black text-xl text-gray-800 dark:text-gray-100">{{ number_format($stat->total_due, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Paid Block -->
            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-800/80 rounded-2xl p-6 sm:p-7 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b dark:border-gray-700/50 pb-3">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">إجمالي المدفوعات المستلمة</span>
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700/30">
                    @foreach($financialStats as $stat)
                        <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                            <span class="text-xs font-black font-mono bg-gray-50 dark:bg-gray-900 text-gray-500 px-2 py-1 rounded-md">{{ $stat->currency->code }}</span>
                            <span class="font-black text-xl text-gray-800 dark:text-gray-100">{{ number_format(abs($stat->total_paid), 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Net Remaining -->
            <div class="bg-gradient-to-b from-gray-50/30 to-white dark:from-gray-800/40 dark:to-gray-800 border border-gray-100 dark:border-gray-800/80 rounded-2xl p-6 sm:p-7 space-y-4 shadow-sm">
                <div class="flex items-center justify-between border-b dark:border-gray-700/50 pb-3">
                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">صافي رصيد التسوية الحالي</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-gray-700/30">
                    @foreach($financialStats as $stat)
                        <div class="flex justify-between items-center py-3 first:pt-0 last:pb-0">
                            <span class="text-xs font-black font-mono bg-gray-50 dark:bg-gray-900 text-gray-500 px-2 py-1 rounded-md">{{ $stat->currency->code }}</span>
                            <span class="font-black text-xl {{ $stat->balance >= 0 ? 'text-emerald-600 dark:text-emerald-400':'text-rose-600 dark:text-rose-400' }}">
                            {{ number_format($stat->balance, 2) }}
                        </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- ─── 4. LARGE WALLETS GRIDS (MAGNIFIED WALLETS) ─── -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($balances as $b)
                <div class="bg-white dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-100 dark:border-gray-800/70 flex items-center justify-between shadow-sm hover:shadow transition duration-200">
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 block uppercase tracking-wider">محفظة {{ $b->currency->code }}</span>
                        <span class="text-xl sm:text-2xl font-black tracking-tight block mt-1 {{ $b->total >= 0 ? 'text-gray-800 dark:text-gray-100':'text-rose-600 dark:text-rose-400' }}">
                        {{ number_format($b->total, 2) }}
                    </span>
                    </div>
                    <div class="text-xs font-black px-3 py-1.5 bg-gray-50 dark:bg-gray-900 rounded-xl text-gray-400 dark:text-gray-500 border dark:border-gray-700/40">
                        {{ $b->currency->symbol }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ─── 5. THE SaaS LEDGER (TRANS-LIST & TABLE) ─── -->
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">

            <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/20 dark:bg-gray-800/10">
                <div>
                    <h2 class="font-bold text-sm text-gray-800 dark:text-gray-100">دفتر الحركات التفصيلي</h2>
                    <p class="text-[11px] text-gray-400 mt-0.5">مراقبة المعاملات المالية الموثقة للوكيل الحالي</p>
                </div>
                <div class="text-[10px] bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 px-2.5 py-1 rounded-md font-mono border dark:border-gray-700/40">
                    TOTAL: {{ $transactions->total() }}
                </div>
            </div>

            <!-- 📱 SaaS MOBILE FEED (أقل من md) -->
            <div class="block md:hidden divide-y divide-gray-50 dark:divide-gray-800/50">
                @forelse($transactions as $t)
                    <div class="p-4 flex flex-col gap-2.5 hover:bg-gray-50/40 dark:hover:bg-gray-900/10 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono text-gray-400">{{ $t->created_at->format('Y-m-d') }}</span>
                            <div>
                                @if($t->type == 'visa_cost')
                                    <span class="bg-blue-50/80 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 text-[10px] font-bold px-2 py-0.5 rounded-full">تأشيرة</span>
                                @elseif($t->type == 'payment')
                                    <span class="bg-emerald-50/80 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-full">دفعة</span>
                                @elseif($t->type == 'booking_cost')
                                    <span class="bg-purple-50/80 text-purple-600 dark:bg-purple-950/30 dark:text-purple-400 text-[10px] font-bold px-2 py-0.5 rounded-full">حجز سفر</span>
                                @else
                                    <span class="bg-amber-50/80 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded-full">تعديل</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <div class="text-gray-400">
                                @if($t->visa) <span class="font-mono text-gray-600 dark:text-gray-300">#{{ $t->visa->visa_number }}</span>
                                @elseif($t->booking) <span class="text-gray-600 dark:text-gray-300">حجز بفتحة {{ $t->booking->trip->bus->plate_number }}</span>
                                @else <span class="text-gray-300 dark:text-gray-700">—</span> @endif
                            </div>
                            <div class="font-bold {{ $t->amount < 0 ? 'text-rose-600 dark:text-rose-400':'text-emerald-600 dark:text-emerald-400' }}">
                                {{ number_format($t->amount, 2) }} <span class="text-[10px] font-mono text-gray-400">{{ $t->currency->code }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 text-xs text-gray-400">لا توجد سجلات متوفرة.</div>
                @endforelse
            </div>

            <!-- 💻 SaaS ULTRA-CLEAN TABLE (أعلى من md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-xs text-right whitespace-nowrap">
                    <thead class="bg-gray-50/60 dark:bg-gray-800/40 text-gray-400 dark:text-gray-400 font-semibold border-b border-gray-100 dark:border-gray-800">
                    <tr>
                        <th class="p-4">التاريخ</th>
                        <th class="p-4">النوع</th>
                        <th class="p-4">المرجع السحابي</th>
                        <th class="p-4">المبلغ</th>
                        <th class="p-4">العملة</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-900/10 transition duration-150">
                            <td class="p-4 font-mono text-gray-400 dark:text-gray-500">{{ $t->created_at->format('Y-m-d') }}</td>
                            <td class="p-4">
                                @if($t->type == 'visa_cost')
                                    <span class="text-blue-600 dark:text-blue-400 font-semibold inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-blue-500"></span>تكلفة تأشيرة</span>
                                @elseif($t->type == 'payment')
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-emerald-500"></span>دفعة حسابية</span>
                                @elseif($t->type == 'booking_cost')
                                    <span class="text-purple-600 dark:text-purple-400 font-semibold inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-purple-500"></span>حجز سفر</span>
                                @else
                                    <span class="text-amber-600 dark:text-amber-400 font-semibold inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-amber-500"></span>تعديل</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-500 dark:text-gray-400">
                                @if($t->visa) <span class="font-mono text-xs">{{ $t->visa->visa_number }}</span>
                                @elseif($t->booking) <span>حجز {{ $t->booking->trip->bus->plate_number }}</span>
                                @else <span class="text-gray-300 dark:text-gray-700">—</span> @endif
                            </td>
                            <td class="p-4 font-bold text-sm {{ $t->amount < 0 ? 'text-rose-600':'text-emerald-600' }}">
                                {{ number_format($t->amount, 2) }}
                            </td>
                            <td class="p-4 font-bold text-gray-400 font-mono">{{ $t->currency->code }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-12 text-gray-400 font-medium">لا توجد عمليات مسجلة في كشف حساب الوكيل.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION BAR -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/20">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    <!-- ─── 6. SaaS SIDE-DRAWER / MODAL ─── -->
    <div id="saasPaymentModal" class="fixed inset-0 bg-gray-950/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-all duration-200">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm overflow-hidden scale-95 opacity-0 transition-all duration-200 border border-gray-100 dark:border-gray-800" id="saasPaymentCard">

            <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-sm text-gray-800 dark:text-gray-100">إنشاء دفعة مالية قيداً</h3>
                    <p class="text-[10px] text-gray-400">إدخال الدفعات المباشرة إلى الخزينة السحابية</p>
                </div>
                <button onclick="closeSaaSPaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xs font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('agents.pay', $agent->id) }}" class="p-5 space-y-4">
                @csrf

                <div>
                    <label class="text-[11px] font-medium block mb-1 text-gray-400">المبلغ</label>
                    <input type="number" step="0.01" name="amount" required placeholder="0.00" class="w-full border border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-1 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="text-[11px] font-medium block mb-1 text-gray-400">العملة المقبولة</label>
                    <select name="currency_id" class="w-full border border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white rounded-xl px-3.5 py-2.5 text-xs font-semibold focus:ring-1 focus:ring-blue-500 outline-none transition">
                        @foreach($agentCurrencies as $currency)
                            <option value="{{ $currency->currency_id }}">
                                {{ $currency->currency->code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-medium block mb-1 text-gray-400">الوصف / البيان الحسابي</label>
                    <textarea name="description" placeholder="ملاحظات الحركة الداخلية..." class="w-full border border-gray-200 dark:border-gray-800 dark:bg-gray-950 dark:text-white rounded-xl px-3.5 py-2.5 text-xs outline-none focus:ring-1 focus:ring-blue-500 h-20 resize-none transition"></textarea>
                </div>

                <div class="flex gap-2 pt-2 text-xs">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl font-semibold transition shadow-sm">تأكيد وترحيل</button>
                    <button type="button" onclick="closeSaaSPaymentModal()" class="flex-1 bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-200 py-2.5 rounded-xl font-semibold transition">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSaaSPaymentModal(){
            const modal = document.getElementById('saasPaymentModal');
            const card = document.getElementById('saasPaymentCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.replace('scale-95', 'scale-100');
                card.classList.replace('opacity-0', 'opacity-100');
            }, 30);
        }

        function closeSaaSPaymentModal() {
            const modal = document.getElementById('saasPaymentModal');
            const card = document.getElementById('saasPaymentCard');
            card.classList.replace('scale-100', 'scale-95');
            card.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 180);
        }
    </script>

@endsection
