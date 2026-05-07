@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'User Management')

@section('content')

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;">
    <div>
        <p style="font-size:13px;color:var(--text2);margin-top:3px;">
            Manage all staff accounts
        </p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-sl btn-primary-sl">
        <i class="bi bi-plus-lg"></i> Add New User
    </a>
</div>

{{-- Stats mini row --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:22px;">
    @foreach([
        ['label'=>'Receptionists','role'=>'receptionist','color'=>'var(--amber)','icon'=>'bi-person-badge'],
        ['label'=>'Biologists',   'role'=>'biologist',   'color'=>'var(--green)', 'icon'=>'bi-eyedropper'],
        ['label'=>'Doctors',      'role'=>'doctor',      'color'=>'var(--purple)','icon'=>'bi-heart-pulse'],
        ['label'=>'Total Staff',  'role'=>'all',         'color'=>'var(--blue)',  'icon'=>'bi-people-fill'],
    ] as $s)
    <div style="background:var(--surface);border:1px solid var(--border);
                border-radius:12px;padding:14px;display:flex;align-items:center;gap:12px;">
        <div style="width:38px;height:38px;border-radius:10px;
                    background:{{ $s['color'] }}20;
                    display:flex;align-items:center;justify-content:center;
                    font-size:17px;color:{{ $s['color'] }};">
            <i class="bi {{ $s['icon'] }}"></i>
        </div>
        <div>
            <div style="font-size:20px;font-weight:700;color:var(--text);">
                {{ $s['role']==='all' ? $users->count() : $users->where('role',$s['role'])->count() }}
            </div>
            <div style="font-size:11px;color:var(--text2);">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Table --}}
<div class="section-card">
    <div class="section-head">
        <span class="section-title">
            <i class="bi bi-people-fill me-2" style="color:var(--blue);"></i>
            All Staff Members
        </span>
        {{-- Search --}}
        <div style="position:relative;">
            <i class="bi bi-search"
               style="position:absolute;left:11px;top:50%;transform:translateY(-50%);
                      color:var(--text3);font-size:13px;"></i>
            <input type="text" id="searchInput" placeholder="Search users..."
                   oninput="filterTable()"
                   style="background:var(--surface2);border:1px solid var(--border);
                          border-radius:9px;padding:7px 12px 7px 32px;
                          font-size:13px;color:var(--text);font-family:'Inter',sans-serif;
                          outline:none;width:200px;">
        </div>
    </div>

    <table class="sl-table" id="usersTable">
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Username</th>
                <th>Status</th>
                <th>Last Seen</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:11px;">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/'.$user->profile_photo) }}"
                                 style="width:34px;height:34px;border-radius:50%;object-fit:cover;
                                        border:2px solid var(--border);">
                        @else
                            <div class="av-circle" style="width:34px;height:34px;font-size:12px;
                                background:{{ $user->role==='doctor'?'linear-gradient(135deg,#a78bfa,#7c3aed)':($user->role==='biologist'?'linear-gradient(135deg,#10b981,#059669)':($user->role==='receptionist'?'linear-gradient(135deg,#f59e0b,#d97706)':'linear-gradient(135deg,#4f8ef7,#2563eb)')) }}">
                                {{ strtoupper(substr($user->first_name,0,1)) }}{{ strtoupper(substr($user->last_name,0,1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight:500;color:var(--text);font-size:13px;">
                                {{ $user->full_name }}
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge-sl
                        {{ $user->role==='doctor'?'badge-purple':($user->role==='biologist'?'badge-green':($user->role==='receptionist'?'badge-amber':'badge-blue')) }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>
                    <code style="background:var(--surface2);padding:3px 8px;
                                 border-radius:6px;font-size:12px;color:var(--blue);">
                        {{ $user->username }}
                    </code>
                </td>
                <td>
                    <div class="status-wrap">
                        <span class="s-dot {{ $user->isOnline() ? 's-online' : 's-offline' }}"></span>
                        <span style="font-size:12px;color:{{ $user->isOnline()?'var(--green)':'var(--text3)' }};">
                            {{ $user->isOnline() ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                </td>
                <td style="font-size:12px;color:var(--text3);">
                    {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Never' }}
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="btn-sl btn-sm-sl">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}"
                              method="POST"
                              onsubmit="return confirm('Delete {{ $user->full_name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-sl btn-sm-sl btn-danger-sl">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:var(--text3);">
                    <i class="bi bi-people" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                    No users found. <a href="{{ route('admin.users.create') }}"
                    style="color:var(--blue);">Add the first one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
@endpush