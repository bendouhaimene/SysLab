@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="stat-cards">
    <div class="stat-card blue">
        <div class="stat-icon"><i class="bi bi-eyedropper"></i></div>
        <div class="stat-val">{{ $stats['tests_today'] }}</div>
        <div class="stat-lbl">Tests Today</div>
        <div class="stat-delta"><i class="bi bi-arrow-up"></i> Updated live</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        <div class="stat-val">{{ $stats['patients_today'] }}</div>
        <div class="stat-lbl">Patients Today</div>
        <div class="stat-delta"><i class="bi bi-person-plus"></i> New registrations</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-val">{{ number_format($stats['revenue_today']) }}</div>
        <div class="stat-lbl">Revenue Today (DZD)</div>
        <div class="stat-delta" style="color:var(--amber);">
            <i class="bi bi-graph-up"></i> Daily total
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-val">{{ $stats['pending'] }}</div>
        <div class="stat-lbl">Pending Validation</div>
        <div class="stat-delta">
            <i class="bi bi-exclamation-circle"></i> Doctor review needed
        </div>
    </div>
</div>

{{-- ── Charts Row ── --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">

    {{-- Bar Chart --}}
    <div class="section-card">
        <div class="section-head">
            <span class="section-title">
                <i class="bi bi-bar-chart-fill me-2" style="color:var(--blue);"></i>
                Weekly Tests
            </span>
            <span class="section-sub">Last 7 days</span>
        </div>
        <div style="padding:20px;">
            <canvas id="weeklyChart" height="160"></canvas>
        </div>
    </div>

    {{-- Doughnut Chart --}}
    <div class="section-card">
        <div class="section-head">
            <span class="section-title">
                <i class="bi bi-pie-chart-fill me-2" style="color:var(--purple);"></i>
                Tests by Category
            </span>
            <span class="section-sub">All time</span>
        </div>
        <div style="padding:20px;display:flex;align-items:center;gap:24px;">
            <div style="width:160px;flex-shrink:0;">
                <canvas id="categoryChart"></canvas>
            </div>
            <div id="chart-legend" style="display:flex;flex-direction:column;gap:8px;"></div>
        </div>
    </div>
</div>

{{-- ── Recent Activity ── --}}
<div class="section-card" style="margin-bottom:20px;">
    <div class="section-head">
        <span class="section-title">
            <i class="bi bi-activity me-2" style="color:var(--green);"></i>
            Recent Activity
        </span>
        <a href="{{ route('admin.reports') }}" class="btn-sl btn-sm-sl">View All</a>
    </div>
    <table class="sl-table">
        <thead>
            <tr>
                <th>Time</th>
                <th>Action</th>
                <th>Staff</th>
                <th>Patient</th>
                <th>Invoice</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activity as $result)
            <tr>
                <td style="font-family:monospace;font-size:11px;color:var(--text3);">
                    {{ $result->created_at->format('H:i') }}
                </td>
                <td>
                    @if($result->status === 'submitted')
                        <span style="color:var(--text);">Results submitted</span>
                    @elseif($result->status === 'validated')
                        <span style="color:var(--text);">Results validated</span>
                    @elseif($result->status === 'rejected')
                        <span style="color:var(--text);">Results rejected</span>
                    @else
                        <span style="color:var(--text);">Pending</span>
                    @endif
                </td>
                <td>
                    @if($result->status === 'validated' && $result->doctor)
                        <span class="badge-sl badge-purple">
                            Dr. {{ $result->doctor->last_name }}
                        </span>
                    @elseif($result->biologist)
                        <span class="badge-sl badge-green">
                            {{ $result->biologist->last_name }}
                        </span>
                    @endif
                </td>
                <td style="color:var(--text);">
                    {{ $result->invoice->patient->full_name ?? '—' }}
                </td>
                <td>
                    <span style="font-family:monospace;font-size:11px;color:var(--blue);">
                        #{{ $result->invoice->invoice_number ?? '—' }}
                    </span>
                </td>
                <td>
                    @if($result->status === 'validated')
                        <span class="badge-sl badge-green">Validated</span>
                    @elseif($result->status === 'submitted')
                        <span class="badge-sl badge-amber">Awaiting Doctor</span>
                    @elseif($result->status === 'rejected')
                        <span class="badge-sl badge-red">Rejected</span>
                    @else
                        <span class="badge-sl badge-gray">Pending</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:30px;color:var(--text3);">
                    <i class="bi bi-inbox" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                    No activity yet
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ── Staff Status ── --}}
<div class="section-card">
    <div class="section-head">
        <span class="section-title">
            <i class="bi bi-people-fill me-2" style="color:var(--amber);"></i>
            Staff Status
        </span>
        <a href="{{ route('admin.users.index') }}" class="btn-sl btn-sm-sl">
            Manage Users
        </a>
    </div>
    <table class="sl-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Seen</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $member)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        @if($member->profile_photo)
                            <img src="{{ asset('storage/'.$member->profile_photo) }}"
                                 style="width:30px;height:30px;border-radius:50%;object-fit:cover;">
                        @else
                            <div class="av-circle"
                                 style="background:{{ $member->role==='doctor'?'linear-gradient(135deg,#a78bfa,#7c3aed)':($member->role==='biologist'?'linear-gradient(135deg,#10b981,#059669)':($member->role==='receptionist'?'linear-gradient(135deg,#f59e0b,#d97706)':'linear-gradient(135deg,#4f8ef7,#2563eb)')) }}">
                                {{ strtoupper(substr($member->first_name,0,1)) }}{{ strtoupper(substr($member->last_name,0,1)) }}
                            </div>
                        @endif
                        <span style="color:var(--text);font-weight:500;">
                            {{ $member->full_name }}
                        </span>
                    </div>
                </td>
                <td>
                    <span class="badge-sl
                        {{ $member->role==='doctor'?'badge-purple':($member->role==='biologist'?'badge-green':($member->role==='receptionist'?'badge-amber':'badge-blue')) }}">
                        {{ ucfirst($member->role) }}
                    </span>
                </td>
                <td>
                    <div class="status-wrap">
                        <span class="s-dot
                            {{ $member->online_status==='online'?'s-online':($member->online_status==='busy'?'s-busy':'s-offline') }}">
                        </span>
                        <span style="font-size:12px;
                            color:{{ $member->online_status==='online'?'var(--green)':($member->online_status==='busy'?'var(--amber)':'var(--text3)') }}">
                            {{ ucfirst($member->online_status) }}
                        </span>
                    </div>
                </td>
                <td style="font-size:12px;color:var(--text3);">
                    {{ $member->last_seen ? $member->last_seen->diffForHumans() : 'Never' }}
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $member) }}"
                       class="btn-sl btn-sm-sl">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
