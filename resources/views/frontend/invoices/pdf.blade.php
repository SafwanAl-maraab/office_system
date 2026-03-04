<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; direction: rtl; }
        .header { border-bottom: 2px solid #000; margin-bottom:20px; }
        .section { margin-bottom:15px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:8px; text-align:center; }
        .total { font-weight:bold; }
    </style>
</head>
<body>

<div class="header">
    <h2>فاتورة رسمية</h2>
    <p>رقم الفاتورة: #{{ $invoice->id }}</p>
</div>

<div class="section">
    <p>العميل: {{ $invoice->client->full_name }}</p>
    <p>التاريخ: {{ $invoice->created_at->format('Y-m-d') }}</p>
</div>

<table>
    <tr>
        <th>الإجمالي</th>
        <th>المدفوع</th>
        <th>المتبقي</th>
    </tr>
    <tr>
        <td>{{ $invoice->total_amount }} {{ $invoice->currency->symbol }}</td>
        <td>{{ $invoice->paid_amount }} {{ $invoice->currency->symbol }}</td>
        <td>{{ $invoice->remaining_amount }} {{ $invoice->currency->symbol }}</td>
    </tr>
</table>

<h3 style="margin-top:20px;">سجل الدفعات</h3>

<table>
    <tr>
        <th>المبلغ</th>
        <th>طريقة الدفع</th>
        <th>التاريخ</th>
    </tr>

    @foreach($invoice->payments as $payment)
        <tr>
            <td>{{ $payment->amount }}</td>
            <td>{{ $payment->payment_method }}</td>
            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach

</table>

</body>
</html>
