<div id="editBookingModal"
     class="fixed inset-0 z-50 hidden items-center justify-center">

    <!-- الخلفية -->

    <div class="absolute inset-0 bg-black/60" ></div>


    <!-- الحاوية -->

    <div class="relative w-full h-full flex items-end sm:items-center justify-center p-2 sm:p-6">

        <div
            class="w-full sm:max-w-2xl md:max-w-3xl
        bg-white dark:bg-gray-900
        rounded-t-3xl sm:rounded-3xl
        shadow-2xl
        max-h-[95vh]
        overflow-y-auto">


            <div class="p-5 sm:p-6 space-y-5">


                <!-- Header -->

                <div class="flex justify-between items-center">

                    <h3 class="text-lg sm:text-xl font-bold dark:text-white">
                        تعديل الحجز
                    </h3>

                    <button
                        type="button"
                        onclick="closes()"
                        class="text-gray-500 hover:text-red-500 text-xl">
                        ✕
                    </button>

                </div>


                <form id="editBookingForm" method="POST">

                    @csrf
                    @method('PUT')


                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                        <!-- العميل -->

                        <div>

                            <label class="text-sm text-gray-500">
                                العميل
                            </label>

                            <select name="client_id"
                                    id="editClient"
                                    class="w-full border rounded-xl px-4 py-2 dark:bg-gray-800 dark:text-white">

                                @foreach($clients as $client)

                                    <option value="{{ $client->id }}">
                                        {{ $client->full_name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- الرحلة -->

                        <div>

                            <label class="text-sm text-gray-500">
                                الرحلة
                            </label>

                            <select name="trip_id"
                                    id="editTrip"
                                    class="w-full border rounded-xl px-4 py-2 dark:bg-gray-800 dark:text-white">

                                @foreach($trips as $trip)

                                    <option
                                        value="{{ $trip->id }}"
                                        data-sale="{{ $trip->sale_price }}"
                                        data-purchase="{{ $trip->purchase_price }}"
                                        data-currency="{{ $trip->currency_id }}">

                                        {{ $trip->from_city }} → {{ $trip->to_city }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <!-- المقعد -->

                        <div>

                            <label class="text-sm text-gray-500">
                                المقعد
                            </label>

                            <input
                                name="seat_number"
                                id="editSeat"
                                class="w-full border rounded-xl px-4 py-2">

                        </div>


                        <!-- التكلفة -->

                        <div>

                            <label class="text-sm text-gray-500">
                                التكلفة
                            </label>

                            <input
                                id="editPurchase"
                                class="w-full border rounded-xl px-4 py-2 bg-gray-100"
                                readonly>

                        </div>


                        <!-- سعر البيع -->

                        <div>

                            <label class="text-sm text-gray-500">
                                سعر البيع
                            </label>

                            <input
                                id="editSale"
                                class="w-full border rounded-xl px-4 py-2 bg-gray-100"
                                readonly>

                        </div>


                        <!-- السعر النهائي -->

                        <div>

                            <label class="text-sm text-gray-500">
                                السعر النهائي
                            </label>

                            <input
                                id="editFinal"
                                class="w-full border rounded-xl px-4 py-2 bg-gray-100"
                                readonly>

                        </div>


                        <!-- العملة -->

                        <div class="sm:col-span-2">

                            <label class="text-sm text-gray-500">
                                العملة
                            </label>

                            <input
                                id="editCurrency"
                                class="w-full border rounded-xl px-4 py-2 bg-gray-100"
                                readonly>

                        </div>


                    </div>


                    <!-- معلومات الفاتورة -->

                    <div
                        class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl text-sm">

                        <div class="flex justify-between">

                            <span>المدفوع</span>

                            <span
                                id="paidAmount"
                                class="text-green-600 font-semibold"></span>

                        </div>

                        <div class="flex justify-between mt-1">

                            <span>المتبقي</span>

                            <span
                                id="remainingAmount"
                                class="text-red-600 font-semibold"></span>

                        </div>

                    </div>


                    <!-- الأزرار -->

                    <div class="flex gap-3 pt-3">

                        <button
                            type="button"
                            onclick="closes()"
                            class="flex-1 border py-3 rounded-xl">

                            إلغاء

                        </button>

                        <button
                            class="flex-1 bg-blue-600 text-white py-3 rounded-xl">

                            حفظ التعديل

                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

</div>

<script>

    const editTrip = document.getElementById("editTrip")

    /* تحديث الأسعار عند تغيير الرحلة */

    editTrip.addEventListener("change",function(){

        const option = this.options[this.selectedIndex]

        const purchase = option.dataset.purchase
        const sale = option.dataset.sale
        const currency = option.dataset.currency

        document.getElementById("editPurchase").value = purchase
        document.getElementById("editSale").value = sale
        document.getElementById("editFinal").value = sale
        document.getElementById("editCurrency").value = currency

    })


    /* فتح مودال التعديل */

    document.querySelectorAll(".editBookingBtn")
        .forEach(btn=>{

            btn.onclick = function(){

                const data = JSON.parse(this.dataset.booking)

                const form = document.getElementById("editBookingForm")

                form.action = "/dashboard/bookings/"+data.id


                document.getElementById("editClient").value = data.client_id
                document.getElementById("editTrip").value = data.trip_id
                document.getElementById("editSeat").value = data.seat_number


                document.getElementById("editPurchase").value = data.purchase_price
                document.getElementById("editSale").value = data.sale_price
                document.getElementById("editFinal").value = data.invoice.total_amount
                document.getElementById("editCurrency").value = data.currency_id ?? ""


                document.getElementById("paidAmount").innerText = data.invoice.paid_amount
                document.getElementById("remainingAmount").innerText = data.invoice.remaining_amount


                document.getElementById("editBookingModal").classList.remove("hidden")

            }

        })


    /* إغلاق المودال */

    function closes(){
        document.getElementById("editBookingModal").classList.add("hidden")
    }

</script>
