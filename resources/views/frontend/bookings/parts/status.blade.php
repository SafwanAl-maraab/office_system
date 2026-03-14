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

                        <select name="status"
                                class="w-full border rounded-lg p-2">

                            <option value="pending">قيد الانتظار</option>

                            <option value="confirmed">مؤكد</option>

                            <option value="issued">تم إصدار التذكرة</option>

                            <option value="cancelled">ملغي</option>

                        </select>

                    </div>

                </div>

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

    document.querySelectorAll(".changeStatusBtn")
        .forEach(btn=>{

            btn.onclick = function(){

                const bookingId = this.dataset.bookingId
                const status = this.dataset.currentStatus

                const form = document.getElementById("statusForm")

                form.action = "/dashboard/bookings/"+bookingId+"/status"

                document.getElementById("currentStatus").value = status

                document.getElementById("statusModal").classList.remove("hidden")

            }

        })

    function closeStatusModal(){

        document.getElementById("statusModal").classList.add("hidden")

    }

</script>
