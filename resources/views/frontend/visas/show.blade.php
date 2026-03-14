@extends('frontend.layouts.app')

@section('content')

<div class="p-4 md:p-8 space-y-8 bg-gray-50 dark:bg-gray-900 min-h-screen">

    <div class="glass-card fade-in">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight">
                    تفاصيل التأشيرة
                </h1>
                <p class="text-gray-500 text-sm mt-1 flex items-center">
                    <span class="opacity-70 ml-1">العميل:</span> 
                    <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $visa->client->full_name ?? '-' }}</span>
                </p>
            </div>

            <div class="flex flex-wrap gap-3 items-center">
                <span class="status-badge @if($visa->status == 'issued') status-success @elseif($visa->status == 'cancelled') status-danger @else status-warning @endif">
                    {{ ucfirst($visa->status) }}
                </span>
                
                <div class="flex gap-2">
                    <button onclick="printVisaReport()" class="visa-btn btn-print">
                        <i class="fas fa-print ml-1"></i> طباعة التقرير
                    </button>
                    @if($visa->document_file || $visa->image_file)
                    <button onclick="sendVisaWhatsapp()" class="visa-btn btn-whatsapp">
                        <i class="fab fa-whatsapp ml-1"></i> واتساب
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <div class="xl:col-span-2 space-y-8">

            <div class="glass-card">
                <h2 class="section-title border-b pb-4 mb-6">
                    <i class="fas fa-info-circle ml-2 text-blue-500"></i> المعلومات العامة
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

            @if($visa->document_file || $visa->image_file)
            <div class="glass-card border-l-4 border-blue-500">
                <h2 class="section-title mb-6">
                    <i class="fas fa-file-pdf ml-2 text-red-500"></i> ملفات التأشيرة والمرفقات
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    @if($visa->document_file)
                    <div class="visa-file-container">
                        <div class="visa-file-header">
                            <span class="font-bold text-gray-700">مستند PDF</span>
                            <div class="flex gap-2">
                                <button onclick="previewPDF('{{ asset('storage/'.$visa->document_file) }}')" class="visa-btn btn-blue-outline text-xs">👁 معاينة</button>
                                <a href="{{ asset('storage/'.$visa->document_file) }}" download class="visa-btn btn-green-outline text-xs">⬇ تحميل</a>
                                <button onclick="printFile('{{ asset('storage/'.$visa->document_file) }}')" class="visa-btn btn-gray-outline text-xs">🖨 طباعة</button>
                            </div>
                        </div>
                        <iframe src="{{ asset('storage/'.$visa->document_file) }}" class="visa-preview-iframe"></iframe>
                    </div>
                    @endif

                    @if($visa->image_file)
                    <div class="visa-file-container">
                        <div class="visa-file-header">
                            <span class="font-bold text-gray-700">نسخة الصورة</span>
                            <div class="flex gap-2">
                                <button onclick="openImageModal('{{ asset('storage/'.$visa->image_file) }}')" class="visa-btn btn-blue-outline text-xs">🔍 تكبير</button>
                                <a href="{{ asset('storage/'.$visa->image_file) }}" download class="visa-btn btn-green-outline text-xs">⬇ تحميل</a>
                                <button onclick="printFile('{{ asset('storage/'.$visa->image_file) }}')" class="visa-btn btn-gray-outline text-xs">🖨 طباعة</button>
                            </div>
                        </div>
                        <div class="flex justify-center p-4 bg-gray-100 rounded-b-xl">
                            <img src="{{ asset('storage/'.$visa->image_file) }}" class="visa-image-preview">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <div class="glass-card">
                <h2 class="section-title mb-6 text-green-700">
                    <i class="fas fa-money-bill-wave ml-2"></i> المعلومات المالية والربحية
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="finance-card bg-blue-50 border-blue-100">
                        <span>السعر الأصلي</span>
                        <h3 class="text-blue-700">{{ number_format($visa->original_price,2) }}</h3>
                    </div>
                    <div class="finance-card bg-yellow-50 border-yellow-100">
                        <span>الخصم</span>
                        <h3 class="text-yellow-700">{{ number_format($visa->discount_amount,2) }}</h3>
                        <p class="text-[10px]">{{ $visa->discount_percentage ?? 0 }} %</p>
                    </div>
                    <div class="finance-card bg-emerald-50 border-emerald-100">
                        <span>سعر البيع</span>
                        <h3 class="text-emerald-700">{{ number_format($visa->sale_price,2) }}</h3>
                    </div>
                    <div class="finance-card bg-gray-50 border-gray-100">
                        <span>التكلفة</span>
                        <h3>{{ number_format($visa->cost_price,2) }}</h3>
                    </div>
                    <div class="finance-card bg-green-100 border-green-200">
                        <span>الربح الصافي</span>
                        <h3 class="text-green-800">{{ number_format($visa->profit,2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="glass-card">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="section-title">
                        <i class="fas fa-file-invoice-dollar ml-2 text-indigo-500"></i> الفاتورة والمدفوعات
                    </h2>
                    @if(!$visa->is_paid)
                    <button onclick="openPaymentModal()" class="btn-primary-action">
                        + إضافة دفعة جديدة
                    </button>
                    @endif
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-8">
                    <div class="stat-box border-b-4 border-indigo-500">
                        <span>إجمالي الفاتورة</span>
                        <div class="flex items-baseline gap-1">
                            <h3>{{ number_format($visa->invoice->total_amount,2) }}</h3>
                            <small>{{ $visa->invoice->currency->symbol ?? '' }}</small>
                        </div>
                    </div>
                    <div class="stat-box border-b-4 border-emerald-500">
                        <span>المبلغ المدفوع</span>
                        <div class="flex items-baseline gap-1 text-emerald-600">
                            <h3>{{ number_format($visa->invoice->paid_amount,2) }}</h3>
                        </div>
                    </div>
                    <div class="stat-box border-b-4 border-red-500">
                        <span>المبلغ المتبقي</span>
                        <div class="flex items-baseline gap-1 text-red-600">
                            <h3>{{ number_format($visa->invoice->remaining_amount,2) }}</h3>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="table-label mb-4">سجل العمليات المالية</h3>
                    <div class="overflow-hidden rounded-xl border border-gray-200">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المبلغ</th>
                                    <th>العملة</th>
                                    <th>طريقة الدفع</th>
                                    <th>بواسطة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visa->invoice->payments as $payment)
                                <tr>
                                    <td>{{ $payment->created_at->format('Y-m-d') }}</td>
                                    <td class="font-bold text-emerald-600">{{ number_format($payment->amount,2) }}</td>
                                    <td>{{ $payment->currency->name ?? '-' }}</td>
                                    <td><span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ ucfirst($payment->payment_method) }}</span></td>
                                    <td>{{ $payment->creator->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="empty-state">لا توجد دفعات مسجلة حتى الآن</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            
            @if($visa->agent)
            <div class="glass-card border-t-4 border-orange-400">
                <h2 class="section-title mb-4">الوكيل المعتمد</h2>
                <div class="space-y-4">
                    <div class="info-card bg-orange-50/50 p-3 rounded-lg">
                        <span class="text-orange-600">الاسم</span>
                        <p class="text-lg">{{ $visa->agent->name }}</p>
                    </div>
                    <div class="info-card bg-gray-50 p-3 rounded-lg">
                        <span>تكلفة الوكيل</span>
                        <p class="text-lg font-bold">{{ number_format($visa->agent_cost,2) }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="glass-card overflow-hidden">
                <h2 class="section-title mb-6">سجل تتبع الحالة</h2>
                <div class="timeline-container">
                    @foreach($visa->statusHistories as $history)
                    <div class="timeline-item">
                        <div class="timeline-line"></div>
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium">
                                    تغيير إلى <span class="text-blue-600 font-bold uppercase">{{ $history->new_status }}</span>
                                </p>
                            </div>
                            <span class="text-[10px] text-gray-400 block mt-1">
                                {{ $history->created_at->format('Y-m-d | H:i') }}
                            </span>
                            <p class="text-xs text-gray-500 mt-1 italic">بواسطة: {{ $history->user->name ?? 'النظام' }}</p>
                            @if($history->notes)
                            <div class="mt-2 p-2 bg-blue-50 rounded text-xs text-blue-700">
                                {{ $history->notes }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 bg-black/90 z-[9999] hidden flex items-center justify-center p-4">
    <button onclick="closeImageModal()" class="absolute top-5 right-5 text-white text-4xl hover:text-red-500 transition">&times;</button>
    <img id="visaModalImage" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl">
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap');

    :root {
        --primary: #2563eb;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
    }

    body { font-family: 'Tajawal', sans-serif; }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(229, 231, 235, 1);
        transition: transform 0.3s ease;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1f2937;
        display: flex;
        align-items: center;
    }

    /* Badges */
    .status-badge {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .status-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

    /* Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
    }
    .info-card span { font-size: 0.75rem; color: #9ca3af; display: block; margin-bottom: 4px; }
    .info-card p { font-size: 0.95rem; font-weight: 700; color: #374151; }

    /* Finance & Stats */
    .finance-card {
        padding: 16px;
        border-radius: 15px;
        text-align: center;
        border: 1px solid transparent;
    }
    .finance-card span { font-size: 0.7rem; font-weight: 600; opacity: 0.8; }
    .finance-card h3 { font-size: 1.25rem; font-weight: 800; margin-top: 4px; }

    .stat-box {
        padding: 20px;
        background: #f9fafb;
        border-radius: 15px;
    }
    .stat-box span { font-size: 0.8rem; color: #6b7280; font-weight: 600; }
    .stat-box h3 { font-size: 1.75rem; font-weight: 900; margin-top: 5px; }

    /* Buttons */
    .btn-primary-action {
        background: var(--primary);
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        transition: 0.3s;
    }
    .btn-primary-action:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3); }

    .visa-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s;
    }
    .btn-print { background: #374151; color: white; }
    .btn-whatsapp { background: #16a34a; color: white; }
    .btn-blue-outline { border: 1px solid #2563eb; color: #2563eb; background: transparent; }
    .btn-green-outline { border: 1px solid #16a34a; color: #16a34a; background: transparent; }
    .btn-gray-outline { border: 1px solid #6b7280; color: #6b7280; background: transparent; }
    .visa-btn:hover { filter: brightness(1.1); }

    /* Table */
    .modern-table { width: 100%; border-collapse: collapse; text-align: right; }
    .modern-table th { background: #f8fafc; padding: 14px; color: #64748b; font-size: 0.8rem; text-transform: uppercase; }
    .modern-table td { padding: 14px; border-top: 1px solid #f1f5f9; font-size: 0.9rem; }
    .empty-state { padding: 40px; text-align: center; color: #94a3b8; font-italic: italic; }

    /* Timeline */
    .timeline-container { position: relative; padding-right: 20px; }
    .timeline-item { position: relative; padding-bottom: 25px; }
    .timeline-line { position: absolute; right: 4px; top: 10px; bottom: 0; width: 2px; background: #e5e7eb; }
    .timeline-dot { position: absolute; right: 0; top: 10px; width: 10px; height: 10px; background: var(--primary); border-radius: 50%; z-index: 10; border: 2px solid white; }
    .timeline-content { padding-right: 20px; }

    /* Files */
    .visa-file-container { border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; background: white; }
    .visa-file-header { background: #f9fafb; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; }
    .visa-preview-iframe { width: 100%; height: 400px; border: none; }
    .visa-image-preview { max-height: 350px; width: auto; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: zoom-in; }

    .fade-in { animation: fadeIn 0.5s ease-in; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

</style>

<script>
    function openPaymentModal() {
        // Logic handled by included partial
    }

    function openImageModal(src) {
        const modal = document.getElementById("imageModal");
        const img = document.getElementById("visaModalImage");
        img.src = src;
        modal.classList.remove("hidden");
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById("imageModal").classList.add("hidden");
        document.body.style.overflow = 'auto';
    }

    function previewPDF(url) {
        window.open(url, '_blank');
    }

    function printFile(url) {
        const win = window.open(url, '_blank');
        win.onload = function() { win.print(); }
    }

    function printVisaReport() {
        window.print(); // Best practice for modern browsers
    }

    function sendVisaWhatsapp() {
        let fileName = "ملف التأشيرة";
        let fileUrl = "";
        
        @if($visa->document_file)
            fileUrl = "{{ asset('storage/'.$visa->document_file) }}";
        @elseif($visa->image_file)
            fileUrl = "{{ asset('storage/'.$visa->image_file) }}";
        @endif

        const message = encodeURIComponent(`عزيزي العميل، تم إصدار التأشيرة الخاصة بك.\nيمكنك تحميل الملف من الرابط التالي:\n${fileUrl}`);
        window.open(`https://wa.me/?text=${message}`, '_blank');
    }
</script>

@include('frontend.visas.partials.add_pay')

@endsection