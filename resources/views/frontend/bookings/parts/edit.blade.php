<div id="editModal"
     class="fixed inset-0 hidden items-center justify-center bg-black/50 z-50">

    <div
        class="bg-white dark:bg-gray-800 w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-xl shadow-lg p-6">

        <h2 class="text-xl font-bold mb-4 dark:text-white">
            تعديل الحجز
        </h2>


        <form id="editForm" method="POST">

            @csrf
            @method('PUT')


            <input type="hidden" id="edit_id">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- CLIENT --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        العميل
                    </label>

                    <select
                        name="client_id"
                        id="edit_client_id"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                        @foreach($clients as $client)

                            <option value="{{ $client->id }}">
                                {{ $client->full_name }}
                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- TRIP --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        الرحلة
                    </label>

                    <select
                        name="trip_id"
                        id="edit_trip_id"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                        @foreach($trips as $trip)

                            <option value="{{ $trip->id }}">
                                {{ $trip->from_city }} → {{ $trip->to_city }}
                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- SEAT --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        المقعد
                    </label>

                    <input
                        type="number"
                        name="seat_number"
                        id="edit_seat_number"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                </div>



                {{-- SALE PRICE --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        سعر البيع
                    </label>

                    <input
                        type="number"
                        name="sale_price"
                        id="edit_sale_price"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                </div>



                {{-- DISCOUNT PERCENT --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        نسبة الخصم
                    </label>

                    <input
                        type="number"
                        name="discount_percent"
                        id="edit_discount_percent"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                </div>



                {{-- DISCOUNT AMOUNT --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        قيمة الخصم
                    </label>

                    <input
                        type="number"
                        id="edit_discount_amount"
                        readonly
                        class="border px-3 py-2 rounded w-full bg-gray-100">

                </div>



                {{-- TOTAL BEFORE DISCOUNT --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        الإجمالي قبل الخصم
                    </label>

                    <input
                        type="number"
                        id="edit_total_before_discount"
                        readonly
                        class="border px-3 py-2 rounded w-full bg-gray-100">

                </div>



                {{-- FINAL PRICE --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        السعر النهائي
                    </label>

                    <input
                        type="number"
                        name="final_price"
                        id="edit_final_price"
                        readonly
                        class="border px-3 py-2 rounded w-full bg-gray-100">

                </div>



                {{-- PAID --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        المدفوع
                    </label>

                    <input
                        type="number"
                        name="paid_amount"
                        id="edit_paid_amount"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                </div>



                {{-- STATUS --}}

                <div>

                    <label class="text-sm dark:text-gray-300">
                        الحالة
                    </label>

                    <select
                        name="status"
                        id="edit_status"
                        class="border px-3 py-2 rounded w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                        <option value="confirmed">مؤكد</option>
                        <option value="pending">معلق</option>
                        <option value="cancelled">ملغي</option>

                    </select>

                </div>


            </div>



            <div class="flex justify-end gap-3 mt-6">

                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded">

                    إلغاء

                </button>


                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded">

                    حفظ التعديل

                </button>

            </div>

        </form>

    </div>

</div>

 <script>


     function calculateEdit(){

         let sale=parseFloat(document.getElementById("edit_sale_price").value)||0

         let percent=parseFloat(document.getElementById("edit_discount_percent").value)||0

         let purchase=parseFloat(document.getElementById("edit_purchase_price")?.value)||0

         let total=purchase+sale

         let discount=(total*percent)/100

         let final=total-discount

         document.getElementById("edit_discount_amount").value=discount

         document.getElementById("edit_total_before_discount").value=total

         document.getElementById("edit_final_price").value=final

     }

     document.querySelectorAll(
         "#edit_sale_price,#edit_discount_percent"
     ).forEach(el=>el.addEventListener("input",calculateEdit))
 </script>
