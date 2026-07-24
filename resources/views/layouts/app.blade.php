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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
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

<!-- Photorealistic Three.js WebGL 3D Canvas Background -->
<div class="three-canvas-wrapper" id="threeCanvasWrapper">
    <canvas id="three-bg-canvas"></canvas>
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

    // ═══ PHOTOREALISTIC THREE.JS WEBGL 3D CANVAS & LIGHTING ═══
    (function() {
        if (typeof THREE === 'undefined') return;
        const canvas = document.getElementById('three-bg-canvas');
        if (!canvas) return;

        // === RENDERER ===
        const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true, powerPreference: 'high-performance' });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1.4;
        renderer.outputEncoding = THREE.sRGBEncoding;

        const scene = new THREE.Scene();
        scene.fog = new THREE.FogExp2(0x030712, 0.035);

        const camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 0, 12);

        // === MAIN 3D GROUP ===
        const mainGroup = new THREE.Group();
        scene.add(mainGroup);

        // -- OUTER WIREFRAME ICOSAHEDRON --
        const icoGeo = new THREE.IcosahedronGeometry(2.8, 3);
        const icoMat = new THREE.MeshStandardMaterial({
            color: 0x00f0ff, wireframe: true, transparent: true, opacity: 0.18,
            roughness: 0.05, metalness: 1.0
        });
        const icoMesh = new THREE.Mesh(icoGeo, icoMat);
        mainGroup.add(icoMesh);

        // -- SECOND OUTER SHELL (faint dodecahedron) --
        const shellGeo = new THREE.DodecahedronGeometry(3.4, 1);
        const shellMat = new THREE.MeshStandardMaterial({
            color: 0x9d4edd, wireframe: true, transparent: true, opacity: 0.08,
            roughness: 0.1, metalness: 0.9
        });
        const shellMesh = new THREE.Mesh(shellGeo, shellMat);
        mainGroup.add(shellMesh);

        // -- INNER SPECULAR GLASS CORE --
        const coreGeo = new THREE.SphereGeometry(1.85, 128, 128);
        const coreMat = new THREE.MeshPhysicalMaterial({
            color: 0x0a1428, emissive: 0x0d0620, emissiveIntensity: 0.35,
            roughness: 0.08, metalness: 0.92,
            clearcoat: 1.0, clearcoatRoughness: 0.05,
            reflectivity: 1.0, transparent: true, opacity: 0.9,
            envMapIntensity: 2.0
        });
        const coreMesh = new THREE.Mesh(coreGeo, coreMat);
        mainGroup.add(coreMesh);

        // -- INNER GLOW SHELL (emissive fresnel-like) --
        const glowGeo = new THREE.SphereGeometry(2.05, 64, 64);
        const glowMat = new THREE.MeshBasicMaterial({
            color: 0x00f0ff, transparent: true, opacity: 0.06,
            blending: THREE.AdditiveBlending, side: THREE.BackSide
        });
        const glowMesh = new THREE.Mesh(glowGeo, glowMat);
        mainGroup.add(glowMesh);

        // -- LARGE OUTER GLOW AURA --
        const auraGeo = new THREE.SphereGeometry(4.5, 32, 32);
        const auraMat = new THREE.MeshBasicMaterial({
            color: 0x00f0ff, transparent: true, opacity: 0.025,
            blending: THREE.AdditiveBlending, side: THREE.BackSide
        });
        const auraMesh = new THREE.Mesh(auraGeo, auraMat);
        mainGroup.add(auraMesh);

        // === ORBITAL TORUS RINGS ===
        function createRing(radius, tube, color, emissiveIntensity) {
            const geo = new THREE.TorusGeometry(radius, tube, 24, 200);
            const mat = new THREE.MeshStandardMaterial({
                color, emissive: color, emissiveIntensity,
                roughness: 0.1, metalness: 0.8, transparent: true, opacity: 0.7
            });
            return new THREE.Mesh(geo, mat);
        }
        const ring1 = createRing(3.8, 0.018, 0x00f0ff, 1.2);
        ring1.rotation.x = 1.1;
        ring1.rotation.y = 0.3;
        mainGroup.add(ring1);

        const ring2 = createRing(4.5, 0.012, 0x9d4edd, 1.0);
        ring2.rotation.x = -0.7;
        ring2.rotation.y = 0.8;
        mainGroup.add(ring2);

        const ring3 = createRing(3.2, 0.008, 0xe0aaff, 0.8);
        ring3.rotation.x = 0.5;
        ring3.rotation.z = 1.2;
        mainGroup.add(ring3);

        // === POSITION GROUP ===
        function updateGroupPosition() {
            mainGroup.position.set(window.innerWidth > 992 ? 3.2 : 0, window.innerWidth > 992 ? 0.3 : 1.5, window.innerWidth > 992 ? 0 : -3);
        }
        updateGroupPosition();

        // === MULTI-DEPTH PARTICLE SYSTEMS ===
        function createParticles(count, spread, size, color, opacity) {
            const pos = new Float32Array(count * 3);
            for (let i = 0; i < count * 3; i++) pos[i] = (Math.random() - 0.5) * spread;
            const geo = new THREE.BufferGeometry();
            geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
            const mat = new THREE.PointsMaterial({
                size, color, transparent: true, opacity,
                blending: THREE.AdditiveBlending, depthWrite: false
            });
            return new THREE.Points(geo, mat);
        }
        const nearParticles = createParticles(300, 25, 0.04, 0x00f0ff, 0.7);
        const farParticles = createParticles(600, 50, 0.025, 0x9d4edd, 0.3);
        const dustParticles = createParticles(200, 15, 0.06, 0xffffff, 0.15);
        scene.add(nearParticles, farParticles, dustParticles);

        // === DYNAMIC LIGHTING RIG ===
        scene.add(new THREE.AmbientLight(0x080c18, 3.0));

        const cyanKey = new THREE.PointLight(0x00f0ff, 6, 40);
        cyanKey.position.set(5, 5, 8);
        scene.add(cyanKey);

        const purpleFill = new THREE.PointLight(0x9d4edd, 5, 35);
        purpleFill.position.set(-5, -4, 6);
        scene.add(purpleFill);

        const whiteRim = new THREE.PointLight(0xffffff, 2.5, 25);
        whiteRim.position.set(0, 6, -4);
        scene.add(whiteRim);

        const mouseLight = new THREE.PointLight(0xe0f8ff, 3.0, 20);
        scene.add(mouseLight);

        // Orbiting accent light
        const orbitLight = new THREE.PointLight(0x00f0ff, 2.0, 15);
        scene.add(orbitLight);

        // === MOUSE INTERACTION (SPRING PHYSICS) ===
        let mouseX = 0, mouseY = 0, springX = 0, springY = 0, velX = 0, velY = 0;
        const stiffness = 0.015, damping = 0.88;

        window.addEventListener('mousemove', (e) => {
            mouseX = (e.clientX / window.innerWidth - 0.5) * 2;
            mouseY = (e.clientY / window.innerHeight - 0.5) * 2;
        });

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
            updateGroupPosition();
        });

        // === ANIMATION LOOP ===
        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);

            if (document.body.dataset.theme !== 'neon') {
                canvas.style.display = 'none';
                return;
            }
            canvas.style.display = 'block';

            const t = clock.getElapsedTime();

            // Spring physics mouse damping
            velX += (mouseX - springX) * stiffness;
            velY += (mouseY - springY) * stiffness;
            velX *= damping;
            velY *= damping;
            springX += velX;
            springY += velY;

            // Mouse light follows cursor
            mouseLight.position.set(springX * 8, -springY * 8, 5);

            // Orbiting accent light
            orbitLight.position.set(Math.cos(t * 0.5) * 6, Math.sin(t * 0.7) * 4, Math.sin(t * 0.3) * 5 + 3);

            // Object self-rotations
            icoMesh.rotation.y = t * 0.12;
            icoMesh.rotation.x = t * 0.08;
            shellMesh.rotation.y = -t * 0.06;
            shellMesh.rotation.z = t * 0.04;
            coreMesh.rotation.y = -t * 0.08;
            glowMesh.rotation.y = t * 0.05;

            ring1.rotation.z = t * 0.18;
            ring2.rotation.z = -t * 0.12;
            ring3.rotation.z = t * 0.25;

            // Breathing scale pulse
            const breathe = 1 + Math.sin(t * 0.8) * 0.02;
            mainGroup.scale.setScalar(breathe);

            // Spring parallax group rotation
            mainGroup.rotation.y = springX * 0.4;
            mainGroup.rotation.x = -springY * 0.35;

            // Particle drift
            nearParticles.rotation.y = t * 0.025;
            nearParticles.rotation.x = t * 0.01;
            farParticles.rotation.y = -t * 0.012;
            dustParticles.rotation.z = t * 0.008;

            // Glow intensity pulse
            glowMat.opacity = 0.04 + Math.sin(t * 1.2) * 0.03;
            auraMat.opacity = 0.02 + Math.sin(t * 0.6) * 0.015;

            renderer.render(scene, camera);
        }
        animate();
    })();

    // ═══ REALISTIC SUBTLE 3D CARD TILT ═══
    (function() {
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
                    
                    const rotateX = ((y - centerY) / centerY) * -7;
                    const rotateY = ((x - centerX) / centerX) * 7;

                    card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateZ(8px)`;
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
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
