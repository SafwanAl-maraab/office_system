@extends('frontend.layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4 md:p-6 space-y-8">


{{-- FILTER --}}
<form method="GET"
class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 flex flex-col md:flex-row gap-4">

<div>
<label class="text-sm">من تاريخ</label>
<input type="date" name="from" value="{{ $from }}"
class="border rounded-xl px-3 py-2 dark:bg-gray-900">
</div>

<div>
<label class="text-sm">إلى تاريخ</label>
<input type="date" name="to" value="{{ $to }}"
class="border rounded-xl px-3 py-2 dark:bg-gray-900">
</div>

<button class="bg-blue-600 text-white px-5 py-2 rounded-xl mt-5">
تحديث
</button>

</form>



{{-- OPERATIONS CARDS --}}

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

<div class="text-gray-500 text-sm">التأشيرات اليوم</div>

<div class="text-2xl font-bold mt-2 text-blue-600">
{{ $todayVisas }}
</div>

</div>


<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

<div class="text-gray-500 text-sm">الحجوزات اليوم</div>

<div class="text-2xl font-bold mt-2 text-green-600">
{{ $todayBookings }}
</div>

</div>


<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

<div class="text-gray-500 text-sm">الطلبات اليوم</div>

<div class="text-2xl font-bold mt-2 text-purple-600">
{{ $todayRequests }}
</div>

</div>


<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

<div class="text-gray-500 text-sm">العملاء</div>

<div class="text-2xl font-bold mt-2">
{{ $stats['clients'] }}
</div>

</div>

</div>



{{-- PROFITS --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

<div class="bg-green-100 dark:bg-green-900 rounded-xl p-6">

<h3 class="font-bold mb-3">
ربح التأشيرات حسب العملة
</h3>

@foreach($visaProfit as $p)

<div class="flex justify-between">

<span>{{ $p->code }}</span>

<span class="font-bold">
{{ number_format($p->profit) }} {{ $p->symbol }}
</span>

</div>

@endforeach

</div>



<div class="bg-blue-100 dark:bg-blue-900 rounded-xl p-6">

<h3 class="font-bold mb-3">
ربح الحجوزات حسب العملة
</h3>

@foreach($bookingProfit as $p)

<div class="flex justify-between">

<span>{{ $p->code }}</span>

<span class="font-bold">
{{ number_format($p->profit) }} {{ $p->symbol }}
</span>

</div>

@endforeach

</div>

</div>



{{-- REVENUE --}}

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
الإيرادات حسب العملة
</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

@foreach($revenueByCurrency as $r)

<div class="bg-green-100 dark:bg-green-900 p-4 rounded-xl">

<div class="text-sm">
{{ $r->code }}
</div>

<div class="text-xl font-bold mt-2">
{{ number_format($r->total) }} {{ $r->symbol }}
</div>

</div>

@endforeach

</div>

</div>



{{-- EXPENSES --}}

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
المصاريف حسب العملة
</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

@foreach($expensesByCurrency as $e)

<div class="bg-red-100 dark:bg-red-900 p-4 rounded-xl">

<div class="text-sm">
{{ $e->code }}
</div>

<div class="text-xl font-bold mt-2">
{{ number_format($e->total) }} {{ $e->symbol }}
</div>

</div>

@endforeach

</div>

</div>



{{-- CASHBOX --}}

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
رصيد الخزنة
</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">

@foreach($cashbox as $c)

<div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-xl">

<div class="text-sm">
{{ $c->code }}
</div>

<div class="text-xl font-bold mt-2">
{{ number_format($c->balance) }} {{ $c->symbol }}
</div>

</div>

@endforeach

</div>

</div>



{{-- CLIENTS DEBTS --}}

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
المتبقي عند العملاء
</h2>

@foreach($clientsRemaining as $c)

<div class="flex justify-between border-b py-2">

<span>{{ $c->code }}</span>

<span class="font-bold">
{{ number_format($c->total) }} {{ $c->symbol }}
</span>

</div>

@endforeach

</div>



{{-- AGENTS DEBTS --}}

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
المتبقي للوكلاء
</h2>

@foreach($agentsRemaining as $a)

<div class="flex justify-between border-b py-2">

<span>{{ $a->code }}</span>

<span class="font-bold">
{{ number_format($a->total) }} {{ $a->symbol }}
</span>

</div>

@endforeach

</div>



{{-- CHARTS --}}

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
تحليل الإيرادات
</h2>

<canvas id="revenueChart"></canvas>

</div>



<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
حالة التأشيرات
</h2>

<canvas id="visaChart"></canvas>

</div>

</div>



{{-- LATEST OPERATIONS --}}

<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
آخر التأشيرات
</h2>

@foreach($latestVisas as $v)

<div class="flex justify-between border-b py-2">

<span>{{ $v->full_name }}</span>

<span>
{{ number_format($v->sale_price) }}
</span>

</div>

@endforeach

</div>



<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

<h2 class="font-bold mb-4">
آخر الحجوزات
</h2>

@foreach($latestBookings as $b)

<div class="flex justify-between border-b py-2">

<span>{{ $b->full_name }}</span>

<span>
{{ number_format($b->final_price) }}
</span>

</div>

@endforeach

</div>

</div>


</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(
document.getElementById('revenueChart'),
{
type:'line',
data:{
labels:{!! json_encode(array_keys($monthlyRevenue->toArray())) !!},
datasets:[{
label:'Revenue',
data:{!! json_encode(array_values($monthlyRevenue->toArray())) !!}
}]
}
}
)


new Chart(
document.getElementById('visaChart'),
{
type:'pie',
data:{
labels:{!! json_encode(array_keys($visaStatus->toArray())) !!},
datasets:[{
data:{!! json_encode(array_values($visaStatus->toArray())) !!}
}]
}
}
)

</script>


@endsection