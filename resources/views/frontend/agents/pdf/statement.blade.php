<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

<meta charset="UTF-8">

<style>
@font-face {
    font-family: 'DejaVu Sans';
    font-style: normal;
    font-weight: normal;
}

body {
    font-family: 'DejaVu Sans', sans-serif;
    direction: rtl;
    text-align: right;
}
body{
font-family: DejaVu Sans;
direction: rtl;
text-align:right;
font-size:13px;
}

.container{
width:100%;
}

.header{
text-align:center;
margin-bottom:20px;
}

.title{
font-size:20px;
font-weight:bold;
margin-bottom:5px;
}

.agent-info{
margin-bottom:15px;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#f2f2f2;
border:1px solid #ccc;
padding:8px;
font-weight:bold;
}

td{
border:1px solid #ccc;
padding:7px;
}

.amount-plus{
color:green;
font-weight:bold;
}

.amount-minus{
color:red;
font-weight:bold;
}

.footer{
margin-top:20px;
text-align:left;
font-size:12px;
}

</style>

</head>

<body>

<div class="container">

<!-- HEADER -->

<div class="header">

<div class="title">
كشف حساب وكيل
</div>

</div>

<!-- AGENT INFO -->

<div class="agent-info">

<strong>اسم الوكيل:</strong>
{{ $agent->name }}

<br>

<strong>الرصيد الحالي:</strong>
{{ number_format($balance,2) }}

</div>


<!-- TABLE -->

<table>

<thead>

<tr>

<th>التاريخ</th>
<th>العملية</th>
<th>المرجع</th>
<th>المبلغ</th>
<th>الرصيد</th>

</tr>

</thead>

<tbody>

@php
$runningBalance = 0;
@endphp

@foreach($transactions as $t)

@php
$runningBalance += $t->amount;
@endphp

<tr>

<td>

{{ $t->created_at->format('Y-m-d') }}

</td>

<td>

@if($t->type == 'visa_cost')

تكلفة تأشيرة

@elseif($t->type == 'payment')

دفعة للوكيل

@else

تعديل

@endif

</td>

<td>

@if($t->visa)

{{ $t->visa->visa_number }}

@else

-

@endif

</td>

<td class="{{ $t->amount < 0 ? 'amount-minus':'amount-plus' }}">

{{ number_format($t->amount,2) }}

</td>

<td>

{{ number_format($runningBalance,2) }}

</td>

</tr>

@endforeach

</tbody>

</table>


<div class="footer">

تم إنشاء التقرير في:

{{ now()->format('Y-m-d H:i') }}

</div>

</div>

</body>

</html>