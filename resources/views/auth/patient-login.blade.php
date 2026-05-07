<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SysLab — Patient Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            font-family:'Inter',sans-serif;min-height:100vh;
            background:#0a0d14;
            display:flex;align-items:center;justify-content:center;
            position:relative;overflow:hidden;
        }
        body::before{
            content:'';position:absolute;
            width:500px;height:500px;
            background:radial-gradient(circle,rgba(16,185,129,.07),transparent 70%);
            top:-100px;left:-100px;pointer-events:none;
        }
        .login-wrap{
            width:100%;max-width:420px;
            background:#0d1120;border:1px solid #1e2235;
            border-radius:18px;padding:40px 36px;
            position:relative;z-index:1;
        }
        .login-logo{
            font-size:24px;font-weight:700;
            background:linear-gradient(135deg,#10b981,#34d399);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
            background-clip:text;margin-bottom:4px;
        }
        .login-sub{font-size:13px;color:#4a5168;margin-bottom:16px}
        .qr-hint{
            background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);
            color:#34d399;border-radius:9px;padding:10px 14px;
            font-size:12px;margin-bottom:20px;
            display:flex;align-items:center;gap:8px;
        }
        .lbl{font-size:12px;font-weight:500;color:#8892aa;margin-bottom:6px;display:block}
        .inp-wrap{position:relative;margin-bottom:16px}
        .inp-wrap i{
            position:absolute;left:13px;top:50%;transform:translateY(-50%);
            color:#4a5168;font-size:15px;pointer-events:none;
        }
        input{
            width:100%;padding:11px 13px 11px 38px;
            background:#141828;border:1px solid #1e2235;
            color:#e2e8f0;border-radius:10px;
            font-size:13.5px;font-family:'Inter',sans-serif;
            outline:none;transition:border-color .2s,box-shadow .2s;
        }
        input:focus{border-color:#10b981;box-shadow:0 0 0 3px rgba(16,185,129,.15)}
        input::placeholder{color:#4a5168}
        .btn-login{
            width:100%;padding:12px;
            background:linear-gradient(135deg,#10b981,#059669);
            color:#fff;border:none;border-radius:10px;
            font-size:14px;font-weight:600;
            font-family:'Inter',sans-serif;
            cursor:pointer;transition:opacity .2s;margin-top:4px;
        }
        .btn-login:hover{opacity:.9}
        .err{
            background:rgba(226,75,74,.1);border:1px solid rgba(226,75,74,.25);
            color:#f87171;border-radius:9px;padding:10px 14px;
            font-size:13px;margin-bottom:16px;
            display:flex;align-items:center;gap:8px;
        }
        .back-link{
            display:block;text-align:center;
            margin-top:18px;color:#4a5168;
            font-size:12px;text-decoration:none;
        }
        .back-link:hover{color:#8892aa}

        @media (max-width:768px){

    .login-wrap{
        margin:20px;
        padding:24px;
    }

}
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-logo">⚗ SysLab</div>
    <div class="login-sub">Patient Portal — View your results</div>

    <div class="qr-hint">
        <i class="bi bi-qr-code-scan" style="font-size:16px"></i>
        Use credentials from your invoice or scan the QR code
    </div>

    @if($errors->any())
        <div class="err">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('patient.login.post') }}" method="POST">
        @csrf
        <input type="hidden" name="ref" value="{{ request('ref') }}">
        <div>
            <label class="lbl">Username</label>
            <div class="inp-wrap">
                <i class="bi bi-person-fill"></i>
                <input type="text" name="username"
                       value="{{ old('username') }}"
                       placeholder="e.g. patient_2841" required autofocus>
            </div>
        </div>
        <div>
            <label class="lbl">Password</label>
            <div class="inp-wrap">
                <i class="bi bi-lock-fill"></i>
                <input type="password" name="password"
                       placeholder="Your password" required>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="bi bi-eye-fill me-2"></i>View My Results
        </button>
    </form>
    <a href="{{ route('login') }}" class="back-link">
        ← Back to Staff Login
    </a>
</div>
</body>
</html>