<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Zydus Portal</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('css')

    <style>
        /* ═══════════════════════════════════════════
           CSS VARIABLES (Premium Theme Sync)
        ═══════════════════════════════════════════ */
        :root {
            /* Core Colors */
            --color-a:       #009ea3;
            --color-b:       #b3569f;
            --gradient-primary: linear-gradient(135deg, var(--color-a), var(--color-b));
            
            /* Layout & Spacing */
            --sidebar-w:     250px;
            --topbar-h:      65px;
            
            /* Surface Colors */
            --bg:            #f4f7fe;
            --card-bg:       #ffffff;
            --text:          #2b3674;
            --text-muted:    #8a8fa8;
            --border:        #e4e8f0;
            --shadow:        0 8px 24px rgba(0, 158, 163, 0.08);
            --sidebar-bg:    #0f172a;
            --transition:    all .22s cubic-bezier(.4,0,.2,1);
            
            /* Alert Colors */
            --success:       #10b981;
            --danger:        #ef4444;
            --info:          #3b82f6;
        }

        /* ═══════════════════════════════════════════
           RESET / BASE
        ═══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        a:hover { text-decoration: none; color: inherit; } /* Added this line to fix hover underline issue */
        img { max-width: 100%; }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--gradient-primary); /* Same premium gradient applied */
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }

        /* ── Brand ── */
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            height: 100px; /* Height badhai gayi */
            border-bottom: 1px solid rgba(255, 255, 255, 0.3); /* White solid line */
        }

        .sidebar-brand .brand-logo {
            max-width: 100%;
            max-height: 80px; /* Logo ki size MR portal jitni ki */
            width: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        /* ── Nav ── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 20px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.1) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.7); /* Visibility badhane ke liye .7 kiya */
            padding: 12px 24px 8px;
        }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 24px;
            color: rgba(255,255,255,.8); /* .8 kiya clear visibility ke liye */
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: var(--transition);
        }
        .nav-link-item:hover,
        .nav-link-item.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.15) 0%, transparent 100%); /* White transparent gradient */
            border-left-color: #fff; /* Pure white border */
        }

        .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: rgba(255,255,255,.8); /* Icon bhi brighter kiya */
            transition: color .3s ease;
        }

        .nav-link-item:hover .nav-icon,
        .nav-link-item.active .nav-icon {
            color: #fff; /* White icon on hover */
        }

        /* ═══════════════════════════════════════════
           MAIN WRAPPER & TOPBAR
        ═══════════════════════════════════════════ */
        #main-wrapper {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        #topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-h);
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            z-index: 900;
        }

        /* Hamburger */
        #sidebarToggle {
            display: none; /* Hide on desktop */
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text);
            cursor: pointer;
            padding: 4px 8px;
        }

        /* Breadcrumb (Optional styling to match theme) */
        .topbar-breadcrumb {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .tb-page-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }
        .tb-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }
        .tb-breadcrumb a { color: var(--color-a); font-weight: 500; }
        .tb-breadcrumb .sep { color: var(--border); }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ── Profile Dropdown UI Elements (Premium Sync) ── */
        .avatar-circle {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            margin-right: 6px;
        }

        .user-details .user-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--text);
            line-height: 1.2;
        }

        .user-details .user-role {
            font-size: 12px;
            color: var(--text-muted);
        }

        .profile-dropdown {
            position: absolute;
            top: 150%;
            right: 0;
            width: 180px;
            background: #ffffff;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
            z-index: 1050;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .profile-dropdown::before {
            content: "";
            position: absolute;
            top: -8px;
            right: 20px;
            width: 0;
            height: 0;
            border-left: 8px solid transparent;
            border-right: 8px solid transparent;
            border-bottom: 8px solid #ffffff;
        }

        .profile-info::after {
            content: "";
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 20px;
        }

        .profile-info:hover .profile-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            top: calc(100% + 15px);
        }

        .profile-info:hover .profile-chevron {
            transform: rotate(180deg);
        }

        .profile-chevron {
            transition: transform 0.3s ease;
        }

        .profile-dropdown .dropdown-item {
            font-size: 14px;
            font-weight: 600;
            transition: background-color 0.2s ease, color 0.2s ease;
            cursor: pointer;
        }

        .profile-dropdown .dropdown-item:hover {
            background-color: #f8f9fc;
        }

        /* ═══════════════════════════════════════════
           CONTENT AREA
        ═══════════════════════════════════════════ */
        #page-content {
            flex: 1;
            padding: 30px;
            animation: fadeUp .3s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════════
           CARD & BUTTON OVERRIDES
        ═══════════════════════════════════════════ */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 700;
            font-size: 18px;
            color: var(--text);
        }

        .card-body { padding: 24px; }

        .btn-primary {
            background: var(--gradient-primary) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.3s ease, transform 0.2s ease;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(179, 86, 159, 0.3);
        }

        /* ═══════════════════════════════════════════
           ALERT / FLASH
        ═══════════════════════════════════════════ */
        .flash-msg {
            position: fixed;
            top: 20px; right: 20px;
            z-index: 9999;
            min-width: 280px;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow);
            animation: slideIn .3s ease both;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .flash-success { background: rgba(16, 185, 129, 0.1); color: var(--success); border-left: 4px solid var(--success); }
        .flash-error   { background: rgba(239, 68, 68, 0.1); color: var(--danger); border-left: 4px solid var(--danger); }
        .flash-info    { background: rgba(59, 130, 246, 0.1); color: var(--info); border-left: 4px solid var(--info); }

        .flash-close { margin-left: auto; cursor: pointer; opacity: .6; font-size: 16px; }
        .flash-close:hover { opacity: 1; }

        /* ═══════════════════════════════════════════
           OVERLAY (loading)
        ═══════════════════════════════════════════ */
        #page-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
        }

        #page-overlay.show { display: flex; }

        .spinner {
            width: 54px; height: 54px;
            border: 4px solid rgba(255,255,255,.2);
            border-top-color: var(--color-a);
            border-right-color: var(--color-b);
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ═══════════════════════════════════════════
           SIDEBAR COLLAPSED & RESPONSIVE
        ═══════════════════════════════════════════ */
        body.sidebar-collapsed #sidebar { width: 70px; }
        body.sidebar-collapsed .brand-text,
        body.sidebar-collapsed .nav-section-label,
        body.sidebar-collapsed .nav-link-item span:not(.nav-icon) { display: none; }
        body.sidebar-collapsed #main-wrapper { margin-left: 70px; }
        body.sidebar-collapsed .sidebar-brand { padding: 10px; }
        body.sidebar-collapsed .nav-link-item { padding: 12px 0; justify-content: center; }

        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }
            body.sidebar-open #sidebar { transform: translateX(0); }
            body.sidebar-open #sidebar-overlay { display: block; }
            #main-wrapper { margin-left: 0 !important; }
            #page-content { padding: 20px; }
            #sidebarToggle { display: block; }
            .topbar-breadcrumb .tb-breadcrumb { display: none; }
        }

        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.5);
            backdrop-filter: blur(2px);
            z-index: 1040;
        }

        /* DataTable tweaks sync */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 14px;
            color: var(--text);
            outline: none;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--color-a);
            box-shadow: 0 0 0 3px rgba(0, 158, 163, 0.1);
        }
        table.dataTable thead th {
            background: #f8f9fc;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            padding: 12px 16px;
        }
        table.dataTable tbody tr td { vertical-align: middle; border-bottom: 1px solid var(--border); }
        table.dataTable tbody tr:hover td { background: rgba(0, 158, 163, 0.03); }
    </style>
