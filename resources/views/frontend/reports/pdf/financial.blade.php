<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>التقرير المالي</title>
    <style>
        @page { margin: 110px 30px 60px 30px; }
        body {
            font-family: 'XBRiyaz', 'DejaVu Sans', sans-serif;
            direction: rtl;
            color: #2c3e50;
            font-size: 12px;
            line-height: 1.5;
        }

        /* الهيدر والفوتر */
        .header { position: fixed; top: -90px; left: 0px; right: 0px; height: 80px; border-bottom: 2px solid #2563eb; padding-bottom: 5px; }
        .footer { position: fixed; bottom: -40px; left: 0px; right: 0px; height: 30px; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 5px; }

        .company-name { font-size: 16px; font-weight: bold; color: #1e3a8a; }
        .meta-info { font-size: 10px; color: #64748b; line-height: 1.3; }
        .report-title-container { text-align: center; margin-top: 5px; margin-bottom: 10px; }
        .report-title { font-size: 20px; font-weight: bold; color: #2563eb; }

        /* جدول كروت الإحصائيات - تم إصلاحه ليتوافق مع DomPDF */
        .stats-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .stats-table td { width: 20%; padding: 10px; text-align: center; background: #f8fafc; border: 3px solid #ffffff; border-top: 4px solid #3b82f6; }
        .stats-table td.success { border-top-color: #10b981; }
        .stats-table td.danger { border-top-color: #ef4444; }
        .stat-title { font-size: 11px; color: #64748b; margin-bottom: 4px; }
        .stat-value { font-size: 14px; font-weight: bold; color: #0f172a; }

        /* صندوق الملخص التنفيذي */
        .summary-box { background: #f1f5f9; border: 1px solid #cbd5e1; padding: 10px; margin-bottom: 15px; }
        .summary-title { font-weight: bold; color: #1e293b; font-size: 12px; margin-bottom: 5px; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { border: none; padding: 3px 0; font-size: 11px; }

        /* الجداول العامة للبيانات */
        .section-title { font-size: 12px; font-weight: bold; color: #1e3a8a; margin: 15px 0 5px 0; border-right: 4px solid #2563eb; padding-right: 6px; }
        table.data-table { width: 100%; border-collapse: collapse; background: white; }
        table.data-table th { background-color: #334155; color: white; text-align: right; padding: 7px; font-size: 11px; font-weight: bold; border: 1px solid #334155; }
        table.data-table td { padding: 7px; border: 1px solid #e2e8f0; color: #334155; }
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }
        .text-center { text-align: center !important; }
        .success-text { color: #059669; font-weight: bold; }
        .danger-text { color: #dc2626; font-weight: bold; }

        .signature-table { width: 100%; margin-top: 25px; border-collapse: collapse; }
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
    <div class="report-title">{{ $labels['title'] ?? 'التقرير المالي التنفيذي' }}</div>
    <div class="meta-info" style="text-align: center; margin-top: 3px;">
        {{ $labels['period'] ?? 'الفترة:' }} {{ $from }} - {{ $to }} &nbsp;|&nbsp; {{ $labels['created_at'] ?? 'تاريخ الإنشاء:' }} {{ now()->format('Y-m-d H:i') }}
    </div>
</div>

<table class="stats-table">
    <tr>
        <td>
            <div class="stat-title">{{ $labels['sales'] ?? 'المبيعات' }}</div>
            <div class="stat-value" style="color: #2563eb;">{{ number_format($salesTotal, 2) }}</div>
        </td>
        <td class="success">
            <div class="stat-title">{{ $labels['payments'] ?? 'المقبوضات' }}</div>
            <div class="stat-value" style="color: #059669;">{{ number_format($paymentsTotal, 2) }}</div>
        </td>
        <td class="success">
            <div class="stat-title">{{ $labels['income'] ?? 'الإيرادات' }}</div>
            <div class="stat-value" style="color: #10b981;">{{ number_format($incomeTotal, 2) }}</div>
        </td>
        <td class="danger">
            <div class="stat-title">{{ $labels['expense'] ?? 'المصروفات' }}</div>
            <div class="stat-value" style="color: #dc2626;">{{ number_format($expenseTotal, 2) }}</div>
        </td>
        <td>
            <div class="stat-title">{{ $labels['remaining'] ?? 'الذمم' }}</div>
            <div class="stat-value" style="color: #d97706;">{{ number_format($remainingInvoices, 2) }}</div>
        </td>
    </tr>
</table>

<div class="summary-box">
    <div class="summary-title">{{ $labels['summary_title'] ?? 'الملخص التنفيذي للمؤشرات' }}</div>
    <table class="summary-table">
        <tr>
            <td width="50%"><strong>{{ $labels['net_cash'] ?? 'صافي التدفق النقدي:' }} {{ number_format($netCashFlow, 2) }}</strong></td>
            <td width="50%">{{ $labels['inc_count'] ?? 'عدد الإيرادات:' }} {{ $incomeCount }}</td>
        </tr>
        <tr>
            <td>{{ $labels['inv_count'] ?? 'عدد الفواتير:' }} {{ $invoiceCount }}</td>
            <td>{{ $labels['exp_count'] ?? 'عدد المصروفات:' }} {{ $expenseCount }}</td>
        </tr>
        <tr>
            <td>{{ $labels['pay_count'] ?? 'عدد المدفوعات:' }} {{ $paymentCount }}</td>
            <td></td>
        </tr>
    </table>
</div>

<div class="section-title">{{ $labels['cashbox_section'] ?? 'أرصدة وحركات الخزائن الحالية' }}</div>
<table class="data-table">
    <thead>
    <tr>
        <th>{{ $labels['currency_box'] ?? 'الخزينة والعملة' }}</th>
        <th class="text-center" width="25%">{{ $labels['th_balance'] ?? 'الرصيد الحالي' }}</th>
        <th class="text-center" width="20%">{{ $labels['th_actions'] ?? 'عدد الحركات' }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cashboxes as $cashbox)
        <tr>
            <td>{{ $cashbox->currency->name ?? '' }} ({{ $cashbox->currency->code ?? '' }})</td>
            <td class="text-center"><strong>{{ number_format($cashbox->balance, 2) }}</strong></td>
            <td class="text-center">{{ $cashbox->transactions_count ?? 0 }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="section-title">{{ $labels['income_section'] ?? 'آخر الإيرادات المسجلة' }}</div>
<table class="data-table">
    <thead>
    <tr>
        <th width="20%">{{ $labels['th_date'] ?? 'التاريخ' }}</th>
        <th>{{ $labels['th_desc'] ?? 'الوصف' }}</th>
        <th width="25%" class="text-center">{{ $labels['th_amount'] ?? 'المبلغ' }}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($latestIncomes as $income)
        <tr>
            <td>{{ $income->created_at->format('Y-m-d') }}</td>
            <td>{{ $income->description }}</td>
            <td class="text-center success-text">{{ number_format($income->amount, 2) }}</td>
        </tr>
    @empty
        <tr><td colspan="3" class="text-center" style="color: #94a3b8;">{{ $labels['no_data'] ?? 'لا توجد بيانات مسجلة' }}</td></tr>
    @endforelse
    </tbody>
</table>

<div class="section-title">{{ $labels['expense_section'] ?? 'آخر المصروفات المسجلة' }}</div>
<table class="data-table">
    <thead>
    <tr>
        <th width="20%">{{ $labels['th_date'] ?? 'التاريخ' }}</th>
        <th>{{ $labels['th_desc'] ?? 'الوصف' }}</th>
        <th width="25%" class="text-center">{{ $labels['th_amount'] ?? 'المبلغ' }}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($latestExpenses as $expense)
        <tr>
            <td>{{ $expense->created_at->format('Y-m-d') }}</td>
            <td>{{ $expense->description }}</td>
            <td class="text-center danger-text">{{ number_format($expense->amount, 2) }}</td>
        </tr>
    @empty
        <tr><td colspan="3" class="text-center" style="color: #94a3b8;">{{ $labels['no_data'] ?? 'لا توجد بيانات مسجلة' }}</td></tr>
    @endforelse
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