const isDark = !document.getElementById('app-body').classList.contains('light-mode');
const gridColor  = isDark ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.06)';
const labelColor = isDark ? '#4a5168' : '#8892aa';
const tooltipBg  = isDark ? '#1a2035' : '#ffffff';
const tooltipTxt = isDark ? '#e2e8f0' : '#1a1d3a';

// ── Chart 1: Weekly Bar ──
const weeklyData = @json($weeklyTests);
new Chart(document.getElementById('weeklyChart'), {
    type: 'bar',
    data: {
        labels: weeklyData.map(d => d.label),
        datasets: [{
            data: weeklyData.map(d => d.count),
            backgroundColor: weeklyData.map((_, i) =>
                i === weeklyData.length - 1
                    ? 'rgba(79,142,247,.9)'
                    : 'rgba(79,142,247,.4)'
            ),
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: tooltipBg,
                titleColor: tooltipTxt,
                bodyColor: tooltipTxt,
                borderColor: isDark ? '#2a3050' : '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                callbacks: {
                    label: ctx => ` ${ctx.raw} tests`
                }
            }
        },
        scales: {
            x: {
                grid: { color: gridColor },
                ticks: { color: labelColor, font: { size: 11 } }
            },
            y: {
                grid: { color: gridColor },
                ticks: { color: labelColor, font: { size: 11 } }
            }
        }
    }
});

// ── Chart 2: Category Doughnut ──
const catData  = @json($byCategory);
const catColors = ['#4f8ef7','#10b981','#f59e0b','#a78bfa','#e24b4a','#34d399'];

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: catData.map(d => d.category),
        datasets: [{
            data: catData.map(d => d.total),
            backgroundColor: catColors,
            borderWidth: 0,
            hoverOffset: 4,
        }]
    },
    options: {
        responsive: true,
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: tooltipBg,
                titleColor: tooltipTxt,
                bodyColor: tooltipTxt,
                borderColor: isDark ? '#2a3050' : '#e2e8f0',
                borderWidth: 1,
                padding: 10,
            }
        }
    }
});

// Build custom legend
const legend = document.getElementById('chart-legend');
const total  = catData.reduce((s, d) => s + d.total, 0);
catData.forEach((d, i) => {
    const pct = total > 0 ? Math.round(d.total / total * 100) : 0;
    legend.innerHTML += `
        <div style="display:flex;align-items:center;gap:8px;">
            <div style="width:9px;height:9px;border-radius:50%;
                        background:${catColors[i]};flex-shrink:0;"></div>
            <span style="font-size:12px;color:var(--text2);">
                ${d.category}
            </span>
            <span style="margin-left:auto;font-size:12px;
                         font-weight:600;color:var(--text);">
                ${pct}%
            </span>
        </div>`;
});
</script>
@endpush