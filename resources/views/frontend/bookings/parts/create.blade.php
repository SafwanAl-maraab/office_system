<div id="bookingModal" class="fixed inset-0 z-50 hidden">

    <!-- overlay -->
    <div id="bookingOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <!-- wrapper -->
    <div class="relative w-full h-full flex items-end sm:items-center justify-center p-4">

        <div class="w-full sm:max-w-3xl bg-white dark:bg-gray-900 rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">

            <!-- HEADER -->
            <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700">

                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                    إنشاء حجز جديد
                </h3>

                <button id="closeBookingModal"
                        class="text-gray-500 hover:text-red-500 text-xl">
                    ✕
                </button>

            </div>


            <!-- BODY -->
            <div class="p-6 overflow-y-auto">

                <form method="POST"
                      action="{{ route('dashboard.bookings.store') }}"
                      id="bookingForm"
                      class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    @csrf


                    <!-- client -->
                    <div class="sm:col-span-2">

                        <label class="text-sm text-gray-500">
                            العميل
                        </label>

                        <select
                            name="client_id"
                            id="clientSelect"
                            required
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">

                            <option value="">اختر العميل</option>

                            @foreach($clients as $client)

                                <option
                                    value="{{ $client->id }}"
                                    data-passport="{{ $client->passport_number }}">

                                    {{ $client->full_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- passport -->
                    <div class="sm:col-span-2">

                        <label class="text-sm text-gray-500">
                            رقم الجواز
                        </label>

                        <input
                            type="text"
                            id="passportNumber"
                            readonly
                            class="w-full border border-gray-300
                            dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2 bg-gray-50">

                    </div>


                    <!-- trip -->
                    <div class="sm:col-span-2">

                        <label class="text-sm text-gray-500">
                            الرحلة
                        </label>

                        <select
                            name="trip_id"
                            id="tripSelect"
                            required
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500">

                            <option value="">اختر الرحلة</option>

                            @foreach($trips as $trip)

                                <option
                                    value="{{ $trip->id }}"
                                    data-purchase="{{ $trip->purchase_price }}"
                                    data-sale="{{ $trip->sale_price }}"
                                    data-currency="{{ $trip->currency->code }}">

                                    {{ $trip->from_city }} →
                                    {{ $trip->to_city }}
                                    | {{ $trip->trip_date }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- seats -->
                    <div class="sm:col-span-2">

                        <label class="text-sm text-gray-500 mb-2 block">
                            اختر المقعد
                        </label>

                        <input type="hidden" name="seat_number" id="seatNumber">

                        <div id="seatsContainer"
                             class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-8 gap-2 mt-2">
                        </div>

                    </div>


                    <!-- discount -->
                    <div>

                        <label class="text-sm text-gray-500">
                            نسبة الخصم %
                        </label>

                        <input
                            type="number"
                            name="discount_percent"
                            value="0"
                            min="0"
                            max="100"
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2">

                    </div>


                    <!-- purchase -->
                    <div>

                        <label class="text-sm text-gray-500">
                            سعر التكلفة
                        </label>

                        <input
                            type="text"
                            id="purchasePrice"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2 bg-gray-50">

                    </div>


                    <!-- sale -->
                    <div>

                        <label class="text-sm text-gray-500">
                            سعر البيع
                        </label>

                        <input
                            type="text"
                            id="salePrice"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2 bg-gray-50">

                    </div>


                    <!-- currency -->
                    <div class="sm:col-span-2">

                        <label class="text-sm text-gray-500">
                            العملة
                        </label>

                        <input
                            type="text"
                            id="currencyCode"
                            readonly
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2 bg-gray-50">

                    </div>


                    <!-- payment -->
                    <div class="sm:col-span-2">

                        <label class="text-sm text-gray-500">
                            دفعة أولية
                        </label>

                        <input
                            type="number"
                            name="payment_amount"
                            value="0"
                            min="0"
                            step="0.01"
                            class="w-full border border-gray-300 dark:border-gray-700
                            dark:bg-gray-800 dark:text-white
                            rounded-xl px-4 py-2">

                    </div>

                </form>

            </div>


            <!-- FOOTER -->
            <div class="px-6 py-4 border-t dark:border-gray-700 flex justify-end">

                <button
                    type="submit"
                    form="bookingForm"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold">

                    حفظ الحجز

                </button>

            </div>

        </div>

    </div>

</div>
<script>

    document.addEventListener("DOMContentLoaded", function(){

        const clientSelect = document.getElementById("clientSelect");
        const passportInput = document.getElementById("passportNumber");

        clientSelect.addEventListener("change", function(){

            const option = this.options[this.selectedIndex];
            passportInput.value = option.dataset.passport || "";

        });


        const tripSelect = document.getElementById("tripSelect");

        const purchase = document.getElementById("purchasePrice");
        const sale = document.getElementById("salePrice");
        const currency = document.getElementById("currencyCode");

        tripSelect.addEventListener("change", function(){

            const option = this.options[this.selectedIndex];

            purchase.value = option.dataset.purchase || "";
            sale.value = option.dataset.sale || "";
            currency.value = option.dataset.currency || "";

        });

    });
</script>


<script>

    document.addEventListener("DOMContentLoaded", function(){

        const modal = document.getElementById('bookingModal')
        const openBtn = document.querySelector('[data-open-booking]')
        const closeBtn = document.getElementById('closeBookingModal')
        const overlay = document.getElementById('bookingOverlay')

        if(openBtn){
            openBtn.addEventListener('click', function(){
                modal.classList.remove('hidden')
            })
        }

        if(closeBtn){
            closeBtn.addEventListener('click', function(){
                modal.classList.add('hidden')
            })
        }

        if(overlay){
            overlay.addEventListener('click', function(){
                modal.classList.add('hidden')
            })
        }

    })

</script>

<script>

    const tripSelect = document.getElementById("tripSelect");
    const seatsContainer = document.getElementById("seatsContainer");
    const seatInput = document.getElementById("seatNumber");

    tripSelect.addEventListener("change", function(){

        const tripId = this.value;

        seatsContainer.innerHTML = "";

        if(!tripId) return;

        fetch(`/dashboard/trips/${tripId}/seats`)
            .then(res => res.json())
            .then(data => {

                const total = data.totalSeats;

                const booked = data.bookedSeats;

                for(let i=1;i<=total;i++){

                    const seat = document.createElement("button");

                    seat.type = "button";

                    seat.innerText = i;

                    seat.className = "p-2 rounded-lg text-sm border";

                    if(booked.includes(i)){

                        seat.classList.add(
                            "bg-red-200",
                            "text-red-700",
                            "cursor-not-allowed"
                        );

                        seat.disabled = true;

                    }else{

                        seat.classList.add(
                            "bg-green-100",
                            "hover:bg-green-200"
                        );

                        seat.onclick = () => {

                            document.querySelectorAll("#seatsContainer button")
                                .forEach(btn => btn.classList.remove("ring-2","ring-blue-500"));

                            seat.classList.add("ring-2","ring-blue-500");

                            seatInput.value = i;

                        };

                    }

                    seatsContainer.appendChild(seat);

                }

            });

    });

</script>
<script>

    document.getElementById("bookingForm").addEventListener("submit", function(e){

        const client = document.getElementById("clientSelect").value
        const trip = document.getElementById("tripSelect").value
        const seat = document.getElementById("seatNumber").value

        if(!client){
            alert("يجب اختيار العميل")
            e.preventDefault()
            return
        }

        if(!trip){
            alert("يجب اختيار الرحلة")
            e.preventDefault()
            return
        }

        if(!seat){
            alert("يجب اختيار المقعد")
            e.preventDefault()
            return
        }

    })
</script>
