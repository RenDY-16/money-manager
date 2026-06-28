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
            --primary: #00288e;
            --secondary: #0058be;
            --soft: #d8e2ff;
            --surface: #f8f9fa;
            --text: #191c1d;
            --muted: #5b6072;
            --border: #e5e7eb;
            --success: #16a34a;
            --danger: #dc2626;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #edf3ff 100%);
            color: var(--text);
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
        .brand { display: flex; align-items: center; gap: 12px; color: var(--primary); text-decoration: none; }
        .brand img { width: 44px; height: 44px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border); }
        .brand strong { display: block; font-size: 17px; font-weight: 900; letter-spacing: -0.03em; }
        .brand span { display: block; color: var(--muted); font-size: 12px; font-weight: 600; }
        .btn-login, .btn-dashboard {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 11px 18px;
            border-radius: 8px;
            border: 0;
            color: #fff;
            background: var(--primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
        }
        .btn-login:hover, .btn-dashboard:hover { background: #001f6c; color: #fff; }
        .hero {
            max-width: 1180px;
            margin: 0 auto;
            padding: 52px 20px 72px;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(320px, .95fr);
            gap: 38px;
            align-items: center;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #fff;
            color: var(--secondary);
            border: 1px solid var(--border);
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            color: var(--primary);
            font-size: clamp(36px, 5vw, 64px);
            line-height: 1.02;
            font-weight: 900;
            letter-spacing: -0.055em;
        }
        .hero p {
            margin: 18px 0 0;
            max-width: 610px;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.7;
            font-weight: 500;
        }
        .hero-actions { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 28px; }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 11px 18px;
            border-radius: 8px;
            border: 1px solid var(--border);
            color: var(--primary);
            background: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
        }
        .hero-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 26px;
            padding: 24px;
            box-shadow: 0 22px 60px rgba(0, 40, 142, .13);
        }
        .screen-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }
        .screen-title strong { display: block; color: var(--primary); font-size: 18px; font-weight: 900; }
        .screen-title span { color: var(--muted); font-size: 12px; font-weight: 600; }
        .mini-stat-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-top: 18px; }
        .mini-stat {
            padding: 16px;
            border-radius: 16px;
            background: var(--surface);
            border: 1px solid var(--border);
        }
        .mini-stat .label { color: var(--muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
        .mini-stat .value { margin-top: 6px; color: var(--primary); font-size: 23px; font-weight: 900; }
        .feature-grid {
            max-width: 1180px;
            margin: 0 auto;
            padding: 0 20px 70px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }
        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 20px;
        }
        .feature-card i { color: var(--secondary); font-size: 24px; }
        .feature-card h6 { margin: 14px 0 6px; color: var(--primary); font-weight: 900; }
        .feature-card p { margin: 0; color: var(--muted); font-size: 13px; line-height: 1.6; }
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; padding-top: 28px; }
            .feature-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 575px) {
            .landing-nav { align-items: flex-start; }
            .feature-grid, .mini-stat-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav class="landing-nav">
        <a class="brand" href="{{ route('landing') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
            <div>
                <strong>Kost AJ Lanraki</strong>
                <span>Sistem Manajemen Kost</span>
            </div>
        </a>
        @auth
            <a href="{{ route('dashboard') }}" class="btn-dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-box-arrow-in-right"></i> Login Admin</a>
        @endauth
    </nav>

    @if(session('success'))
        <div class="container" style="max-width:1180px;">
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        </div>
    @endif

    <section class="hero">
        <div>
            <div class="hero-badge"><i class="bi bi-shield-check"></i> Admin panel untuk operasional kost</div>
            <h1>Kelola kamar, penghuni, dan keuangan kost dalam satu sistem.</h1>
            <p>Kost AJ Lanraki membantu admin mencatat data kamar, penghuni aktif, pemasukan, pengeluaran, dan laporan keuangan secara rapi.</p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-login"><i class="bi bi-grid-1x2-fill"></i> Masuk Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login"><i class="bi bi-lock-fill"></i> Login Admin</a>
                @endauth
                <a href="#fitur" class="btn-outline">Lihat Fitur</a>
            </div>
        </div>
        <div class="hero-card">
            <div class="screen-top">
                <div class="screen-title">
                    <strong>Dashboard Preview</strong>
                    <span>Ringkasan operasional kost</span>
                </div>
                <span class="badge text-bg-primary rounded-pill">Live</span>
            </div>
            <div class="mini-stat-grid">
                <div class="mini-stat">
                    <div class="label">Kamar</div>
                    <div class="value">18</div>
                </div>
                <div class="mini-stat">
                    <div class="label">Penghuni</div>
                    <div class="value">15</div>
                </div>
                <div class="mini-stat">
                    <div class="label">Status Kamar</div>
                    <div class="value">Aktif</div>
                </div>
                <div class="mini-stat">
                    <div class="label">Laporan</div>
                    <div class="value">Siap</div>
                </div>
            </div>
        </div>
    </section>

    <section class="feature-grid" id="fitur">
        <div class="feature-card">
            <i class="bi bi-door-open"></i>
            <h6>Data Kamar</h6>
            <p>Kelola nomor kamar, tipe kamar, harga sewa, dan status kamar.</p>
        </div>
        <div class="feature-card">
            <i class="bi bi-people"></i>
            <h6>Data Penghuni</h6>
            <p>Catat penghuni aktif, nomor HP, kamar, dan tanggal masuk.</p>
        </div>
        <div class="feature-card">
            <i class="bi bi-cash-stack"></i>
            <h6>Keuangan</h6>
            <p>Input pemasukan dan pengeluaran harian dengan riwayat transaksi.</p>
        </div>
        <div class="feature-card">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <h6>Laporan</h6>
            <p>Filter laporan berdasarkan bulan, tahun, dan jenis transaksi.</p>
        </div>
    </section>
</body>
</html>
