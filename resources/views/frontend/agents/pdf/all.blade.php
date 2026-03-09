<!DOCTYPE html>
<html lang="ar">

<head>

<meta charset="UTF-8">

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    direction: rtl;
    text-align: right;
    font-size: 14px;
}

.title{
    text-align:center;
    font-size:20px;
    margin-bottom:10px;
    font-weight:bold;
}

.date{
    text-align:center;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#f2f2f2;
    border:1px solid #ccc;
    padding:8px;
}

td{
    border:1px solid #ccc;
    padding:8px;
}

</style>

</head>

<body>

<div class="title">

كشف حساب الوكلاء

</div>

<div class="date">

تاريخ التقرير : {{ date('Y-m-d') }}

</div>

<table>

<thead>

<tr>

<th>#</th>
<th>اسم الوكيل</th>
<th>الهاتف</th>
<th>الدولة</th>
<th>الرصيد</th>

</tr>

</thead>

<tbody>

@foreach($agents as $index => $agent)

@php
$balance = $agent->transactions->sum('amount');
@endphp

<tr>

<td>{{ $index+1 }}</td>

<td>{{ $agent->name }}</td>

<td>{{ $agent->phone ?? '-' }}</td>

<td>{{ $agent->country ?? '-' }}</td>

<td>{{ number_format($balance,2) }}</td>

</tr>

@endforeach

</tbody>

</table>

</body>
</html>