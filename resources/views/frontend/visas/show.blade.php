@extends('frontend.layouts.app')

@section('content')

<div class="p-6 space-y-8">

<!-- HEADER -->

<div class="glass-card">

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

<div>
<h1 class="text-2xl font-bold text-gray-800 dark:text-white">
تفاصيل التأشيرة
</h1>

<p class="text-gray-500 text-sm">
{{ $visa->client->full_name ?? '-' }}
</p>
</div>

<span class="status-badge
@if($visa->status == 'issued')
status-success
@elseif($visa->status == 'cancelled')
status-danger
@else
status-warning
@endif
">

{{ ucfirst($visa->status) }}

</span>

</div>

</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

<!-- LEFT SIDE -->

<div class="xl:col-span-2 space-y-8">

<!-- GENERAL INFO -->

<div class="glass-card">

<h2 class="section-title">
المعلومات العامة
</h2>

<div class="info-grid">

<div class="info-card">
<span>👤 العميل</span>
<p>{{ $visa->client->full_name ?? '-' }}</p>
</div>

<div class="info-card">
<span>🛂 رقم الجواز</span>
<p>{{ $visa->passport_number }}</p>
</div>

<div class="info-card">
<span>✈ نوع التأشيرة</span>
<p>{{ $visa->visaType->name ?? '-' }}</p>
</div>

<div class="info-card">
<span>📅 تاريخ الإصدار</span>
<p>{{ optional($visa->issue_date)->format('Y-m-d') }}</p>
</div>

<div class="info-card">
<span>⏳ تاريخ الانتهاء</span>
<p>{{ optional($visa->expiry_date)->format('Y-m-d') }}</p>
</div>

<div class="info-card">
<span>👨‍💼 أنشئت بواسطة</span>
<p>{{ $visa->employee->full_name ?? '-' }}</p>
</div>

</div>

</div>

<!-- FINANCIAL -->

<div class="glass-card">

<h2 class="section-title">
المعلومات المالية
</h2>

<div class="grid md:grid-cols-3 gap-6">

<div class="finance-card blue">
<span>السعر الأصلي</span>
<h3>{{ number_format($visa->original_price,2) }}</h3>
</div>

<div class="finance-card yellow">
<span>الخصم</span>
<h3>{{ number_format($visa->discount_amount,2) }}</h3>
<p>{{ $visa->discount_percentage ?? 0 }} %</p>
</div>

<div class="finance-card green">
<span>سعر البيع</span>
<h3>{{ number_format($visa->sale_price,2) }}</h3>
</div>

<div class="finance-card">
<span>التكلفة</span>
<h3>{{ number_format($visa->cost_price,2) }}</h3>
</div>

<div class="finance-card success">
<span>الربح</span>
<h3>{{ number_format($visa->profit,2) }}</h3>
</div>

</div>

</div>

<!-- INVOICE -->

<div class="glass-card">

<div class="flex justify-between items-center mb-6">

<h2 class="section-title">
الفاتورة والمدفوعات
</h2>

@if(!$visa->is_paid)

<button onclick="openPaymentModal()" class="btn-primary">
إضافة دفعة
</button>

@endif

</div>

<div class="grid md:grid-cols-3 gap-6">

<div class="finance-card blue">

<span>إجمالي الفاتورة</span>

<h3>

{{ number_format($visa->invoice->total_amount,2) }}

{{ $visa->invoice->currency->symbol ?? '' }}

</h3>

<p>{{ $visa->invoice->currency->name ?? '' }}</p>

</div>

<div class="finance-card green">

<span>المدفوع</span>

<h3>

{{ number_format($visa->invoice->paid_amount,2) }}

{{ $visa->invoice->currency->symbol ?? '' }}

</h3>

</div>

<div class="finance-card red">

<span>المتبقي</span>

<h3>

{{ number_format($visa->invoice->remaining_amount,2) }}

{{ $visa->invoice->currency->symbol ?? '' }}

</h3>

</div>

</div>

<div class="mt-8">

<h3 class="table-title">
سجل المدفوعات
</h3>

<div class="overflow-x-auto">

<table class="modern-table">

<thead>

<tr>
<th>التاريخ</th>
<th>المبلغ</th>
<th>العملة</th>
<th>طريقة الدفع</th>
<th>أنشأ بواسطة</th>
</tr>

