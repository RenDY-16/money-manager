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
    <link href="{{ asset('css/custom-style.css') }}" rel="stylesheet">
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
        <a href="{{ route('backup.index') }}" class="nav-link-sidebar {{ request()->is('backup-data*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">backup</span>
            <span>Backup Data</span>
        </a>
        <a href="{{ route('profil.index') }}" class="nav-link-sidebar {{ request()->is('profil-admin*') ? 'active' : '' }}" style="margin-top:auto;">
            <span class="material-symbols-outlined">person</span>
            <span>Profil Admin</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <small>&copy; {{ date('Y') }} Kost AJ Lanraki</small>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-demo">
            <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
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
                <div class="avatar">
                    @if(auth()->user()?->profile_photo)
                        <img src="{{ asset(auth()->user()->profile_photo) }}" alt="Foto admin">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'AJ', 0, 2)) }}
                    @endif
                </div>
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

        @if($errors->any())
            <div class="alert-modern alert-danger-modern">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
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
