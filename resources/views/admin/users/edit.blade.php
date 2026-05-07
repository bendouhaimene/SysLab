@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

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
                <i class="bi bi-pencil me-2" style="color:var(--amber);"></i>
                Edit — {{ $user->full_name }}
            </span>
            <span class="badge-sl
                {{ $user->role==='doctor'?'badge-purple':($user->role==='biologist'?'badge-green':'badge-amber') }}">
                {{ ucfirst($user->role) }}
            </span>
        </div>
        <div style="padding:24px;">
            <form action="{{ route('admin.users.update', $user) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Avatar --}}
                <div style="display:flex;align-items:center;gap:18px;margin-bottom:24px;">
                    <div id="avatar-preview"
                         style="width:72px;height:72px;border-radius:50%;overflow:hidden;
                                border:3px solid var(--border);flex-shrink:0;">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;
                                        background:linear-gradient(135deg,#4f8ef7,#a78bfa);
                                        display:flex;align-items:center;justify-content:center;
                                        font-size:24px;color:#fff;font-weight:700;">
                                {{ strtoupper(substr($user->first_name,0,1)) }}{{ strtoupper(substr($user->last_name,0,1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <label style="display:inline-flex;align-items:center;gap:7px;
                                      cursor:pointer;padding:8px 14px;
                                      background:var(--surface2);border:1px solid var(--border);
                                      border-radius:9px;font-size:13px;color:var(--text2);">
                            <i class="bi bi-upload"></i> Change Photo
                            <input type="file" name="profile_photo" accept="image/*"
                                   style="display:none;" onchange="previewPhoto(this)">
                        </label>
                        <p style="font-size:11px;color:var(--text3);margin-top:6px;">
                            Leave empty to keep current photo
                        </p>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">First Name</label>
                        <input type="text" name="first_name"
                               value="{{ old('first_name', $user->first_name) }}"
                               class="form-control-sl" required>
                        @error('first_name')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">Last Name</label>
                        <input type="text" name="last_name"
                               value="{{ old('last_name', $user->last_name) }}"
                               class="form-control-sl" required>
                        @error('last_name')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="font-size:12px;font-weight:500;color:var(--text2);
                                  display:block;margin-bottom:6px;">Role</label>
                    <select name="role" class="form-select-sl" required>
                        <option value="receptionist" {{ $user->role==='receptionist'?'selected':'' }}>Receptionist</option>
                        <option value="biologist"    {{ $user->role==='biologist'   ?'selected':'' }}>Biologist</option>
                        <option value="doctor"       {{ $user->role==='doctor'      ?'selected':'' }}>Doctor</option>
                    </select>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">Username</label>
                        <input type="text" name="username"
                               value="{{ old('username', $user->username) }}"
                               class="form-control-sl" required>
                        @error('username')
                            <p style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label style="font-size:12px;font-weight:500;color:var(--text2);
                                      display:block;margin-bottom:6px;">
                            New Password
                        </label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="pwd"
                                   class="form-control-sl"
                                   placeholder="Leave empty to keep current"
                                   style="padding-right:40px;">
                            <i class="bi bi-eye" id="eye-icon"
                               onclick="togglePwd()"
                               style="position:absolute;right:12px;top:50%;
                                      transform:translateY(-50%);
                                      color:var(--text3);cursor:pointer;font-size:14px;"></i>
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn-sl btn-primary-sl">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn-sl">Cancel</a>
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
            document.getElementById('avatar-preview').innerHTML =
                `<img src="${e.target.result}"
                      style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function togglePwd() {
    const pwd  = document.getElementById('pwd');
    const icon = document.getElementById('eye-icon');
    pwd.type   = pwd.type === 'password' ? 'text' : 'password';
    icon.className = pwd.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
@endpush