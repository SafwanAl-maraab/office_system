@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

<div class="flex justify-between items-center">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">
الحملات
</h1>

<button onclick="openCreateTripModal()"
class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow">

+ إضافة حملة

</button>

</div>


<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

@foreach($tripGroups as $group)

<div class="bg-white dark:bg-gray-900 rounded-3xl shadow p-6">

<div class="flex justify-between">

<h2 class="text-lg font-bold text-gray-800 dark:text-white">
{{ $group->name }}
</h2>

<span class="text-sm text-gray-500">
{{ $group->departure_date }}
</span>

</div>


<div class="mt-4 text-sm text-gray-600 dark:text-gray-400 space-y-1">

<p>العودة: {{ $group->return_date }}</p>
<p>السعة: {{ $group->total_seats }}</p>

</div>


@if($group->tripGroupBuses->count())

<div class="mt-4 text-sm text-green-600">

تم ربط باص

</div>

@else

<button
onclick="openAttachBusModal({{ $group->id }})"
class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-xl">

ربط باص

</button>

@endif


</div>

@endforeach

</div>

@include('frontend.trip-groups.modals')

</div>

<!-- ATTACH BUS -->

<div id="attachBusModal"
class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-2xl p-6">

<h2 class="text-lg font-bold mb-6 text-gray-800 dark:text-white">

ربط الحملة بالباص

</h2>


<form method="POST" action="{{ route('trip-groups.attachBus') }}">

@csrf

<input type="hidden" name="trip_group_id" id="tripGroupId">


<div class="space-y-4">

<select name="bus_id" class="input-style">

@foreach($buses as $bus)
<option value="{{ $bus->id }}">

{{ $bus->plate_number }}

</option>
@endforeach

</select>


<select name="driver_id" class="input-style">

@foreach($drivers as $driver)

<option value="{{ $driver->id }}">
{{ $driver->name }}
</option>

@endforeach

</select>

</div>


<div class="flex justify-end gap-3 mt-6">

<button type="button" onclick="closeAttachBusModal()"
class="px-4 py-2 bg-gray-400 text-white rounded-lg">

إلغاء

</button>

<button type="submit"
class="px-4 py-2 bg-green-600 text-white rounded-lg">

ربط

</button>

</div>

</form>

</div>

</div>


<script>

function openCreateTripModal(){

document.getElementById('createTripModal').classList.remove('hidden');

}

function closeCreateTripModal(){

document.getElementById('createTripModal').classList.add('hidden');

}


function openAttachBusModal(id){

document.getElementById('attachBusModal').classList.remove('hidden');

document.getElementById('tripGroupId').value=id;

}

function closeAttachBusModal(){

document.getElementById('attachBusModal').classList.add('hidden');

}

</script>

@endsection