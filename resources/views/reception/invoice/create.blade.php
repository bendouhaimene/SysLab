@extends('layouts.app')
@section('title','Create Invoice')
@section('page-title','Create Invoice')

@section('content')

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

    {{-- ── Tests Selection ── --}}
    <div>
        {{-- Patient card --}}
        <div style="background:var(--surface);border:1px solid var(--border);
                    border-radius:14px;padding:18px;margin-bottom:18px;
                    display:flex;align-items:center;gap:14px;">
            <div style="width:48px;height:48px;border-radius:50%;flex-shrink:0;
                        background:linear-gradient(135deg,#4f8ef7,#a78bfa);
                        display:flex;align-items:center;justify-content:center;
                        font-size:18px;color:#fff;font-weight:700;">
                {{ strtoupper(substr($patient->first_name,0,1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-size:16px;font-weight:700;color:var(--text);">
                    {{ $patient->full_name }}
                </div>
                <div style="font-size:12px;color:var(--text3);font-family:monospace;
                            letter-spacing:1px;margin-top:2px;">
                    {{ $patient->national_id }}
                </div>
            </div>
            <span class="badge-sl badge-green">
                <i class="bi bi-person-check me-1"></i> Patient Found
            </span>
        </div>

        {{-- Tests catalogue --}}
        <form action="{{ route('reception.invoice.store') }}" method="POST" id="invoice-form">
            @csrf
            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

            @foreach($tests->groupBy('category') as $category => $categoryTests)
            <div class="section-card" style="margin-bottom:14px;">
                <div class="section-head">
                    <span class="section-title">
                        @php
                            $icons = [
                                'Hematology'   => 'bi-droplet-fill',
                                'Biochemistry' => 'bi-flask',
                                'Immunology'   => 'bi-shield-plus',
                                'Microbiology' => 'bi-bug',
                            ];
                            $colors = [
                                'Hematology'   => 'var(--blue)',
                                'Biochemistry' => 'var(--green)',
                                'Immunology'   => 'var(--amber)',
                                'Microbiology' => 'var(--red)',
                            ];
                        @endphp
                        <i class="bi {{ $icons[$category] ?? 'bi-eyedropper' }} me-2"
                           style="color:{{ $colors[$category] ?? 'var(--purple)' }};"></i>
                        {{ $category }}
                    </span>
                    <button type="button"
                            onclick="selectAllInCategory('{{ $category }}')"
                            class="btn-sl btn-sm-sl">
                        Select All
                    </button>
                </div>
                <div style="padding:6px 0;">
                    @foreach($categoryTests as $test)
                    <label style="display:flex;align-items:center;justify-content:space-between;
                                  padding:11px 18px;cursor:pointer;transition:background .15s;
                                  border-bottom:1px solid var(--border);"
                           onmouseover="this.style.background='var(--surface2)'"
                           onmouseout="this.style.background='transparent'">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <input type="checkbox"
                                   name="test_ids[]"
                                   value="{{ $test->id }}"
                                   data-price="{{ $test->price }}"
                                   data-category="{{ $category }}"
                                   onchange="updateTotal()"
                                   style="width:16px;height:16px;accent-color:var(--blue);cursor:pointer;">
                            <div>
                                <div style="font-size:13px;font-weight:500;color:var(--text);">
                                    {{ $test->name }}
                                </div>
                                @if($test->normal_min && $test->normal_max)
                                <div style="font-size:11px;color:var(--text3);">
                                    Normal: {{ $test->normal_min }}–{{ $test->normal_max }}
                                    {{ $test->unit }}
                                </div>
                                @endif
                            </div>
                        </div>
                        <span style="font-weight:600;color:var(--text);font-size:13px;">
                            {{ number_format($test->price) }} DZD
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            @error('test_ids')
            <div style="background:rgba(226,75,74,.1);border:1px solid rgba(226,75,74,.2);
                        border-radius:10px;padding:10px 14px;font-size:13px;color:var(--red);
                        display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
            @enderror

        </form>
    </div>

    {{-- ── Invoice Summary (sticky) ── --}}
    <div style="position:sticky;top:72px;">
        <div class="section-card">
            <div class="section-head">
                <span class="section-title">
                    <i class="bi bi-receipt me-2" style="color:var(--green);"></i>
                    Invoice Summary
                </span>
            </div>
            <div style="padding:18px;">

                {{-- Selected tests list --}}
                <div id="selected-list"
                     style="min-height:80px;margin-bottom:16px;">
                    <p style="font-size:12px;color:var(--text3);text-align:center;
                               padding:20px 0;">
                        No tests selected yet
                    </p>
                </div>

                <div style="border-top:1px solid var(--border);padding-top:14px;margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;
                                align-items:center;margin-bottom:6px;">
                        <span style="font-size:12px;color:var(--text2);">
                            Tests Selected
                        </span>
                        <span id="test-count"
                              style="font-size:12px;font-weight:600;color:var(--text);">0</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:14px;font-weight:600;color:var(--text);">
                            Total Amount
                        </span>
                        <span id="total-display"
                              style="font-size:20px;font-weight:700;color:var(--blue);">
                            0 DZD
                        </span>
                    </div>
                </div>

                <button type="submit" form="invoice-form"
                        class="btn-sl btn-primary-sl"
                        style="width:100%;justify-content:center;padding:13px;">
                    <i class="bi bi-printer"></i> Generate Invoice
                </button>

                <a href="{{ route('reception.search') }}"
                   class="btn-sl"
                   style="width:100%;justify-content:center;
                          margin-top:8px;display:flex;">
                    Cancel
                </a>

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function updateTotal() {
    const checked  = document.querySelectorAll('input[name="test_ids[]"]:checked');
    let total = 0;
    const list  = document.getElementById('selected-list');
    list.innerHTML = '';

    checked.forEach(cb => {
        const price = parseFloat(cb.dataset.price);
        total += price;
        const label = cb.closest('label');
        const name  = label.querySelector('div > div').textContent.trim();
        list.innerHTML += `
            <div style="display:flex;justify-content:space-between;
                        align-items:center;padding:6px 0;
                        border-bottom:1px solid var(--border);">
                <span style="font-size:12px;color:var(--text);">${name}</span>
                <span style="font-size:12px;font-weight:600;color:var(--text);">
                    ${price.toLocaleString()} DZD
                </span>
            </div>`;
    });

    if (checked.length === 0) {
        list.innerHTML = '<p style="font-size:12px;color:var(--text3);' +
            'text-align:center;padding:20px 0;">No tests selected yet</p>';
    }

    document.getElementById('test-count').textContent    = checked.length;
    document.getElementById('total-display').textContent =
        total.toLocaleString() + ' DZD';
}

function selectAllInCategory(cat) {
    document.querySelectorAll(`input[data-category="${cat}"]`)
        .forEach(cb => { cb.checked = true; });
    updateTotal();
}
</script>
@endpush