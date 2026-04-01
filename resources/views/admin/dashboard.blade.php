@extends('layouts.master')

@section('title') Admin Dashboard @endsection

@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --blue:    #2e86de;
            --blue-lt: #54a0ff;
            --green:   #1dd1a1;
            --orange:  #ff9f43;
            --red:     #ff6b6b;
            --purple:  #a29bfe;
        }

        body { font-family: 'Sora', sans-serif !important; }

        /* ── Page header ── */
        .dash-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px; flex-wrap: wrap; gap: 14px;
        }
        .dash-greeting h2 {
            font-size: 22px; font-weight: 700; color: #2d3748; margin: 0 0 4px;
        }
        .dash-greeting p { font-size: 13px; color: #718096; margin: 0; }
        .dash-date {
            font-size: 12px; font-weight: 500; color: #a0aec0;
            background: #f7fafc; border: 1px solid #e2e8f0;
            padding: 8px 16px; border-radius: 20px;
        }

        /* ── Stat cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 20px; margin-bottom: 28px;
        }
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px 22px;
            border: 1px solid #edf2f7;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            position: relative; overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(0,0,0,0.1);
        }
        .stat-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
        }
        .stat-card.blue::before  { background: linear-gradient(90deg, var(--blue), var(--blue-lt)); }
        .stat-card.green::before { background: linear-gradient(90deg, var(--green), #00b894); }
        .stat-card.orange::before{ background: linear-gradient(90deg, var(--orange), #ffeaa7); }
        .stat-card.purple::before{ background: linear-gradient(90deg, var(--purple), #74b9ff); }

        .stat-icon {
            width: 48px; height: 48px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 16px;
        }
        .stat-card.blue   .stat-icon { background: rgba(46,134,222,0.1); }
        .stat-card.green  .stat-icon { background: rgba(29,209,161,0.1); }
        .stat-card.orange .stat-icon { background: rgba(255,159,67,0.1); }
        .stat-card.purple .stat-icon { background: rgba(162,155,254,0.1); }

        .stat-value {
            font-size: 32px; font-weight: 700; color: #2d3748;
            line-height: 1; margin-bottom: 6px;
            font-variant-numeric: tabular-nums;
        }
        .stat-label { font-size: 12px; color: #a0aec0; font-weight: 500; text-transform: uppercase; letter-spacing: .5px; }
        .stat-badge {
            position: absolute; top: 20px; right: 20px;
            font-size: 11px; font-weight: 600; padding: 3px 10px;
            border-radius: 20px;
        }
        .stat-card.blue   .stat-badge { background: rgba(46,134,222,0.1);  color: var(--blue); }
        .stat-card.green  .stat-badge { background: rgba(29,209,161,0.1);  color: var(--green); }
        .stat-card.orange .stat-badge { background: rgba(255,159,67,0.1);  color: var(--orange); }
        .stat-card.purple .stat-badge { background: rgba(162,155,254,0.1); color: var(--purple); }

        /* ── Quick actions ── */
        .section-title {
            font-size: 14px; font-weight: 600; color: #4a5568;
            margin-bottom: 14px; text-transform: uppercase; letter-spacing: .8px;
        }
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 14px; margin-bottom: 28px;
        }
        .qa-btn {
            display: flex; flex-direction: column; align-items: center;
            gap: 10px; padding: 20px 12px;
            background: #fff; border: 1px solid #edf2f7;
            border-radius: 14px; text-decoration: none;
            color: #4a5568; font-size: 13px; font-weight: 500;
            transition: all .2s; text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .qa-btn:hover {
            background: #f0f7ff; border-color: var(--blue);
            color: var(--blue); transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46,134,222,0.1);
            text-decoration: none;
        }
        .qa-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
        }

        /* ── Two-col layout ── */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
        @media (max-width: 768px) { .two-col { grid-template-columns: 1fr; } }

        /* ── Panel card ── */
        .panel {
            background: #fff; border: 1px solid #edf2f7;
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        }
        .panel-head {
            padding: 18px 22px; border-bottom: 1px solid #f0f4f8;
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-head h5 { margin: 0; font-size: 14px; font-weight: 600; color: #2d3748; }
        .panel-head a { font-size: 12px; color: var(--blue); text-decoration: none; font-weight: 500; }
        .panel-body { padding: 4px 0; }

        /* ── Mini list ── */
        .mini-list-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 22px; border-bottom: 1px solid #f7fafc;
            transition: background .15s;
        }
        .mini-list-item:last-child { border-bottom: none; }
        .mini-list-item:hover { background: #fafcff; }
        .avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--blue), var(--blue-lt));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 600; color: #fff;
            flex-shrink: 0;
        }
        .avatar.g { background: linear-gradient(135deg, var(--green), #00b894); }
        .avatar.o { background: linear-gradient(135deg, var(--orange), #f9ca24); }
        .item-info { flex: 1; min-width: 0; }
        .item-name { font-size: 13px; font-weight: 600; color: #2d3748; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .item-sub  { font-size: 11px; color: #a0aec0; }
        .item-badge {
            font-size: 11px; font-weight: 600; padding: 3px 10px;
            border-radius: 20px; white-space: nowrap;
        }
        .badge-active   { background: rgba(29,209,161,0.12); color: var(--green); }
        .badge-inactive { background: rgba(255,107,107,0.12); color: var(--red); }

        /* ── Activity feed ── */
        .activity-item {
            display: flex; gap: 14px; padding: 12px 22px;
            border-bottom: 1px solid #f7fafc;
        }
        .activity-item:last-child { border-bottom: none; }
        .act-dot {
            width: 10px; height: 10px; border-radius: 50%;
            margin-top: 5px; flex-shrink: 0;
        }
        .act-dot.blue   { background: var(--blue); }
        .act-dot.green  { background: var(--green); }
        .act-dot.orange { background: var(--orange); }
        .act-text { font-size: 13px; color: #4a5568; line-height: 1.5; }
        .act-time { font-size: 11px; color: #a0aec0; margin-top: 2px; }

        /* ── Number counter animation ── */
        .count-up { display: inline-block; }

        /* ── Welcome banner ── */
        .welcome-banner {
            background: linear-gradient(135deg, #1a3c5e 0%, #2e86de 60%, #54a0ff 100%);
            border-radius: 16px; padding: 28px 32px;
            color: #fff; margin-bottom: 28px;
            display: flex; align-items: center; justify-content: space-between;
            overflow: hidden; position: relative;
        }
        .welcome-banner::after {
            content: ''; position: absolute;
            right: -20px; top: -30px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .welcome-banner::before {
            content: ''; position: absolute;
            right: 80px; bottom: -40px;
            width: 140px; height: 140px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .wb-title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .wb-sub   { font-size: 13px; opacity: .8; }
        .wb-emoji { font-size: 48px; position: relative; z-index: 1; }
    </style>
@endsection

@section('content')

    <!-- Page Header -->
    <div class="dash-header">
        <div class="dash-greeting">
            <h2>Admin Dashboard</h2>
            <p>Welcome back! Here's what's happening today.</p>
        </div>
        <div class="dash-date" id="live-date"></div>
    </div>

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div>
            <div class="wb-title">Good to see you, Admin 👋</div>
            <div class="wb-sub">You have {{ $activeEmployees }} active employees and {{ $totalDoctors }} allocated doctors across all regions.</div>
        </div>
        <div class="wb-emoji">📊</div>
    </div>

    <!-- Stat Cards -->
    <div class="stat-grid">
        <div class="stat-card blue">
            <span class="stat-badge">Total</span>
            <div class="stat-icon">👥</div>
            <div class="stat-value"><span class="count-up" data-target="{{ $totalEmployees }}">0</span></div>
            <div class="stat-label">Total Employees</div>
        </div>
        <div class="stat-card green">
            <span class="stat-badge">Active</span>
            <div class="stat-icon">✅</div>
            <div class="stat-value"><span class="count-up" data-target="{{ $activeEmployees }}">0</span></div>
            <div class="stat-label">Active Employees</div>
        </div>
        <div class="stat-card orange">
            <span class="stat-badge">Inactive</span>
            <div class="stat-icon">⏸️</div>
            <div class="stat-value"><span class="count-up" data-target="{{ $totalEmployees - $activeEmployees }}">0</span></div>
            <div class="stat-label">Inactive Employees</div>
        </div>
        <div class="stat-card purple">
            <span class="stat-badge">Allocated</span>
            <div class="stat-icon">🏥</div>
            <div class="stat-value"><span class="count-up" data-target="{{ $totalDoctors }}">0</span></div>
            <div class="stat-label">Allocated Doctors</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <p class="section-title">Quick Actions</p>
    <div class="quick-actions">
        <a href="{{ route('admin.employees.index') }}" class="qa-btn">
            <div class="qa-icon" style="background:rgba(46,134,222,0.1)">👤</div>
            Manage Employees
        </a>
        <a href="{{ route('admin.doctors.index') }}" class="qa-btn">
            <div class="qa-icon" style="background:rgba(29,209,161,0.1)">🏥</div>
            View Doctors
        </a>
        <a href="{{ route('admin.employees.index') }}?status=active" class="qa-btn">
            <div class="qa-icon" style="background:rgba(255,159,67,0.1)">📋</div>
            Active MRs
        </a>
        <a href="#" onclick="window.print()" class="qa-btn">
            <div class="qa-icon" style="background:rgba(162,155,254,0.1)">🖨️</div>
            Print Report
        </a>
    </div>

    <!-- Two Col -->
    <div class="two-col">
        <!-- Recent Employees -->
        <div class="panel">
            <div class="panel-head">
                <h5>Recent Employees</h5>
                <a href="{{ route('admin.employees.index') }}">View All →</a>
            </div>
            <div class="panel-body" id="recent-employees-body">
                @forelse($recentEmployees ?? [] as $emp)
                    <div class="mini-list-item">
                        <div class="avatar {{ $emp->status === 'active' ? '' : 'o' }}">{{ strtoupper(substr($emp->name,0,1)) }}</div>
                        <div class="item-info">
                            <div class="item-name">{{ $emp->name }}</div>
                            <div class="item-sub">{{ $emp->hq }} • {{ $emp->type }}</div>
                        </div>
                        <span class="item-badge {{ $emp->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                    {{ ucfirst($emp->status) }}
                </span>
                    </div>
                @empty
                    <div style="padding:20px 22px;font-size:13px;color:#a0aec0;text-align:center;">No recent employees</div>
                @endforelse
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="panel">
            <div class="panel-head">
                <h5>Activity Overview</h5>
            </div>
            <div class="panel-body">
                <div class="activity-item">
                    <div class="act-dot blue"></div>
                    <div>
                        <div class="act-text">Total <strong>{{ $totalEmployees }}</strong> employees registered in the system</div>
                        <div class="act-time">System overview</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-dot green"></div>
                    <div>
                        <div class="act-text"><strong>{{ $activeEmployees }}</strong> employees currently active</div>
                        <div class="act-time">Active workforce</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-dot orange"></div>
                    <div>
                        <div class="act-text"><strong>{{ $totalEmployees - $activeEmployees }}</strong> employees marked inactive</div>
                        <div class="act-time">Needs attention</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-dot blue"></div>
                    <div>
                        <div class="act-text"><strong>{{ $totalDoctors }}</strong> doctors allocated across all MRs</div>
                        <div class="act-time">Doctor allocation</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="act-dot green"></div>
                    <div>
                        <div class="act-text">Dashboard loaded successfully</div>
                        <div class="act-time">Just now</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        // Live date
        document.getElementById('live-date').textContent = new Date().toLocaleDateString('en-IN', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });

        // Count-up animation
        document.querySelectorAll('.count-up').forEach(el => {
            const target = parseInt(el.dataset.target) || 0;
            if (target === 0) { el.textContent = '0'; return; }
            const duration = 1200;
            const step = Math.ceil(duration / target);
            let current = 0;
            const timer = setInterval(() => {
                current += Math.max(1, Math.ceil(target / 60));
                if (current >= target) { el.textContent = target; clearInterval(timer); }
                else { el.textContent = current; }
            }, step);
        });
    </script>
@endsection
