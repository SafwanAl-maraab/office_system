@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-6">

<!-- PAGE HEADER -->

<div class="flex items-center justify-between">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">

إدارة الوكلاء

</h1>

<button
onclick="document.getElementById('createAgentModal').classList.remove('hidden')"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition">

إضافة وكيل

</button>

</div>


<!-- STATISTICS -->

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">

<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-gray-500 text-sm">

عدد الوكلاء

</p>

<p class="text-2xl font-bold text-blue-600">

{{ $totalAgents }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-gray-500 text-sm">

إجمالي المستحقات

</p>

<p class="text-2xl font-bold text-green-600">

{{ number_format($totalDue,2) }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-gray-500 text-sm">

إجمالي المدفوعات

</p>

<p class="text-2xl font-bold text-red-600">

{{ number_format(abs($totalPayments),2) }}

</p>

</div>


<div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<p class="text-gray-500 text-sm">

الرصيد الحالي

</p>

<p class="text-2xl font-bold text-purple-600">

{{ number_format($currentBalance,2) }}

</p>

</div>

</div>


<!-- SEARCH -->

<form method="GET"
class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">

<div class="grid grid-cols-3 gap-4">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="بحث باسم الوكيل أو الهاتف"
class="border rounded-lg p-2 dark:bg-gray-900 dark:border-gray-700">

<button
class="bg-blue-600 text-white px-4 py-2 rounded-lg">

بحث

</button>

</div>

</form>


<!-- AGENTS TABLE -->

<div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr>

<th class="p-3 text-right">الوكيل</th>
<th class="p-3 text-right">الهاتف</th>
<th class="p-3 text-right">الدولة</th>
<th class="p-3 text-right">الرصيد</th>
<th class="p-3 text-right">العمليات</th>

</tr>

</thead>

<tbody>

@foreach($agents as $agent)

<tr class="border-b dark:border-gray-700">

<td class="p-3 font-semibold">

{{ $agent->name }}

</td>

<td class="p-3">

{{ $agent->phone }}

</td>

<td class="p-3">

{{ $agent->country }}

</td>

<td class="p-3">

{{ number_format($agent->balance,2) }}

</td>

<td class="p-3 flex gap-2">

<a
href="{{ route('agents.show',$agent->id) }}"
class="px-3 py-1 bg-blue-500 text-white rounded">

عرض

</a>

<form method="POST"
action="{{ route('agents.destroy',$agent->id) }}">

@csrf
@method('DELETE')

<button
class="px-3 py-1 bg-red-600 text-white rounded">

حذف

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<!-- PAGINATION -->

<div>

{{ $agents->links() }}

</div>


</div>

@include('frontend.agents.partials.create_modal')

@endsection