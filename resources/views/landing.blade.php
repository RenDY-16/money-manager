<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kost AJ Lanraki | Sistem Manajemen Kost</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #fbfbfb;
            --white: #ffffff;
            --card: #ffffff;
            --card-light: #fafafa;
            --border: #e4e4e7;
            --text: #1a1a1a;
            --muted: #52525b;
            --muted-soft: #71717a;
            --primary: #09090b;
            --primary-hover: #27272a;
            --success: #10b981;
            --success-soft: #ecfdf5;
            --shadow-sm: 0 1px 2px 0 rgba(9, 9, 11, 0.01), 0 2px 6px 0 rgba(9, 9, 11, 0.02);
            --shadow-md: 0 12px 34px -10px rgba(9, 9, 11, 0.06), 0 2px 8px -2px rgba(9, 9, 11, 0.02);
            --shadow-hover: 0 30px 60px -15px rgba(9, 9, 11, 0.08), 0 12px 24px -10px rgba(9, 9, 11, 0.03);
            --transition-bezier: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .page-wrapper {
            min-height: 100vh;
            background: var(--bg);
        }

        .landing-nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(251, 251, 251, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 16px 24px;
            width: 100%;
            transition: var(--transition-bezier);
        }

        .nav-container {
            max-width: 1180px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            width: 100%;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
        }

        .brand img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--white);
        }

        .brand strong {
            display: block;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .brand span {
            display: block;
            color: var(--muted);
            font-size: 11px;
            font-weight: 500;
            margin-top: 1px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-main,
        .btn-outline-light-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 40px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            transition: all .2s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-main {
            border: 0;
            background: var(--primary);
            color: var(--white);
        }

        .btn-main:hover {
            background: var(--primary-hover);
            color: var(--white);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-light-custom {
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--text);
        }

        .btn-outline-light-custom:hover {
            background: var(--card-light);
            color: var(--text);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .hero {
            max-width: 1180px;
            margin: 0 auto;
            padding: 80px 20px 100px;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(320px, 0.9fr);
            gap: 60px;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--white);
            color: var(--muted);
            border: 1px solid var(--border);
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .hero-badge i {
            color: var(--success);
        }

        h1 {
            margin: 0;
            color: var(--text);
            font-size: clamp(34px, 4.5vw, 56px);
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .hero-text {
            margin: 24px 0 0;
            max-width: 600px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.7;
            font-weight: 400;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 32px;
        }

        .hero-note {
            margin-top: 20px;
            color: var(--muted-soft);
            font-size: 12px;
            font-weight: 500;
        }

        .hero-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 28px;
            box-shadow: var(--shadow-md);
        }

        .mac-dots {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-right: 12px;
        }
        .dot-mac {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .dot-mac.red { background: #ff5f56; }
        .dot-mac.yellow { background: #ffbd2e; }
        .dot-mac.green { background: #27c93f; }

        .screen-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        .screen-title strong {
            display: block;
            color: var(--text);
            font-size: 16px;
            font-weight: 800;
        }

        .screen-title span {
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            color: #065f46;
            background: var(--success-soft);
            border: 1px solid #a7f3d0;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--success);
        }

        .mini-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 20px;
        }

        .mini-stat {
            padding: 16px;
            border-radius: 8px;
            background: var(--card-light);
            border: 1px solid var(--border);
        }

        .mini-stat .label {
            color: var(--muted-soft);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .mini-stat .value {
            margin-top: 4px;
            color: var(--text);
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .preview-list {
            margin-top: 20px;
            display: grid;
            gap: 8px;
        }

        .preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 8px;
            background: var(--white);
            border: 1px solid var(--border);
        }

        .preview-item span {
            color: var(--text);
            font-size: 13px;
            font-weight: 600;
        }

        .preview-item small {
            color: var(--muted-soft);
            font-size: 12px;
            font-weight: 500;
        }

        .section-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 20px 100px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 40px;
            border-top: 1px solid var(--border);
            padding-top: 60px;
        }

        .section-heading h2 {
            margin: 0;
            color: var(--text);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .section-heading p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            max-width: 560px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            min-height: 180px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-bezier);
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: var(--card-light);
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 1px solid var(--border);
        }

        .feature-card h6 {
            margin: 16px 0 6px;
            color: var(--text);
            font-size: 15px;
            font-weight: 800;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
            font-weight: 400;
        }

        .footer {
            border-top: 1px solid var(--border);
            background: var(--white);
        }

        .footer-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            color: var(--muted-soft);
            font-size: 12px;
            font-weight: 500;
        }

        .alert-custom {
            max-width: 1180px;
            margin: 0 auto 10px;
            padding: 0 20px;
        }

        .alert-custom .alert {
            color: #065f46;
            background: var(--success-soft);
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
                padding-top: 40px;
                gap: 40px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575px) {
            .landing-nav {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                padding: 24px 20px;
            }

            .nav-actions {
                width: 100%;
            }

            .nav-actions a {
                width: 100%;
            }

            .mini-stat-grid,
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .footer-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <nav class="landing-nav">
            <div class="nav-container">
                <a class="brand" href="{{ route('landing') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
                    <div>
                        <strong>Kost AJ Lanraki</strong>
                        <span>Management v1.0</span>
                    </div>
                </a>

                <div class="nav-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-main">
                            <i class="bi bi-speedometer2"></i>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-main">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login Admin
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        @if(session('success'))
            <div class="alert-custom">
                <div class="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <section class="hero">
            <div>
                <div class="hero-badge">
                    <i class="bi bi-shield-check"></i>
                    Sistem administrasi kost berbasis web
                </div>

                <h1>Kelola operasional Kost AJ Lanraki dengan tampilan yang rapi.</h1>

                <p class="hero-text">
                    Sistem ini membantu admin mengelola data kamar, penghuni, transaksi, laporan keuangan, pengingat pembayaran, backup data, dan profil admin dalam satu dashboard.
                </p>

                <div class="hero-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-main">
                            <i class="bi bi-grid-1x2-fill"></i>
                            Masuk Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-main">
                            <i class="bi bi-lock-fill"></i>
                            Login Admin
                        </a>
                    @endauth

                    <a href="#fitur" class="btn-outline-light-custom">
                        <i class="bi bi-arrow-down-circle"></i>
                        Lihat Fitur
                    </a>
                </div>

                <div class="hero-note">
                    Tidak menampilkan data sensitif pada halaman publik.
                </div>
            </div>

            <div class="hero-card">
                <div class="screen-top">
                    <div class="mac-dots">
                        <span class="dot-mac red"></span>
                        <span class="dot-mac yellow"></span>
                        <span class="dot-mac green"></span>
                    </div>
                    <div class="screen-title" style="flex: 1; margin-left: 12px;">
                        <strong>Dashboard Preview</strong>
                        <span>Ringkasan fitur administrasi kost</span>
                    </div>

                    <div class="status-pill">
                        <span class="status-dot"></span>
                        Online
                    </div>
                </div>

                <div class="mini-stat-grid">
                    <div class="mini-stat">
                        <div class="label">Kamar</div>
                        <div class="value">Data</div>
                    </div>

                    <div class="mini-stat">
                        <div class="label">Penghuni</div>
                        <div class="value">Aktif</div>
                    </div>

                    <div class="mini-stat">
                        <div class="label">Status Bayar</div>
                        <div class="value">Lunas</div>
                    </div>

                    <div class="mini-stat">
                        <div class="label">Backup</div>
                        <div class="value">Siap</div>
                    </div>
                </div>

                <div class="preview-list">
                    <div class="preview-item">
                        <span><i class="bi bi-door-open me-2"></i> Data Kamar</span>
                        <small>Terstruktur</small>
                    </div>

                    <div class="preview-item">
                        <span><i class="bi bi-people me-2"></i> Data Penghuni</span>
                        <small>Terkelola</small>
                    </div>

                    <div class="preview-item">
                        <span><i class="bi bi-file-earmark-spreadsheet me-2"></i> Laporan Excel</span>
                        <small>Tersedia</small>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-wrap" id="fitur">
            <div class="section-heading">
                <div>
                    <h2>Fitur Utama</h2>
                    <p>
                        Fitur dibuat untuk membantu admin mencatat, memantau, dan mengelola aktivitas kost dengan alur yang sederhana.
                    </p>
                </div>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-door-open"></i>
                    </div>
                    <h6>Data Kamar</h6>
                    <p>Kelola nomor kamar, tipe kamar, harga sewa, dan status kamar secara rapi.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h6>Data Penghuni</h6>
                    <p>Catat penghuni aktif, nomor HP, kamar, tanggal masuk, dan status pembayaran.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h6>Transaksi</h6>
                    <p>Input pemasukan, pembayaran kost, pemasukan lain, dan pengeluaran harian.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                    <h6>Laporan</h6>
                    <p>Filter laporan berdasarkan bulan, tahun, dan jenis transaksi lalu cetak atau export Excel.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h6>Reminder WhatsApp</h6>
                    <p>Buat template chat untuk mengingatkan penghuni yang belum membayar.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cloud-arrow-down"></i>
                    </div>
                    <h6>Backup Data</h6>
                    <p>Unduh backup data agar admin memiliki cadangan data operasional.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <h6>Profil Admin</h6>
                    <p>Admin dapat mengatur nama, email, password, dan foto profil.</p>
                </div>

                
            </div>
        </section>
    </div>

    <footer class="footer">
        <div class="footer-inner">
            <div>© {{ date('Y') }} Kost AJ Lanraki</div>
            <div>Sistem Manajemen Kost</div>
        </div>
    </footer>
</body>
</html>