<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:   #1a3c5e;
            --accent:    #2e86de;
            --accent2:   #54a0ff;
            --bg:        #0d1b2a;
            --card:      #112035;
            --border:    rgba(46,134,222,0.18);
            --text:      #e8edf3;
            --muted:     #7a93aa;
            --error:     #ff6b6b;
            --success:   #1dd1a1;
        }

        html, body {
            height: 100%;
            font-family: 'Sora', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow: hidden;
        }

        /* ── Animated background ── */
        .bg-wrap {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 70% 20%, rgba(46,134,222,0.13) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 10% 80%, rgba(84,160,255,0.09) 0%, transparent 55%),
                var(--bg);
        }
        .grid-lines {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(46,134,222,0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(46,134,222,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: gridMove 20s linear infinite;
        }
        @keyframes gridMove {
            0%   { background-position: 0 0; }
            100% { background-position: 40px 40px; }
        }

        /* Floating orbs */
        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(60px); animation: float linear infinite;
            z-index: 0; pointer-events: none;
        }
        .orb-1 { width:300px;height:300px; background:rgba(46,134,222,0.08); top:-80px; right:10%; animation-duration:18s; }
        .orb-2 { width:200px;height:200px; background:rgba(84,160,255,0.07); bottom:5%; left:5%; animation-duration:14s; animation-delay:-6s; }
        .orb-3 { width:150px;height:150px; background:rgba(29,209,161,0.06); top:40%; left:20%; animation-duration:22s; animation-delay:-10s; }
        @keyframes float {
            0%,100% { transform: translateY(0) scale(1); }
            33%      { transform: translateY(-30px) scale(1.05); }
            66%      { transform: translateY(20px) scale(0.97); }
        }

        /* ── Layout ── */
        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 460px 1fr;
            grid-template-rows: 1fr auto 1fr;
            align-items: center;
        }

        /* ── Brand side text ── */
        .brand-side {
            grid-column: 1;
            grid-row: 2;
            padding: 0 20px 0 60px;
            animation: slideIn 0.8s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes slideIn {
            from { opacity:0; transform: translateX(-30px); }
            to   { opacity:1; transform: translateX(0); }
        }
        .brand-tag {
            display: inline-block;
            font-size: 11px; letter-spacing: 3px; text-transform: uppercase;
            color: var(--accent2); font-weight: 600;
            border: 1px solid rgba(84,160,255,0.3);
            padding: 5px 14px; border-radius: 20px;
            margin-bottom: 24px;
            background: rgba(84,160,255,0.05);
        }
        .brand-title {
            font-size: clamp(28px, 3vw, 44px);
            font-weight: 700; line-height: 1.15;
            color: var(--text);
            margin-bottom: 16px;
        }
        .brand-title span { color: var(--accent2); }
        .brand-desc {
            font-size: 14px; color: var(--muted);
            line-height: 1.7; max-width: 320px;
        }
        .brand-stats {
            display: flex; gap: 32px; margin-top: 36px;
        }
        .stat { display: flex; flex-direction: column; gap: 4px; }
        .stat-num {
            font-size: 22px; font-weight: 700; color: var(--accent2);
        }
        .stat-label { font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; }

        /* ── Card ── */
        .card {
            grid-column: 2; grid-row: 2;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 44px 40px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.03);
            animation: riseUp 0.7s cubic-bezier(.22,1,.36,1) 0.1s both;
        }
        @keyframes riseUp {
            from { opacity:0; transform: translateY(24px); }
            to   { opacity:1; transform: translateY(0); }
        }

        .card-header {
            text-align: center; margin-bottom: 36px;
        }
        .logo-ring {
            width: 60px; height: 60px; border-radius: 16px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            box-shadow: 0 8px 24px rgba(46,134,222,0.35);
        }
        .card-title {
            font-size: 22px; font-weight: 700; color: var(--text);
            margin-bottom: 6px;
        }
        .card-sub {
            font-size: 13px; color: var(--muted);
        }

        /* ── Form ── */
        .form-group { margin-bottom: 20px; }
        label {
            display: block; font-size: 12px; font-weight: 600;
            color: var(--muted); text-transform: uppercase;
            letter-spacing: 1px; margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-wrap svg {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 16px; height: 16px; color: var(--muted); pointer-events: none;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 14px 13px 42px;
            font-family: 'Sora', sans-serif;
            font-size: 14px; color: var(--text);
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }
        input:focus {
            border-color: var(--accent);
            background: rgba(46,134,222,0.06);
            box-shadow: 0 0 0 3px rgba(46,134,222,0.12);
        }
        input::placeholder { color: rgba(122,147,170,0.5); }

        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            cursor: pointer; color: var(--muted);
            background: none; border: none; padding: 0;
            transition: color .2s;
        }
        .toggle-pw:hover { color: var(--accent2); }

        /* ── Alert ── */
        .alert {
            background: rgba(255,107,107,0.1);
            border: 1px solid rgba(255,107,107,0.3);
            border-radius: 10px; padding: 12px 16px;
            font-size: 13px; color: var(--error);
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
            animation: shake .4s ease;
        }
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20%,60%  { transform: translateX(-6px); }
            40%,80%  { transform: translateX(6px); }
        }

        /* ── Button ── */
        .btn-login {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            border: none; border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-size: 14px; font-weight: 600;
            color: #fff; cursor: pointer;
            margin-top: 8px;
            position: relative; overflow: hidden;
            transition: transform .15s, box-shadow .2s;
            box-shadow: 0 6px 20px rgba(46,134,222,0.3);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(46,134,222,0.4);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login .ripple {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transform: scale(0); animation: ripple .5s linear;
            pointer-events: none;
        }
        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }

        /* ── Responsive ── */
        @media (max-width: 960px) {
            .page { grid-template-columns: 1fr; grid-template-rows: auto; align-items: start; overflow: auto; }
            .brand-side { display: none; }
            .card { grid-column: 1; padding: 36px 28px; margin: 40px 20px; border-radius: 16px; }
        }
    </style>
