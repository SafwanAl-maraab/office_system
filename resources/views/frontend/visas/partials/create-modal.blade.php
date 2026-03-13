<div id="createModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-2">

<div class="bg-white dark:bg-gray-900 w-full max-w-3xl rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">

<!-- HEADER -->

<div class="flex justify-between items-center px-4 py-3 border-b dark:border-gray-700">

<h2 class="text-lg font-bold text-gray-800 dark:text-white">
إنشاء تأشيرة
</h2>

<button type="button"
onclick="closeCreateModal()"
class="text-gray-500 hover:text-red-500 text-lg">
✕
</button>

</div>


<form id="visaForm"
method="POST"
action="{{ route('visas.store') }}"
class="p-4 space-y-4">

@csrf

<!-- CLIENT -->

<div class="grid grid-cols-2 gap-3">

<div class="relative">

<label class="text-xs font-medium">العميل *</label>

<input
type="text"
id="clientSearch"
placeholder="ابحث باسم العميل"
class="input" 
required
>

<input
type="hidden"
name="client_id"
id="client_id" 

>

<div
id="clientResults"
class="absolute  w-full bg-white dark:bg-gray-800 shadow rounded mt-1 hidden z-50 max-h-48 overflow-y-auto">
</div>

</div>


<div>

<label class="text-xs font-medium">رقم الجواز</label>

<input
type="text"
name="passport_number"
id="passport_number"
class="input"
required
>

</div>

</div>


<!-- VISA TYPE + CURRENCY -->

<div class="grid grid-cols-2 gap-3">

<div>

<label class="text-xs font-medium">نوع التأشيرة *</label>

<select name="visa_type_id" class="input" required>

@foreach($visaTypes as $type)

<option value="{{ $type->id }}">
{{ $type->name }}
</option>

@endforeach

</select>

</div>


<div>

<label class="text-xs font-medium">العملة *</label>

<select name="currency_id" class="input" required>

@foreach(\App\Models\Currency::where('status',1)->get() as $currency)

<option value="{{ $currency->id }}">
{{ $currency->name }} ({{ $currency->code }})
</option>

@endforeach

</select>

</div>

</div>



<!-- PRICES -->

<div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">

<div class="grid grid-cols-4 gap-2">

<div>

<label class="text-xs">تكلفة المكتب</label>

<input type="number"
name="original_price"
id="original_price"
class="input"
min="0"

>

</div>


<div>

<label class="text-xs">تكلفة الوكيل</label>

<input type="number"
name="agent_cost"
id="agent_cost"
class="input" 
value="0"
min="0"
>

</div>


<div>

<label class="text-xs">سعر البيع *</label>

<input type="number"
name="sale_price"
id="sale_price"
class="input"
required
min="0"
>

</div>


<div>

<label class="text-xs">خصم %</label>

<input type="number"
name="discount_percentage"
id="discount_percentage"
class="input"
value="0"
min="0"
>

</div>

</div>



<div class="grid grid-cols-3 gap-2 mt-3">

<div>

<label class="text-xs">إجمالي التكلفة</label>

<input
type="number"
name="cost_price"
id="cost_price"
class="input bg-gray-100"
readonly>

</div>


<div>

<label class="text-xs">السعر النهائي</label>

<input
type="number"
id="final_price"
class="input bg-gray-100"
readonly>

</div>


<div>

<label class="text-xs">الربح</label>

<input
type="number"
id="profit"
class="input bg-gray-100"
readonly>

</div>

</div>

</div>



<!-- AGENT -->

<div>

<label class="flex items-center gap-2 text-xs">

<input type="checkbox" id="agent_toggle" name="chagent">

ربط وكيل

</label>

<div id="agentBox" class="hidden mt-2">

<select name="agent_id" class="input">

<option value="">اختر الوكيل</option>

@foreach(\App\Models\Agent::where('status',1)->get() as $agent)

<option value="{{ $agent->id }}">
{{ $agent->name }}
</option>

@endforeach

</select>

</div>

</div>



<!-- PAYMENT -->

<div>

<label class="flex items-center gap-2 text-xs">

<input type="checkbox" id="payment_toggle">

تسجيل دفعة

</label>

<div id="paymentBox" class="hidden mt-2">

