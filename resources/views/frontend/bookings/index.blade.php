@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-6 max-w-7xl mx-auto">

<!-- PAGE HEADER -->

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">

إدارة الحجوزات

</h1>

<a href="{{ route('bookings.create') }}"
class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">

إنشاء حجز جديد

</a>

</div>


<!-- STATS -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

<div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">

<p class="text-gray-500 text-sm">إجمالي الحجوزات</p>

<p class="text-2xl font-bold text-blue-600">

{{ $stats['total'] }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">

<p class="text-gray-500 text-sm">قيد الانتظار</p>

<p class="text-2xl font-bold text-yellow-500">

{{ $stats['pending'] }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">

<p class="text-gray-500 text-sm">المؤكدة</p>

<p class="text-2xl font-bold text-green-600">

{{ $stats['confirmed'] }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow">

<p class="text-gray-500 text-sm">الإيرادات</p>

<p class="text-2xl font-bold text-purple-600">

{{ number_format($stats['revenue'],2) }}

</p>

</div>

</div>


<!-- SEARCH FILTER -->

<form method="GET"
class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">

<input
type="text"
name="search"
placeholder="بحث برقم الحجز أو اسم العميل"
value="{{ request('search') }}"
class="border dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg p-2 w-full">

<select name="status"
class="border dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg p-2">

<option value="">كل الحالات</option>

<option value="pending">قيد الانتظار</option>
<option value="confirmed">مؤكد</option>
<option value="cancelled">ملغي</option>
<option value="completed">مكتمل</option>

</select>


<select name="trip_group_id"
class="border dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-lg p-2">

<option value="">كل الحملات</option>

@foreach($tripGroups as $group)

<option value="{{ $group->id }}">

{{ $group->name }}

</option>

@endforeach

</select>


<button
class="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700">

بحث

</button>

</div>

</form>



<!-- BOOKINGS TABLE -->

<div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr>

<th class="p-3 text-right">رقم الحجز</th>
<th class="p-3 text-right">العميل</th>
<th class="p-3 text-right">الحملة</th>
<th class="p-3 text-right">المقعد</th>
<th class="p-3 text-right">السعر</th>
<th class="p-3 text-right">الحالة</th>
<th class="p-3 text-right">التاريخ</th>
<th class="p-3 text-right">العمليات</th>

</tr>

</thead>


<tbody>

@foreach($bookings as $booking)

<tr class="border-b dark:border-gray-700">

<td class="p-3 font-semibold">

{{ $booking->booking_number }}

</td>

<td class="p-3">

{{ $booking->client->full_name }}

</td>

<td class="p-3">

{{ $booking->tripGroup->name ?? '-' }}

</td>

<td class="p-3">

{{ $booking->seat_number ?? '-' }}

</td>

<td class="p-3">

{{ number_format($booking->price,2) }}

</td>


<td class="p-3">

@if($booking->status=='pending')

<span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">

قيد الانتظار

</span>

@elseif($booking->status=='confirmed')

<span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">

مؤكد

</span>

@elseif($booking->status=='cancelled')

<span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">

ملغي

</span>

@endif

</td>

<td class="p-3">

{{ $booking->created_at->format('Y-m-d') }}

</td>


<td class="p-3 flex gap-2">

<a href="{{ route('bookings.show',$booking->id) }}"
class="px-3 py-1 bg-blue-500 text-white rounded text-xs">

عرض

</a>

<form method="POST"
action="{{ route('bookings.destroy',$booking->id) }}">

@csrf
@method('DELETE')

<button
class="px-3 py-1 bg-red-600 text-white rounded text-xs">

حذف

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<div>

{{ $bookings->links() }}

</div>

</div>

@endsection