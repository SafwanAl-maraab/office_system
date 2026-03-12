@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

<!-- HEADER -->

<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

<div>

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">
إدارة الوكلاء
</h1>

<p class="text-sm text-gray-500">
إدارة حسابات الوكلاء والمدفوعات المالية
</p>

</div>

<div class="flex flex-wrap gap-3">

<button
onclick="document.getElementById('createAgentModal').classList.remove('hidden')"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow">

إضافة وكيل

</button>

<a
href="{{ route('agents.export', request()->query()) }}"
class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl shadow">

تصدير PDF

</a>

</div>

</div>



<!-- FINANCIAL CARDS -->

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

<!-- DUE -->

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border">

<p class="text-gray-500 text-sm mb-4">
إجمالي المستحقات
</p>

<div class="space-y-2">

@foreach($stats as $stat)

<div class="flex justify-between">

<span>{{ $stat->currency->code }}</span>

<span class="font-bold text-red-600">
{{ number_format($stat->total_due,2) }}
</span>

</div>

@endforeach

</div>

</div>



<!-- PAYMENTS -->

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border">

<p class="text-gray-500 text-sm mb-4">
إجمالي المدفوعات
</p>

<div class="space-y-2">

@foreach($stats as $stat)

<div class="flex justify-between">

<span>{{ $stat->currency->code }}</span>

<span class="font-bold text-green-600">
{{ number_format(abs($stat->total_payment),2) }}
</span>

</div>

@endforeach

</div>

</div>



<!-- BALANCE -->

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow border">

<p class="text-gray-500 text-sm mb-4">
الرصيد الحالي
</p>

<div class="space-y-2">

@foreach($stats as $stat)

<div class="flex justify-between">

<span>{{ $stat->currency->code }}</span>

<span class="font-bold {{ $stat->balance >=0 ? 'text-green-600':'text-red-600' }}">
{{ number_format($stat->balance,2) }}
</span>

</div>

@endforeach

</div>

</div>

</div>



<!-- SEARCH -->

<form method="GET"
class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow grid md:grid-cols-4 gap-4">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="بحث باسم الوكيل أو الهاتف"
class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 dark:bg-gray-900">

<input
type="date"
name="from_date"
value="{{ request('from_date') }}"
class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 dark:bg-gray-900">

<input
type="date"
name="to_date"
value="{{ request('to_date') }}"
class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 dark:bg-gray-900">

<button
class="bg-blue-600 hover:bg-blue-700 text-white rounded-xl">

بحث

</button>

</form>



<!-- AGENTS TABLE -->

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr>

<th class="p-4 text-right">الوكيل</th>
<th class="p-4 text-right">الهاتف</th>
<th class="p-4 text-right">الدولة</th>
<th class="p-4 text-right">الرصيد</th>
<th class="p-4 text-right">الإجراءات</th>

</tr>

</thead>

<tbody>

@forelse($agents as $agent)

<tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

<td class="p-4 font-semibold">
{{ $agent->name }}
</td>

<td class="p-4">
{{ $agent->phone ?? '-' }}
</td>

<td class="p-4">
{{ $agent->country ?? '-' }}
</td>

<td class="p-4 font-bold {{ $agent->balance >=0 ? 'text-green-600':'text-red-600' }}">
{{ number_format($agent->balance,2) }}
</td>

<td class="p-4 flex flex-wrap gap-2">

<a
href="{{ route('agents.show',$agent->id) }}"
class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">

عرض

</a>

<button
onclick="openEditAgent({{ $agent->id }},'{{ $agent->name }}','{{ $agent->phone }}','{{ $agent->country }}','{{ $agent->city }}')"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

تعديل

</button>

<button
onclick="openPaymentModal({{ $agent->id }})"
class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

دفع

</button>

<form method="POST"
action="{{ route('agents.destroy',$agent->id) }}"
onsubmit="return confirm('هل أنت متأكد من حذف الوكيل؟')">

@csrf
@method('DELETE')

<button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
حذف
</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="text-center p-6 text-gray-500">
لا يوجد وكلاء
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>



<!-- PAGINATION -->

<div class="pt-4">

{{ $agents->links() }}

</div>

</div>



<!-- CREATE AGENT MODAL -->

<div id="createAgentModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow w-full max-w-md p-6">

<h2 class="text-lg font-bold mb-4">
إضافة وكيل
</h2>

<form method="POST" action="{{ route('agents.store') }}">

@csrf

<input name="name" placeholder="اسم الوكيل" required
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<input name="phone" placeholder="الهاتف"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<input name="country" placeholder="الدولة"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<input name="city" placeholder="المدينة"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<div class="flex justify-end gap-3">

<button type="button"
onclick="document.getElementById('createAgentModal').classList.add('hidden')"
class="bg-gray-400 text-white px-4 py-2 rounded">

إلغاء

</button>

<button class="bg-blue-600 text-white px-4 py-2 rounded">

حفظ

</button>

</div>

</form>

</div>

</div>



<!-- EDIT AGENT MODAL -->

<div id="editAgentModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow w-full max-w-md p-6">

<h2 class="text-lg font-bold mb-4">
تعديل الوكيل
</h2>

<form method="POST" id="editAgentForm">

@csrf
@method('PUT')

<input id="editName" name="name"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<input id="editPhone" name="phone"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<input id="editCountry" name="country"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<input id="editCity" name="city"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900">

<div class="flex justify-end gap-3">

<button type="button"
onclick="closeEditAgent()"
class="bg-gray-400 text-white px-4 py-2 rounded">

إلغاء

</button>

<button class="bg-yellow-600 text-white px-4 py-2 rounded">

حفظ

</button>

</div>

</form>

</div>

</div>



<script>

function openPaymentModal(id){

document.getElementById('paymentModal').classList.remove('hidden');

document.getElementById('paymentForm').action =
"/dashboard/agents/"+id+"/payment";

}

function openEditAgent(id,name,phone,country,city){

document.getElementById('editAgentModal').classList.remove('hidden');

document.getElementById('editAgentForm').action =
"/dashboard/agents/"+id;

document.getElementById('editName').value=name;
document.getElementById('editPhone').value=phone;
document.getElementById('editCountry').value=country;
document.getElementById('editCity').value=city;

}

function closeEditAgent(){

document.getElementById('editAgentModal').classList.add('hidden');

}

</script>

@endsection