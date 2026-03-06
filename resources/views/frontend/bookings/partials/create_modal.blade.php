@extends('frontend.layouts.app')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

<h1 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">

إنشاء حجز جديد

</h1>

<form method="POST"
action="{{ route('bookings.store') }}">

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- CLIENT -->

<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

العميل

</label>

<select name="client_id"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

@foreach($clients as $client)

<option value="{{ $client->id }}">

{{ $client->full_name }}

</option>

@endforeach

</select>

</div>


<!-- TRIP GROUP -->

<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

الحملة

</label>

<select name="trip_group_id"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

<option value="">بدون حملة</option>

@foreach($tripGroups as $group)

<option value="{{ $group->id }}">

{{ $group->name }}

</option>

@endforeach

</select>

</div>


<!-- SEAT -->

<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

رقم المقعد

</label>

<input
type="text"
name="seat_number"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

</div>


<!-- PRICE -->

<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

السعر

</label>

<input
type="number"
name="price"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

</div>


<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

التكلفة على المكتب

</label>

<input
type="number"
name="cost_price"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

</div>



<!-- CURRENCY -->

<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

العملة

</label>

<select name="currency_id"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

@foreach($currencies as $currency)

<option value="{{ $currency->id }}">

{{ $currency->code }}

</option>

@endforeach

</select>

</div>


<!-- STATUS -->

<div>

<label class="text-sm text-gray-600 dark:text-gray-300">

الحالة

</label>

<select name="status"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

<option value="pending">قيد الانتظار</option>
<option value="confirmed">مؤكد</option>

</select>

</div>


</div>


<div class="mt-6">

<label class="text-sm text-gray-600 dark:text-gray-300">

ملاحظات

</label>

<textarea
name="notes"
class="w-full border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

</textarea>

</div>


<div class="mt-6 flex gap-3">

<button
class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">

حفظ الحجز

</button>

<a href="{{ route('bookings.index') }}"
class="px-6 py-2 bg-gray-400 text-white rounded-lg">

إلغاء

</a>

</div>

</form>

</div>

</div>

@endsection