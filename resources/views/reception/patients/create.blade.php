@extends('layouts.app')
@section('title','New Patient')
@section('page-title','Register New Patient')

@section('content')

<div style="max-width:640px;">
    <a href="{{ route('reception.search') }}"
       style="display:inline-flex;align-items:center;gap:6px;
              font-size:13px;color:var(--text2);margin-bottom:20px;">
        <i class="bi bi-arrow-left"></i> Back to Search
    </a>

    {{-- Info banner --}}
    <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);
                border-radius:12px;padding:14px 18px;margin-bottom:20px;
                display:flex;align-items:center;gap:10px;">
        <i class="bi bi-person-plus-fill" style="color:var(--amber);font-size:18px;"></i>
        <div>
            <div style="font-size:13px;font-weight:500;color:var(--text);">
                Patient not found
            </div>
            <div style="font-size:12px;color:var(--text2);">
                Fill in the details below to register a new patient account.
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-head">
            <span class="section-title">
                <i class="bi bi-person-plus me-2" style="color:var(--amber);"></i>
                Patient Information
            </span>
        </div>
        <div style="padding:24px;">
            <form action="{{ route('reception.patients.store') }}" method="POST">
                @csrf

                {{-- National ID (pre-filled, readonly) --}}
                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;font-weight:500;color:var(--text2);
                                  display:block;margin-bottom:6px;">
                        National ID
                        <span style="color:var(--text3);font-weight:400;">(18 digits)</span>
                    </label>
                    <input type="text" name="national_id"
                           value="{{ $nationalId }}"
                           class="form-control-sl"
                           style="font-family:monospace;letter-spacing:2px;
                                  background:rgba(79,142,247,.05);
                                  border-color:rgba(79,142,247,.3);color:var(--blue);"
                           maxlength="18" required readonly>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            First Name <span style="color:var(--red)">*</span>
                        </label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control-sl"
                               placeholder="e.g. Mohammed" required>
                        @error('first_name')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            Last Name <span style="color:var(--red)">*</span>
                        </label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control-sl"
                               placeholder="e.g. Benali" required>
                        @error('last_name')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            Date of Birth
                        </label>
                        <input type="date" name="date_of_birth"
                               value="{{ old('date_of_birth') }}"
                               class="form-control-sl">
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">Gender</label>
                        <select name="gender" class="form-select-sl">
                            <option value="">— Select —</option>
                            <option value="male"   {{ old('gender')==='male'  ?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')==='female'?'selected':'' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="font-size:12px;font-weight:500;color:var(--text2);
                                  display:block;margin-bottom:6px;">Phone</label>
                    <input type="text" name="phone"
                           value="{{ old('phone') }}"
                           class="form-control-sl"
                           placeholder="e.g. 0555 123 456">
                </div>

                {{-- Credentials info --}}
                <div style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.15);
                            border-radius:10px;padding:12px 16px;margin-bottom:20px;">
                    <div style="font-size:12px;font-weight:500;color:var(--green);margin-bottom:4px;">
                        <i class="bi bi-key-fill me-1"></i> Auto-generated Credentials
                    </div>
                    <div style="font-size:12px;color:var(--text2);">
                        A username and password will be automatically generated
                        and printed on the invoice for the patient to access results online.
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn-sl btn-primary-sl">
                        <i class="bi bi-person-check"></i> Register & Continue
                    </button>
                    <a href="{{ route('reception.search') }}" class="btn-sl">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection