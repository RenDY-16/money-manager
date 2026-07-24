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
<body data-theme="neon" data-density="comfortable">
<script>
    (function() {
        const theme = localStorage.getItem('kostAjTheme') || 'neon';
        const density = localStorage.getItem('kostAjDensity') || 'comfortable';
        document.body.setAttribute('data-theme', theme);
        document.body.setAttribute('data-density', density);
    })();
</script>

<!-- Futuristic 3D Tech Ambient Background Canvas -->
<div class="neon-ambient-bg">
    <div class="tech-3d-sphere-container">
        <div class="tech-3d-sphere"></div>
        <div class="tech-3d-ring ring-1"></div>
        <div class="tech-3d-ring ring-2"></div>
        <div class="tech-3d-ring ring-3"></div>
    </div>
    <div class="ambient-orb ambient-orb-1"></div>
    <div class="ambient-orb ambient-orb-2"></div>
    <div class="ambient-orb ambient-orb-3"></div>
    <div class="ambient-grid-overlay"></div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-row">
            <div class="brand-mark">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
            </div>
            <div>
                <h5>Kost AJ Lanraki</h5>
                <small>Management v4.0</small>
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
        <a href="{{ route('recycle-bin.index') }}" class="nav-link-sidebar {{ request()->is('recycle-bin*') ? 'active' : '' }}">
            <span class="material-symbols-outlined">delete_sweep</span>
            <span>Recycle Bin</span>
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
        <div class="topbar-search" id="globalSearchContainer">
            <span class="material-symbols-outlined">search</span>
            <input type="text" id="globalSearchInput" placeholder="Cari kamar, penghuni, transaksi..." autocomplete="off">
            <div class="search-results-dropdown" id="searchResultsDropdown"></div>
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
                        <button type="button" class="theme-option" data-theme="neon" onclick="setTheme('neon')">Neon Glass</button>
                        <button type="button" class="theme-option" data-theme="dark" onclick="setTheme('dark')">Gelap</button>
                        <button type="button" class="theme-option" data-theme="light" onclick="setTheme('light')">Terang</button>
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
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast-item toast-success" role="alert">
                <span class="material-symbols-outlined toast-icon">check_circle</span>
                <div class="toast-content">{{ session('success') }}</div>
                <button class="toast-close" onclick="closeToast(this)">&times;</button>
            </div>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <div class="toast-item toast-danger" role="alert">
                    <span class="material-symbols-outlined toast-icon">warning</span>
                    <div class="toast-content">{{ $error }}</div>
                    <button class="toast-close" onclick="closeToast(this)">&times;</button>
                </div>
            @endforeach
        @endif
    </div>

        @yield('content')
    </main>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-dialog-custom">
        <div class="modal-icon">
            <span class="material-symbols-outlined">warning</span>
        </div>
        <h5>Konfirmasi Hapus</h5>
        <p id="deleteModalMessage">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button type="button" class="btn-modal-delete" id="deleteModalConfirm">Ya, Hapus</button>
        </div>
    </div>
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

    function closeToast(button) {
        const toast = button.closest('.toast-item');
        if (toast) {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 300);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.body.dataset.theme = localStorage.getItem('kostAjTheme') || 'neon';
        document.body.dataset.density = localStorage.getItem('kostAjDensity') || 'comfortable';
        refreshSettingsState();

        // Auto dismiss toasts after 4 seconds
        document.querySelectorAll('.toast-item').forEach(toast => {
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        });
    });

    document.addEventListener('click', (event) => {
        const panel = document.getElementById('settingsPanel');
        if (panel && !panel.contains(event.target) && !event.target.closest('.settings-button')) {
            panel.classList.remove('show');
        }
    });

    // ═══ DELETE MODAL (Fitur 15) ═══
    let pendingDeleteForm = null;
    function confirmDelete(form, message) {
        pendingDeleteForm = form;
        document.getElementById('deleteModalMessage').textContent = message || 'Apakah Anda yakin ingin menghapus data ini?';
        document.getElementById('deleteModal').classList.add('show');
        return false;
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
        pendingDeleteForm = null;
    }
    document.getElementById('deleteModalConfirm').addEventListener('click', () => {
        if (pendingDeleteForm) {
            pendingDeleteForm.submit();
        }
    });
    document.getElementById('deleteModal').addEventListener('click', (e) => {
        if (e.target === e.currentTarget) closeDeleteModal();
    });

    // ═══ LOADING SPINNER (Fitur 13) ═══
    document.querySelectorAll('form').forEach(form => {
        if (form.id === 'logout-form' || form.id === 'deleteForm') return;
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"], .btn-submit, .btn-primary-custom[type="submit"]');
            if (btn && !btn.classList.contains('btn-loading') && !form.hasAttribute('data-no-spinner')) {
                btn.classList.add('btn-loading');
                btn.disabled = true;
            }
        });
    });

    // ═══ GLOBAL SEARCH (Fitur 11) ═══
    (function() {
        const input = document.getElementById('globalSearchInput');
        const dropdown = document.getElementById('searchResultsDropdown');
        if (!input || !dropdown) return;

        let debounceTimer;
        input.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const query = input.value.trim();
            if (query.length < 2) {
                dropdown.classList.remove('show');
                return;
            }
            debounceTimer = setTimeout(() => {
                fetch(`/search?q=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    const groups = [
                        { key: 'kamar', label: 'Kamar', icon: 'bed' },
                        { key: 'penghuni', label: 'Penghuni', icon: 'group' },
                        { key: 'pemasukan', label: 'Pemasukan', icon: 'payments' },
                        { key: 'pengeluaran', label: 'Pengeluaran', icon: 'account_balance_wallet' }
                    ];
                    let hasResults = false;
                    groups.forEach(g => {
                        if (data[g.key] && data[g.key].length > 0) {
                            hasResults = true;
                            html += `<div class="search-group-label">${g.label}</div>`;
                            data[g.key].forEach(item => {
                                html += `<a href="${item.url}" class="search-item">`
                                    + `<span class="material-symbols-outlined">${g.icon}</span>`
                                    + `<div><div>${item.title}</div><div class="search-item-sub">${item.subtitle}</div></div>`
                                    + `</a>`;
                            });
                        }
                    });
                    if (!hasResults) html = '<div class="search-empty">Tidak ada hasil untuk "' + query + '"</div>';
                    dropdown.innerHTML = html;
                    dropdown.classList.add('show');
                })
                .catch(() => {
                    dropdown.innerHTML = '<div class="search-empty">Gagal memuat hasil pencarian</div>';
                    dropdown.classList.add('show');
                });
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#globalSearchContainer')) {
                dropdown.classList.remove('show');
            }
        });

        input.addEventListener('focus', () => {
            if (dropdown.innerHTML.trim() && input.value.trim().length >= 2) {
                dropdown.classList.add('show');
            }
        });
    })();

    // ═══ INTERACTIVE 3D MOUSE PARALLAX & TILT SYSTEM ═══
    (function() {
        // 1. Mouse Parallax on 3D Background Sphere
        const sphereContainer = document.querySelector('.tech-3d-sphere-container');
        let mouseX = 0, mouseY = 0;
        let currentX = 0, currentY = 0;

        document.addEventListener('mousemove', (e) => {
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;
            mouseX = (e.clientX - windowWidth / 2) / (windowWidth / 2);
            mouseY = (e.clientY - windowHeight / 2) / (windowHeight / 2);
        });

        function animateParallax() {
            currentX += (mouseX - currentX) * 0.05;
            currentY += (mouseY - currentY) * 0.05;

            if (sphereContainer && document.body.dataset.theme === 'neon') {
                const moveX = currentX * 45;
                const moveY = currentY * 45;
                const rotX = currentY * -25;
                const rotY = currentX * 25;
                sphereContainer.style.transform = `translate3d(${moveX}px, ${moveY}px, 0) rotateX(${rotX}deg) rotateY(${rotY}deg)`;
            }
            requestAnimationFrame(animateParallax);
        }
        animateParallax();

        // 2. Interactive 3D Card Tilt Effect
        function initInteractive3DTilt() {
            const cards = document.querySelectorAll('.stat-card, .metric-card, .card, .card-custom, .filter-card');
            
            cards.forEach(card => {
                if (card.dataset.tiltInitialized) return;
                card.dataset.tiltInitialized = 'true';
                
                card.addEventListener('mousemove', (e) => {
                    if (document.body.dataset.theme !== 'neon') return;
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = ((y - centerY) / centerY) * -14;
                    const rotateY = ((x - centerX) / centerX) * 14;

                    card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateZ(14px) scale3d(1.02, 1.02, 1.02)`;
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px) scale3d(1, 1, 1)';
                });
            });
        }

        document.addEventListener('DOMContentLoaded', initInteractive3DTilt);
        const observer = new MutationObserver(initInteractive3DTilt);
        observer.observe(document.body, { childList: true, subtree: true });
    })();
</script>
@stack('scripts')
</body>
</html>
