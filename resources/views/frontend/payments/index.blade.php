@extends('frontend.layouts.app')

@section('content')

    <div class="max-w-7xl mx-auto p-4 md:p-6 space-y-10">

        {{-- الترويسة (Header) --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 print:hidden">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                    إدارة المدفوعات المالية
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    عرض وطباعة السندات والعمليات المالية المقيدة للفرع الحالي
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button onclick="openPaymentModal()"
                        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-green-600/10 active:scale-[0.98] transition-all">
                    + إضافة دفعة جديدة
                </button>
            </div>
        </div>

        {{-- شريط الفلترة والبحث --}}
        <form method="GET"
              class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-5 grid grid-cols-1 md:grid-cols-3 gap-4 mb-8 print:hidden">

            <input type="text"
                   name="client"
                   value="{{ request('client') }}"
                   placeholder="بحث باسم العميل..."
                   class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <input type="number"
                   name="invoice_number"
                   value="{{ request('invoice_number') }}"
                   placeholder="بحث برقم الفاتورة..."
                   class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none">

            <button class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-4 py-2.5 font-bold text-sm shadow-md shadow-blue-600/10 transition-all">
                بحث وتصفية
            </button>
        </form>

        {{-- شبكة بطاقات المدفوعات --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 print:hidden">

            @forelse($payments as $payment)
                @php
                    $isRefund = $payment->invoice->is_refund;
                    $currencyCode = $payment->currency->code ?? '';
                    $currencySymbol = $payment->currency->symbol ?? '';
                @endphp

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 space-y-5 transition-all hover:shadow-xl flex flex-col justify-between">

                    <div class="space-y-4">
                        {{-- رأس البطاقة --}}
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase font-mono">عملية رقم</div>
                                <div class="font-black text-xl text-gray-800 dark:text-white">#{{ $payment->id }}</div>
                            </div>

                            <span class="text-xs font-bold px-3 py-1 rounded-full shadow-sm
                                {{ $isRefund ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400' }}">
                                {{ $isRefund ? '🔁 استرجاع مالي' : '💵 قيد دفعة' }}
                            </span>
                        </div>

                        {{-- مساحة المبلغ المالي --}}
                        <div class="text-center py-4 rounded-2xl border
                                {{ $isRefund ? 'bg-rose-50/50 dark:bg-rose-950/10 border-rose-100 dark:border-rose-900/20' : 'bg-emerald-50/50 dark:bg-emerald-950/10 border-emerald-100 dark:border-emerald-900/20' }}">
                            <div class="text-xs text-gray-400 dark:text-gray-500">المبلغ المقيد</div>
                            <div class="text-2xl font-black mt-1 font-mono {{ $isRefund ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ number_format(abs($payment->amount), 2) }}
                                <span class="text-xs font-bold text-gray-400">{{ $currencyCode }}</span>
                            </div>
                        </div>

                        {{-- التفاصيل والبيانات الحسابية --}}
                        <div class="space-y-2.5 text-sm border-t border-gray-50 dark:border-gray-700/50 pt-3 text-gray-700 dark:text-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 dark:text-gray-500">العميل والمستفيد:</span>
                                <span class="font-bold text-gray-900 dark:text-gray-100 text-xs">{{ $payment->invoice->client->full_name }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 dark:text-gray-500">مرجع الفاتورة:</span>
                                <span class="font-mono text-xs bg-gray-50 dark:bg-gray-900 px-2 py-0.5 rounded border dark:border-gray-700">#{{ $payment->invoice->id }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 dark:text-gray-500">طريقة الدفع:</span>
                                @if($payment->payment_method === 'refund' || $isRefund)
                                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400">عبر الاسترداد المحاسبي</span>
                                @else
                                    <span class="text-xs font-semibold bg-gray-100 dark:bg-gray-700 px-2.5 py-0.5 rounded-lg">{{ $payment->payment_method }}</span>
                                @endif
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 dark:text-gray-500">الموظف المسؤول:</span>
                                <span class="text-xs font-medium">{{ $payment->creator->full_name ?? 'المركز الرئيسي' }}</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400 dark:text-gray-500">تاريخ القيد:</span>
                                <span class="font-mono text-xs text-gray-400">{{ $payment->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- أزرار العمليات والطباعة الفورية في البطاقة --}}
                    <div class="border-t border-gray-100 dark:border-gray-700/60 pt-4 mt-4 flex gap-2">
                        <button type="button"
                                onclick="showReceiptModal({{ json_encode([
                                    'id' => $payment->id,
                                    'type' => $isRefund ? 'refund' : 'payment',
                                    'amount' => number_format(abs($payment->amount), 2),
                                    'currency' => $currencyCode,
                                    'client' => $payment->invoice->client->full_name,
                                    'invoice' => $payment->invoice->id,
                                    'method' => $payment->payment_method,
                                    'creator' => $payment->creator->full_name ?? 'المركز الرئيسي',
                                    'date' => $payment->created_at->format('Y-m-d H:i')
                                ]) }})"
                                class="flex-1 text-center py-2 text-xs font-bold rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-950/40 dark:text-blue-400 dark:hover:bg-blue-900/40 transition-colors">
                            🔍 عرض التذكرة والسند
                        </button>
                    </div>

                </div>

            @empty

                <div class="col-span-full text-center text-gray-400 py-20 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                    🔔 لا توجد عمليات مالية مسجلة حالياً للفرع.
                </div>

            @endforelse

        </div>

        {{-- الترقيم التلقائي (Pagination) --}}
        <div class="print:hidden">
            {{ $payments->links() }}
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- نَافِذَةُ عَرْضِ السَّنَدِ وَالطِّبَاعَةِ (Receipt Preview & Print Modal) --}}
    {{-- ========================================== --}}
    <div id="receiptModal" class="fixed inset-0 z-50 overflow-y-auto hidden print:block" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            {{-- الخلفية المظلمة --}}
            <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-950/80 transition-opacity print:hidden" onclick="closeReceiptModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- جسم السند المالي المخصص للطباعة والعرض --}}
            <div class="inline-block align-bottom bg-white dark:bg-gray-900 text-right rounded-3xl shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gray-100 dark:border-gray-800 p-6 md:p-8 print:p-0 print:border-none print:shadow-none print:my-0">

                {{-- أزرار تحكم المودال - تختفي فوراً عند أمر الطباعة --}}
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4 mb-6 print:hidden">
                    <h3 class="font-black text-lg text-gray-900 dark:text-white flex items-center gap-1.5">
                        <span>🧾</span> مراجعة وطباعة السند المالي
                    </h3>
                    <button onclick="closeReceiptModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                </div>

                {{-- منطقة السند الفعلي المطبوع --}}
                <div id="printArea" class="space-y-6">

                    {{-- ترويسة السند الرسمية --}}
                    <div class="flex justify-between items-center border-b-2 border-dashed border-gray-200 dark:border-gray-700 pb-5">
                        <div>
                            <h2 id="modalTypeBadge" class="text-xl font-black text-gray-900 dark:text-white">
                                --
                            </h2>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mt-1">
                                {{ config('app.name', 'نظام السفر والحسابات') }}
                            </p>
                        </div>
                        <div class="text-left">
                            <span class="text-[10px] font-bold text-gray-400 uppercase font-mono block">رقم السند المرجعي</span>
                            <span id="modalReceiptId" class="text-2xl font-black font-mono text-blue-600">#00</span>
                        </div>
                    </div>

                    {{-- خانة السعر الضخم --}}
                    <div class="bg-gray-50 dark:bg-gray-950 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 text-center">
                        <span class="text-xs text-gray-400 font-medium block">المبلغ المقبوض والمثبت بالحساب</span>
                        <div class="text-3xl font-black font-mono text-gray-900 dark:text-white mt-1.5">
                            <span id="modalAmount">0.00</span>
                            <span id="modalCurrency" class="text-sm font-bold text-blue-600">--</span>
                        </div>
                    </div>

                    {{-- جدول حقول تفاصيل السند المالي --}}
                    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
                        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800/50">
                            <span class="text-gray-400">استلمنا من السيد / السيدة:</span>
                            <span id="modalClientName" class="font-bold text-gray-900 dark:text-white">--</span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800/50">
                            <span class="text-gray-400">وذلك لدفعه عن الفاتورة الملحقة:</span>
                            <span id="modalInvoiceId" class="font-mono font-bold text-gray-800 dark:text-gray-200">--</span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800/50">
                            <span class="text-gray-400">طريقة وقناة السداد المالي:</span>
                            <span id="modalPaymentMethod" class="font-medium">--</span>
                        </div>

                        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800/50">
                            <span class="text-gray-400">تاريخ تحرير السند الزمني:</span>
                            <span id="modalDate" class="font-mono text-xs text-gray-500">--</span>
                        </div>

                        <div class="flex justify-between py-2">
                            <span class="text-gray-400">أمين الصندوق المسؤول:</span>
                            <span id="modalCreatorName" class="font-bold text-xs">--</span>
                        </div>
                    </div>

                    {{-- قسم التواقيع والختم الرسمي للطباعة --}}
                    <div class="pt-8 border-t border-dashed border-gray-200 dark:border-gray-700 grid grid-cols-2 gap-4 text-center text-xs">
                        <div>
                            <p class="text-gray-400">توقيع المستلم / العميل</p>
                            <div class="h-12"></div>
                            <p class="border-t border-gray-200 dark:border-gray-800 pt-2 font-medium">............................</p>
                        </div>
                        <div>
                            <p class="text-gray-400">ختم وتوقيع أمين الصندوق</p>
                            <div class="h-12"></div>
                            <p class="border-t border-gray-200 dark:border-gray-800 pt-2 font-bold text-blue-600">{{ config('app.name') }}</p>
                        </div>
                    </div>

                </div>

                {{-- أزرار ذيل المودال للطباعة الفورية --}}
                <div class="mt-8 pt-4 border-t border-gray-100 dark:border-gray-800 flex gap-3 print:hidden">
                    <button onclick="closeReceiptModal()" type="button" class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 text-xs font-bold transition-colors">
                        إلغاء وإغلاق
                    </button>
                    <button onclick="window.print()" type="button" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-md shadow-blue-600/10 transition-colors">
                        🖨️ طباعة السند الآن
                    </button>
                </div>

            </div>
        </div>
    </div>

    @include('frontend.payments.modals.add_payment')

    {{-- ========================================== --}}
    {{-- سْكْرِيبْتْ جَافَا سْكْرِيبْتْ لِلتَّحَكُّمِ التَّفَاعُلِيّ --}}
    {{-- ========================================== --}}
    <script>
        function showReceiptModal(data) {
            // تعبئة البيانات ميكانيكياً داخل المودال قبل الفتح
            document.getElementById('modalReceiptId').innerText = '#' + data.id;
            document.getElementById('modalAmount').innerText = data.amount;
            document.getElementById('modalCurrency').innerText = data.currency;
            document.getElementById('modalClientName').innerText = data.client;
            document.getElementById('modalInvoiceId').innerText = '#' + data.invoice;
            document.getElementById('modalPaymentMethod').innerText = data.method === 'refund' ? 'استرداد مالي محاسبي' : data.method;
            document.getElementById('modalDate').innerText = data.date;
            document.getElementById('modalCreatorName').innerText = data.creator;

            // تحديث ترويسة ونوع السند بناءً على الحالة
            const typeBadge = document.getElementById('modalTypeBadge');
            if(data.type === 'refund') {
                typeBadge.innerText = 'سند صرف مسترجع رحلة';
                typeBadge.className = "text-xl font-black text-rose-600";
            } else {
                typeBadge.innerText = 'سند قبض واستلام نقدي';
                typeBadge.className = "text-xl font-black text-emerald-600";
            }

            // إظهار المودال بالكامل في الشاشة
            document.getElementById('receiptModal').classList.remove('hidden');
        }

        function closeReceiptModal() {
            document.getElementById('receiptModal').classList.add('hidden');
        }
    </script>

    {{-- تخصيص معايير الـ CSS لإنتاج طباعة نظيفة ورسمية --}}
    <style>
        @media print {
            /* إخفاء عناصر النظام والروابط والخلفيات */
            body * {
                visibility: hidden;
                background-color: transparent !important;
                box-shadow: none !important;
            }
            /* تحديد مساحة السند المالي فقط وإظهارها للطباعة بالكامل */
            #receiptModal, #receiptModal * {
                visibility: visible;
            }
            #receiptModal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print\:hidden {
                display: none !important;
            }
        }
    </style>

@endsection
