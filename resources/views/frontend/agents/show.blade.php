@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

<!-- HEADER -->

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">

{{ $agent->name }}

</h1>

<button
onclick="document.getElementById('paymentModal').classList.remove('hidden')"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow">

إضافة دفعة

</button>

</div>
<a
href="{{ route('agents.statement.pdf',$agent->id) }}"
class="bg-blue-600 text-white px-4 py-2 rounded-xl">

طباعة كشف الحساب

</a>
<a
href="{{ route('agents.statement.pdf',$agent->id) }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">

طباعة كشف الحساب

</a>

<!-- AGENT CARDS -->

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-sm text-gray-500 mb-2">
الهاتف
</p>

<p class="font-semibold text-gray-800 dark:text-white">
{{ $agent->phone ?? '-' }}
</p>

</div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-sm text-gray-500 mb-2">
الدولة
</p>

<p class="font-semibold text-gray-800 dark:text-white">
{{ $agent->country ?? '-' }}
</p>

</div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-sm text-gray-500 mb-2">
المدينة
</p>

<p class="font-semibold text-gray-800 dark:text-white">
{{ $agent->city ?? '-' }}
</p>

</div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-sm text-gray-500 mb-2">
الرصيد الحالي
</p>

<p class="text-2xl font-bold
{{ $balance >=0 ? 'text-green-600':'text-red-600' }}">

{{ number_format($balance,2) }}

</p>

</div>

</div>



<!-- STATEMENT -->

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

<div class="p-6 border-b border-gray-200 dark:border-gray-700">

<h2 class="text-lg font-bold text-gray-800 dark:text-white">

كشف حساب الوكيل

</h2>

</div>


<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr>

<th class="p-4 text-right">التاريخ</th>
<th class="p-4 text-right">نوع العملية</th>
<th class="p-4 text-right">المرجع</th>
<th class="p-4 text-right">المبلغ</th>
<th class="p-4 text-right">الرصيد بعد العملية</th>

</tr>

</thead>


<tbody>

@php
$runningBalance = 0;
@endphp


@foreach($transactions as $t)

@php
$runningBalance += $t->amount;
@endphp

<tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">


<td class="p-4">

{{ $t->created_at->format('Y-m-d') }}

</td>


<td class="p-4">

@if($t->type == 'visa_cost')

<span class="text-blue-600 font-semibold">

تكلفة تأشيرة

</span>

@elseif($t->type == 'payment')

<span class="text-green-600 font-semibold">

دفعة للوكيل

</span>

@else

<span class="text-yellow-600 font-semibold">

تعديل

</span>

@endif

</td>


<td class="p-4">

@if($t->visa)

{{ $t->visa->visa_number }}

@else

-

@endif

</td>


<td class="p-4 font-semibold
{{ $t->amount < 0 ? 'text-red-600':'text-green-600' }}">

{{ number_format($t->amount,2) }}

{{ $t->currency->symbol ?? '' }}

</td>


<td class="p-4 font-bold">

{{ number_format($runningBalance,2) }}

</td>


</tr>

@endforeach

</tbody>

</table>

</div>


<div class="p-4">

{{ $transactions->links() }}

</div>

</div>

</div>



<!-- PAYMENT MODAL -->

<div id="paymentModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6 space-y-4">

<h2 class="text-lg font-bold text-gray-800 dark:text-white">

إضافة دفعة للوكيل

</h2>

<form method="POST"
action="{{ route('agents.pay',$agent->id) }}">

@csrf


<div>

<label class="text-sm text-gray-500">
المبلغ
</label>

<input
type="number"
name="amount"
required
class="w-full border rounded-xl p-3 mt-1 dark:bg-gray-900 dark:border-gray-700">

</div>


<div>

<label class="text-sm text-gray-500">
العملة
</label>

<select
name="currency_id"
class="w-full border rounded-xl p-3 mt-1 dark:bg-gray-900 dark:border-gray-700">

@foreach($currencies as $currency)

<option value="{{ $currency->id }}">

{{ $currency->code }}

</option>

@endforeach

</select>

</div>


<div>

<label class="text-sm text-gray-500">
الوصف
</label>

<textarea
name="description"
class="w-full border rounded-xl p-3 mt-1 dark:bg-gray-900 dark:border-gray-700"></textarea>

</div>


<div class="flex justify-end gap-3 pt-3">

<button
type="button"
onclick="document.getElementById('paymentModal').classList.add('hidden')"
class="bg-gray-400 text-white px-4 py-2 rounded-lg">

إلغاء

</button>


<button
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

حفظ

</button>

</div>


</form>

</div>

</div>

@endsection