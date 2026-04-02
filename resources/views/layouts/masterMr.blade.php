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

    {{-- Google Font: Only DM Sans used for entire webpage as requested --}}
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
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
        }

        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'DM Sans', sans-serif; /* Applied everywhere */
        }

        body {
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

    /* 🔥 Gradient (Same as Profile Icon) */
    background: var(--gradient-primary);

    display: flex;
    flex-direction: column;
    z-index: 1000;
    transition: transform .3s ease;
    box-shadow: 4px 0 20px rgba(0,0,0,0.15);
}

       
        /* Sidebar logo container ki height thodi badhayi hai taaki logo fit ho jaye */
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            height: 100px; 
            border-bottom: 1px solid rgba(255, 255, 255, 0.3); /* Opacity badhakar 30% (.3) kar di */
        }

/* Logo ki exact size badha di gayi hai */
.sidebar-brand .brand-logo {
    max-width: 100%;
    max-height: 80px; /* Size 60px se badha kar 80px kar di gayi hai */
    width: auto;
    object-fit: contain;
    transition: transform 0.3s ease;
}

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 20px 0;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 4px; }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,.7); /* 30% se badha kar 70% (.7) kar diya */
            padding: 12px 24px 8px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 24px;
            color: rgba(255,255,255,.8); /* .6 se badha kar .8 kar diya better visibility ke liye */
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: all .3s ease;
        }

        .nav-item a:hover,
        .nav-item a.active {
            color: #fff;
            /* White color ka transparent gradient taaki teal aur purple dono jagah mast dikhe */
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.15) 0%, transparent 100%);
            border-left-color: #fff; /* Border pure white kar diya taki highlight ho */
        }

        .nav-item a .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: rgba(255,255,255,.8); /* .4 se badha kar .8 kar diya taaki icon clear dikhe */
            transition: color .3s ease;
        }

        .nav-item a:hover .nav-icon,
        .nav-item a.active .nav-icon {
            color: #fff; /* Active hone pe icon bhi pure white chamkega */
        }

        .sidebar-footer {
            padding: 20px;
            background: rgba(0,0,0,0.15);
            border-top: 1px solid rgba(255,255,255,.05);
        }

        

        

        

        

        .sidebar-footer {
            padding: 20px;
            background: rgba(0,0,0,0.15);
            border-top: 1px solid rgba(255,255,255,.05);
        }

        .sidebar-footer .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: rgba(255, 86, 86, 0.1);
            border: 1px solid rgba(255, 86, 86, 0.2);
            border-radius: 8px;
            color: #ff6b6b; /* Reddish alert color */
            cursor: pointer;
            padding: 10px;
            font-size: 15px;
            font-weight: 600;
            transition: all .3s ease;
        }

        .sidebar-footer .logout-btn:hover {
            color: #fff;
            background: rgba(239, 68, 68, 0.9);
            border-color: rgba(239, 68, 68, 0.9);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* ── Topbar ───────────────────────────────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
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
            font-size: 18px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ── Profile Dropdown UI Elements (Mimicking my_profile.html) ── */
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
    margin-right: 6px; /* Arrow icon se halka sa gap rakhne ke liye adjust kiya gaya */
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

/* Dropdown ke upar wala chhota sa arrow (triangle) */
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

/* Hover karne me space empty hone par dropdown hide na ho, uske liye invisible bridge */
.profile-info::after {
    content: "";
    position: absolute;
    bottom: -20px;
    left: 0;
    right: 0;
    height: 20px;
}

/* Hover Actions: Hover karne par dropdown aayega aur arrow 180 degree ghumega */
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

        /* ── Main Content ─────────────────────────────── */
        #main-content {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 30px;
            min-height: calc(100vh - var(--topbar-h));
        }

        /* ── Cards & Dashboard UI ─────────────────────── */
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header, .section-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-header h3, .section-header h3 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: var(--text);
        }

        .card-body { padding: 24px; }

        /* Dashboard Stat Cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 158, 163, 0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: var(--gradient-primary);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: rgba(0, 158, 163, 0.1);
            color: var(--color-a);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        .stat-card[style*="--accent"] .stat-icon {
            background: rgba(179, 86, 159, 0.1);
            color: var(--color-b);
        }

        .stat-card[style*="--accent"]::before {
            background: var(--color-b);
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }

        /* ── Buttons ──────────────────────────────────── */
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

        .btn-outline {
            border: 2px solid var(--color-a);
            color: var(--color-a);
            background: transparent;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: var(--color-a);
            color: #fff;
            text-decoration: none;
            box-shadow: 0 6px 15px rgba(0, 158, 163, 0.3);
        }

        .btn-warning { font-weight: 600; border-radius: 8px; }

        /* ── Alerts ───────────────────────────────────── */
        .alert {
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        /* ── Overlay ──────────────────────────────────── */
        #page-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            align-items: center;
            justify-content: center;
        }

        #page-overlay.active { display: flex; }

        .spinner-ring {
            width: 54px; height: 54px;
            border: 4px solid rgba(255,255,255,.2);
            border-top-color: var(--color-a);
            border-right-color: var(--color-b);
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
                background: rgba(0,0,0,.5);
                backdrop-filter: blur(2px);
                z-index: 999;
            }
            .sidebar-overlay.active { display: block; }
        }

        /* DataTable tweaks */
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

        table.dataTable tbody tr td {
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        table.dataTable tbody tr:hover td { 
            background: rgba(0, 158, 163, 0.03); 
        }
    </style>

    {{-- Page-specific CSS --}}
    @yield('css')
</head>
<body>

{{-- ── Sidebar ──────────────────────────────────────────── --}}
<div id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ URL::asset('assets/logo.png') }}" alt="Lipaglyn Logo" class="brand-logo">
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

        
    </nav>

    
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
    <div class="profile-info position-relative d-flex align-items-center cursor-pointer" style="cursor: pointer;">
    <div class="user-details mr-3 d-none d-md-block text-right">
        <div class="user-name">{{ session('employee_name', 'MR User') }}</div>
        <div class="user-role">Medical Representative</div>
    </div>

    <div class="avatar-circle">
        {{ strtoupper(substr(session('employee_name', 'MR'), 0, 2)) }}
    </div>
    
    <i class="fas fa-chevron-down text-muted small profile-chevron ml-1"></i>

        <div class="profile-dropdown shadow-lg rounded-3 bg-white">
            <ul class="list-unstyled mb-0 py-2">
                <li>
                    <form method="POST" action="{{ route('employee.logout') }}" style="margin:0;">
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