<input type="number"
name="paid_amount"
placeholder="المبلغ المدفوع"
class="input">

</div>

</div>



<!-- BUTTONS -->

<div class="flex justify-end gap-2 pt-2 border-t dark:border-gray-700">

<button type="button"
onclick="closeCreateModal()"
class="btn-gray">

إلغاء

</button>

<button type="submit"
class="btn-blue" >

حفظ

</button>

</div>

</form>

</div>
</div>



<style>

.input{
width:100%;
padding:7px;
border:1px solid #d1d5db;
border-radius:8px;
font-size:13px;
}

.dark .input{
background:#1f2937;
border-color:#374151;
color:white;
}

.btn-blue{
background:#2563eb;
color:white;
padding:7px 16px;
border-radius:8px;
}

.btn-gray{
background:#9ca3af;
color:white;
padding:7px 16px;
border-radius:8px;
}

</style>

<script>

const clientSearch = document.getElementById("clientSearch");
const clientResults = document.getElementById("clientResults");
const clientId = document.getElementById("client_id");
const passport = document.getElementById("passport_number");

clientSearch.addEventListener("keyup", function(){

let q = this.value.trim();

if(q.length < 1){
clientResults.classList.add("hidden");
clientResults.innerHTML="";

return;
}

fetch("{{ route('visas.searchClients') }}?q=" + q)

.then(response => response.json())

.then(data => {

clientResults.innerHTML="";

if(data.length === 0){
clientResults.classList.add("hidden");

return;
}

clientResults.classList.remove("hidden");

data.forEach(client => {

let div = document.createElement("div");

div.className="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b text-sm";

div.innerHTML =
"<b>"+client.full_name+"</b>";

div.onclick = function(){

clientSearch.value = client.full_name;

clientId.value = client.id;

passport.value = client.passport_number ?? "";

clientResults.classList.add("hidden");

};

clientResults.appendChild(div);

});

})

.catch(error => {
console.error("Search error:", error);
});

});






</script>



<script>

function calculateVisa(){

let officeCost = parseFloat(document.getElementById("original_price").value) || 0;

let agentCost = parseFloat(document.getElementById("agent_cost").value) || 0;

let salePrice = parseFloat(document.getElementById("sale_price").value) || 0;

let discountPercent = parseFloat(document.getElementById("discount_percentage").value) || 0;


/* حساب الخصم */

let discountAmount = salePrice * (discountPercent / 100);


/* السعر النهائي بعد الخصم */

let finalPrice = salePrice - discountAmount;


/* التكلفة الكلية */

let totalCost = officeCost + agentCost;


/* الربح */

let profit = finalPrice - totalCost;


/* عرض القيم */

document.getElementById("cost_price").value = totalCost.toFixed(2);

document.getElementById("final_price").value = finalPrice.toFixed(2);

document.getElementById("profit").value = profit.toFixed(2);


/* لون الربح */

let profitBox = document.getElementById("profit");

if(profit < 0){
profitBox.style.color = "red";
}else{
profitBox.style.color = "green";
}

}


/* تشغيل الحساب عند التغيير */

["original_price","agent_cost","sale_price","discount_percentage"].forEach(id => {

let el = document.getElementById(id);

if(el){
el.addEventListener("input", calculateVisa);
}

});

</script>

<script>

/* ================================
   AGENT TOGGLE
================================ */

const agentToggle = document.getElementById("agent_toggle");
const agentBox = document.getElementById("agentBox");

agentToggle.addEventListener("change", function(){

if(this.checked){

agentBox.classList.remove("hidden");
// agentBox.style.require=tru

agentBox.setAttribute("required", "required");


}else{

agentBox.classList.add("hidden");



/* مسح القيمة */

let select = agentBox.querySelector("select");

if(select) select.value = "";

}

});


/* ================================
   PAYMENT TOGGLE
================================ */

const paymentToggle = document.getElementById("payment_toggle");
const paymentBox = document.getElementById("paymentBox");

paymentToggle.addEventListener("change", function(){

if(this.checked){

paymentBox.classList.remove("hidden");

}else{

paymentBox.classList.add("hidden");

/* مسح القيمة */

let input = paymentBox.querySelector("input");

if(input) input.value = "";

}

});

</script>