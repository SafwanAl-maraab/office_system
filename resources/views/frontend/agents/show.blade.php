@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-6">

<!-- HEADER -->

<div class="flex justify-between items-center">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">

{{ $agent->name }}

</h1>

<button
onclick="document.getElementById('paymentModal').classList.remove('hidden')"
class="bg-green-600 text-white px-4 py-2 rounded-xl">

دفع للوكيل

</button>

</div>


<!-- AGENT INFO -->

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">

<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-sm text-gray-500">

الهاتف

</p>

<p class="font-semibold">

{{ $agent->phone }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-sm text-gray-500">

الدولة

</p>

<p class="font-semibold">

{{ $agent->country }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-sm text-gray-500">

المدينة

</p>

<p class="font-semibold">

{{ $agent->city }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-sm text-gray-500">

الرصيد الحالي

</p>

<p class="text-xl font-bold
{{ $balance >=0 ? 'text-green-600' : 'text-red-600' }}">

{{ number_format($balance,2) }}

</p>

</div>

</div>


<!-- STATEMENT -->

<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">

<h2 class="text-lg font-bold mb-4">

كشف الحساب

</h2>

@include('frontend.agents.partials.statement_table')

</div>

</div>


@include('frontend.agents.partials.payment_modal')

@endsection