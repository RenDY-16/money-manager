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
            --black: #000000;
            --black-soft: #070707;
            --card: #101010;
            --card-light: #151515;
            --border: #2a2a2a;
            --white: #ffffff;
            --text: #f8fafc;
            --muted: #d1d5db;
            --muted-soft: #a1a1aa;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success: #22c55e;
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
            background: var(--black);
            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        .page-wrapper {
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .18), transparent 32%),
                radial-gradient(circle at top left, rgba(255, 255, 255, .05), transparent 28%),
                var(--black);
        }

        .landing-nav {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--white);
        }

        .brand:hover {
            color: var(--white);
        }

        .brand img {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--card);
        }

        .brand strong {
            display: block;
            font-size: 17px;
            font-weight: 900;
            letter-spacing: -0.03em;
            color: var(--white);
        }

        .brand span {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
            margin-top: 2px;
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
            min-height: 44px;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 800;
            transition: .2s ease;
        }

        .btn-main {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: var(--white);
        }

        .btn-main:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: var(--white);
            transform: translateY(-1px);
        }

        .btn-outline-light-custom {
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--white);
        }

        .btn-outline-light-custom:hover {
            border-color: var(--white);
            background: var(--white);
            color: var(--black);
            transform: translateY(-1px);
        }

        .hero {
            max-width: 1180px;
            margin: 0 auto;
            padding: 54px 20px 76px;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(320px, .95fr);
            gap: 40px;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            background: var(--card);
            color: var(--white);
            border: 1px solid var(--border);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .hero-badge i {
            color: var(--success);
        }

        h1 {
            margin: 0;
            color: var(--white);
            font-size: clamp(36px, 5vw, 64px);
            line-height: 1.04;
            font-weight: 900;
            letter-spacing: -0.055em;
        }

        .hero-text {
            margin: 20px 0 0;
            max-width: 640px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.75;
            font-weight: 500;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .hero-note {
            margin-top: 18px;
            color: var(--muted-soft);
            font-size: 13px;
            font-weight: 600;
        }

        .hero-card {
            background: linear-gradient(180deg, var(--card-light), var(--card));
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 24px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .55);
        }

        .screen-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .screen-title strong {
            display: block;
            color: var(--white);
            font-size: 18px;
            font-weight: 900;
        }

        .screen-title span {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            color: var(--white);
            background: rgba(34, 197, 94, .16);
            border: 1px solid rgba(34, 197, 94, .45);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
        }

        .mini-stat-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .mini-stat {
            padding: 17px;
            border-radius: 18px;
            background: #000000;
            border: 1px solid var(--border);
        }

        .mini-stat .label {
            color: var(--muted);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .mini-stat .value {
            margin-top: 7px;
            color: var(--white);
            font-size: 24px;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .preview-list {
            margin-top: 18px;
            display: grid;
            gap: 10px;
        }

        .preview-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 14px;
            border-radius: 14px;
            background: #050505;
            border: 1px solid var(--border);
        }

        .preview-item span {
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
        }

        .preview-item small {
            color: var(--muted);
            font-size: 12px;
            font-weight: 600;
        }

        .section-wrap {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 20px 74px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 20px;
        }

        .section-heading h2 {
            margin: 0;
            color: var(--white);
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -0.04em;
        }

        .section-heading p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            max-width: 560px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .feature-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 21px;
            min-height: 180px;
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: rgba(37, 99, 235, .16);
            color: #93c5fd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            border: 1px solid rgba(147, 197, 253, .25);
        }

        .feature-card h6 {
            margin: 16px 0 7px;
            color: var(--white);
            font-size: 15px;
            font-weight: 900;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.65;
            font-weight: 500;
        }

        .footer {
            border-top: 1px solid var(--border);
            background: var(--black);
        }

        .footer-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 22px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
        }

        .alert-custom {
            max-width: 1180px;
            margin: 0 auto 10px;
            padding: 0 20px;
        }

        .alert-custom .alert {
            color: var(--white);
            background: rgba(34, 197, 94, .18);
            border: 1px solid rgba(34, 197, 94, .45);
            border-radius: 14px;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
                padding-top: 28px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575px) {
            .landing-nav {
                align-items: flex-start;
                flex-direction: column;
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
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <nav class="landing-nav">
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
                    <div class="screen-title">
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

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-palette"></i>
                    </div>
                    <h6>Tema Tampilan</h6>
                    <p>Gunakan tema gelap solid agar tampilan lebih fokus dan tetap mudah dibaca.</p>
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