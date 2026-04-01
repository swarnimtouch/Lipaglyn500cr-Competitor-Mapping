<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>@yield('title', 'MR Portal') | Lipaglyn</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ URL::asset('assets/admin/images/favicon.ico') }}">

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand:        #A11A20;
            --brand-dark:   #7e1419;
            --brand-light:  #f9e8e9;
            --sidebar-w:    250px;
            --topbar-h:     60px;
            --bg:           #f4f6fb;
            --card-bg:      #ffffff;
            --text:         #2d3148;
            --text-muted:   #8a8fa8;
            --border:       #e4e8f0;
            --shadow:       0 2px 12px rgba(161,26,32,.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Sidebar ──────────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: #1a1d2e;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px;
            height: var(--topbar-h);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-brand .brand-icon {
            width: 34px; height: 34px;
            background: var(--brand);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 16px;
            color: #fff;
            letter-spacing: -1px;
        }

        .sidebar-brand .brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 17px;
            color: #fff;
            letter-spacing: .3px;
        }

        .sidebar-brand .brand-name span {
            color: var(--brand);
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,.3);
            padding: 12px 20px 6px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .2s;
        }

        .nav-item a:hover,
        .nav-item a.active {
            color: #fff;
            background: rgba(255,255,255,.06);
            border-left-color: var(--brand);
        }

        .nav-item a .nav-icon {
            width: 18px;
            text-align: center;
            font-size: 15px;
            color: rgba(255,255,255,.4);
            transition: color .2s;
        }

        .nav-item a:hover .nav-icon,
        .nav-item a.active .nav-icon {
            color: var(--brand);
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,.07);
        }

        .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-footer .user-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            text-transform: uppercase;
        }

        .sidebar-footer .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
        }

        .sidebar-footer .user-role {
            font-size: 11px;
            color: rgba(255,255,255,.4);
        }

        .sidebar-footer .logout-btn {
            margin-left: auto;
            background: none;
            border: none;
            color: rgba(255,255,255,.4);
            cursor: pointer;
            padding: 4px;
            font-size: 15px;
            transition: color .2s;
        }

        .sidebar-footer .logout-btn:hover { color: var(--brand); }

        /* ── Topbar ───────────────────────────────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 999;
            gap: 16px;
        }

        .topbar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text);
            cursor: pointer;
            padding: 4px 8px;
        }

        .topbar-title {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: var(--brand-light);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: var(--brand);
        }

        /* ── Main Content ─────────────────────────────── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 24px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ── Cards ────────────────────────────────────── */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-body { padding: 20px; }

        /* ── Buttons ──────────────────────────────────── */
        .btn-primary {
            background: var(--brand) !important;
            border-color: var(--brand) !important;
            color: #fff !important;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: var(--brand-dark) !important;
            border-color: var(--brand-dark) !important;
        }

        .btn-warning { font-weight: 500; }

        /* ── Alerts ───────────────────────────────────── */
        .alert {
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
        }

        /* ── Overlay ──────────────────────────────────── */
        #page-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(26,29,46,.5);
            z-index: 9998;
            align-items: center;
            justify-content: center;
        }

        #page-overlay.active { display: flex; }

        .spinner-ring {
            width: 48px; height: 48px;
            border: 4px solid rgba(255,255,255,.2);
            border-top-color: var(--brand);
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Responsive ───────────────────────────────── */
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #topbar { left: 0; }
            #main-content { margin-left: 0; }
            .topbar-toggle { display: block; }
            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.4);
                z-index: 999;
            }
            .sidebar-overlay.active { display: block; }
        }

        /* DataTable tweaks */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 13px;
        }

        table.dataTable thead th {
            background: #f8f9fc;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
        }

        table.dataTable tbody tr:hover td { background: var(--brand-light); }
    </style>

    {{-- Page-specific CSS --}}
    @yield('css')
</head>
<body>

{{-- ── Sidebar ──────────────────────────────────────────── --}}
<div id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">L</div>
        <div class="brand-name">Lipa<span>glyn</span></div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>

        <div class="nav-item">
            <a href="{{ route('portal.dashboard') }}"
               class="{{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                Dashboard
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('portal.doctors.index') }}"
               class="{{ request()->routeIs('portal.doctors.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-md"></i></span>
                Doctors
            </a>
        </div>

        <div class="nav-label">Account</div>

        <div class="nav-item">
            <a href="{{ route('portal.doctors.export') }}">
                <span class="nav-icon"><i class="fas fa-file-excel"></i></span>
                Export Excel
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                {{ strtoupper(substr(session('employee_name', 'MR'), 0, 2)) }}
            </div>
            <div>
                <div class="user-name">{{ session('employee_name', 'MR User') }}</div>
                <div class="user-role">Medical Representative</div>
            </div>
            <form method="POST" action="{{ route('employee.logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Sidebar overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ── Topbar ───────────────────────────────────────────── --}}
<div id="topbar">
    <button class="topbar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    <div class="topbar-title">@yield('title', 'MR Portal')</div>
    <div class="topbar-right">
        <div class="topbar-badge">
            <i class="fas fa-circle" style="font-size:7px;"></i>
            {{ session('employee_name', 'MR') }}
        </div>
    </div>
</div>

{{-- ── Main Content ─────────────────────────────────────── --}}
<div id="main-content">
    @yield('content')
</div>

{{-- ── Page Overlay / Loader ────────────────────────────── --}}
<div id="page-overlay">
    <div class="spinner-ring"></div>
</div>

{{-- ── Scripts ──────────────────────────────────────────── --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- Global helper functions --}}
<script>
    function addOverlay() {
        document.getElementById('page-overlay').classList.add('active');
    }

    function removeOverlay() {
        document.getElementById('page-overlay').classList.remove('active');
    }

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('active');
    }

    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('active');
    }

    // Auto-hide alerts after 4 seconds
    $(document).ready(function () {
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 4000);
    });
</script>

{{-- Page-specific scripts --}}
@yield('script')

</body>
</html>
