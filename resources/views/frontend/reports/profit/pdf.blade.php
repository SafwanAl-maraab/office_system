

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير تحليل الأرباح</title>
    <style>
        @page { margin: 110px 30px 60px 30px; }
        body {
            font-family: 'XBRiyaz', 'DejaVu Sans', sans-serif;
            direction: rtl;
            color: #2c3e50;
            font-size: 12px;
            line-height: 1.5;
        }

        /* الهيدر والفوتر الثابت عبر الصفحات */
        .header { position: fixed; top: -90px; left: 0px; right: 0px; height: 80px; border-bottom: 2px solid #2563eb; padding-bottom: 5px; }
        .footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 30px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }

        .company-name { font-size: 16px; font-weight: bold; color: #1e3a8a; }
        .meta-info { font-size: 10px; color: #64748b; line-height: 1.3; }
        .report-title-container { text-align: center; margin-top: 5px; margin-bottom: 10px; }
        .report-title { font-size: 20px; font-weight: bold; color: #2563eb; }

        /* كروت الإحصائيات الفخمة */
        .stats-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .stats-table td { width: 20%; padding: 10px; text-align: center; background: #f8fafc; border: 3px solid #ffffff; border-top: 4px solid #3b82f6; }
        .stats-table td.success { border-top-color: #10b981; }
        .stats-table td.warning { border-top-color: #f59e0b; }
        .stat-title { font-size: 11px; color: #64748b; margin-bottom: 4px; }
        .stat-value { font-size: 14px; font-weight: bold; color: #0f172a; }

        /* الجداول العامة للبيانات */
        .section-title { font-size: 12px; font-weight: bold; color: #1e3a8a; margin: 20px 0 8px 0; border-right: 4px solid #2563eb; padding-right: 6px; }
        table.data-table { width: 100%; border-collapse: collapse; background: white; }
        table.data-table th { background-color: #334155; color: white; text-align: center; padding: 8px; font-size: 11px; font-weight: bold; border: 1px solid #334155; }
        table.data-table td { padding: 8px; border: 1px solid #e2e8f0; color: #334155; text-align: center; }
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .success-text { color: #059669; font-weight: bold; }
        .primary-text { color: #2563eb; font-weight: bold; }
        .warning-text { color: #d97706; font-weight: bold; }

        .signature-table { width: 100%; margin-top: 35px; border-collapse: collapse; }
        .signature-table td { border: none; text-align: center; font-size: 11px; color: #334155; width: 50%; }
    </style>
</head>
<body>

<div class="header">
    <table width="100%" style="border: none; border-collapse: collapse;">
        <tr>
            <td width="60%" style="border: none; text-align: right; vertical-align: top;">
                <div class="company-name">{{ $info->office_name ?? '' }}</div>
                <div class="meta-info">
                    @if($info)
                        {{ $info->address }} <br>
                        {{ $info->primary_phone }} | {{ $info->email }}
                    @endif
                </div>
            </td>
            <td width="40%" style="border: none; text-align: left; vertical-align: top;">
                @if($info && $info->logo)
                    <img src="{{ public_path('storage/'.$info->logo) }}" style="max-height: 50px;">
                @endif
            </td>
        </tr>
    </table>
</div>



<div class="report-title-container">
    <div class="report-title">{{ $labels['title'] ?? 'تقرير تحليل الأرباح' }}</div>
    <div class="meta-info" style="text-align: center; margin-top: 3px;">
        {{ $labels['from'] ?? 'من:' }} {{ $from }} {{ $labels['to'] ?? 'إلى:' }} {{ $to }} &nbsp;|&nbsp; {{ $labels['created_at'] ?? 'تاريخ الإنشاء:' }} {{ now()->format('Y-m-d H:i') }}
    </div>
</div>
@if($selectedCurrency)

    <div>

        العملة:

        {{ $selectedCurrency->name }}

        ({{ $selectedCurrency->code }})

    </div>

@endif
<table class="stats-table">
    <tr>
        <td>
            <div class="stat-title">{{ $labels['sales'] ?? 'إجمالي المبيعات' }}</div>
            <div class="stat-value" style="color: #2563eb;">{{ number_format($totals['sales'], 2) }}</div>
        </td>
        <td>
            <div class="stat-title">{{ $labels['cost'] ?? 'إجمالي التكلفة' }}</div>
            <div class="stat-value" style="color: #64748b;">{{ number_format($totals['cost'], 2) }}</div>
        </td>
        <td class="success">
            <div class="stat-title">{{ $labels['expected_profit'] ?? 'الربح المتوقع' }}</div>
            <div class="stat-value" style="color: #059669;">{{ number_format($totals['expected_profit'], 2) }}</div>
        </td>
        <td class="success">
            <div class="stat-title">{{ $labels['confirmed_profit'] ?? 'الربح المؤكد' }}</div>
            <div class="stat-value" style="color: #10b981;">{{ number_format($totals['confirmed_profit'], 2) }}</div>
        </td>
        <td class="warning">
            <div class="stat-title">{{ $labels['remaining'] ?? 'المتبقي' }}</div>
            <div class="stat-value" style="color: #d97706;">{{ number_format($totals['remaining'], 2) }}</div>
        </td>
    </tr>
</table>

<div class="section-title">{{ $labels['section_title'] ?? 'تحليل الأرباح حسب النشاط' }}</div>
<table class="data-table">
    <thead>
    <tr>
        <th class="text-right" width="20%">{{ $labels['th_activity'] ?? 'النشاط' }}</th>
        <th>{{ $labels['th_count'] ?? 'عدد العمليات' }}</th>
        <th>{{ $labels['sales'] ?? 'المبيعات' }}</th>
        <th>{{ $labels['cost'] ?? 'التكلفة' }}</th>
        <th>{{ $labels['expected_profit'] ?? 'الربح المتوقع' }}</th>
        <th>{{ $labels['confirmed_profit'] ?? 'الربح المؤكد' }}</th>
        <th>{{ $labels['remaining'] ?? 'المتبقي' }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="text-right" style="font-weight: bold;">{{ $labels['activity_visas'] ?? 'التأشيرات' }}</td>
        <td>{{ $analysis['visas']['count'] }}</td>
        <td>{{ number_format($analysis['visas']['sales'], 2) }}</td>
        <td>{{ number_format($analysis['visas']['cost'], 2) }}</td>
        <td class="primary-text">{{ number_format($analysis['visas']['expected_profit'], 2) }}</td>
        <td class="success-text">{{ number_format($analysis['visas']['confirmed_profit'], 2) }}</td>
        <td class="warning-text">{{ number_format($analysis['visas']['remaining'], 2) }}</td>
    </tr>
    <tr>
        <td class="text-right" style="font-weight: bold;">{{ $labels['activity_bookings'] ?? 'الحجوزات' }}</td>
        <td>{{ $analysis['bookings']['count'] }}</td>
        <td>{{ number_format($analysis['bookings']['sales'], 2) }}</td>
        <td>{{ number_format($analysis['bookings']['cost'], 2) }}</td>
        <td class="primary-text">{{ number_format($analysis['bookings']['expected_profit'], 2) }}</td>
        <td class="success-text">{{ number_format($analysis['bookings']['confirmed_profit'], 2) }}</td>
        <td class="warning-text">{{ number_format($analysis['bookings']['remaining'], 2) }}</td>
    </tr>
    <tr>
        <td class="text-right" style="font-weight: bold;">{{ $labels['activity_services'] ?? 'الطلبات' }}</td>
        <td>{{ $analysis['services']['count'] }}</td>
        <td>{{ number_format($analysis['services']['sales'], 2) }}</td>
        <td>{{ number_format($analysis['services']['cost'], 2) }}</td>
        <td class="primary-text">{{ number_format($analysis['services']['expected_profit'], 2) }}</td>
        <td class="success-text">{{ number_format($analysis['services']['confirmed_profit'], 2) }}</td>
        <td class="warning-text">{{ number_format($analysis['services']['remaining'], 2) }}</td>
    </tr>
    </tbody>
</table>

<table class="signature-table">
    <tr>
        <td>
            ____________________ <br><br>
            <strong>{{ $labels['sign_admin'] ?? 'مسؤول النظام' }}</strong>
        </td>
        <td>
            ____________________ <br><br>
            <strong>{{ $labels['sign_manager'] ?? 'مدير الفرع' }}</strong>
        </td>
    </tr>
</table>

<div class="footer">
    {{ $info->office_name ?? '' }} | {{ $labels['auto_generated'] ?? 'تم التوليد تلقائياً عبر النظام' }} - {{ now()->format('Y') }}
</div>

</body>
</html>