</thead>

<tbody>

@forelse($visa->invoice->payments as $payment)

<tr>

<td>{{ $payment->created_at->format('Y-m-d') }}</td>

<td class="text-green-600">

{{ number_format($payment->amount,2) }}

{{ $payment->currency->symbol ?? '' }}

</td>

<td>{{ $payment->currency->name ?? '-' }}</td>

<td>{{ ucfirst($payment->payment_method) }}</td>

<td>{{ $payment->creator->name ?? '-' }}</td>

</tr>

@empty

<tr>
<td colspan="5" class="empty">
لا توجد مدفوعات
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>

</div>

</div>

<!-- RIGHT SIDE -->

<div class="space-y-8">

@if($visa->agent)

<div class="glass-card">

<h2 class="section-title">
الوكيل
</h2>

<div class="info-card">
<span>اسم الوكيل</span>
<p>{{ $visa->agent->name }}</p>
</div>

<div class="info-card">
<span>تكلفة الوكيل</span>
<p>{{ number_format($visa->agent_cost,2) }}</p>
</div>

</div>

@endif

<div class="glass-card">

<h2 class="section-title">
سجل الحالة
</h2>

<div class="timeline">

@foreach($visa->statusHistories as $history)

<div class="timeline-item">

<div class="timeline-dot"></div>

<div>

<p>

تم تغيير الحالة من

<strong>{{ ucfirst($history->old_status ?? 'جديدة') }}</strong>

إلى

<strong>{{ ucfirst($history->new_status) }}</strong>

</p>

<span>

{{ $history->created_at->format('Y-m-d H:i') }}

بواسطة

{{ $history->user->name ?? 'النظام' }}

</span>

@if($history->notes)

<p class="note">

{{ $history->notes }}

</p>

@endif

</div>

</div>

@endforeach

</div>

</div>

</div>

</div>

</div>

<style>

.glass-card{
background:white;
border-radius:24px;
padding:30px;
box-shadow:0 10px 30px rgba(0,0,0,0.05);
border:1px solid #e5e7eb;
}

.dark .glass-card{
background:#1f2937;
border-color:#374151;
}

.section-title{
font-weight:700;
margin-bottom:20px;
font-size:17px;
}

.status-badge{
padding:6px 14px;
border-radius:999px;
font-size:13px;
font-weight:600;
}

.status-success{
background:#dcfce7;
color:#15803d;
}

.status-danger{
background:#fee2e2;
color:#b91c1c;
}

.status-warning{
background:#fef3c7;
color:#b45309;
}

.info-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
gap:20px;
}

.info-card span{
font-size:12px;
color:#6b7280;
}

.info-card p{
font-weight:600;
margin-top:4px;
}

.finance-card{
padding:20px;
border-radius:16px;
border:1px solid #e5e7eb;
}

.finance-card span{
font-size:12px;
color:#6b7280;
}

.finance-card h3{
font-size:22px;
font-weight:700;
margin-top:4px;
}

.blue{background:#eff6ff;}
.green{background:#ecfdf5;}
.red{background:#fef2f2;}
.yellow{background:#fefce8;}
.success{background:#dcfce7;}

.btn-primary{
background:#2563eb;
color:white;
padding:8px 16px;
border-radius:10px;
font-size:14px;
}

.modern-table{
width:100%;
border-collapse:collapse;
}

.modern-table th{
text-align:right;
padding:12px;
background:#f3f4f6;
font-size:13px;
}

.modern-table td{
padding:12px;
border-bottom:1px solid #e5e7eb;
}

.empty{
text-align:center;
color:#9ca3af;
padding:20px;
}

.timeline-item{
display:flex;
gap:10px;
margin-bottom:14px;
}

.timeline-dot{
width:10px;
height:10px;
background:#2563eb;
border-radius:50%;
margin-top:6px;
}

.timeline span{
font-size:12px;
color:#9ca3af;
}

.note{
font-size:12px;
color:#6b7280;
margin-top:4px;
}

</style>

<script>

function openPaymentModal(){

}

</script>

@include('frontend.visas.partials.add_pay')

@include('frontend.visas.partials.change_state')



@endsection
