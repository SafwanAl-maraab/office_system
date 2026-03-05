<!-- ATTACH TRIP GROUP MODAL -->
<div id="tripModal"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-6">

    <div class="bg-white dark:bg-gray-900 w-full max-w-4xl rounded-3xl shadow-2xl p-8">

        <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">
            ربط التأشيرة بحملة
        </h2>

        <form method="POST"
              action="{{ route('visas.attachTripGroup',$visa->id) }}"
              class="space-y-8">
            @csrf

            <!-- SEARCH GROUP -->
            <div class="relative">
                <input type="text"
                       id="tripSearch"
                       placeholder="ابحث باسم الحملة..."
                       class="input-style">

                <input type="hidden" name="trip_group_id" id="trip_group_id">

                <div id="tripResults"
                     class="absolute w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg mt-2 hidden"></div>
            </div>

            <!-- SEATS -->
            <div id="seatSection" class="hidden">

                <label class="block text-sm mb-2">اختر الباص والمقعد</label>

                <select name="trip_group_bus_id"
                        id="busSelect"
                        class="input-style">
                </select>

                <p id="remainingSeats"
                   class="text-sm text-green-600 mt-2"></p>

            </div>

            <div class="flex justify-end gap-4">
                <button type="button"
                        onclick="closeTripModal()"
                        class="px-4 py-2 bg-gray-400 text-white rounded-xl">
                    إلغاء
                </button>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-xl">
                    حفظ
                </button>
            </div>

        </form>

    </div>
</div>


<script>

function openTripModal(){
    document.getElementById('tripModal').classList.remove('hidden');
}
function closeTripModal(){
    document.getElementById('tripModal').classList.add('hidden');
}

document.getElementById('tripSearch').addEventListener('keyup',function(){
    let q=this.value;
    if(q.length<1) return;

    fetch(`/dashboard/trip-groups/search?q=${q}`)
    .then(res=>res.json())
    .then(data=>{
        let box=document.getElementById('tripResults');
        box.innerHTML='';
        box.classList.remove('hidden');

        data.forEach(group=>{
            let div=document.createElement('div');
            div.className="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer";
            div.innerText=group.name+" - "+group.departure_date;
            div.onclick=function(){
                document.getElementById('trip_group_id').value=group.id;
                loadSeats(group.id);
                box.classList.add('hidden');
            };
            box.appendChild(div);
        });
    });
});

function loadSeats(id){
    fetch(`/dashboard/trip-groups/${id}/seats`)
    .then(res=>res.json())
    .then(data=>{
        if(data.error){
            alert(data.error);
            return;
        }

        document.getElementById('seatSection').classList.remove('hidden');
        document.getElementById('remainingSeats').innerText=
            "المقاعد المتبقية: "+data.remaining;

        let select=document.getElementById('busSelect');
        select.innerHTML='';
        data.buses.forEach(bus=>{
            let option=document.createElement('option');
            option.value=bus.id;
            option.text=bus.bus.plate_number+" - "+bus.driver.name;
            select.appendChild(option);
        });
    });
}

</script>