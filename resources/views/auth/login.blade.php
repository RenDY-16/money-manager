<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Kost AJ Lanraki</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary:#00288e; --secondary:#0058be; --surface:#f8f9fa; --border:#e5e7eb; --muted:#5b6072; }
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #edf3ff 0%, #f8f9fa 100%);
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .login-shell {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: .95fr 1.05fr;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 26px;
            overflow: hidden;
            box-shadow: 0 24px 70px rgba(0, 40, 142, .15);
        }
        .login-info {
            background: var(--primary);
            color: #fff;
            padding: 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 560px;
        }
        .brand { display: flex; align-items: center; gap: 12px; }
        .brand img { width: 48px; height: 48px; border-radius: 14px; object-fit: cover; background: #fff; }
        .brand strong { display: block; font-size: 18px; font-weight: 900; }
        .brand span { display: block; color: #d8e2ff; font-size: 12px; font-weight: 600; }
        .login-info h1 { margin: 42px 0 0; font-size: 38px; line-height: 1.08; font-weight: 900; letter-spacing: -0.045em; }
        .login-info p { margin: 16px 0 0; color: #d8e2ff; line-height: 1.7; font-weight: 500; }
        .credential-box { margin-top: 28px; padding: 18px; border-radius: 16px; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.18); }
        .credential-box span { display: block; color: #d8e2ff; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; margin-bottom: 8px; }
        .credential-box code { display: block; color: #fff; font-size: 13px; margin-top: 4px; }
        .login-form { padding: 42px; display: flex; flex-direction: column; justify-content: center; }
        .login-form h2 { margin: 0; color: var(--primary); font-size: 30px; font-weight: 900; letter-spacing: -0.04em; }
        .login-form > p { margin: 8px 0 26px; color: var(--muted); font-size: 14px; }
        label { color: #1f2937; font-size: 13px; font-weight: 800; margin-bottom: 8px; }
        .form-control { min-height: 46px; border-radius: 10px; border-color: var(--border); font-size: 14px; }
        .form-control:focus { border-color: var(--secondary); box-shadow: 0 0 0 3px rgba(0,88,190,.12); }
        .btn-submit { min-height: 46px; border:0; border-radius: 10px; background: var(--primary); color:#fff; font-weight:900; }
        .btn-submit:hover { background:#001f6c; color:#fff; }
        .back-link { color: var(--primary); text-decoration: none; font-weight: 800; font-size: 13px; }
        @media (max-width: 850px) { .login-shell { grid-template-columns: 1fr; } .login-info { min-height: auto; } }
    </style>
</head>
<body>
    <div class="login-shell">
        <aside class="login-info">
            <div>
                <div class="brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
                    <div>
                        <strong>Kost AJ Lanraki</strong>
                        <span>Admin Management</span>
                    </div>
                </div>
                <h1>Masuk ke sistem pengelolaan kost.</h1>
                <p>Gunakan akun admin untuk mengakses dashboard, data kamar, penghuni, pemasukan, pengeluaran, dan laporan keuangan.</p>
            </div>
            <small>© {{ date('Y') }} Kost AJ Lanraki</small>
        </aside>
        <main class="login-form">
            <a href="{{ route('landing') }}" class="back-link mb-4"><i class="bi bi-arrow-left"></i> Kembali ke landing page</a>
            <h2>Login Admin</h2>
            <p>Silakan masuk untuk membuka panel manajemen.</p>

            @if($errors->any())
                <div class="alert alert-danger border-0">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email', 'admin@kostaj.com') }}" autocomplete="email" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control" placeholder="Masukkan password" autocomplete="current-password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <label class="d-flex align-items-center gap-2 mb-0 fw-semibold text-muted">
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" class="btn btn-submit w-100">Masuk Dashboard</button>
            </form>
        </main>
    </div>
</body>
</html>
