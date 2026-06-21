<!DOCTYPE html>
<html lang="ar" dir="tlr">
<head>
    <meta charset="UTF-8">
    <title>كشف حساب الوكلاء الأجمالي</title>
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

        /* الترويسة الرئيسية */
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

        /* جدول الوكلاء المطور محاسبياً */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .main-table th {
            background-color: #1e3a8a; /* كحلي فاخر لرأس الجدول */
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
        /* تأثير الصفوف المتبادلة */
        .main-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* الألوان المالية الصريحة للرصيد */
        .balance-positive {
            color: #15803d;
            font-weight: bold;
        }
        .balance-negative {
            color: #b91c1c;
            font-weight: bold;
        }
        .balance-zero {
            color: #64748b;
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
    // تهيئة كلاس الـ Arabic لمعالجة النصوص الفردية وحماية الكود
    $arabic = new \ArPHP\I18N\Arabic();
@endphp

<div class="container">

    <table class="header-table">
        <tr>
            <td>
                <div class="company-name">{{ $arabic->utf8Glyphs(config('app.name', 'نظام السفر والحسابات')) }}</div>
                <div class="report-title">{{ $arabic->utf8Glyphs('كشف الحساب الإجمالي لكافة الوكلاء') }}</div>
                <div style="font-size: 12px; color: #64748b; margin-top: 5px;">
                    {{ $arabic->utf8Glyphs('تاريخ التقرير:') }} {{ date('Y-m-d') }}
                </div>
            </td>
            <td style="text-align: left; vertical-align: middle;">
                <span style="background: #f1f5f9; padding: 6px 12px; border: 1px solid #cbd5e1; font-weight: bold; border-radius: 4px;">
                    {{ $arabic->utf8Glyphs('تقرير تجميعي عام') }}
                </span>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
        <tr>
            <th style="width: 8%;">#</th>
            <th style="width: 32%; text-align: right;">{{ $arabic->utf8Glyphs('اسم الوكيل المعمتد') }}</th>
            <th style="width: 20%;">{{ $arabic->utf8Glyphs('رقم الهاتف') }}</th>
            <th style="width: 18%;">{{ $arabic->utf8Glyphs('الدولة') }}</th>
            <th style="width: 22%;">{{ $arabic->utf8Glyphs('الصـافي الحـالي') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($agents as $index => $agent)
            @php
                $balance = $agent->transactions->sum('amount');
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: right; font-weight: 500; color: #1e293b;">
                    {{ $arabic->utf8Glyphs($agent->name) }}
                </td>
                <td>{{ $agent->phone ?? '-' }}</td>
                <td>{{ $agent->country ? $arabic->utf8Glyphs($agent->country) : '-' }}</td>
                <td>
                        <span class="@if($balance > 0) balance-positive @elseif($balance < 0) balance-negative @else balance-zero @endif">
                            {{ number_format($balance, 2) }}
                        </span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="footer">
        {{ $arabic->utf8Glyphs('تم استخراج هذا الكشف التجميعي تلقائياً من النظام بتاريخ:') }} {{ now()->format('Y-m-d H:i') }}
    </div>

</div>

</body>
</html>
