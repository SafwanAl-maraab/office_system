@extends('frontend.layouts.app')

@section('title','تفاصيل السند الأرشيفية')
@section('subtitle','عرض البيانات الرسمية المقيدة للسند المالي')

@section('content')

    <div class="max-w-4xl mx-auto space-y-6">

        {{-- لوحة التحكم العلوية والرجوع --}}
        <div class="flex justify-between items-center bg-white dark:bg-gray-900 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <a href="{{ route('client-vouchers.index') }}"
               class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 text-sm font-bold transition-all flex items-center gap-1.5">
                ⬅️ العودة لقائمة السندات
            </a>

            <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-bold text-sm shadow-md transition-all flex items-center gap-1.5 print:hidden">
                🖨️ طباعة مستند السند
            </button>
        </div>

        {{-- وثيقة بيانات السند الأساسية الصرفة (Voucher Document Details) --}}
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden p-6 md:p-8 space-y-8 relative">

            {{-- ترويسة المستند المالي --}}
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🧾</span>
                        <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white">
                            مستند مالي رسمي
                        </h2>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                        {{ config('app.name', 'نظام المبيعات والحسابات') }}
                    </p>
                </div>

                <div>
                    @if($voucher->type === 'receipt')
                        <span class="px-5 py-2 text-sm font-extrabold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 shadow-sm">
                            سند قبض مالي
                        </span>
                    @else
                        <span class="px-5 py-2 text-sm font-extrabold rounded-full bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 shadow-sm">
                            سند صرف مالي
                        </span>
                    @endif
                </div>
            </div>

            {{-- تفاصيل السند المالية والبيانات المقيدة --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- مربع العميل المكلف --}}
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800/60">
                    <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 block">صاحب الحساب المالي (العميل):</span>
                    <span class="text-base font-bold text-gray-800 dark:text-gray-100 block mt-1">
                        {{ $voucher->client->full_name }}
                    </span>
                    <span class="text-xs font-mono text-gray-400 dark:text-gray-500 block mt-0.5">
                        📞 {{ $voucher->client->phone }}
                    </span>
                </div>

                {{-- مربع رقم السند والمبلغ الرسمي --}}
                <div class="p-4 rounded-2xl bg-indigo-50/40 dark:bg-indigo-950/10 border border-indigo-100/50 dark:border-indigo-900/20">
                    <span class="text-xs font-semibold text-indigo-500 dark:text-indigo-400 block">رقم وتكلفة السند الإجمالية:</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-black font-mono text-indigo-600 dark:text-indigo-400">
                            {{ number_format($voucher->amount, 2) }}
                        </span>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500">
                            {{ $voucher->currency->code }}
                        </span>
                    </div>
                    <span class="text-xs font-mono text-gray-400 block mt-0.5">
                        كود السند التلقائي: #{{ $voucher->id }}
                    </span>
                </div>

            </div>

            {{-- الشبكة الإدارية لتفاصيل القيد والتدقيق --}}
            <div class="border-t border-gray-100 dark:border-gray-800 pt-6">
                <h3 class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 tracking-wider mb-4 flex items-center gap-1">
                    📋 معلومات التدقيق والتسجيل الإداري
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6 text-sm text-gray-700 dark:text-gray-300">

                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">عملة القيد الأساسية:</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200 mt-1 flex items-center gap-1">
                            <span class="font-mono bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded border dark:border-gray-700 text-xs">{{ $voucher->currency->code }}</span>
                            <span class="text-xs text-gray-400">({{ $voucher->currency->name ?? 'العملة المعتمدة' }})</span>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 dark:text-gray-500">الموظف المسجل المقيد للعملية:</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200 mt-1">
                            {{ $voucher->employee->full_name ?? 'المركز الرئيسي' }}
                        </p>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <p class="text-xs text-gray-400 dark:text-gray-500">تاريخ ووقت تحرير المستند المالي:</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200 font-mono mt-1 text-xs">
                            📅 {{ $voucher->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- الملاحظات والبيان المحاسبي --}}
            <div class="border-t border-gray-100 dark:border-gray-800 pt-6 bg-gray-50/50 dark:bg-gray-900/30 p-4 rounded-2xl border dark:border-gray-800/80">
                <p class="text-xs font-bold uppercase text-gray-400 dark:text-gray-500 tracking-wider">
                    📝 البيان والشرط الإضافي للسند (الملاحظات)
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 leading-relaxed bg-white dark:bg-gray-900 p-3 rounded-xl border border-gray-100 dark:border-gray-800/50 min-h-[60px]">
                    {{ $voucher->notes ?? 'لم يتم إدراج أي ملاحظات تكميلية أو بيان إضافي لهذا السند المالي.' }}
                </p>
            </div>

            {{-- ملاحظة تذكيرية محاسبية أسفل الصفحة للتأكيد على الموظفين --}}
            <div class="rounded-xl bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100/50 dark:border-amber-900/20 p-3 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                <span>💡</span>
                <span>بناءً على التحديث المحاسبي الأخير؛ الحركات المالية والتسويات والتحويلات تعرض حصراً في **دفتر الأستاذ (Client Ledger)** وليس هنا.</span>
            </div>

        </div>

    </div>

@endsection
