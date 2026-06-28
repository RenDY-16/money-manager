<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kost AJ Lanraki — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #00288e;
            --primary-container: #1e40af;
            --secondary: #0058be;
            --secondary-soft: #d8e2ff;
            --surface: #f8f9fa;
            --surface-card: #ffffff;
            --surface-low: #f3f4f5;
            --surface-high: #e7e8e9;
            --text-main: #191c1d;
            --text-muted: #444653;
            --border: #e5e7eb;
            --border-strong: #c4c5d5;
            --success: #16a34a;
            --success-soft: #dcfce7;
            --danger: #dc2626;
            --danger-soft: #fee2e2;
            --warning: #d97706;
            --warning-soft: #fef3c7;
            --sidebar-width: 260px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 10px 15px -8px rgba(0, 0, 0, 0.14);
            --navy-900: var(--primary);
            --navy-500: var(--primary-container);
            --accent-rose: var(--danger);
            --accent-emerald: var(--success);
            --accent-gold: var(--warning);
        }

        body[data-theme="dark"] {
            --primary: #c7d2fe;
            --primary-container: #2563eb;
            --secondary: #60a5fa;
            --secondary-soft: #172554;
            --surface: #0f172a;
            --surface-card: #111827;
            --surface-low: #1f2937;
            --surface-high: #334155;
            --text-main: #f8fafc;
            --text-muted: #cbd5e1;
            --border: #263244;
            --border-strong: #334155;
            --success-soft: #052e16;
            --danger-soft: #450a0a;
            --warning-soft: #451a03;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.35);
            --shadow-md: 0 14px 24px -12px rgba(0, 0, 0, 0.55);
        }

        body[data-theme="green"] {
            --primary: #064e3b;
            --primary-container: #047857;
            --secondary: #059669;
            --secondary-soft: #d1fae5;
        }

        body[data-density="compact"] {
            --sidebar-width: 230px;
        }

        body[data-density="compact"] .page-content { padding: 24px; }
        body[data-density="compact"] .stat-card,
        body[data-density="compact"] .metric-card { min-height: 112px; padding: 16px; }
        body[data-density="compact"] .nav-link-sidebar { min-height: 38px; padding-block: 9px; }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--surface);
            color: var(--text-main);
            font-size: 14px;
        }

        a { text-decoration: none; }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 22px;
            line-height: 1;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--surface-card);
            border-right: 1px solid var(--border-strong);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            padding: 0 24px 24px;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--secondary-soft);
            border: 1px solid var(--border);
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-brand h5 {
            margin: 0;
            color: var(--primary);
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .sidebar-brand small {
            display: block;
            margin-top: 2px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 500;
        }

        .sidebar-nav {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .nav-label {
            padding: 14px 24px 8px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .nav-link-sidebar {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 44px;
            padding: 12px 24px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            border-left: 4px solid transparent;
            transition: background .18s ease, color .18s ease, border-color .18s ease;
        }

        .nav-link-sidebar:hover {
            background: var(--secondary-soft);
            color: #001a42;
        }

        .nav-link-sidebar.active {
            background: var(--surface-low);
            color: var(--primary);
            border-left-color: var(--secondary);
            font-weight: 800;
        }

        .sidebar-footer {
            padding: 16px 24px 0;
            border-top: 1px solid var(--border);
        }

        .sidebar-footer small {
            color: #6b7280;
            font-size: 12px;
        }

        .logout-demo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 14px;
            padding: 10px 12px;
            background: var(--danger);
            color: #fff;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            min-width: 0;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 900;
            height: 64px;
            background: var(--surface-card);
            border-bottom: 1px solid var(--border-strong);
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 32px;
        }

        .topbar-title {
            min-width: 190px;
        }

        .topbar-title h4 {
            margin: 0;
            color: var(--primary);
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .topbar-title p {
            margin: 1px 0 0;
            color: #6b7280;
            font-size: 12px;
            font-weight: 500;
        }

        .topbar-search {
            flex: 1;
            max-width: 420px;
            position: relative;
        }

        .topbar-search .material-symbols-outlined {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-size: 20px;
        }

        .topbar-search input {
            width: 100%;
            height: 38px;
            padding: 0 14px 0 40px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--surface-low);
            color: var(--text-main);
            outline: none;
            font-size: 13px;
        }

        .topbar-search input:focus {
            background: #fff;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(0, 88, 190, .12);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
        }

        .settings-wrapper { position: relative; }

        .settings-button {
            border: 0;
            background: transparent;
            color: var(--text-muted);
        }

        .settings-panel {
            position: absolute;
            top: 48px;
            right: 0;
            width: 280px;
            padding: 16px;
            background: var(--surface-card);
            border: 1px solid var(--border-strong);
            border-radius: 14px;
            box-shadow: var(--shadow-md);
            display: none;
            z-index: 1200;
        }

        .settings-panel.show { display: block; }

        .settings-panel h6 {
            margin: 0 0 4px;
            color: var(--text-main);
            font-size: 14px;
            font-weight: 900;
        }

        .settings-panel p {
            margin: 0 0 14px;
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
        }

        .settings-label {
            display: block;
            margin: 12px 0 8px;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .settings-options {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .settings-options.two { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .theme-option,
        .density-option {
            min-height: 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface-low);
            color: var(--text-main);
            font-size: 12px;
            font-weight: 800;
        }

        .theme-option.active,
        .density-option.active {
            border-color: var(--secondary);
            color: var(--secondary);
            background: var(--secondary-soft);
        }

        .topbar-icon {
            position: relative;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            color: var(--text-muted);
            border-radius: 999px;
            cursor: pointer;
        }

        .topbar-icon:hover { background: var(--surface-low); }

        .notification-dot {
            position: absolute;
            top: 9px;
            right: 9px;
            width: 8px;
            height: 8px;
            background: var(--danger);
            border-radius: 999px;
            border: 1px solid white;
        }

        .user-mini {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-left: 16px;
            border-left: 1px solid var(--border-strong);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            background: var(--primary);
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 800;
            box-shadow: var(--shadow-sm);
        }

        .user-mini strong {
            display: block;
            font-size: 13px;
            color: var(--text-main);
            line-height: 1.1;
        }

        .user-mini span {
            display: block;
            color: #6b7280;
            font-size: 11px;
            font-weight: 500;
        }

        .btn-sidebar-toggle {
            display: none;
            border: 0;
            background: var(--surface-low);
            color: var(--primary);
            width: 40px;
            height: 40px;
            border-radius: 8px;
        }

        .page-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--primary);
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .page-heading p {
            margin: 4px 0 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 24px;
            margin-bottom: 24px;
        }

        .metric-grid-5 { grid-template-columns: repeat(5, minmax(0, 1fr)); }

        .stat-card,
        .metric-card,
        .content-card,
        .form-card {
            background: var(--surface-card);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
        }

        .stat-card,
        .metric-card {
            padding: 20px;
            min-height: 132px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover,
        .metric-card:hover,
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .metric-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-icon,
        .metric-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--secondary-soft);
            color: var(--secondary);
            font-size: 22px;
        }

        .metric-icon.success { background: var(--success-soft); color: var(--success); }
        .metric-icon.danger { background: var(--danger-soft); color: var(--danger); }
        .metric-icon.warning { background: var(--warning-soft); color: var(--warning); }

        .metric-trend {
            color: var(--secondary);
            font-size: 12px;
            font-weight: 800;
        }

        .stat-card .stat-value,
        .metric-value {
            color: var(--primary);
            font-size: 22px;
            font-weight: 800;
            line-height: 1.15;
            font-variant-numeric: tabular-nums;
        }

        .stat-card .stat-label,
        .metric-label {
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .metric-note {
            margin-top: 8px;
            color: #6b7280;
            font-size: 12px;
            font-weight: 500;
        }

        .content-card { overflow: hidden; }

        .content-card-header {
            min-height: 58px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .content-card-header h5 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-main);
            font-size: 16px;
            font-weight: 800;
        }

        .content-card-header .material-symbols-outlined,
        .content-card-header i {
            color: var(--secondary);
        }

        .content-card-body { padding: 20px; }
        .content-card-body.flush { padding: 0; }

        .table-modern {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-modern thead th {
            background: var(--surface);
            color: #6b7280;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: 13px 18px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .table-modern tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
            vertical-align: middle;
            font-variant-numeric: tabular-nums;
        }

        .table-modern tbody tr:hover { background: #eff6ff; }
        .table-modern tbody tr:last-child td { border-bottom: 0; }

        .table-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            color: var(--primary);
        }

        .row-avatar {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: inline-grid;
            place-items: center;
            background: var(--secondary-soft);
            color: var(--primary);
            font-weight: 800;
            font-size: 12px;
            flex: 0 0 auto;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
        }

        .badge-tersedia,
        .badge-paid,
        .badge-success { background: var(--success-soft); color: #166534; }
        .badge-terisi,
        .badge-danger { background: var(--danger-soft); color: #991b1b; }
        .badge-single,
        .badge-blue { background: var(--secondary-soft); color: #1e3a8a; }
        .badge-double,
        .badge-warning { background: var(--warning-soft); color: #92400e; }
        .badge-neutral { background: var(--surface-low); color: var(--text-muted); }

        .btn-primary-custom,
        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 10px 16px;
            border: 0;
            border-radius: 4px;
            background: var(--primary-container);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
            transition: transform .18s ease, background .18s ease;
        }

        .btn-primary-custom:hover,
        .btn-submit:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-cancel,
        .btn-secondary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: #fff;
            color: var(--primary);
            font-size: 13px;
            font-weight: 800;
            line-height: 1;
        }

        .btn-cancel:hover,
        .btn-secondary-custom:hover {
            background: var(--surface-low);
            color: var(--primary);
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: var(--text-muted);
            transition: background .18s ease, color .18s ease;
        }

        .btn-edit:hover { background: var(--secondary-soft); color: var(--primary); }
        .btn-delete:hover { background: var(--danger-soft); color: var(--danger); }

        .form-card {
            max-width: 720px;
            padding: 24px;
        }

        .form-card .form-label,
        .form-label {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 7px;
        }

        .form-card .form-control,
        .form-card .form-select,
        .form-control,
        .form-select {
            min-height: 40px;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: #fff;
            color: var(--text-main);
            font-size: 14px;
        }

        .form-card .form-control:focus,
        .form-card .form-select:focus,
        .form-control:focus,
        .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(0, 88, 190, .12);
        }

        .alert-modern {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 8px;
            border: 1px solid transparent;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 13px;
        }

        .alert-success-modern {
            background: var(--success-soft);
            color: #166534;
            border-color: #bbf7d0;
        }

        .empty-state {
            text-align: center;
            padding: 52px 24px;
        }

        .empty-state i,
        .empty-state .material-symbols-outlined {
            font-size: 48px;
            color: #9ca3af;
            margin-bottom: 14px;
        }

        .empty-state h6 {
            margin: 0 0 6px;
            color: var(--text-main);
            font-weight: 800;
        }

        .empty-state p {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }

        .finance-panel {
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface-card);
            padding: 18px;
        }

        .finance-panel.primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .finance-panel .label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #6b7280;
        }

        .finance-panel.primary .label { color: #d8e2ff; }
        .finance-panel .value { margin-top: 8px; font-size: 24px; font-weight: 800; font-variant-numeric: tabular-nums; }

        .css-chart {
            height: 260px;
            display: flex;
            align-items: flex-end;
            gap: 16px;
            padding: 12px 4px 0;
        }

        .chart-month {
            flex: 1;
            min-width: 38px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
        }

        .chart-bars {
            height: 210px;
            width: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 6px;
            border-bottom: 1px solid var(--border);
        }

        .chart-bar {
            width: 16px;
            min-height: 4px;
            border-radius: 4px 4px 0 0;
            background: var(--secondary-soft);
        }

        .chart-bar.income { background: #9fb2e9; }
        .chart-bar.expense { background: #f3b7bd; }

        .chart-label {
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            display: inline-block;
            margin-right: 6px;
        }

        .filter-box {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            background: #fff;
        }

        .compact-input {
            width: auto;
            min-width: 170px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 13px;
        }

        .summary-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        @media print {
            .sidebar,
            .topbar,
            .sidebar-overlay,
            .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; max-width: none !important; }
            body { background: #fff !important; }
            .content-card, .finance-panel, .metric-card, .stat-card {
                box-shadow: none !important;
                break-inside: avoid;
            }
            @page { size: A4; margin: 12mm; }
        }

        .print-document { background: #fff; color: #111827; }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 999;
        }

        .sidebar-overlay.show { display: block; }

        @media (max-width: 1199.98px) {
            .metric-grid,
            .metric-grid-5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .btn-sidebar-toggle { display: grid; place-items: center; }
            .topbar { padding: 0 16px; }
            .topbar-search { display: none; }
            .page-content { padding: 20px 16px; }
            .user-mini div { display: none; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeInUp .3s ease both; }

        @media (max-width: 575.98px) {
            .metric-grid,
            .metric-grid-5,
            .summary-strip { grid-template-columns: 1fr; }
            .page-heading { flex-direction: column; }
            .css-chart { gap: 8px; overflow-x: auto; align-items: flex-end; }
            .chart-month { min-width: 52px; }
        }
    </style>
    @stack('styles')
</head>
<body data-theme="light" data-density="comfortable">
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-row">
            <div class="brand-mark">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
            </div>
            <div>
                <h5>Kost AJ Lanraki</h5>
                <small>Management v1.0</small>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link-sidebar {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="material-symbols-outlined">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('penghuni.index') }}" class="nav-link-sidebar {{ request()->is('penghuni*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">group</span>
            <span>Data Penghuni</span>
        </a>
        <a href="{{ route('kamar.index') }}" class="nav-link-sidebar {{ request()->is('kamar*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">bed</span>
            <span>Data Kamar</span>
        </a>
        <a href="{{ route('pemasukan.index') }}" class="nav-link-sidebar {{ request()->is('pemasukan*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">payments</span>
            <span>Pemasukan</span>
        </a>
        <a href="{{ route('pengeluaran.index') }}" class="nav-link-sidebar {{ request()->is('pengeluaran*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">account_balance_wallet</span>
            <span>Pengeluaran</span>
        </a>
        <a href="{{ route('laporan.index') }}" class="nav-link-sidebar {{ request()->is('laporan*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">description</span>
            <span>Laporan Keuangan</span>
        </a>
        <a href="{{ route('profil.index') }}" class="nav-link-sidebar {{ request()->is('profil-admin*') ? 'active' : '' }}" style="margin-top:auto;">
            <span class="material-symbols-outlined">person</span>
            <span>Profil Admin</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <small>&copy; {{ date('Y') }} Kost AJ Lanraki</small>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="logout-demo border-0">
                <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                Logout
            </button>
        </form>
    </div>
</aside>

<div class="main-content">
    <header class="topbar">
        <button class="btn-sidebar-toggle" onclick="toggleSidebar()" aria-label="Buka menu">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <div class="topbar-title">
            <h4>@yield('title', 'Financial Management')</h4>
            <p>@yield('subtitle', 'Sistem Manajemen Keuangan Kost')</p>
        </div>
        <div class="topbar-actions">
            <div class="settings-wrapper">
                <button type="button" class="topbar-icon settings-button" title="Pengaturan" onclick="toggleSettingsPanel(event)">
                    <span class="material-symbols-outlined">settings</span>
                </button>
                <div class="settings-panel" id="settingsPanel">
                    <h6>Pengaturan Tampilan</h6>
                    <p>Atur tema dan kepadatan tampilan sesuai kebutuhan kerja admin.</p>

                    <span class="settings-label">Tema</span>
                    <div class="settings-options">
                        <button type="button" class="theme-option" data-theme="light" onclick="setTheme('light')">Terang</button>
                        <button type="button" class="theme-option" data-theme="dark" onclick="setTheme('dark')">Gelap</button>
                        <button type="button" class="theme-option" data-theme="green" onclick="setTheme('green')">Hijau</button>
                    </div>

                    <span class="settings-label">Layout</span>
                    <div class="settings-options two">
                        <button type="button" class="density-option" data-density="comfortable" onclick="setDensity('comfortable')">Normal</button>
                        <button type="button" class="density-option" data-density="compact" onclick="setDensity('compact')">Kompak</button>
                    </div>
                </div>
            </div>
            <div class="user-mini">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'AJ', 0, 2)) }}</div>
                <div>
                    <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                    <span>Owner</span>
                </div>
            </div>
        </div>
    </header>

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

    function toggleSettingsPanel(event) {
        event.stopPropagation();
        document.getElementById('settingsPanel').classList.toggle('show');
    }

    function setTheme(theme) {
        document.body.dataset.theme = theme;
        localStorage.setItem('kostAjTheme', theme);
        refreshSettingsState();
    }

    function setDensity(density) {
        document.body.dataset.density = density;
        localStorage.setItem('kostAjDensity', density);
        refreshSettingsState();
    }

    function refreshSettingsState() {
        const theme = document.body.dataset.theme || 'light';
        const density = document.body.dataset.density || 'comfortable';

        document.querySelectorAll('.theme-option').forEach(button => {
            button.classList.toggle('active', button.dataset.theme === theme);
        });

        document.querySelectorAll('.density-option').forEach(button => {
            button.classList.toggle('active', button.dataset.density === density);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.body.dataset.theme = localStorage.getItem('kostAjTheme') || 'light';
        document.body.dataset.density = localStorage.getItem('kostAjDensity') || 'comfortable';
        refreshSettingsState();
    });

    document.addEventListener('click', (event) => {
        const panel = document.getElementById('settingsPanel');
        if (panel && !panel.contains(event.target) && !event.target.closest('.settings-button')) {
            panel.classList.remove('show');
        }
    });
</script>
@stack('scripts')
</body>
</html>
