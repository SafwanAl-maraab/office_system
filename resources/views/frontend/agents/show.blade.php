@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

<!-- HEADER -->

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">
{{ $agent->name }}
</h1>

<div class="flex gap-3">

<button
onclick="document.getElementById('paymentModal').classList.remove('hidden')"
class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow">

إضافة دفعة

</button>

<a
href="{{ route('agents.statement.pdf',$agent->id) }}"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl">

طباعة كشف الحساب

</a>

</div>

</div>


<!-- AGENT INFO -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
<p class="text-sm text-gray-500 mb-2">الهاتف</p>
<p class="font-semibold text-gray-800 dark:text-white">
{{ $agent->phone ?? '-' }}
</p>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
<p class="text-sm text-gray-500 mb-2">الدولة</p>
<p class="font-semibold text-gray-800 dark:text-white">
{{ $agent->country ?? '-' }}
</p>
</div>

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">
<p class="text-sm text-gray-500 mb-2">المدينة</p>
<p class="font-semibold text-gray-800 dark:text-white">
{{ $agent->city ?? '-' }}
</p>
</div>

</div>


<!-- FINANCIAL STATS -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

<!-- المستحقات -->

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-4">
إجمالي المستحقات
</p>

@foreach($financialStats as $stat)

<div class="flex justify-between mb-2">

<span>{{ $stat->currency->code }}</span>

<span class="font-bold text-red-600">

{{ number_format($stat->total_due,2) }}

</span>

</div>

@endforeach

</div>


<!-- المدفوع -->

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-4">
إجمالي المدفوع
</p>

@foreach($financialStats as $stat)

<div class="flex justify-between mb-2">

<span>{{ $stat->currency->code }}</span>

<span class="font-bold text-green-600">

{{ number_format(abs($stat->total_paid),2) }}

</span>

</div>

@endforeach

</div>


<!-- المتبقي -->

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-4">
المتبقي
</p>

@foreach($financialStats as $stat)

<div class="flex justify-between mb-2">

<span>{{ $stat->currency->code }}</span>

<span class="font-bold {{ $stat->balance >=0 ? 'text-green-600':'text-red-600' }}">

{{ number_format($stat->balance,2) }}

</span>

</div>

@endforeach

</div>

</div>



<!-- BALANCE BY CURRENCY -->

<div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6">

@foreach($balances as $b)

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-sm text-gray-500 mb-2">

رصيد {{ $b->currency->code }}

</p>

<p class="text-2xl font-bold
{{ $b->total >=0 ? 'text-green-600':'text-red-600' }}">

{{ number_format($b->total,2) }}

{{ $b->currency->symbol }}

</p>

</div>

@endforeach

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
<th class="p-4 text-right">العملة</th>

</tr>

</thead>

<tbody>

@foreach($transactions as $t)

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
دفعة
</span>


    @elseif($t->type == 'booking_cost')

        <span class="text-green-600 font-semibold">
            حجز سفر
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

    @elseif($t->booking)
            {{ ' حجز' .$t->booking->trip->bus->plate_number }}
@else
-
@endif

</td>

<td class="p-4 font-semibold {{ $t->amount < 0 ? 'text-red-600':'text-green-600' }}">

{{ number_format($t->amount,2) }}

</td>

<td class="p-4">

{{ $t->currency->code }}

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

@foreach($agentCurrencies as $currency)

<option value="{{ $currency->currency_id }}">

{{ $currency->currency->code }}

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
