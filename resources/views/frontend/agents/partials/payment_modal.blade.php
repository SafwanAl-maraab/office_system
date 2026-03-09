<div
id="paymentModal"
class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">

<div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-96">

<h2 class="text-lg font-bold mb-4">

دفع للوكيل

</h2>

<form method="POST"
action="{{ route('agents.payment',$agent->id) }}">

@csrf

<input
type="number"
name="amount"
placeholder="المبلغ"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">


<select
name="currency_id"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">

@foreach($currencies as $currency)

<option value="{{ $currency->id }}">

{{ $currency->code }}

</option>

@endforeach

</select>


<textarea
name="description"
placeholder="وصف العملية"
class="w-full border rounded-lg p-2 mb-3 dark:bg-gray-900 dark:border-gray-700">

</textarea>


<div class="flex gap-3">

<button
class="bg-green-600 text-white px-4 py-2 rounded-lg">

حفظ

</button>

<button
type="button"
onclick="document.getElementById('paymentModal').classList.add('hidden')"
class="bg-gray-400 text-white px-4 py-2 rounded-lg">

إلغاء

</button>

</div>

</form>

</div>

</div>