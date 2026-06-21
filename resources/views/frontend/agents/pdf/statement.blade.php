<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>كشف حساب وكيل</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 13px;
            color: #334155;
            margin: 0;
            padding: 5px;
        }

        /* الترويسة الرئيسية المحمية */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .header-table td {
            border: none;
            padding: 0;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin-top: 5px;
        }

        /* مربعات البيانات المالية والوكيل */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .summary-table td {
            border: none;
            padding: 0;
            width: 50%;
            vertical-align: top;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 12px;
            margin-left: 10px;
            border-radius: 6px;
        }
        .balance-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 12px;
            margin-right: 10px;
            border-radius: 6px;
            text-align: center;
        }

        /* جدول الحركات المطور محاسبياً */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .main-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            border: 1px solid #1e3a8a;
            padding: 10px 8px;
            font-weight: bold;
            text-align: center;
            font-size: 12px;
        }
        .main-table td {
            border: 1px solid #cbd5e1;
            padding: 9px 8px;
            text-align: center;
            font-size: 12px;
        }
        .main-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* الألوان المالية الصريحة للـ PDF */
        .amount-plus {
            color: #15803d;
            font-weight: bold;
        }
        .amount-minus {
            color: #b91c1c;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px dashed #94a3b8;
            padding-top: 10px;
            font-size: 11px;
            color: #64748b;
        }
    </style>
</head>
<body>

@php
    // تهيئة كلاس الـ Arabic لمعالجة النصوص الفردية فقط وحماية الـ HTML
    $arabic = new \ArPHP\I18N\Arabic();
@endphp

<div class="container">

    <table class="header-table">
        <tr>
            <td>
                <div class="company-name">{{ $arabic->utf8Glyphs(config('app.name', 'نظام السفر والحسابات')) }}</div>
                <div class="report-title">{{ $arabic->utf8Glyphs('كشف حساب وكيل تفصيلي') }}</div>
            </td>
            <td style="text-align: left; vertical-align: middle;">
                <span style="background: #f1f5f9; padding: 6px 12px; border: 1px solid #cbd5e1; font-weight: bold; border-radius: 4px;">
                    {{ $arabic->utf8Glyphs('مستند محاسبي رسمي') }}
                </span>
            </td>
        </tr>
    </table>

    <table class="summary-table">
        <tr>
            <td>
                <div class="info-box">
                    <strong>{{ $arabic->utf8Glyphs('اسم الوكيل المعتمد:') }}</strong>
                    <span style="color: #1e3a8a; font-weight: bold;">{{ $arabic->utf8Glyphs($agent->name) }}</span>
                    <br><br>
                    <span style="font-size: 11px; color: #64748b;">
                        {{ $arabic->utf8Glyphs('تاريخ استخراج الكشف:') }} {{ now()->format('Y-m-d') }}
                    </span>
                </div>
            </td>
            <td>
                <div class="balance-box">
                    <span style="font-size: 12px; color: #166534; font-weight: bold;">
                        {{ $arabic->utf8Glyphs('صافي الرصيد الحالي المستحق') }}
                    </span><br>
                    <span style="font-size: 20px; font-weight: bold; color: #166534;">
                        {{ number_format($balance, 2) }}
                    </span>
                </div>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
        <tr>
            <th style="width: 15%;">{{ $arabic->utf8Glyphs('التاريخ') }}</th>
            <th style="width: 35%;">{{ $arabic->utf8Glyphs('طبيعة العملية / البيان') }}</th>
            <th style="width: 15%;">{{ $arabic->utf8Glyphs('رقم المرجع') }}</th>
            <th style="width: 17%;">{{ $arabic->utf8Glyphs('المبلغ المالي') }}</th>
            <th style="width: 18%;">{{ $arabic->utf8Glyphs('الرصيد') }}</th>
        </tr>
        </thead>
        <tbody>
        @php $runningBalance = 0; @endphp
        @foreach($transactions as $t)
            @php $runningBalance += $t->amount; @endphp
            <tr>
                <td>{{ $t->created_at->format('Y-m-d') }}</td>
                <td style="text-align: right;">
                    @if($t->type == 'visa_cost')
                        {{ $arabic->utf8Glyphs('تكلفة إصدار تأشيرة') }}
                    @elseif($t->type == 'payment')
                        {{ $arabic->utf8Glyphs('توريد دفعة مالية من الوكيل') }}
                    @else
                        {{ $arabic->utf8Glyphs('تعديل قيد محاسبي') }}
                    @endif
                </td>
                <td>{{ $t->visa ? $t->visa->visa_number : '-' }}</td>
                <td>
                        <span class="{{ $t->amount < 0 ? 'amount-minus' : 'amount-plus' }}">
                            {{ number_format($t->amount, 2) }}
                        </span>
                </td>
                <td style="font-weight: bold;">{{ number_format($runningBalance, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ $arabic->utf8Glyphs('يرجى مطابقة الرصيد فور الاستلام. تم إنشاء التقرير تلقائياً بتاريخ:') }} {{ now()->format('Y-m-d H:i') }}
    </div>

</div>

</body>
</html>
