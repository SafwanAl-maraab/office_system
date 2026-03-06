@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-6">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">

تفاصيل الحجز

</h1>


<div class="grid grid-cols-2 gap-6">


{{-- معلومات الحجز --}}

<div class="bg-white dark:bg-gray-800 p-4 rounded shadow">

<h2 class="font-bold mb-3">معلومات الحجز</h2>

<p>رقم الحجز: {{ $booking->booking_number }}</p>

<p>المقعد: {{ $booking->seat_number ?? '-' }}</p>

<p>السعر: {{ number_format($booking->price,2) }}</p>

<p>الحالة: {{ $booking->status }}</p>

</div>


{{-- معلومات العميل --}}

<div class="bg-white dark:bg-gray-800 p-4 rounded shadow">

<h2 class="font-bold mb-3">العميل</h2>

<p>{{ $booking->client->full_name }}</p>

<p>{{ $booking->client->phone }}</p>

</div>


{{-- الحملة --}}

<div class="bg-white dark:bg-gray-800 p-4 rounded shadow">

<h2 class="font-bold mb-3">الحملة</h2>

<p>{{ $booking->tripGroup->name ?? '-' }}</p>

</div>


{{-- الباص --}}

<div class="bg-white dark:bg-gray-800 p-4 rounded shadow">

<h2 class="font-bold mb-3">الباص</h2>

<p>{{ $booking->bus->id ?? '-' }}</p>

</div>

</div>


{{-- الفاتورة --}}

<div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

<h2 class="font-bold mb-4">

الفاتورة

</h2>

<p>الإجمالي: {{ $booking->invoice->total_amount ?? 0 }}</p>

<p>المدفوع: {{ $booking->invoice->paid_amount ?? 0 }}</p>

<p>المتبقي: {{ $booking->invoice->remaining_amount ?? 0 }}</p>

<p>الحالة: {{ $booking->invoice->status ?? '-' }}</p>

</div>


{{-- المدفوعات --}}

<div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

<h2 class="font-bold mb-4">

المدفوعات

</h2>

<table class="w-full text-sm">

<thead>

<tr>

<th>المبلغ</th>
<th>العملة</th>
<th>التاريخ</th>

</tr>

</thead>

<tbody>

@foreach($booking->invoice->payments ?? [] as $payment)

<tr>

<td>{{ $payment->amount }}</td>

<td>{{ $payment->currency->code }}</td>

<td>{{ $payment->created_at }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>
<div class="bg-white dark:bg-gray-800 p-6 rounded shadow">

<h2 class="font-bold mb-4">

إضافة دفعة

</h2>

<form method="POST"
action="{{ route('bookings.payment',$booking->id) }}">

@csrf

<input
type="number"
step="0.01"
name="amount"
placeholder="المبلغ"
class="border p-2 rounded w-full mb-3">

<button
class="bg-green-600 text-white px-4 py-2 rounded">

تسجيل الدفع

</button>

</form>

</div>
@endsection