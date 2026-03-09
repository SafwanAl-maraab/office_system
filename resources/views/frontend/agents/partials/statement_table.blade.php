<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr>

<th class="p-3 text-right">التاريخ</th>
<th class="p-3 text-right">العملية</th>
<th class="p-3 text-right">المبلغ</th>
<th class="p-3 text-right">العملة</th>

</tr>

</thead>

<tbody>

@foreach($transactions as $transaction)

<tr class="border-b dark:border-gray-700">

<td class="p-3">

{{ $transaction->created_at->format('Y-m-d') }}

</td>

<td class="p-3">

@if($transaction->type == 'visa_cost')

تكلفة تأشيرة

@elseif($transaction->type == 'payment')

دفع

@else

تعديل

@endif

</td>

<td class="p-3 font-semibold
{{ $transaction->amount >=0 ? 'text-green-600':'text-red-600' }}">

{{ number_format($transaction->amount,2) }}

</td>

<td class="p-3">

{{ $transaction->currency->code }}

</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<div class="mt-4">

{{ $transactions->links() }}

</div>