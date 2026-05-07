<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SysLab — Staff Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0a0d14;
            display: flex;
            overflow: hidden;
        }

        /* ── Left Panel ── */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
            overflow: hidden;
        }

        /* animated background grid */
        .left-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, #0a0d14 0%, #0d1525 50%, #0a1020 100%);
            z-index: 0;
        }

        .grid-lines {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(79,142,247,.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,142,247,.06) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
        }

        /* glowing orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: rgba(79,142,247,.12);
            top: -100px; left: -100px;
        }
        .orb-2 {
            width: 300px; height: 300px;
            background: rgba(167,139,250,.1);
            bottom: -50px; right: -50px;
            animation-delay: -4s;
        }
        .orb-3 {
            width: 200px; height: 200px;
            background: rgba(16,185,129,.08);
            top: 50%; left: 40%;
            animation-delay: -2s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); }
            50% { transform: translateY(-20px) scale(1.05); }
        }

        .left-content { position: relative; z-index: 1; }

        .brand {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 60px;
        }
        .brand-icon {
            width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg, #4f8ef7, #a78bfa);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }
        .brand-text {
            font-size: 22px; font-weight: 700;
            background: linear-gradient(135deg, #4f8ef7, #a78bfa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .brand-sub {
            font-size: 11px; color: #3a4060;
            text-transform: uppercase; letter-spacing: 1px;
        }

        .hero-title {
            font-size: 42px; font-weight: 700; line-height: 1.2;
            color: #e2e8f0; margin-bottom: 16px;
        }
        .hero-title span {
            background: linear-gradient(135deg, #4f8ef7, #a78bfa);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .hero-sub {
            font-size: 15px; color: #4a5168; line-height: 1.7;
            max-width: 380px; margin-bottom: 40px;
        }

        /* stats row */
        .stats-row {
            display: flex; gap: 20px; margin-bottom: 50px;
        }
        .stat-pill {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 12px; padding: 14px 20px;
            flex: 1;
        }
        .stat-pill .num {
            font-size: 22px; font-weight: 700; color: #e2e8f0;
        }
        .stat-pill .lbl {
            font-size: 11px; color: #4a5168; margin-top: 2px;
        }
        .stat-pill.blue .num { color: #4f8ef7; }
        .stat-pill.green .num { color: #10b981; }
        .stat-pill.purple .num { color: #a78bfa; }

        /* floating cards */
        .float-cards {
            display: flex; gap: 12px; flex-wrap: wrap;
        }
        .float-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.07);
            border-radius: 10px; padding: 10px 14px;
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: #6b7494;
            animation: fadeUp .6s ease both;
        }
        .float-card i { font-size: 14px; }
        .float-card.a { color:#4f8ef7; border-color:rgba(79,142,247,.2); animation-delay:.1s; }
        .float-card.b { color:#10b981; border-color:rgba(16,185,129,.2); animation-delay:.2s; }
        .float-card.c { color:#a78bfa; border-color:rgba(167,139,250,.2); animation-delay:.3s; }
        .float-card.d { color:#f59e0b; border-color:rgba(245,158,11,.2); animation-delay:.4s; }
        .float-card.e { color:#e24b4a; border-color:rgba(226,75,74,.2); animation-delay:.5s; }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ── Right Panel (Login Box) ── */
        .right-panel {
            width: 440px;
            background: #0d1120;
            border-left: 1px solid #1e2235;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            flex-shrink: 0;
        }

        .login-box { width: 100%; }

        .login-header { margin-bottom: 32px; }
        .login-header h2 {
            font-size: 24px; font-weight: 700; color: #e2e8f0;
            margin-bottom: 6px;
        }
        .login-header p { font-size: 13px; color: #4a5168; }

        .form-label-custom {
            display: block; font-size: 12px; font-weight: 500;
            color: #6b7494; margin-bottom: 7px; letter-spacing: .3px;
        }

        .input-wrap {
            position: relative; margin-bottom: 18px;
        }
        .input-wrap i {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #3a4060; font-size: 14px; z-index: 1;
            transition: color .2s;
        }
        .input-custom {
            width: 100%;
            background: #141828;
            border: 1px solid #1e2235;
            border-radius: 10px;
            padding: 12px 14px 12px 40px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            color: #e2e8f0;
            outline: none;
            transition: all .2s;
        }
        .input-custom::placeholder { color: #2a3050; }
        .input-custom:focus {
            border-color: #4f8ef7;
            background: #0f1520;
            box-shadow: 0 0 0 3px rgba(79,142,247,.12);
        }
        .input-custom:focus + i,
        .input-wrap:focus-within i { color: #4f8ef7; }

        /* fix icon z-order */
        .input-wrap i { pointer-events: none; }

        .remember-row {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 24px;
        }
        .remember-row label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: #6b7494; cursor: pointer;
        }
        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px; accent-color: #4f8ef7;
        }

        .btn-login {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #4f8ef7, #6a6af4);
            border: none; border-radius: 10px;
            font-size: 14px; font-weight: 600; font-family: 'Inter', sans-serif;
            color: #fff; cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(79,142,247,.3);
        }
        .btn-login:active { transform: translateY(0); }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 22px 0; color: #2a3050; font-size: 12px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #1e2235;
        }

        .btn-patient {
            width: 100%; padding: 12px;
            background: transparent;
            border: 1px solid #1e2235;
            border-radius: 10px;
            font-size: 13px; font-weight: 500; font-family: 'Inter', sans-serif;
            color: #6b7494; cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            text-decoration: none;
        }
        .btn-patient:hover {
            background: #141828; color: #c5cce8;
            border-color: #2a3050;
        }

        .error-box {
            background: rgba(226,75,74,.1);
            border: 1px solid rgba(226,75,74,.25);
            border-radius: 10px; padding: 10px 14px;
            font-size: 13px; color: #f87171;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 18px;
        }

        /* role badges at bottom */
        .roles-row {
            display: flex; gap: 8px; margin-top: 28px; flex-wrap: wrap;
        }
        .role-badge {
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 500;
        }

        /* Light mode */
        body.light {
            background: #f0f4ff;
        }
        body.light .left-panel::before {
            background: linear-gradient(135deg, #e8f0ff 0%, #f0f4ff 50%, #e4eeff 100%);
        }
        body.light .grid-lines {
            background-image:
                linear-gradient(rgba(79,142,247,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,142,247,.08) 1px, transparent 1px);
        }
        body.light .hero-title { color: #1a1d3a; }
        body.light .right-panel { background: #ffffff; border-color: #e2e8f0; }
        body.light .input-custom { background: #f8faff; border-color: #e2e8f0; color: #1a1d3a; }
        body.light .input-custom::placeholder { color: #b0b8d0; }
        body.light .input-custom:focus { background: #fff; border-color: #4f8ef7; }
        body.light .login-header h2 { color: #1a1d3a; }
        body.light .stat-pill { background: rgba(0,0,0,.03); border-color: rgba(0,0,0,.08); }
        body.light .stat-pill .lbl { color: #8892aa; }
        body.light .float-card { background: rgba(0,0,0,.03); border-color: rgba(0,0,0,.08); }
        body.light .orb-1 { background: rgba(79,142,247,.15); }
        body.light .orb-2 { background: rgba(167,139,250,.12); }
        body.light .divider { color: #b0b8d0; }
        body.light .divider::before,
        body.light .divider::after { background: #e2e8f0; }
        body.light .btn-patient { border-color: #e2e8f0; color: #8892aa; }
        body.light .btn-patient:hover { background: #f8faff; color: #1a1d3a; }

        /* theme toggle */
        .theme-btn {
            position: absolute; top: 20px; right: 20px;
            width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            color: #6b7494; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; transition: all .2s;
        }
        .theme-btn:hover { background: rgba(255,255,255,.1); color: #c5cce8; }
        body.light .theme-btn { background: #f0f4ff; border-color: #e2e8f0; color: #6b7494; }
        @media (max-width:768px){

    body{
        overflow-y:auto;
    }

    .left-panel{
        display:none;
    }

    .right-panel{
        width:100%;
        min-height:100vh;
        padding:20px;
        border-left:none;
    }

    .login-box{
        max-width:100%;
    }

    .stats-row{
        flex-direction:column;
    }

    .float-cards{
        flex-direction:column;
    }

    .hero-title{
        font-size:28px;
    }
}
    </style>
</head>
<body id="body">

<!-- Left decorative panel -->
<div class="left-panel">
    <div class="grid-lines"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="left-content">
        <div class="brand">
            <div class="brand-icon">⚗</div>
            <div>
                <div class="brand-text">SysLab</div>
                <div class="brand-sub">Medical Laboratory</div>
            </div>
        </div>

        <div class="hero-title">
            Smart Lab<br>Management<br><span>Made Simple</span>
        </div>
        <p class="hero-sub">
            A complete solution for managing patients, test results,
            invoices and staff — all in one secure platform.
        </p>

        <div class="stats-row">
            <div class="stat-pill blue">
                <div class="num">2,400+</div>
                <div class="lbl">Tests Processed</div>
            </div>
            <div class="stat-pill green">
                <div class="num">98%</div>
                <div class="lbl">Result Accuracy</div>
            </div>
            <div class="stat-pill purple">
                <div class="num">5</div>
                <div class="lbl">User Roles</div>
            </div>
        </div>

        <div class="float-cards">
            <div class="float-card a">
                <i class="bi bi-shield-check"></i> Secure Access
            </div>
            <div class="float-card b">
                <i class="bi bi-qr-code"></i> QR Results
            </div>
            <div class="float-card c">
                <i class="bi bi-file-earmark-pdf"></i> PDF Reports
            </div>
            <div class="float-card d">
                <i class="bi bi-bar-chart-fill"></i> Analytics
            </div>
            <div class="float-card e">
                <i class="bi bi-archive"></i> Full Archive
            </div>
        </div>
    </div>

    <div class="left-content" style="position:relative;z-index:1;">
        <p style="font-size:11px; color:#2a3050;">
            © 2026 SysLab · Medical Laboratory Management System
        </p>
    </div>
</div>

<!-- Right Login Panel -->
<div class="right-panel">
    <button class="theme-btn" onclick="toggleTheme()" id="themeBtn">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
    </button>

    <div class="login-box">
        <div class="login-header">
            <h2>Welcome back </h2>
            <p>Sign in to your staff account to continue</p>
        </div>

        @if($errors->any())
        <div class="error-box">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div>
                <label class="form-label-custom">Username</label>
                <div class="input-wrap">
                    <i class="bi bi-person-fill"></i>
                    <input type="text" name="username"
                           value="{{ old('username') }}"
                           class="input-custom"
                           placeholder="Enter your username"
                           required autofocus>
                </div>
            </div>

            <div>
                <label class="form-label-custom">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock-fill"></i>
                    <input type="password" name="password"
                           class="input-custom"
                           placeholder="Enter your password"
                           required>
                </div>
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In to SysLab
            </button>
        </form>

        <div class="divider">or</div>

        <a href="{{ route('patient.login') }}" class="btn-patient">
            <i class="bi bi-person-badge"></i>
            Patient Portal — View My Results
        </a>

        <div class="roles-row">
            <span class="role-badge" style="background:rgba(79,142,247,.12);color:#4f8ef7;">
                Admin
            </span>
            <span class="role-badge" style="background:rgba(245,158,11,.12);color:#f59e0b;">
                Receptionist
            </span>
            <span class="role-badge" style="background:rgba(16,185,129,.12);color:#10b981;">
                Biologist
            </span>
            <span class="role-badge" style="background:rgba(167,139,250,.12);color:#a78bfa;">
                Doctor
            </span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const body      = document.getElementById('body');
    const themeIcon = document.getElementById('themeIcon');
    const saved     = localStorage.getItem('syslab_theme') || 'dark';

    if (saved === 'light') {
        body.classList.add('light');
        themeIcon.className = 'bi bi-sun-fill';
    }

    function toggleTheme() {
        const isLight = body.classList.toggle('light');
        localStorage.setItem('syslab_theme', isLight ? 'light' : 'dark');
        themeIcon.className = isLight ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    }
</script>
</body>
</html>