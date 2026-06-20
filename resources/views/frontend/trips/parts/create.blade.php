<div id="createTripModal"
     class="fixed inset-0 z-50 hidden  ">

    <!-- BACKDROP -->
    <div id="createTripBackdrop"
         class="absolute inset-0 bg-black/60"></div>


    <!-- MODAL WRAPPER -->
    <div class="relative flex items-center justify-center h-full p-4">

        <div
            class="w-full max-w-2xl bg-white dark:bg-gray-900
rounded-xl shadow-xl flex flex-col
max-h-[90vh] overflow-y-auto ">




            <!-- HEADER -->
            <div class="flex items-center justify-between
px-6 py-4 border-b dark:border-gray-700">

                <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                    إضافة رحلة جديدة
                </h2>

                <button
                    type="button"
                    onclick="closeCreateTripModal()"
                    class="text-gray-500 hover:text-black dark:hover:text-white text-xl">
                    ✕
                </button>

            </div>




            <!-- FORM -->
            <form method="POST"
                  action="{{ route('trips.store') }}"
                  class="flex flex-col h-full">

                @csrf




                <!-- BODY -->
                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-5">



                    <!-- FROM / TO -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                من المدينة
                            </label>

                            <input
                                type="text"
                                name="from_city"
                                required
                                class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>


                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                إلى المدينة
                            </label>

                            <input
                                type="text"
                                name="to_city"
                                required
                                class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>

                    </div>




                    <!-- BUS -->
                    <div>

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            الباص
                        </label>

                        <select
                            name="bus_id"
                            required
                            class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                            <option value="">اختر الباص</option>

                            @foreach($buses as $bus)

                                <option value="{{ $bus->id }}">
                                    {{ $bus->model }} - {{ $bus->plate_number }}
                                </option>

                            @endforeach

                        </select>

                    </div>




                    <!-- DATE / TIME -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                تاريخ الرحلة
                            </label>

                            <input
                                type="date"
                                name="trip_date"
                                required
                                class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>


                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                وقت الرحلة
                            </label>

                            <input
                                type="time"
                                name="trip_time"
                                required
                                class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>

                    </div>




                    <!-- PRICES -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                سعر الشراء
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="purchase_price"
                                required
                                class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>


                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                سعر البيع
                            </label>

                            <input
                                type="number"
                                step="0.01"
                                name="sale_price"
                                required
                                class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">
                        </div>

                    </div>




                    <!-- CURRENCY -->
                    <div>

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            العملة
                        </label>

                        <select
                            name="currency_id"
                            required
                            class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white">

                            @foreach($currencies as $currency)

                                <option value="{{ $currency->id }}">
                                    {{ $currency->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>




                    <!-- NOTES -->
                    <div>

                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            ملاحظات
                        </label>

                        <textarea
                            name="notes"
                            rows="3"
                            class="mt-1 w-full px-3 py-2 border rounded-lg
focus:outline-none focus:ring-2 focus:ring-black
dark:bg-gray-800 dark:border-gray-700 dark:text-white"></textarea>

                    </div>



                </div>




                <!-- FOOTER -->
                <div
                    class="px-6 py-4 border-t dark:border-gray-700
flex justify-end gap-3">

                    <button
                        type="button"
                        onclick="closeCreateTripModal()"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">

                        إلغاء

                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2 bg-black text-white rounded-lg">

                        حفظ الرحلة

                    </button>

                </div>



            </form>

        </div>

    </div>

</div>



<script>

    function openCreateTripModal() {

        const modal = document.getElementById("createTripModal");

        if(modal){
            modal.classList.remove("hidden");
        }

    }


    function closeCreateTripModal() {

        const modal = document.getElementById("createTripModal");

        if(modal){
            modal.classList.add("hidden");
        }

    }


    document.addEventListener("DOMContentLoaded", function(){

        const backdrop = document.getElementById("createTripBackdrop");

        if(backdrop){

            backdrop.addEventListener("click", function(){
                closeCreateTripModal();
            });

        }

    });


    document.addEventListener("keydown", function(e){

        if(e.key === "Escape"){

            closeCreateTripModal();

        }

    });

</script>
