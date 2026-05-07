@extends('layouts.app')
@section('title','Search Patient')
@section('page-title','Patient Search')

@section('content')

<div style="max-width:560px;margin:0 auto;padding-top:20px;">

    {{-- Hero --}}
    <div style="text-align:center;margin-bottom:32px;">
        <div style="width:64px;height:64px;border-radius:18px;
                    background:linear-gradient(135deg,#4f8ef7,#a78bfa);
                    display:flex;align-items:center;justify-content:center;
                    font-size:28px;margin:0 auto 14px;">
            🔍
        </div>
        <h2 style="font-size:20px;font-weight:700;color:var(--text);margin-bottom:6px;">
            Search Patient
        </h2>
        <p style="font-size:13px;color:var(--text2);">
            Enter the patient's 18-digit National ID
        </p>
    </div>

    <div class="section-card">
        <div style="padding:28px;">
            <form action="{{ route('reception.search.post') }}" method="POST">
                @csrf

                <div style="margin-bottom:20px;">
                    <label style="font-size:12px;font-weight:500;color:var(--text2);
                                  display:block;margin-bottom:8px;">
                        National ID (18 digits)
                    </label>
                    <div style="position:relative;">
                        <i class="bi bi-person-vcard"
                           style="position:absolute;left:13px;top:50%;transform:translateY(-50%);
                                  color:var(--text3);font-size:16px;"></i>
                        <input type="text"
                               name="national_id"
                               id="nid-input"
                               value="{{ old('national_id') }}"
                               maxlength="18"
                               oninput="updateCounter(this)"
                               class="form-control-sl"
                               style="padding-left:42px;padding-right:60px;
                                      font-family:monospace;font-size:15px;
                                      letter-spacing:2px;"
                               placeholder="000000000000000000"
                               required autofocus>
                        <span id="nid-counter"
                              style="position:absolute;right:13px;top:50%;transform:translateY(-50%);
                                     font-size:11px;color:var(--text3);font-family:monospace;">
                            0/18
                        </span>
                    </div>

                    {{-- Progress bar --}}
                    <div style="height:3px;background:var(--border);border-radius:2px;
                                margin-top:8px;overflow:hidden;">
                        <div id="nid-progress"
                             style="height:100%;width:0%;border-radius:2px;
                                    background:linear-gradient(90deg,#4f8ef7,#a78bfa);
                                    transition:width .2s,background .2s;">
                        </div>
                    </div>

                    @error('national_id')
                        <div style="margin-top:8px;padding:8px 12px;
                                    background:rgba(226,75,74,.1);
                                    border:1px solid rgba(226,75,74,.2);
                                    border-radius:8px;font-size:12px;color:var(--red);
                                    display:flex;align-items:center;gap:6px;">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-sl btn-primary-sl"
                        style="width:100%;justify-content:center;padding:13px;">
                    <i class="bi bi-search"></i> Search Patient
                </button>
            </form>
        </div>
    </div>

    {{-- Info cards --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:16px;">
        <div style="background:var(--surface);border:1px solid var(--border);
                    border-radius:12px;padding:14px;display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:9px;
                        background:rgba(16,185,129,.1);color:var(--green);
                        display:flex;align-items:center;justify-content:center;font-size:15px;">
                <i class="bi bi-person-check"></i>
            </div>
            <div>
                <div style="font-size:12px;font-weight:500;color:var(--text);">Found</div>
                <div style="font-size:11px;color:var(--text3);">Go to invoice creation</div>
            </div>
        </div>
        <div style="background:var(--surface);border:1px solid var(--border);
                    border-radius:12px;padding:14px;display:flex;align-items:center;gap:10px;">
            <div style="width:34px;height:34px;border-radius:9px;
                        background:rgba(245,158,11,.1);color:var(--amber);
                        display:flex;align-items:center;justify-content:center;font-size:15px;">
                <i class="bi bi-person-plus"></i>
            </div>
            <div>
                <div style="font-size:12px;font-weight:500;color:var(--text);">Not Found</div>
                <div style="font-size:11px;color:var(--text3);">Register new patient</div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function updateCounter(input) {
    const len  = input.value.replace(/\D/g,'').length;
    input.value = input.value.replace(/\D/g,'');
    document.getElementById('nid-counter').textContent = len + '/18';
    const pct  = (len / 18) * 100;
    const prog = document.getElementById('nid-progress');
    prog.style.width = pct + '%';
    prog.style.background = len === 18
        ? 'linear-gradient(90deg,#10b981,#34d399)'
        : 'linear-gradient(90deg,#4f8ef7,#a78bfa)';
}
</script>
@endpush