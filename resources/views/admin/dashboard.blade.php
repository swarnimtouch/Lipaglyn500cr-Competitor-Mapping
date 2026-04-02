@extends('layouts.master')

@section('title') Admin Dashboard @endsection

@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sora', sans-serif !important; }

        /* ── Page header ── */
        .dash-header {
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            margin-bottom: 28px; 
            flex-wrap: wrap; 
            gap: 14px;
        }
        
        .dash-greeting h2 {
            font-size: 24px; 
            font-weight: 800; 
            color: var(--text-main); 
            margin: 0;
        }
        
        .dash-date {
            font-size: 13px; 
            font-weight: 700; 
            color: var(--primary);
            background: rgba(161, 26, 32, 0.08); /* Light Red background matching master theme */
            border: 1px solid rgba(161, 26, 32, 0.15);
            padding: 8px 18px; 
            border-radius: 20px;
            display: inline-flex; 
            align-items: center; 
            gap: 8px;
        }

        /* ── Premium Stat Cards ── */
        /* ── Premium Stat Cards ── */
        .stat-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; 
            margin-bottom: 32px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 200px;
            max-width: 250px; /* Card isse bada nahi hoga, compact rahega */
            background: #ffffff;
            border-radius: 14px; /* Radius thoda kam kiya */
            padding: 20px; /* Padding kam ki gayi hai */
            box-shadow: 0 8px 24px rgba(0, 158, 163, 0.08);
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 12px; /* Gap kam kiya gaya hai */
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 24px rgba(0, 158, 163, 0.12);
        }

        /* Top gradient line */
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(135deg, var(--color-a), var(--color-b));
        }
        
        .stat-card.purple-card::before {
            background: var(--color-b);
        }

        .stat-icon {
            width: 44px; height: 44px; /* Icon ka box size chhota kiya */
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; /* Icon ka size chhota kiya */
        }

        .stat-card.teal-card .stat-icon {
            background: rgba(0, 158, 163, 0.1);
            color: var(--color-a);
        }

        .stat-card.purple-card .stat-icon {
            background: rgba(179, 86, 159, 0.1);
            color: var(--color-b);
        }

        .stat-label {
            font-size: 13px; /* Font size chhota kiya */
            color: var(--text-muted);
            font-weight: 600;
        }

        .stat-value {
            font-size: 24px; /* Text size 30px se 24px kar diya */
            font-weight: 800;
            color: var(--text-main);
            line-height: 1;
        }

        /* ── Mobile Responsiveness ── */
        @media (max-width: 767px) {
            .stat-card {
                max-width: 100%; /* Mobile me max-width limit hata dega */
                flex: 1 1 100%; /* Force karega ki card puri width le */
            }
        }
    </style>
@endsection

@section('content')

    <div class="dash-header">
        <div class="dash-greeting">
            <h2>Admin Dashboard</h2>
        </div>
        
        <div class="dash-date">
            <i class="far fa-calendar-alt"></i>
            <span id="live-date"></span>
        </div>
    </div>

    <div class="stat-grid">
        
        {{-- Total Employees Card --}}
        <div class="stat-card teal-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-label">Total Employees</div>
            <div class="stat-value">{{ $totalEmployees ?? 0 }}</div>
        </div>

        {{-- Allocated Doctors Card --}}
        <div class="stat-card purple-card">
            <div class="stat-icon"><i class="fas fa-hospital-user"></i></div>
            <div class="stat-label">Allocated Doctors</div>
            <div class="stat-value">{{ $totalDoctors ?? 0 }}</div>
        </div>

    </div>

@endsection

@section('script')
    <script>
        // Live date
        document.getElementById('live-date').textContent = new Date().toLocaleDateString('en-IN', {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
        });
    </script>
@endsection