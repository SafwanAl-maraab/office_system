<!-- STATUS MODAL -->
<div id="statusModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">

    <div class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 p-8">

        <h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">
            تغيير حالة التأشيرة
        </h2>

        <form method="POST"
              id="statusForm"
              enctype="multipart/form-data">

            @csrf

            <!-- الحالة -->

            <div class="space-y-2">

                <label class="block text-sm font-medium">
                    اختر الحالة الجديدة
                </label>

                <select
                    name="status"
                    id="statusSelect"
                    required
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700
                           bg-white dark:bg-gray-800 text-gray-800 dark:text-white">

                    <option value="pending">
                        قيد المعالجة
                    </option>

                    <option value="issued">
                        صادرة
                    </option>

                    <option value="cancelled">
                        ملغية
                    </option>

                </select>

            </div>

            <!-- رسالة الإلغاء -->

            <div id="cancelAlert"
                 class="hidden mt-5 p-4 rounded-2xl bg-red-50 border border-red-200">

                <div class="font-bold text-red-700 mb-2">

                    سيتم إلغاء العملية بالكامل

                </div>

                <ul class="text-sm text-red-600 space-y-1">

                    <li>
                        ✓ إلغاء التأشيرة
                    </li>

                    <li>
                        ✓ إلغاء الفاتورة المرتبطة
                    </li>

                    <li>
                        ✓ إنشاء فاتورة مسترجع إذا وجد مبلغ مدفوع
                    </li>

                    <li>
                        ✓ عكس تكلفة الوكيل
                    </li>

                </ul>

            </div>

            <!-- سبب الإلغاء -->

            <div id="cancelReasonBox"
                 class="hidden mt-4 space-y-2">

                <label class="block text-sm font-medium text-red-600">

                    سبب الإلغاء

                </label>

                <textarea
                    name="cancel_reason"
                    rows="3"
                    class="w-full px-4 py-2 rounded-xl border border-red-300
                           dark:border-red-700
                           bg-white dark:bg-gray-800"
                    placeholder="اكتب سبب الإلغاء"></textarea>

            </div>

            <!-- طريقة الاسترجاع -->

            <div id="refundMethodBox"
                 class="hidden mt-4">

                <label class="block text-sm font-medium mb-3">

                    في حال وجود مبلغ مدفوع

                </label>

                <div class="space-y-3">

                    <label class="flex items-center gap-3">

                        <input type="radio"
                               name="refund_method"
                               value="cash"
                               checked>

                        <span>

                            تسليم المبلغ للعميل الآن (خصم من الخزنة)

                        </span>

                    </label>

                    <label class="flex items-center gap-3">

                        <input type="radio"
                               name="refund_method"
                               value="balance">

                        <span>

                            إضافة المبلغ إلى رصيد العميل

                        </span>

                    </label>

                </div>

            </div>

            <!-- ملف التأشيرة -->

            <div id="visaFileBox"
                 class="hidden mt-4 space-y-2">

                <label class="text-sm font-medium text-green-600">

                    ملف التأشيرة

                </label>

                <input
                    type="file"
                    name="visa_file"
                    accept="image/*,.pdf"
                    class="w-full border border-gray-300 dark:border-gray-700
                           rounded-xl px-4 py-2">

                <p class="text-xs text-gray-500">

                    صورة أو PDF

                </p>

            </div>

            <!-- الأزرار -->

            <div class="flex justify-end gap-4 pt-6">

                <button
                    type="button"
                    onclick="closeStatusModal()"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-xl">

                    إلغاء

                </button>

                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">

                    حفظ

                </button>

            </div>

        </form>

    </div>

</div>

<script>

    function openStatusModal(id)
    {
        if(!id)
        {
            alert('لم يتم العثور على التأشيرة');
            return;
        }

        let form =
            document.getElementById(
                'statusForm'
            );

        form.action =
            '/visas/' +
            id +
            '/change-status';

        document
            .getElementById(
                'statusModal'
            )
            .classList
            .remove('hidden');
    }

    function closeStatusModal()
    {
        document
            .getElementById(
                'statusModal'
            )
            .classList
            .add('hidden');
    }

    const statusSelect =
        document.getElementById(
            'statusSelect'
        );

    statusSelect.addEventListener(
        'change',
        function(){

            const cancelBox =
                document.getElementById(
                    'cancelReasonBox'
                );

            const refundBox =
                document.getElementById(
                    'refundMethodBox'
                );

            const alertBox =
                document.getElementById(
                    'cancelAlert'
                );

            const visaFileBox =
                document.getElementById(
                    'visaFileBox'
                );

            cancelBox.classList.add('hidden');
            refundBox.classList.add('hidden');
            alertBox.classList.add('hidden');
            visaFileBox.classList.add('hidden');

            if(
                this.value
                ===
                'cancelled'
            ){

                cancelBox.classList.remove('hidden');

                refundBox.classList.remove('hidden');

                alertBox.classList.remove('hidden');
            }

            if(
                this.value
                ===
                'issued'
            ){

                visaFileBox.classList.remove('hidden');
            }
        }
    );

</script>
