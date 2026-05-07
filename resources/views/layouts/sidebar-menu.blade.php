@php $role = Auth::user()->role; @endphp

@if($role === 'admin')

    <div class="nav-section">Overview</div>

    <a href="{{ route('admin.dashboard') }}"
       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="1" y="1" width="6" height="6" rx="1.5"/>
            <rect x="9" y="1" width="6" height="6" rx="1.5"/>
            <rect x="1" y="9" width="6" height="6" rx="1.5"/>
            <rect x="9" y="9" width="6" height="6" rx="1.5"/>
        </svg>
        Dashboard
    </a>

    <div class="nav-section">Management</div>

    <a href="{{ route('admin.users.index') }}"
       class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="8" cy="5" r="3"/>
            <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6"/>
        </svg>
        Users
    </a>

    <a href="{{ route('admin.tests.index') }}"
       class="nav-link {{ request()->routeIs('admin.tests*') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M6 1v6L2 13h12L10 7V1"/>
            <line x1="5" y1="1" x2="11" y2="1"/>
        </svg>
        Lab Tests
    </a>

    <div class="nav-section">Reports</div>

    <a href="{{ route('admin.reports') }}"
       class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <polyline points="1,12 5,7 8,9 11,4 15,6"/>
        </svg>
        Analytics
    </a>

    <a href="{{ route('admin.payments') }}"
       class="nav-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="1" y="4" width="14" height="9" rx="2"/>
            <line x1="1" y1="8" x2="15" y2="8"/>
        </svg>
        Payments
        @php $pending_pay = \App\Models\Invoice::where('status','pending')->count(); @endphp
        @if($pending_pay > 0)
            <span class="nav-badge">{{ $pending_pay }}</span>
        @endif
    </a>

@elseif($role === 'receptionist')

    <div class="nav-section">Patients</div>

    <a href="{{ route('reception.search') }}"
       class="nav-link {{ request()->routeIs('reception.search') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="6.5" cy="6.5" r="4.5"/>
            <line x1="10" y1="10" x2="14" y2="14"/>
        </svg>
        Search Patient
    </a>

    <a href="{{ route('reception.invoices') }}"
       class="nav-link {{ request()->routeIs('reception.invoices') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="1" width="12" height="14" rx="2"/>
            <line x1="5" y1="5" x2="11" y2="5"/>
            <line x1="5" y1="8" x2="11" y2="8"/>
            <line x1="5" y1="11" x2="8" y2="11"/>
        </svg>
        Invoices
    </a>

@elseif($role === 'biologist')

    <div class="nav-section">Work Queue</div>

    <a href="{{ route('biologist.queue') }}"
       class="nav-link {{ request()->routeIs('biologist.queue') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="8" cy="8" r="7"/>
            <polyline points="8,4 8,8 11,10"/>
        </svg>
        Pending
        @php $q = \App\Models\Invoice::whereHas('results', fn($r) => $r->where('status','pending'))->orWhereDoesntHave('results')->whereHas('tests')->count(); @endphp
        @if($q > 0)
            <span class="nav-badge">{{ $q }}</span>
        @endif
    </a>

    <a href="{{ route('biologist.done') }}"
       class="nav-link {{ request()->routeIs('biologist.done') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <polyline points="1,8 5,12 15,3"/>
        </svg>
        Completed
    </a>

@elseif($role === 'doctor')

    <div class="nav-section">Validation</div>

    <a href="{{ route('doctor.pending') }}"
       class="nav-link {{ request()->routeIs('doctor.pending') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
        </svg>
        Pending Review
        @php $pend_doc = \App\Models\Result::where('status','submitted')->count(); @endphp
        @if($pend_doc > 0)
            <span class="nav-badge">{{ $pend_doc }}</span>
        @endif
    </a>

    <a href="{{ route('doctor.validated') }}"
       class="nav-link {{ request()->routeIs('doctor.validated') ? 'active' : '' }}">
        <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M8 1a7 7 0 1 1 0 14A7 7 0 0 1 8 1z"/>
            <polyline points="5,8 7,10 11,6"/>
        </svg>
        Validated
    </a>

@endif