<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Zydus Portal</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 4 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @yield('css')

    <style>
        /* ═══════════════════════════════════════════
           CSS VARIABLES
        ═══════════════════════════════════════════ */
        :root {
            --primary:       #A11A20;
            --primary-dark:  #7a1218;
            --primary-light: #c9373f;
            --sidebar-bg:    #0f1117;
            --sidebar-width: 260px;
            --topbar-h:      64px;
            --text-main:     #1a1d23;
            --text-muted:    #6b7280;
            --border:        #e5e8ef;
            --surface:       #f7f8fc;
            --white:         #ffffff;
            --success:       #16a34a;
            --warning:       #d97706;
            --danger:        #dc2626;
            --info:          #0284c7;
            --radius:        10px;
            --shadow-sm:     0 1px 3px rgba(0,0,0,.08);
            --shadow-md:     0 4px 16px rgba(0,0,0,.10);
            --shadow-lg:     0 8px 32px rgba(0,0,0,.14);
            --transition:    all .22s cubic-bezier(.4,0,.2,1);
        }

        /* ═══════════════════════════════════════════
           RESET / BASE
        ═══════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            color: var(--text-main);
            font-size: 14px;
            line-height: 1.6;
            overflow-x: hidden;
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: var(--transition);
            overflow: hidden;
        }

        /* Subtle red accent line at top */
        #sidebar::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }

        /* ── Brand ── */
        .sidebar-brand {
            padding: 22px 24px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(161,26,32,.4);
        }

        .brand-icon svg { width: 20px; height: 20px; fill: #fff; }

        .brand-text { display: flex; flex-direction: column; }
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .3px;
            line-height: 1.2;
        }
        .brand-sub {
            font-size: 10px;
            color: rgba(255,255,255,.35);
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 500;
        }

        /* ── Nav ── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.1) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-section-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: rgba(255,255,255,.25);
            padding: 16px 24px 6px;
        }

        .nav-item { position: relative; }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 24px;
            color: rgba(255,255,255,.55);
            font-size: 13.5px;
            font-weight: 400;
            cursor: pointer;
            transition: var(--transition);
            border-radius: 0;
            position: relative;
        }

        .nav-link-item:hover {
            color: rgba(255,255,255,.9);
            background: rgba(255,255,255,.05);
        }

        .nav-link-item.active {
            color: #fff;
            background: rgba(161,26,32,.18);
            font-weight: 500;
        }

        .nav-link-item.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--primary-light);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 20px; height: 20px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 15px;
            opacity: .7;
        }

        .nav-link-item.active .nav-icon,
        .nav-link-item:hover .nav-icon { opacity: 1; }

        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
        }

        /* Submenu */
        .has-submenu > .nav-link-item .chevron {
            margin-left: auto;
            font-size: 11px;
            transition: transform .2s;
            opacity: .5;
        }

        .has-submenu.open > .nav-link-item .chevron { transform: rotate(90deg); }

        .submenu {
            display: none;
            background: rgba(0,0,0,.2);
        }

        .has-submenu.open .submenu { display: block; }

        .submenu .nav-link-item {
            padding-left: 56px;
            font-size: 13px;
        }

        /* ── Sidebar Footer ── */
        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sf-avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #e05a60);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
        }

        .sf-info { flex: 1; min-width: 0; }
        .sf-name {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sf-role {
            font-size: 11px;
            color: rgba(255,255,255,.3);
        }

        .sf-logout {
            color: rgba(255,255,255,.3);
            font-size: 16px;
            cursor: pointer;
            transition: color .2s;
        }
        .sf-logout:hover { color: #e05a60; }

        /* ═══════════════════════════════════════════
           MAIN WRAPPER
        ═══════════════════════════════════════════ */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        /* ═══════════════════════════════════════════
           TOPBAR
        ═══════════════════════════════════════════ */
        #topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 16px;
            z-index: 900;
            box-shadow: var(--shadow-sm);
        }

        /* Hamburger */
        #sidebarToggle {
            width: 36px; height: 36px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            transition: background .2s;
            padding: 0;
        }

        #sidebarToggle:hover { background: var(--surface); }

        #sidebarToggle span {
            display: block;
            width: 18px; height: 2px;
            background: var(--text-main);
            border-radius: 2px;
            transition: var(--transition);
        }

        /* Breadcrumb */
        .topbar-breadcrumb {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .tb-page-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }

        .tb-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .tb-breadcrumb a { color: var(--primary); }
        .tb-breadcrumb .sep { color: var(--border); }

        /* Right actions */
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tb-icon-btn {
            width: 36px; height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: var(--white);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 15px;
            transition: var(--transition);
            position: relative;
        }

        .tb-icon-btn:hover {
            background: var(--surface);
            color: var(--text-main);
            border-color: #d0d5dd;
        }

        .tb-notif-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: var(--primary);
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        .tb-user {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 12px 5px 5px;
            border: 1px solid var(--border);
            border-radius: 40px;
            cursor: pointer;
            transition: var(--transition);
            background: var(--white);
        }

        .tb-user:hover { background: var(--surface); border-color: #d0d5dd; }

        .tb-user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #e05a60);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        .tb-user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
        }

        /* ═══════════════════════════════════════════
           CONTENT AREA
        ═══════════════════════════════════════════ */
        #page-content {
            flex: 1;
            padding: 28px;
            animation: fadeUp .3s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ═══════════════════════════════════════════
           CARD OVERRIDES
        ═══════════════════════════════════════════ */
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            background: var(--white);
        }

        .card-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
            border-radius: var(--radius) var(--radius) 0 0 !important;
            font-weight: 600;
            font-size: 14px;
        }

        .card-body { padding: 20px; }

        /* Stat cards */
        .stat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
        }

        .stat-card.red::after    { background: linear-gradient(90deg, var(--primary), var(--primary-light)); }
        .stat-card.green::after  { background: linear-gradient(90deg, #16a34a, #22c55e); }
        .stat-card.blue::after   { background: linear-gradient(90deg, #0284c7, #38bdf8); }
        .stat-card.amber::after  { background: linear-gradient(90deg, #d97706, #fbbf24); }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card.red   .stat-icon { background: #fee2e2; color: var(--primary); }
        .stat-card.green .stat-icon { background: #dcfce7; color: #16a34a; }
        .stat-card.blue  .stat-icon { background: #e0f2fe; color: #0284c7; }
        .stat-card.amber .stat-icon { background: #fef3c7; color: #d97706; }

        .stat-info { flex: 1; }
        .stat-value {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.1;
            color: var(--text-main);
        }
        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ═══════════════════════════════════════════
           BUTTON OVERRIDES
        ═══════════════════════════════════════════ */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            font-size: 13px;
            border-radius: 7px;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            box-shadow: 0 4px 12px rgba(161,26,32,.3);
        }

        .btn-xs {
            font-size: 11px;
            padding: 3px 9px;
            border-radius: 5px;
            font-weight: 500;
        }

        .btn-success { background: var(--success); border-color: var(--success); font-weight: 500; font-size: 13px; border-radius: 7px; }
        .btn-warning { background: var(--warning); border-color: var(--warning); font-weight: 500; font-size: 13px; border-radius: 7px; color: #fff; }
        .btn-danger  { background: var(--danger);  border-color: var(--danger);  font-weight: 500; font-size: 13px; border-radius: 7px; }
        .btn-info    { background: var(--info);    border-color: var(--info);    font-weight: 500; font-size: 13px; border-radius: 7px; }

        /* ═══════════════════════════════════════════
           TABLE OVERRIDES
        ═══════════════════════════════════════════ */
        .table thead th {
            background: var(--surface);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            padding: 12px 14px;
            white-space: nowrap;
        }

        .table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px;
        }

        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr:hover td { background: #fafbfd; }

        /* ═══════════════════════════════════════════
           FORM OVERRIDES
        ═══════════════════════════════════════════ */
        .form-control {
            border-radius: 7px;
            border: 1px solid var(--border);
            font-size: 13.5px;
            color: var(--text-main);
            padding: .45rem .75rem;
            transition: border-color .2s, box-shadow .2s;
            font-family: 'DM Sans', sans-serif;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(161,26,32,.1);
            outline: none;
        }

        label { font-weight: 500; font-size: 13px; color: #374151; }
        label.error { color: var(--danger); font-weight: 400; font-size: 12px; margin-top: 3px; }

        /* ═══════════════════════════════════════════
           MODAL OVERRIDES
        ═══════════════════════════════════════════ */
        .modal-content {
            border: none;
            border-radius: 14px;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
        }

        .modal-header {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 18px 24px;
        }

        .modal-title { font-weight: 600; font-size: 16px; }

        .modal-body { padding: 20px 24px; }

        .modal-footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 14px 24px;
        }

        /* ═══════════════════════════════════════════
           BADGE
        ═══════════════════════════════════════════ */
        .badge-success { background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-danger  { background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-warning { background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }
        .badge-info    { background: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600; }

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
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-lg);
            animation: slideIn .3s ease both;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .flash-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .flash-error   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .flash-info    { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }

        .flash-close { margin-left: auto; cursor: pointer; opacity: .6; font-size: 16px; }
        .flash-close:hover { opacity: 1; }

        /* ═══════════════════════════════════════════
           OVERLAY (loading)
        ═══════════════════════════════════════════ */
        #page-overlay {
            position: fixed; inset: 0;
            background: rgba(255,255,255,.6);
            backdrop-filter: blur(2px);
            z-index: 9998;
            display: none;
            align-items: center;
            justify-content: center;
        }

        #page-overlay.show { display: flex; }

        .spinner {
            width: 40px; height: 40px;
            border: 3px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ═══════════════════════════════════════════
           SIDEBAR COLLAPSED
        ═══════════════════════════════════════════ */
        body.sidebar-collapsed #sidebar {
            width: 64px;
        }

        body.sidebar-collapsed .brand-text,
        body.sidebar-collapsed .nav-section-label,
        body.sidebar-collapsed .nav-link-item span:not(.nav-icon),
        body.sidebar-collapsed .nav-badge,
        body.sidebar-collapsed .chevron,
        body.sidebar-collapsed .sf-info,
        body.sidebar-collapsed .sf-logout { display: none; }

        body.sidebar-collapsed #main-wrapper { margin-left: 64px; }

        body.sidebar-collapsed .sidebar-brand { padding: 22px 13px 18px; justify-content: center; }
        body.sidebar-collapsed .nav-link-item { padding: 11px 0; justify-content: center; }
        body.sidebar-collapsed .sidebar-footer { padding: 16px 13px; justify-content: center; }
        body.sidebar-collapsed .sf-avatar { margin: 0; }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }

            body.sidebar-open #sidebar { transform: translateX(0); }
            body.sidebar-open #sidebar-overlay { display: block; }

            #main-wrapper { margin-left: 0 !important; }
            #page-content { padding: 16px; }

            .topbar-breadcrumb .tb-breadcrumb { display: none; }
        }

        #sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
        }

        /* DataTable tweaks */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 5px 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            color: var(--text-main);
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(161,26,32,.08);
            outline: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--surface) !important;
            border-color: var(--border) !important;
            color: var(--text-main) !important;
            border-radius: 6px;
        }

        .dataTables_wrapper .dataTables_info { font-size: 12.5px; color: var(--text-muted); }
    </style>
</head>

<body>

{{-- ════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════ --}}
<aside id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
        </div>
        <div class="brand-text">
            <span class="brand-name">Zydus</span>
            <span class="brand-sub">Admin Portal</span>
        </div>
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

        <div class="nav-section-label">Reports</div>

        <div class="nav-item">
            <a href="#" class="nav-link-item">
                <span class="nav-icon"><i class="fas fa-file-export"></i></span>
                <span>Export Data</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link-item">
                <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                <span>Analytics</span>
            </a>
        </div>

    </nav>

    {{-- Footer --}}
    <div class="sidebar-footer">
        <div class="sf-avatar">
            {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
        </div>
        <div class="sf-info">
            <div class="sf-name">{{ session('admin_name', 'Administrator') }}</div>
            <div class="sf-role">Super Admin</div>
        </div>
        <a href="{{ route('admin.logout') }}" class="sf-logout" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

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
            <span></span><span></span><span></span>
        </button>

        <div class="topbar-breadcrumb">
            <div class="tb-page-title">@yield('title', 'Dashboard')</div>
            <div class="tb-breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Home</a>
                <span class="sep">/</span>
                <span>@yield('title', 'Dashboard')</span>
            </div>
        </div>

        <div class="topbar-actions">
            <div class="tb-icon-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="tb-notif-dot"></span>
            </div>
            <div class="tb-icon-btn" title="Settings">
                <i class="fas fa-cog"></i>
            </div>
            <div class="tb-user">
                <div class="tb-user-avatar">
                    {{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}
                </div>
                <span class="tb-user-name">{{ session('admin_name', 'Admin') }}</span>
                <i class="fas fa-chevron-down" style="font-size:10px; color:#9ca3af; margin-left:4px;"></i>
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
        if (window.innerWidth <= 768) {
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
    if (localStorage.getItem('sidebarCollapsed') === 'true' && window.innerWidth > 768) {
        document.body.classList.add('sidebar-collapsed');
    }

    // ── Submenu Toggle ──────────────────────────────────────────────────────
    document.querySelectorAll('.has-submenu > .nav-link-item').forEach(function (el) {
        el.addEventListener('click', function () {
            el.closest('.has-submenu').classList.toggle('open');
        });
    });

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
