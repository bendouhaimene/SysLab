<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SysLab — @yield('title','Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ════════════════ TOKENS ════════════════ */
        :root {
            --bg:        #0a0d14;
            --surface:   #0d1120;
            --surface2:  #141828;
            --surface3:  #1a2035;
            --border:    #1e2235;
            --border2:   #2a3050;
            --text:      #e2e8f0;
            --text2:     #8892aa;
            --text3:     #4a5168;
            --text4:     #3a4060;
            --blue:      #4f8ef7;
            --blue2:     #7eb3ff;
            --green:     #10b981;
            --amber:     #f59e0b;
            --red:       #e24b4a;
            --purple:    #a78bfa;
            --sidebar-w: 230px;
        }
        .light-mode {
            --bg:       #f0f4ff;
            --surface:  #ffffff;
            --surface2: #f5f7ff;
            --surface3: #eef1fa;
            --border:   #e2e8f0;
            --border2:  #c8d0e8;
            --text:     #1a1d3a;
            --text2:    #5a6280;
            --text3:    #8892aa;
            --text4:    #b0b8d0;
        }

        /* ════════════════ BASE ════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: background .3s, color .3s;
        }
        a { text-decoration: none; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--border2); border-radius: 3px; }

        /* ════════════════ LAYOUT ════════════════ */
        .layout { display: flex; min-height: 100vh; }

        /* ════════════════ SIDEBAR ════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column;
            z-index: 200;
            transition: background .3s, border-color .3s;
        }
        .sidebar-logo {
            padding: 20px 16px 16px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #4f8ef7, #a78bfa);
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; flex-shrink: 0;
        }
        .logo-name {
            font-size: 17px; font-weight: 700;
            background: linear-gradient(135deg, #4f8ef7, #a78bfa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .logo-tagline { font-size: 10px; color: var(--text3); letter-spacing: .5px; }

        .sidebar-nav { padding: 10px 8px; flex: 1; overflow-y: auto; }
        .nav-section {
    font-size: 9px;
    font-weight: 600;
    color: var(--text4);
    letter-spacing: 1px;
    text-transform: uppercase;
    padding: 10px 10px 4px;
    margin-top: 4px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 11px;
    border-radius: 9px;
    color: var(--text2);
    font-size: 13px;
    transition: all .2s;
    margin-bottom: 2px;
    border: 1px solid transparent;
    text-decoration: none;
    white-space: nowrap;
    overflow: hidden;
}
        .nav-link:hover {
            background: var(--surface2);
            color: var(--text);
        }
        .nav-link.active {
            background: rgba(79,142,247,.1);
            color: var(--blue);
            border-color: rgba(79,142,247,.2);
            font-weight: 500;
        }
        .nav-link svg { width: 15px; height: 15px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto;
            background: var(--red); color: #fff;
            font-size: 10px; font-weight: 600;
            padding: 1px 6px; border-radius: 10px;
        }

        /* ── User box at bottom ── */
        .sidebar-user {
            padding: 12px 14px;
            border-top: 1px solid var(--border);
        }
        .user-info {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            object-fit: cover; flex-shrink: 0;
        }
        .user-avatar-initials {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #4f8ef7, #a78bfa);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .user-role { font-size: 11px; color: var(--text3); }
        .btn-logout {
            width: 100%; padding: 7px;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 8px; color: var(--text2);
            font-size: 12px; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .btn-logout:hover { background: rgba(226,75,74,.1); color: var(--red); border-color: rgba(226,75,74,.2); }

        /* ════════════════ MAIN ════════════════ */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1; display: flex; flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ── */
        .topbar {
            height: 56px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            transition: background .3s, border-color .3s;
        }
        .topbar-left h1 {
            font-size: 16px; font-weight: 600; color: var(--text);
        }
        .topbar-left p { font-size: 11px; color: var(--text3); }
        .topbar-right { display: flex; align-items: center; gap: 8px; }

        .icon-btn {
            width: 34px; height: 34px; border-radius: 9px;
            background: var(--surface2); border: 1px solid var(--border);
            color: var(--text2); display: flex; align-items: center;
            justify-content: center; cursor: pointer; font-size: 14px;
            transition: all .2s; position: relative;
        }
        .icon-btn:hover { background: var(--surface3); color: var(--text); }
        .icon-btn .notif-dot {
            position: absolute; top: 5px; right: 5px;
            width: 7px; height: 7px; background: var(--red);
            border-radius: 50%; border: 1px solid var(--surface);
        }

        /* ── Content ── */
        .page-content { padding: 24px; flex: 1; }

        /* ════════════════ COMPONENTS ════════════════ */

        /* Stat cards */
        .stat-cards { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 22px; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px; padding: 16px;
            position: relative; overflow: hidden;
            transition: transform .2s, border-color .2s;
        }
        .stat-card:hover { transform: translateY(-2px); border-color: var(--border2); }
        .stat-card::after {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 2px;
        }
        .stat-card.blue::after  { background: linear-gradient(90deg,#4f8ef7,#7eb3ff); }
        .stat-card.green::after { background: linear-gradient(90deg,#10b981,#34d399); }
        .stat-card.amber::after { background: linear-gradient(90deg,#f59e0b,#fbbf24); }
        .stat-card.red::after   { background: linear-gradient(90deg,#e24b4a,#f87171); }
        .stat-card.purple::after{ background: linear-gradient(90deg,#a78bfa,#c4b5fd); }

        .stat-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; margin-bottom: 12px;
        }
        .blue  .stat-icon { background:rgba(79,142,247,.1);  color:var(--blue); }
        .green .stat-icon { background:rgba(16,185,129,.1);  color:var(--green); }
        .amber .stat-icon { background:rgba(245,158,11,.1);  color:var(--amber); }
        .red   .stat-icon { background:rgba(226,75,74,.1);   color:var(--red); }
        .purple.stat-icon { background:rgba(167,139,250,.1); color:var(--purple); }

        .stat-val { font-size: 24px; font-weight: 700; color: var(--text); margin-bottom: 2px; }
        .stat-lbl { font-size: 12px; color: var(--text2); }
        .stat-delta { font-size: 11px; color: var(--green); margin-top: 5px; }
        .stat-card.red .stat-delta { color: var(--red); }

        /* Section cards */
        .section-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px; overflow: hidden;
            margin-bottom: 20px;
            transition: border-color .3s;
        }
        .section-head {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .section-title { font-size: 14px; font-weight: 600; color: var(--text); }
        .section-sub { font-size: 11px; color: var(--text3); }

        /* Tables */
        .sl-table { width: 100%; border-collapse: collapse; }
        .sl-table th {
            padding: 10px 18px; text-align: left;
            font-size: 10px; font-weight: 600;
            color: var(--text3); text-transform: uppercase;
            letter-spacing: .6px;
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
        }
        .sl-table td {
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            font-size: 13px; color: var(--text2);
            vertical-align: middle;
        }
        .sl-table tbody tr:last-child td { border-bottom: none; }
        .sl-table tbody tr { transition: background .15s; }
        .sl-table tbody tr:hover td { background: var(--surface2); color: var(--text); }

        /* Badges */
        .badge-sl {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 9px; border-radius: 20px;
            font-size: 11px; font-weight: 500;
        }
        .badge-blue   { background:rgba(79,142,247,.1);  color:#4f8ef7; }
        .badge-green  { background:rgba(16,185,129,.1);  color:#10b981; }
        .badge-amber  { background:rgba(245,158,11,.1);  color:#f59e0b; }
        .badge-red    { background:rgba(226,75,74,.1);   color:#e24b4a; }
        .badge-purple { background:rgba(167,139,250,.1); color:#a78bfa; }
        .badge-gray   { background:var(--surface2);      color:var(--text2); }

        /* Status dot */
        .status-wrap { display:flex; align-items:center; gap:6px; }
        .s-dot { width:7px; height:7px; border-radius:50%; flex-shrink:0; }
        .s-online { background:#10b981; }
        .s-offline { background:var(--text4); }
        .s-busy { background:#f59e0b; animation: blink 1.5s infinite; }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* Buttons */
        .btn-sl {
            padding: 7px 14px; border-radius: 9px;
            font-size: 13px; font-weight: 500; font-family: 'Inter', sans-serif;
            cursor: pointer; transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px;
            border: 1px solid var(--border);
            background: var(--surface2); color: var(--text2);
        }
        .btn-sl:hover { background: var(--surface3); color: var(--text); }
        .btn-primary-sl {
            background: linear-gradient(135deg, #4f8ef7, #6a6af4);
            color: #fff; border-color: transparent;
        }
        .btn-primary-sl:hover { box-shadow: 0 4px 16px rgba(79,142,247,.3); transform: translateY(-1px); color:#fff; }
        .btn-danger-sl { color: var(--red); border-color: rgba(226,75,74,.2); }
        .btn-danger-sl:hover { background: rgba(226,75,74,.1); color: var(--red); }
        .btn-sm-sl { padding: 5px 10px; font-size: 12px; }

        /* Forms */
        .form-control-sl, .form-select-sl {
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 9px; padding: 9px 13px;
            font-size: 13px; font-family: 'Inter', sans-serif;
            color: var(--text); outline: none; width: 100%;
            transition: all .2s;
        }
        .form-control-sl::placeholder { color: var(--text4); }
        .form-control-sl:focus, .form-select-sl:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(79,142,247,.12);
            background: var(--surface);
        }

        /* Avatar */
        .av-circle {
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
        }

        /* Alert */
        .alert-sl {
            padding: 11px 16px; border-radius: 10px;
            font-size: 13px; display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px;
        }
        .alert-success { background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2); color:#10b981; }
        .alert-error   { background:rgba(226,75,74,.1);  border:1px solid rgba(226,75,74,.2);  color:#e24b4a; }

        /* Result labels */
        .res-high   { color:#e24b4a; font-weight:600; }
        .res-low    { color:#4f8ef7; font-weight:600; }
        .res-normal { color:#10b981; font-weight:600; }

        /* ═════════ MOBILE RESPONSIVE ═════════ */
@media (max-width: 768px){

    /* MAIN LAYOUT */
    .layout{
        flex-direction: column;
    }

    /* SIDEBAR */
    .sidebar{
        width: 100%;
        height: auto;
        position: relative;
        border-right: none;
        border-bottom: 1px solid var(--border);
    }

    .main-wrap{
        margin-left: 0;
        width: 100%;
    }

    /* TOPBAR */
    .topbar{
        padding: 12px 16px;
    }

    .topbar-left h1{
        font-size: 14px;
    }

    /* PAGE */
    .page-content{
        padding: 16px;
    }

    /* STAT CARDS */
    .stat-cards{
        grid-template-columns: 1fr;
    }

    /* TABLE */
    .sl-table{
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }

    /* LOGIN PAGE */
    body{
        overflow-y: auto;
    }

    .left-panel{
        display: none;
    }

    .right-panel{
        width: 100%;
        min-height: 100vh;
        padding: 20px;
        border-left: none;
    }

    .login-box{
        max-width: 100%;
    }

    .hero-title{
        font-size: 28px;
    }

    .stats-row{
        flex-direction: column;
    }

    .float-cards{
        flex-direction: column;
    }

    .login-wrap{
        margin: 20px;
        padding: 24px;
    }

}
    </style>
    @stack('styles')
</head>
<body id="app-body">

<div class="layout">

    {{-- ══ SIDEBAR ══ --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">⚗</div>
            <div>
                <div class="logo-name">SysLab</div>
                <div class="logo-tagline">Medical Laboratory</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @include('layouts.sidebar-menu')
        </nav>

        <div class="sidebar-user">
            <div class="user-info">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/'.Auth::user()->profile_photo) }}"
                         class="user-avatar" alt="">
                @else
                    <div class="user-avatar-initials">
                        {{ strtoupper(substr(Auth::user()->first_name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name,0,1)) }}
                    </div>
                @endif
                <div style="min-width:0;flex:1;">
                    <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ Auth::user()->full_name }}
                    </div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="main-wrap">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="topbar-left">
                <h1>@yield('page-title','Dashboard')</h1>
                <p>{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="topbar-right">
                {{-- Notification --}}
                @php $pending = \App\Models\Result::where('status','submitted')->count(); @endphp
                <div class="icon-btn">
                    <i class="bi bi-bell-fill"></i>
                    @if($pending > 0)<span class="notif-dot"></span>@endif
                </div>

                {{-- Dark / Light toggle --}}
                <div class="icon-btn" onclick="toggleTheme()" id="theme-btn">
                    <i class="bi bi-moon-fill" id="theme-icon"></i>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="page-content">

            @if(session('success'))
            <div class="alert-sl alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert-sl alert-error">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const appBody  = document.getElementById('app-body');
    const themeIcon = document.getElementById('theme-icon');
    const saved    = localStorage.getItem('syslab_theme') || 'dark';

    if (saved === 'light') {
        appBody.classList.add('light-mode');
        if (themeIcon) themeIcon.className = 'bi bi-sun-fill';
    }

    function toggleTheme() {
        const isLight = appBody.classList.toggle('light-mode');
        localStorage.setItem('syslab_theme', isLight ? 'light' : 'dark');
        if (themeIcon) themeIcon.className = isLight ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
</script>
@stack('scripts')
</body>
</html>