</head>
<body>
<div class="bg-wrap"></div>
<div class="grid-lines"></div>
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

<div class="page">
    <!-- Brand Side -->
    <div class="brand-side">
        <div class="brand-tag">Admin Portal</div>
        <h1 class="brand-title">Manage Everything<br>from <span>One Place</span></h1>
        <p class="brand-desc">Streamline employee management, doctor allocations, and field operations — all in a single powerful dashboard.</p>
        <div class="brand-stats">
            <div class="stat">
                <span class="stat-num" id="emp-count">—</span>
                <span class="stat-label">Employees</span>
            </div>
            <div class="stat">
                <span class="stat-num" id="doc-count">—</span>
                <span class="stat-label">Doctors</span>
            </div>
            <div class="stat">
                <span class="stat-num">24/7</span>
                <span class="stat-label">Uptime</span>
            </div>
        </div>
    </div>

    <!-- Login Card -->
    <div class="card">
        <div class="card-header">
            <div class="logo-ring">🔐</div>
            <h2 class="card-title">Welcome Back</h2>
            <p class="card-sub">Sign in to your admin account</p>
        </div>

        {{-- Error Message --}}
        @if($errors->any())
            <div class="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.doLogin') }}">
            @csrf

            <div class="form-group">
                <label>Email Address</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="admin@example.com" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="password" id="pw" placeholder="••••••••" required>
                    <button type="button" class="toggle-pw" onclick="togglePw(this)">
                        <svg id="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                Sign In to Dashboard
            </button>
        </form>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePw(btn) {
        const inp = document.getElementById('pw');
        const icon = document.getElementById('eye-icon');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            inp.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    // Ripple effect on button
    document.getElementById('loginBtn').addEventListener('click', function(e) {
        const btn = this;
        const circle = document.createElement('span');
        const diameter = Math.max(btn.clientWidth, btn.clientHeight);
        circle.style.cssText = `width:${diameter}px;height:${diameter}px;left:${e.clientX-btn.getBoundingClientRect().left-diameter/2}px;top:${e.clientY-btn.getBoundingClientRect().top-diameter/2}px`;
        circle.classList.add('ripple');
        btn.querySelector('.ripple')?.remove();
        btn.appendChild(circle);
    });
</script>
</body>
</html>
