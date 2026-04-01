<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – MR Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #080b14;
            --surface:  #0e1220;
            --border:   #1c2133;
            --accent:   #2f6fff;
            --accent2:  #00c2a8;
            --text:     #e8eaf0;
            --muted:    #5a6380;
            --danger:   #ff4d6d;
            --success:  #22d3a6;
            --sidebar-w: 240px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0; top: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 28px 20px 20px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo h2 {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--text);
        }

        .sidebar-logo span {
            font-size: 11px;
            color: var(--muted);
            display: block;
            margin-top: 3px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 0;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.2px;
            color: var(--muted);
            text-transform: uppercase;
            padding: 12px 20px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: color 0.2s, background 0.2s;
            position: relative;
        }

        .nav-item:hover,
        .nav-item.active {
            color: var(--text);
            background: rgba(47, 111, 255, 0.08);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
        }

        .employee-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
        }

        .employee-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
        }

        .employee-info strong {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            display: block;
        }

        .employee-info small {
            color: var(--muted);
            font-size: 11px;
        }

        .btn-logout {
            width: 100%;
            background: rgba(255, 77, 109, 0.08);
            border: 1px solid rgba(255, 77, 109, 0.2);
            color: #ff7a93;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            transition: background 0.2s;
        }

        .btn-logout:hover { background: rgba(255, 77, 109, 0.18); }

        /* ── Main ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            height: 60px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
        }

        .topbar-right {
            font-size: 13px;
            color: var(--muted);
        }

        .topbar-right strong { color: var(--text); }

        .page-content { padding: 28px; }

        /* ── Stat Cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .stat-card:hover { border-color: var(--accent); }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            background: rgba(47,111,255,0.06);
        }

        .stat-icon { font-size: 26px; margin-bottom: 12px; }

        .stat-label {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 42px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
            margin-top: 6px;
        }

        /* ── Quick Actions ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
        }

        .card h3 {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .actions-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            border: none;
            font-family: 'DM Sans', sans-serif;
            transition: opacity 0.2s;
        }

        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { opacity: 0.88; }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
        }

        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }

        /* ── Welcome Banner ── */
        .welcome-banner {
            background: linear-gradient(135deg, #0f1f40, #0a1628);
            border: 1px solid #1e3a6e;
            border-radius: 14px;
            padding: 28px 32px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .welcome-banner h1 {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .welcome-banner p { color: var(--muted); font-size: 13.5px; }

        .emp-badge {
            background: #1e3a5f;
            color: #60a5fa;
            border: 1px solid #1d4ed8;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        @media (max-width: 640px) {
            .sidebar { display: none; }
            .main-wrap { margin-left: 0; }
        }
    </style>
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <h2>🏥 MR Portal</h2>
        <span>Medical Representative</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="{{ route('employee.dashboard') }}" class="nav-item active">
            <span class="nav-icon">⊞</span> Dashboard
        </a>

        <div class="nav-label">Manage</div>
        <a href="{{ route('portal.doctors.index') }}" class="nav-item">
            <span class="nav-icon">👨‍⚕️</span> My Doctors
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="employee-chip">
            <div class="employee-avatar">
                {{ strtoupper(substr(session('employee_name', 'U'), 0, 2)) }}
            </div>
            <div class="employee-info">
                <strong>{{ session('employee_name') }}</strong>
                <small>ID: {{ session('employee_eid') }}</small>
            </div>
        </div>
        <form method="POST" action="{{ route('employee.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">↩ Logout</button>
        </form>
    </div>
</aside>

{{-- ── MAIN ── --}}
<div class="main-wrap">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-right">Welcome, <strong>{{ session('employee_name') }}</strong></div>
    </div>

    <div class="page-content">

        {{-- Welcome Banner --}}
        <div class="welcome-banner">
            <div>
                <h1>Namaste, {{ session('employee_name') }}! 👋</h1>
                <p>Aap successfully login ho gaye hain. Yahan se apne doctors manage karein.</p>
            </div>
            <div class="emp-badge">EMP ID: {{ session('employee_eid') }}</div>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">👨‍⚕️</div>
                <div class="stat-label">My Doctors</div>
                <div class="stat-value">{{ $doctorCount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-label">Active Doctors</div>
                <div class="stat-value">{{ $doctorCount }}</div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <h3>Quick Actions</h3>
            <div class="actions-row">
                <a href="{{ route('portal.doctors.index') }}" class="btn btn-outline">👨‍⚕️ View Doctors</a>
                <a href="{{ route('portal.doctors.create') }}" class="btn btn-primary">+ Add Doctor</a>
            </div>
        </div>

    </div>
</div>

</body>
</html>
