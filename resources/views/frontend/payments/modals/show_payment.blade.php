{{-- منطقة السند الفعلي المطبوع --}}
<div id="printArea" class="space-y-6">

    {{-- ترويسة السند الرسمية --}}
    <div class="flex justify-between items-center border-b-2 border-dashed border-gray-200 dark:border-gray-700 pb-5">
        <div>
            <h2 id="modalTypeBadge" class="text-xl font-black">
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

    {{-- خانة السعر الضخم الديناميكية --}}
    <div id="modalAmountBox" class="p-5 rounded-2xl border text-center transition-colors duration-300">
        <span id="modalAmountLabel" class="text-xs text-gray-400 font-medium block">المبلغ المالي المقيد</span>
        <div class="text-3xl font-black font-mono mt-1.5">
            <span id="modalAmount">0.00</span>
            <span id="modalCurrency" class="text-sm font-bold text-blue-600">--</span>
        </div>
    </div>

    {{-- جدول حقول تفاصيل السند المالي الديناميكي --}}
    <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800/50">
            <span id="modalClientLabel" class="text-gray-400">الطرف المستفيد/العميل:</span>
            <span id="modalClientName" class="font-bold text-gray-900 dark:text-white">--</span>
        </div>

        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800/50">
            <span class="text-gray-400">وذلك عن الفاتورة الملحقة:</span>
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
            <p id="modalSignatureLabel" class="text-gray-400">توقيع العميل</p>
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
