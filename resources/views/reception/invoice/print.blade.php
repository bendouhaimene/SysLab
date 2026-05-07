@extends('layouts.app')
@section('title','Invoice')
@section('page-title','Invoice — {{ $invoice->invoice_number }}')

@section('content')

<div style="max-width:680px;margin:0 auto;">

    {{-- Actions --}}
    <div style="display:flex;gap:10px;margin-bottom:20px;">
        <button onclick="window.print()" class="btn-sl btn-primary-sl">
            <i class="bi bi-printer"></i> Print Invoice
        </button>
        <a href="{{ route('reception.search') }}" class="btn-sl">
            <i class="bi bi-plus"></i> New Patient
        </a>
        <a href="{{ route('reception.invoices') }}" class="btn-sl">
            <i class="bi bi-list"></i> All Invoices
        </a>
    </div>

    {{-- Invoice Card --}}
    <div id="print-area" class="section-card">
        <div style="padding:32px;">

            {{-- Header --}}
            <div style="display:flex;justify-content:space-between;
                        align-items:flex-start;margin-bottom:28px;
                        padding-bottom:20px;border-bottom:2px solid var(--border);">
                <div>
                    <div style="font-size:24px;font-weight:700;
                                color:var(--blue);margin-bottom:4px;">
                        ⚗ SysLab
                    </div>
                    <div style="font-size:12px;color:var(--text3);">
                        Medical Laboratory Management
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:20px;font-weight:700;color:var(--text);">
                        INVOICE
                    </div>
                    <div style="font-size:14px;font-weight:600;
                                color:var(--blue);font-family:monospace;">
                        #{{ $invoice->invoice_number }}
                    </div>
                    <div style="font-size:12px;color:var(--text3);margin-top:4px;">
                        {{ $invoice->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            {{-- Patient + Receptionist --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:28px;">
                <div style="background:var(--surface2);border-radius:10px;padding:14px;">
                    <div style="font-size:10px;font-weight:600;color:var(--text3);
                                text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
                        Patient
                    </div>
                    <div style="font-size:15px;font-weight:700;color:var(--text);">
                        {{ $invoice->patient->full_name }}
                    </div>
                    <div style="font-size:11px;color:var(--text3);
                                font-family:monospace;margin-top:3px;">
                        ID: {{ $invoice->patient->national_id }}
                    </div>
                    @if($invoice->patient->phone)
                    <div style="font-size:11px;color:var(--text2);margin-top:3px;">
                        📞 {{ $invoice->patient->phone }}
                    </div>
                    @endif
                </div>
                <div style="background:var(--surface2);border-radius:10px;padding:14px;">
                    <div style="font-size:10px;font-weight:600;color:var(--text3);
                                text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
                        Processed By
                    </div>
                    <div style="font-size:14px;font-weight:600;color:var(--text);">
                        {{ $invoice->receptionist->full_name }}
                    </div>
                    <div style="font-size:11px;color:var(--text3);margin-top:3px;">
                        Receptionist
                    </div>
                    <div style="font-size:11px;color:var(--text2);margin-top:3px;">
                        {{ now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- Total --}}
            <div style="background:linear-gradient(135deg,rgba(79,142,247,.1),rgba(167,139,250,.1));
                        border:1px solid rgba(79,142,247,.2);border-radius:12px;
                        padding:18px 20px;margin-bottom:28px;
                        display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="font-size:12px;color:var(--text2);margin-bottom:4px;">
                        Total Amount Due
                    </div>
                    <div style="font-size:28px;font-weight:700;color:var(--blue);">
                        {{ number_format($invoice->total_amount) }}
                        <span style="font-size:14px;font-weight:500;">DZD</span>
                    </div>
                </div>
                <div style="font-size:40px;">💊</div>
            </div>

            {{-- Portal Credentials --}}
            <div style="background:rgba(16,185,129,.06);
                        border:1px solid rgba(16,185,129,.2);
                        border-radius:12px;padding:18px 20px;margin-bottom:28px;">
                <div style="font-size:12px;font-weight:600;color:var(--green);
                            margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-key-fill"></i> Online Results Access
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                    <div style="background:var(--surface);border-radius:8px;padding:10px 14px;
                                border:1px solid var(--border);">
                        <div style="font-size:10px;color:var(--text3);margin-bottom:3px;">
                            USERNAME
                        </div>
                        <div style="font-family:monospace;font-size:14px;
                                    font-weight:600;color:var(--text);">
                            {{ $invoice->patient->username }}
                        </div>
                    </div>
                    <div style="background:var(--surface);border-radius:8px;padding:10px 14px;
                                border:1px solid var(--border);">
                        <div style="font-size:10px;color:var(--text3);margin-bottom:3px;">
                            PASSWORD
                        </div>
                        <div style="font-family:monospace;font-size:14px;
                                    font-weight:600;color:var(--text);">
                            {{ $plainPassword ?? '••••••••' }}
                        </div>
                    </div>
                </div>
                <div style="font-size:11px;color:var(--text3);">
                    <i class="bi bi-info-circle me-1"></i>
                    Visit
                    <strong style="color:var(--text);">
                        {{ url('/patient/login') }}
                    </strong>
                    or scan the QR code to view your results online.
                </div>
            </div>

            {{-- QR Code + Footer --}}
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:11px;color:var(--text3);max-width:300px;line-height:1.6;">
                    <strong style="color:var(--text);display:block;margin-bottom:4px;">
                        Important Note
                    </strong>
                    Please keep this invoice safe. Present it to the biologist
                    when collecting your samples. Your results will be available
                    online once validated by the doctor.
                </div>
                @if($invoice->qr_code_path && file_exists(storage_path('app/public/'.$invoice->qr_code_path)))
<div style="text-align:center;">
    <div style="width:100px;height:100px;border-radius:8px;
                border:2px solid var(--border);overflow:hidden;
                background:white;padding:4px;">
        <img src="{{ asset('storage/'.$invoice->qr_code_path) }}"
             style="width:100%;height:100%;">
    </div>
    <div style="font-size:10px;color:var(--text3);margin-top:4px;">
        Scan for results
    </div>
</div>
@endif
            </div>

        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
@media print {
    .sidebar, .topbar, .main-wrap > div:first-child,
    .btn-sl, a.btn-sl { display: none !important; }
    .main-wrap { margin-left: 0 !important; }
    .page-content { padding: 0 !important; }
    #print-area {
        border: none !important;
        box-shadow: none !important;
    }
    body { background: white !important; color: black !important; }
}
</style>
@endpush