@extends('layouts.app')
@section('title', 'Add User')
@section('page-title', 'Add New User')

@section('content')

<div style="max-width:640px;">
    <a href="{{ route('admin.users.index') }}"
       style="display:inline-flex;align-items:center;gap:6px;
              font-size:13px;color:var(--text2);margin-bottom:20px;">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>

    <div class="section-card">
        <div class="section-head">
            <span class="section-title">
                <i class="bi bi-person-plus me-2" style="color:var(--blue);"></i>
                New Staff Account
            </span>
        </div>
        <div style="padding:24px;">
            <form action="{{ route('admin.users.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Avatar preview --}}
                <div style="display:flex;align-items:center;gap:18px;margin-bottom:24px;">
                    <div id="avatar-preview"
                         style="width:72px;height:72px;border-radius:50%;
                                background:linear-gradient(135deg,#4f8ef7,#a78bfa);
                                display:flex;align-items:center;justify-content:center;
                                font-size:26px;color:#fff;font-weight:700;
                                border:3px solid var(--border);overflow:hidden;flex-shrink:0;">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <label style="display:inline-flex;align-items:center;gap:7px;
                                      cursor:pointer;padding:8px 14px;
                                      background:var(--surface2);border:1px solid var(--border);
                                      border-radius:9px;font-size:13px;color:var(--text2);
                                      transition:all .2s;"
                               onmouseover="this.style.color='var(--text)'"
                               onmouseout="this.style.color='var(--text2)'">
                            <i class="bi bi-upload"></i> Upload Photo
                            <input type="file" name="profile_photo" accept="image/*"
                                   style="display:none;" onchange="previewPhoto(this)">
                        </label>
                        <p style="font-size:11px;color:var(--text3);margin-top:6px;">
                            JPG, PNG — max 2MB
                        </p>
                    </div>
                </div>

                {{-- Name row --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            First Name <span style="color:var(--red);">*</span>
                        </label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name') }}"
                               class="form-control-sl"
                               placeholder="e.g. Sara" required>
                        @error('first_name')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            Last Name <span style="color:var(--red);">*</span>
                        </label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name') }}"
                               class="form-control-sl"
                               placeholder="e.g. Khelif" required>
                        @error('last_name')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Role --}}
                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;font-weight:500;color:var(--text2);
                                  display:block;margin-bottom:6px;">
                        Role <span style="color:var(--red);">*</span>
                    </label>
                    <select name="role" class="form-select-sl" required>
                        <option value="">— Select role —</option>
                        <option value="receptionist" {{ old('role')==='receptionist'?'selected':'' }}>
                            Receptionist
                        </option>
                        <option value="biologist" {{ old('role')==='biologist'?'selected':'' }}>
                            Biologist
                        </option>
                        <option value="doctor" {{ old('role')==='doctor'?'selected':'' }}>
                            Doctor
                        </option>
                    </select>
                    @error('role')
                        <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username + Password --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            Username <span style="color:var(--red);">*</span>
                        </label>
                        <input type="text" name="username"
                               value="{{ old('username') }}"
                               class="form-control-sl"
                               placeholder="e.g. sara.k" required>
                        @error('username')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            Password <span style="color:var(--red);">*</span>
                        </label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="pwd"
                                   class="form-control-sl"
                                   placeholder="Min 6 characters"
                                   style="padding-right:40px;" required>
                            <i class="bi bi-eye" id="eye-icon"
                               onclick="togglePwd()"
                               style="position:absolute;right:12px;top:50%;
                                      transform:translateY(-50%);
                                      color:var(--text3);cursor:pointer;font-size:14px;"></i>
                        </div>
                        @error('password')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Buttons --}}
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn-sl btn-primary-sl">
                        <i class="bi bi-check-lg"></i> Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-sl">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = `<img src="${e.target.result}"
                style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function togglePwd() {
    const pwd  = document.getElementById('pwd');
    const icon = document.getElementById('eye-icon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush