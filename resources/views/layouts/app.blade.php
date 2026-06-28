<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost AJ Lanraki — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-900: #0d1b3e;
            --navy-800: #12245a;
            --navy-700: #1a3178;
            --navy-600: #1e3a8a;
            --navy-500: #2563eb;
            --navy-400: #3b82f6;
            --accent-gold: #f59e0b;
            --accent-emerald: #10b981;
            --accent-rose: #f43f5e;
            --accent-violet: #8b5cf6;
            --bg-main: #f0f2f7;
            --sidebar-width: 270px;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 6px 16px rgba(0,0,0,0.06);
            --card-shadow-hover: 0 4px 12px rgba(0,0,0,0.08), 0 12px 28px rgba(0,0,0,0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-main);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--navy-900) 0%, var(--navy-800) 40%, var(--navy-700) 100%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 24px rgba(13, 27, 62, 0.3);
        }

        .sidebar-brand {
            padding: 28px 24px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand img {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            object-fit: cover;
            margin-bottom: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
            border: 2px solid rgba(255,255,255,0.15);
        }

        .sidebar-brand h5 {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.45);
            font-size: 11px;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .nav-label {
            color: rgba(255,255,255,0.35);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 12px 16px 8px;
            margin-top: 8px;
        }

        .nav-link-sidebar {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
            position: relative;
        }

        .nav-link-sidebar:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
            transform: translateX(2px);
        }

        .nav-link-sidebar.active {
            background: linear-gradient(135deg, var(--navy-500), var(--navy-400));
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .nav-link-sidebar.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: var(--accent-gold);
            border-radius: 0 4px 4px 0;
        }

        .nav-link-sidebar i {
            font-size: 18px;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-footer small {
            color: rgba(255,255,255,0.3);
            font-size: 11px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: #ffffff;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8ecf4;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-left h4 {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy-900);
            margin: 0;
        }

        .topbar-left p {
            font-size: 13px;
            color: #94a3b8;
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-date {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--bg-main);
            border-radius: 10px;
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        .btn-sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--navy-900);
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
        }

        .btn-sidebar-toggle:hover {
            background: var(--bg-main);
        }

        /* ===== PAGE CONTENT ===== */
        .page-content {
            padding: 28px 32px;
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.06;
            transform: translate(30px, -30px);
        }

        .stat-card.blue::after { background: var(--navy-500); }
        .stat-card.emerald::after { background: var(--accent-emerald); }
        .stat-card.gold::after { background: var(--accent-gold); }
        .stat-card.rose::after { background: var(--accent-rose); }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .stat-card.blue .stat-icon {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: var(--navy-600);
        }
        .stat-card.emerald .stat-icon {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }
        .stat-card.gold .stat-icon {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }
        .stat-card.rose .stat-icon {
            background: linear-gradient(135deg, #fce7f3, #fbcfe8);
            color: #e11d48;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--navy-900);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* ===== CONTENT CARD ===== */
        .content-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }

        .content-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .content-card-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: var(--navy-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .content-card-header h5 i {
            color: var(--navy-500);
        }

        .content-card-body {
            padding: 0;
        }

        /* ===== TABLE ===== */
        .table-modern {
            width: 100%;
            margin: 0;
        }

        .table-modern thead th {
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            border-top: none;
            white-space: nowrap;
        }

        .table-modern tbody td {
            padding: 14px 20px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .table-modern tbody tr {
            transition: background 0.15s ease;
        }

        .table-modern tbody tr:hover {
            background: #f8fafc;
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== BADGES ===== */
        .badge-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-tersedia {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-terisi {
            background: #fce7f3;
            color: #9d174d;
        }

        .badge-single {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-double {
            background: #fef3c7;
            color: #92400e;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--navy-600), var(--navy-500));
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(30, 58, 138, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(30, 58, 138, 0.4);
            color: white;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-edit {
            background: #eff6ff;
            color: var(--navy-500);
        }

        .btn-edit:hover {
            background: var(--navy-500);
            color: white;
        }

        .btn-delete {
            background: #fff1f2;
            color: var(--accent-rose);
        }

        .btn-delete:hover {
            background: var(--accent-rose);
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
        }

        /* ===== FORMS ===== */
        .form-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0,0,0,0.04);
            padding: 32px;
            max-width: 640px;
        }

        .form-card .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-card .form-control,
        .form-card .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            color: #334155;
            transition: all 0.2s ease;
            background: #fafbfc;
        }

        .form-card .form-control:focus,
        .form-card .form-select:focus {
            border-color: var(--navy-500);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: #ffffff;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--navy-600), var(--navy-500));
            border: none;
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(30, 58, 138, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(30, 58, 138, 0.4);
            color: white;
        }

        .btn-cancel {
            background: #f1f5f9;
            border: none;
            color: #64748b;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
            color: #475569;
        }

        /* ===== ALERT ===== */
        .alert-modern {
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success-modern {
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
            color: #065f46;
        }

        /* ===== SUMMARY CARD ===== */
        .summary-card {
            background: linear-gradient(135deg, var(--navy-800), var(--navy-600));
            border-radius: 16px;
            padding: 20px 24px;
            color: white;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card .summary-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .summary-card .summary-value {
            font-size: 24px;
            font-weight: 800;
        }

        .summary-card .summary-label {
            font-size: 13px;
            opacity: 0.8;
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 16px;
        }

        .empty-state h6 {
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .empty-state p {
            color: #cbd5e1;
            font-size: 13px;
        }

        /* ===== RESPONSIVE ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .btn-sidebar-toggle {
                display: block;
            }

            .page-content {
                padding: 20px 16px;
            }

            .topbar {
                padding: 12px 16px;
            }

            .stat-card .stat-value {
                font-size: 22px;
            }

            .form-card {
                max-width: 100%;
            }
        }

        /* ===== SCROLLBAR ===== */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.15);
            border-radius: 4px;
        }

        /* ===== ANIMATION ===== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }

        .animate-in:nth-child(1) { animation-delay: 0s; }
        .animate-in:nth-child(2) { animation-delay: 0.05s; }
        .animate-in:nth-child(3) { animation-delay: 0.1s; }
        .animate-in:nth-child(4) { animation-delay: 0.15s; }
    </style>
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
        <h5>Kost AJ Lanraki</h5>
        <small>Sistem Manajemen Kost</small>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Menu Utama</div>

        <a href="/" class="nav-link-sidebar {{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a href="/kamar" class="nav-link-sidebar {{ request()->is('kamar*') ? 'active' : '' }}">
            <i class="bi bi-door-open-fill"></i>
            <span>Kamar</span>
        </a>

        <a href="/penghuni" class="nav-link-sidebar {{ request()->is('penghuni*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Penghuni</span>
        </a>

        <div class="nav-label">Keuangan</div>

        <a href="/pemasukan" class="nav-link-sidebar {{ request()->is('pemasukan*') ? 'active' : '' }}">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Pemasukan</span>
        </a>

        <a href="/pengeluaran" class="nav-link-sidebar {{ request()->is('pengeluaran*') ? 'active' : '' }}">
            <i class="bi bi-graph-down-arrow"></i>
            <span>Pengeluaran</span>
        </a>

        <a href="/laporan" class="nav-link-sidebar {{ request()->is('laporan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph-fill"></i>
            <span>Laporan Keuangan</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <small>&copy; {{ date('Y') }} Kost AJ Lanraki</small>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="btn-sidebar-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <h4>@yield('title', 'Dashboard')</h4>
                <p>@yield('subtitle', 'Selamat datang di Sistem Manajemen Kost')</p>
            </div>
        </div>
        <div class="topbar-right">
            <div class="topbar-date">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        @if(session('success'))
            <div class="alert-modern alert-success-modern">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
</script>
</body>
</html>