</head>

<body>

{{-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ --}}
<aside id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ URL::asset('assets/logo.png') }}" alt="Lipaglyn Logo" class="brand-logo">
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-section-label">Management</div>
        <div class="nav-item">
            <a href="{{ route('admin.employees.index') }}" class="nav-link-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                <span>Employees</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.doctors.index') }}" class="nav-link-item {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-user-md"></i></span>
                <span>Doctors</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.report') }}" class="nav-link-item {{ request()->routeIs('admin.report.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                <span>Reports</span>
            </a>
        </div>
    </nav>
</aside>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- ════════════════════════════════════════
     MAIN WRAPPER
════════════════════════════════════════ --}}
<div id="main-wrapper">

    {{-- ── Topbar ── --}}
    <header id="topbar">
        <button id="sidebarToggle" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-breadcrumb">
            <div class="tb-page-title">@yield('title', 'Dashboard')</div>
            <div class="tb-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Home</a>
                <span class="sep">/</span>
                <span>@yield('title', 'Dashboard')</span>
            </div>
        </div>

        <div class="topbar-right">
            <div class="profile-info position-relative d-flex align-items-center cursor-pointer" style="cursor: pointer;">
                <div class="user-details mr-3 d-none d-md-block text-right">
                    <div class="user-name">{{ session('admin_name', 'Admin User') }}</div>
                    <div class="user-role">Administrator</div>
                </div>

                <div class="avatar-circle">
                    {{ strtoupper(substr(session('admin_name', 'AD'), 0, 2)) }}
                </div>
                
                <i class="fas fa-chevron-down text-muted small profile-chevron ml-1"></i>

                <div class="profile-dropdown shadow-lg rounded-3 bg-white">
                    <ul class="list-unstyled mb-0 py-2">
                        <li>
                            <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger px-3 py-2 w-100 border-0 bg-transparent text-left">
                                    <i class="fas fa-sign-out-alt mr-3"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    {{-- ── Page Content ── --}}
    <main id="page-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flash-msg flash-success" id="flashMsg">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <span class="flash-close" onclick="dismissFlash()">×</span>
            </div>
        @elseif(session('error'))
            <div class="flash-msg flash-error" id="flashMsg">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
                <span class="flash-close" onclick="dismissFlash()">×</span>
            </div>
        @endif

        @yield('content')

    </main>

</div>

{{-- Page Overlay --}}
<div id="page-overlay">
    <div class="spinner"></div>
</div>

{{-- ════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════ --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

@yield('script')

<script>
    // ── Sidebar Toggle ──────────────────────────────────────────────────────
    function toggleSidebar() {
        if (window.innerWidth <= 991) {
            document.body.classList.toggle('sidebar-open');
        } else {
            document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', document.body.classList.contains('sidebar-collapsed'));
        }
    }

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
    }

    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 991) {
        document.body.classList.add('sidebar-collapsed');
    }

    // ── Flash Auto Dismiss ──────────────────────────────────────────────────
    function dismissFlash() {
        var el = document.getElementById('flashMsg');
        if (el) el.style.display = 'none';
    }
    setTimeout(dismissFlash, 4000);

    // ── Overlay ─────────────────────────────────────────────────────────────
    function addOverlay()    { document.getElementById('page-overlay').classList.add('show'); }
    function removeOverlay() { document.getElementById('page-overlay').classList.remove('show'); }

    // ── CSRF for Ajax ────────────────────────────────────────────────────────
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>

</body>
</html>