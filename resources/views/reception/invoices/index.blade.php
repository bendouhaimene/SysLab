@extends('layouts.app')
@section('title','Invoices')
@section('page-title','My Invoices')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <p style="font-size:13px;color:var(--text2);">
        All invoices you have created
    </p>
    <a href="{{ route('reception.search') }}" class="btn-sl btn-primary-sl">
        <i class="bi bi-plus-lg"></i> New Invoice
    </a>
</div>

<div class="section-card">
    <div class="section-head">
        <span class="section-title">
            <i class="bi bi-receipt me-2" style="color:var(--blue);"></i>
            Invoices History
        </span>
        <span style="font-size:12px;color:var(--text3);">
            {{ $invoices->total() }} total
        </span>
    </div>
    <table class="sl-table">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Patient</th>
                <th>Tests</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
            <tr>
                <td>
                    <span style="font-family:monospace;font-size:12px;color:var(--blue);">
                        #{{ $invoice->invoice_number }}
                    </span>
                </td>
                <td>
                    <div style="font-weight:500;color:var(--text);">
                        {{ $invoice->patient->full_name }}
                    </div>
                    <div style="font-size:11px;color:var(--text3);font-family:monospace;">
                        {{ $invoice->patient->national_id }}
                    </div>
                </td>
                <td>
                    <span class="badge-sl badge-blue">
                        {{ $invoice->tests->count() }} tests
                    </span>
                </td>
                <td style="font-weight:600;color:var(--text);">
                    {{ number_format($invoice->total_amount) }} DZD
                </td>
                <td>
                    <span class="badge-sl {{ $invoice->status==='paid'?'badge-green':'badge-amber' }}">
                        {{ ucfirst($invoice->status) }}
                    </span>
                </td>
                <td style="font-size:12px;color:var(--text3);">
                    {{ $invoice->created_at->format('d/m/Y H:i') }}
                </td>
                <td>
                    <a href="{{ route('reception.invoice.print', $invoice) }}"
                       class="btn-sl btn-sm-sl">
                        <i class="bi bi-eye"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:40px;color:var(--text3);">
                    <i class="bi bi-receipt" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                    No invoices yet
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($invoices->hasPages())
    <div style="padding:14px 18px;">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

@endsection