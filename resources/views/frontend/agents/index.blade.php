@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

<!-- HEADER -->

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

<h1 class="text-2xl font-bold text-gray-800 dark:text-white">
إدارة الوكلاء
</h1>

<div class="flex gap-3">

<button
onclick="document.getElementById('createAgentModal').classList.remove('hidden')"
class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow transition flex items-center gap-2">

<span>+</span>
<span>إضافة وكيل</span>

</button>

<a
href="{{ route('agents.export', request()->query()) }}"
class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl shadow">

تصدير PDF

</a>

</div>

</div>


<!-- STATISTICS -->

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-2">عدد الوكلاء</p>

<p class="text-3xl font-bold text-blue-600">
{{ $totalAgents }}
</p>

</div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-2">إجمالي المستحقات</p>

<p class="text-3xl font-bold text-red-600">
{{ number_format($totalDue,2) }}
</p>

</div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-2">إجمالي المدفوعات</p>

<p class="text-3xl font-bold text-green-600">
{{ number_format(abs($totalPayments),2) }}
</p>

</div>


<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow">

<p class="text-gray-500 text-sm mb-2">الرصيد الحالي</p>

<p class="text-3xl font-bold {{ $currentBalance >=0 ? 'text-green-600':'text-red-600' }}">
{{ number_format($currentBalance,2) }}
</p>

</div>

</div>


<!-- SEARCH -->

<form method="GET"
class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow grid md:grid-cols-4 gap-4">

<input
type="text"
name="search"
value="{{ request('search') }}"
placeholder="بحث باسم الوكيل أو الهاتف"
class="border border-gray-200 dark:border-gray-700 rounded-xl p-3 w-full dark:bg-gray-900">

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


<!-- TABLE -->

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">

<div class="overflow-x-auto">

<table class="w-full text-sm">

<thead class="bg-gray-100 dark:bg-gray-700">

<tr>

<th class="p-4 text-right">الوكيل</th>
<th class="p-4 text-right">الهاتف</th>
<th class="p-4 text-right">الدولة</th>
<th class="p-4 text-right">الرصيد</th>
<th class="p-4 text-right">العمليات</th>

</tr>

</thead>

<tbody>

@forelse($agents as $agent)

<tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">

<td class="p-4 font-semibold text-gray-800 dark:text-white">
{{ $agent->name }}
</td>

<td class="p-4">
{{ $agent->phone ?? '-' }}
</td>

<td class="p-4">
{{ $agent->country ?? '-' }}
</td>

<td class="p-4 font-semibold {{ $agent->balance >=0 ? 'text-green-600':'text-red-600' }}">
{{ number_format($agent->balance,2) }}
</td>

<td class="p-4 flex flex-wrap gap-2">

<a
href="{{ route('agents.show',$agent->id) }}"
class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm">
عرض
</a>

<button
onclick="openEditAgent({{ $agent->id }},'{{ $agent->name }}','{{ $agent->phone }}','{{ $agent->country }}','{{ $agent->city }}')"
class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1.5 rounded-lg text-sm">
تعديل
</button>

<button
onclick="openPaymentModal({{ $agent->id }})"
class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-sm">
دفع
</button>

<form method="POST"
action="{{ route('agents.destroy',$agent->id) }}"
onsubmit="return confirm('هل أنت متأكد من حذف الوكيل؟')">

@csrf
@method('DELETE')

<button
class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-sm">
حذف
</button>

</form>

</td>

</tr>

@empty

<tr>
<td colspan="5" class="p-6 text-center text-gray-500">
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


{{-- =========================
     CREATE AGENT MODAL
========================= --}}

<div id="createAgentModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6">

<h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-white">
إضافة وكيل
</h2>

<form method="POST" action="{{ route('agents.store') }}">

@csrf

<div class="space-y-3">

<input
type="text"
name="name"
placeholder="اسم الوكيل"
required
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700">

<input
type="text"
name="phone"
placeholder="الهاتف"
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700">

<input
type="text"
name="country"
placeholder="الدولة"
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700">

<input
type="text"
name="city"
placeholder="المدينة"
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700">

</div>

<div class="flex justify-end gap-3 mt-6">

<button
type="button"
onclick="document.getElementById('createAgentModal').classList.add('hidden')"
class="bg-gray-400 text-white px-4 py-2 rounded-lg">
إلغاء
</button>

<button
class="bg-blue-600 text-white px-4 py-2 rounded-lg">
حفظ
</button>

</div>

</form>

</div>

</div>


{{-- =========================
     PAYMENT MODAL
========================= --}}

<div id="paymentModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6 space-y-4">

<h2 class="text-lg font-bold">
إضافة دفعة
</h2>

<form method="POST" id="paymentForm">

@csrf

<input
type="number"
name="amount"
placeholder="المبلغ"
required
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700">

<select
name="currency_id"
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700">

@foreach($currencies as $currency)

<option value="{{ $currency->id }}">
{{ $currency->code }}
</option>

@endforeach

</select>

<textarea
name="description"
placeholder="الوصف"
class="w-full border rounded-xl p-3 dark:bg-gray-900 dark:border-gray-700"></textarea>

<div class="flex justify-end gap-3">

<button
type="button"
onclick="closePaymentModal()"
class="bg-gray-400 text-white px-4 py-2 rounded-lg">
إلغاء
</button>

<button
class="bg-green-600 text-white px-4 py-2 rounded-lg">
حفظ
</button>

</div>

</form>

</div>

</div>


{{-- =========================
     EDIT MODAL
========================= --}}

<div id="editAgentModal"
class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6">

<h2 class="text-lg font-bold mb-4">
تعديل الوكيل
</h2>

<form method="POST" id="editAgentForm">

@csrf
@method('PUT')

<input id="editName" name="name"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900 dark:border-gray-700">

<input id="editPhone" name="phone"
placeholder="الهاتف"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900 dark:border-gray-700">

<input id="editCountry" name="country"
placeholder="الدولة"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900 dark:border-gray-700">

<input id="editCity" name="city"
placeholder="المدينة"
class="w-full border rounded-xl p-3 mb-3 dark:bg-gray-900 dark:border-gray-700">

<div class="flex justify-end gap-3">

<button
type="button"
onclick="closeEditAgent()"
class="bg-gray-400 text-white px-4 py-2 rounded-lg">
إلغاء
</button>

<button
class="bg-yellow-600 text-white px-4 py-2 rounded-lg">
حفظ
</button>

</div>

</form>

</div>

</div>


<script>

function openPaymentModal(agentId){

document.getElementById('paymentModal').classList.remove('hidden');

document.getElementById('paymentForm').action =
"/dashboard/agents/"+agentId+"/payment";

}

function closePaymentModal(){

document.getElementById('paymentModal').classList.add('hidden');

}

function openEditAgent(id,name,phone,country,city){

document.getElementById('editAgentModal').classList.remove('hidden');

document.getElementById('editAgentForm').action =
"/dashboard/agents/"+id;

document.getElementById('editName').value = name;
document.getElementById('editPhone').value = phone;
document.getElementById('editCountry').value = country;
document.getElementById('editCity').value = city;

}

function closeEditAgent(){

document.getElementById('editAgentModal').classList.add('hidden');

}

</script>

@endsection