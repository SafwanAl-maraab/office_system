<div id="statusModal" class="fixed inset-0 z-50 hidden">

    <div class="absolute inset-0 bg-black/60"></div>

    <div class="relative w-full h-full flex items-center justify-center">

        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md">

            <h3 class="text-lg font-bold mb-4">

                تغيير حالة الحجز

            </h3>

            <form id="statusForm" method="POST">

                @csrf
                @method('PATCH')

                <div class="space-y-4">

                    <div>

                        <label class="text-gray-500 text-sm">

                            الحالة الحالية

                        </label>

                        <input id="currentStatus"
                               class="w-full border rounded-lg p-2 bg-gray-100"
                               readonly>

                    </div>


                    <div>

                        <label class="text-gray-500 text-sm">

                            الحالة الجديدة

                        </label>

                        <select id="newStatus"
                                name="status"
                                class="w-full border rounded-lg p-2">

                            <option value="pending">قيد الانتظار</option>

                            <option value="confirmed">مؤكد</option>

                            <option value="issued">تم إصدار التذكرة</option>

                            <option value="cancelled">ملغي</option>

                        </select>
                        <div id="cancelSection"
                             class="hidden mt-4 border border-red-200 bg-red-50 rounded-xl p-4">

                            <div class="font-bold text-red-700 mb-2">

                                إلغاء العملية

                            </div>

                            <div class="text-sm text-gray-700 mb-3">

                                عند الإلغاء سيتم:

                                <ul class="list-disc mr-5 mt-2 space-y-1">

                                    <li>إلغاء الحجز</li>

                                    <li>إلغاء الفاتورة</li>

                                    <li>إنشاء فاتورة مسترجع</li>

                                </ul>

                            </div>

                            <div id="cancelInfo"
                                 class="bg-white rounded-lg p-3 text-sm mb-4">
                            </div>

                            <div>

                                <label class="font-semibold block mb-2">

                                    طريقة معالجة المبلغ المدفوع

                                </label>

                                <div class="space-y-2">

                                    <label class="flex items-center gap-2">

                                        <input type="radio"
                                               name="refund_method"
                                               value="cash"
                                               checked>

                                        <span>

                    تسليم المبلغ للعميل الآن

                </span>

                                    </label>

                                    <label class="flex items-center gap-2">

                                        <input type="radio"
                                               name="refund_method"
                                               value="balance">

                                        <span>

                    إضافة المبلغ إلى رصيد العميل

                </span>

                                    </label>

                                </div>

                            </div>

                        </div>


                    </div>

                </div>

                <input type="hidden"
                       id="invoiceId"
                       name="invoice_id">

                <input type="hidden"
                       id="paidAmount"
                       name="paid_amount">

                <div class="flex justify-between mt-6">

                    <button
                        type="button"
                        onclick="closeStatusModal()"
                        class="px-4 py-2 rounded-lg bg-gray-200">

                        إلغاء

                    </button>

                    <button
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white">

                        حفظ

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

    let currentInvoice = null;

    document.querySelectorAll(".changeStatusBtn")
        .forEach(btn => {

            btn.onclick = function(){

                const bookingId =
                    this.dataset.bookingId;

                const status =
                    this.dataset.currentStatus;

                const invoiceId =
                    this.dataset.invoiceId || '';

                const paidAmount =
                    this.dataset.paidAmount || 0;

                const currency =
                    this.dataset.currency || '';

                const form =
                    document.getElementById(
                        "statusForm"
                    );

                form.action =
                    "/dashboard/bookings/"
                    + bookingId
                    + "/status";

                document
                    .getElementById(
                        "currentStatus"
                    )
                    .value = status;

                document
                    .getElementById(
                        "invoiceId"
                    )
                    .value = invoiceId;

                document
                    .getElementById(
                        "paidAmount"
                    )
                    .value = paidAmount;

                document
                    .getElementById(
                        "cancelInfo"
                    )
                    .innerHTML =

                    `
            <div>
                المبلغ المدفوع:
                <b>
                    ${paidAmount}
                    ${currency}
                </b>
            </div>
            `;

                document
                    .getElementById(
                        "cancelSection"
                    )
                    .classList
                    .add("hidden");

                document
                    .getElementById(
                        "statusModal"
                    )
                    .classList
                    .remove("hidden");
            }

        });

    document
        .getElementById(
            "newStatus"
        )
        .addEventListener(
            "change",
            function(){

                if(
                    this.value ===
                    "cancelled"
                ){

                    document
                        .getElementById(
                            "cancelSection"
                        )
                        .classList
                        .remove("hidden");

                }else{

                    document
                        .getElementById(
                            "cancelSection"
                        )
                        .classList
                        .add("hidden");
                }

            }
        );

    function closeStatusModal(){

        document
            .getElementById(
                "statusModal"
            )
            .classList
            .add("hidden");
    }

</script>
