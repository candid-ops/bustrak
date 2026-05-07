<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Cache Control Headers - Prevent back button issues -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>BusTrak - @yield('title', 'Bus Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a1f5e;
            --sidebar-hover: rgba(255,255,255,0.1);
            --sidebar-active: rgba(255,255,255,0.18);
        }

        body {
            background-color: #f0f2f8;
            overflow-x: hidden;
        }

        [data-bs-theme="dark"] body {
            background-color: #0f1117;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #1a1f5e 0%, #2d3494 100%);
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            padding: 24px 20px;
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }

        .sidebar-brand span {
            color: #7eb3ff;
        }

        /* Scrollable nav area */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }

        .sidebar-section {
            padding: 16px 20px 4px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35);
            font-weight: 600;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.72);
            padding: 10px 16px;
            border-radius: 10px;
            margin: 2px 12px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        /* Logout button fixed at bottom */
        .sidebar-logout {
            flex-shrink: 0;
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(180deg, #2d3494 0%, #1a1f5e 100%);
        }

        .logout-btn {
            width: 100%;
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e8eaf0;
            padding: 14px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        [data-bs-theme="dark"] .topbar {
            background: #1a1d2e;
            border-bottom-color: #2a2d3e;
        }

        .topbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1a1f5e;
        }

        [data-bs-theme="dark"] .topbar-title {
            color: #e0e4ff;
        }

        .content-area {
            padding: 28px;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-card .card-body {
            padding: 24px;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            opacity: 0.6;
        }

        .stat-value {
            font-size: 1.9rem;
            font-weight: 700;
            line-height: 1.1;
            margin-top: 4px;
        }

        .content-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            overflow: hidden;
        }

        .content-card .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 18px 24px;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 600;
            padding: 14px 20px;
            border-bottom-width: 1px;
            opacity: 0.6;
        }

        .table tbody td {
            padding: 14px 20px;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .theme-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid #e0e4f0;
            background: #f5f6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #555;
        }

        .theme-btn:hover {
            background: #e8eaff;
            border-color: #c5caff;
        }

        [data-bs-theme="dark"] .theme-btn {
            background: #2a2d3e;
            border-color: #3a3d4e;
            color: #ccc;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a1f5e, #4a54d4);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1000;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-bus-front-fill me-2"></i>Bus<span>Trak</span>
    </div>

    <!-- Scrollable Navigation -->
    <div class="sidebar-nav">
        @if(auth()->check() && auth()->user()->hasRole('admin'))
            <div class="sidebar-section">Main</div>
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            
            <div class="sidebar-section">Fleet</div>
            <a href="{{ route('admin.buses.index') }}"
               class="nav-link {{ request()->routeIs('admin.buses*') ? 'active' : '' }}">
                <i class="bi bi-bus-front-fill"></i> Buses
            </a>
            <a href="{{ route('admin.drivers.index') }}"
               class="nav-link {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Drivers
            </a>
            
            <div class="sidebar-section">Operations</div>
            <a href="{{ route('admin.routes.index') }}"
               class="nav-link {{ request()->routeIs('admin.routes*') ? 'active' : '' }}">
                <i class="bi bi-signpost-2-fill"></i> Routes
            </a>
            <a href="{{ route('admin.schedules.index') }}"
               class="nav-link {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Schedules
            </a>
            <a href="{{ route('admin.bookings.index') }}"
               class="nav-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i> Bookings
            </a>
            
        @elseif(auth()->check() && auth()->user()->hasRole('driver'))
            <div class="sidebar-section">Main</div>
            <a href="{{ route('driver.dashboard') }}"
               class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            
        @elseif(auth()->check() && auth()->user()->hasRole('customer'))
            <div class="sidebar-section">Main</div>
            <a href="{{ route('customer.dashboard') }}"
               class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="{{ route('customer.book') }}"
               class="nav-link {{ request()->routeIs('customer.book*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i> Book a Seat
            </a>
        @endif

        <!-- Live Map Link (shown only if authenticated) -->
        @auth
        <div class="sidebar-section">Navigation</div>
        <a href="{{ route('map') }}"
           class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}">
            <i class="bi bi-geo-alt-fill"></i> Live Map
        </a>
        @endauth
    </div>

    <!-- Logout Button (Fixed at bottom) -->
    @auth
    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="submit" class="logout-btn" id="logoutButton">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
    @endauth
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <div class="topbar-title">@yield('title', 'Dashboard')</div>
        <div class="d-flex align-items-center gap-3">
            @auth
            <div class="d-flex align-items-center gap-2">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:0.85rem; font-weight:600;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.72rem; opacity:0.5; text-transform:capitalize;">
                        {{ auth()->user()->getRoleNames()->first() }}
                    </div>
                </div>
            </div>
            @endauth
            <button class="theme-btn" id="themeToggle">
                <i class="bi bi-moon-fill" id="themeIcon"></i>
            </button>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-2"
                 style="background:#e8f8f0; color:#1a7a4a;">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert border-0 rounded-3 mb-4 d-flex align-items-center gap-2"
                 style="background:#fde8e8; color:#a83232;">
                <i class="bi bi-exclamation-circle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const html = document.documentElement;
    const toggle = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const saved = localStorage.getItem('theme') || 'light';

    html.setAttribute('data-bs-theme', saved);
    icon.className = saved === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';

    toggle.addEventListener('click', () => {
        const next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-bs-theme', next);
        localStorage.setItem('theme', next);
        icon.className = next === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
    });

    // Handle logout with page reload to prevent back button issues
    const logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            // Allow form to submit normally
            setTimeout(function() {
                window.location.replace('/login');
            }, 100);
        });
    }

    // Optional: Mobile sidebar toggle
    if (window.innerWidth <= 768) {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        if (sidebar && mainContent) {
            mainContent.addEventListener('click', () => {
                sidebar.classList.remove('open');
            });
        }
    }

    // Prevent back button from showing cached pages
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            window.location.reload();
        }
    });
</script>
@yield('scripts')
</body>
</html>