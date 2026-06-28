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
            --danger: #ef4444;
            --success: #22c55e;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .18), transparent 32%),
                radial-gradient(circle at bottom left, rgba(255, 255, 255, .05), transparent 28%),
                var(--black);
            color: var(--text);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        a {
            text-decoration: none;
        }

        .login-shell {
            width: 100%;
            max-width: 1040px;
            display: grid;
            grid-template-columns: .95fr 1.05fr;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 90px rgba(0, 0, 0, .65);
        }

        .login-info {
            background:
                linear-gradient(180deg, rgba(37, 99, 235, .18), rgba(0, 0, 0, 0)),
                #000000;
            color: var(--white);
            padding: 42px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 570px;
            border-right: 1px solid var(--border);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--white);
        }

        .brand img {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--card);
        }

        .brand strong {
            display: block;
            font-size: 18px;
            font-weight: 900;
            color: var(--white);
            letter-spacing: -0.03em;
        }

        .brand span {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            margin-top: 2px;
        }

        .login-info h1 {
            margin: 46px 0 0;
            font-size: clamp(32px, 4vw, 44px);
            line-height: 1.08;
            font-weight: 900;
            color: var(--white);
            letter-spacing: -0.055em;
        }

        .login-info p {
            margin: 16px 0 0;
            color: var(--muted);
            line-height: 1.75;
            font-size: 15px;
            font-weight: 500;
        }

        .credential-box {
            margin-top: 30px;
            padding: 18px;
            border-radius: 18px;
            background: #101010;
            border: 1px solid var(--border);
        }

        .credential-box span {
            display: block;
            color: var(--muted);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 10px;
        }

        .credential-box code {
            display: block;
            color: var(--white);
            background: #000000;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 9px 10px;
            font-size: 13px;
            margin-top: 8px;
            white-space: normal;
        }

        .info-footer {
            color: var(--muted-soft);
            font-size: 12px;
            font-weight: 600;
        }

        .login-form {
            padding: 42px;
            background: var(--card);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            width: fit-content;
            color: var(--white);
            background: #000000;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 9px 13px;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 26px;
            transition: .2s ease;
        }

        .back-link:hover {
            color: var(--black);
            background: var(--white);
            border-color: var(--white);
        }

        .login-form h2 {
            margin: 0;
            color: var(--white);
            font-size: 32px;
            font-weight: 900;
            letter-spacing: -0.045em;
        }

        .login-form > p {
            margin: 9px 0 28px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.65;
            font-weight: 500;
        }

        label {
            color: var(--white);
            font-size: 13px;
            font-weight: 850;
            margin-bottom: 8px;
        }

        .form-control {
            min-height: 48px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #000000;
            color: var(--white);
            font-size: 14px;
            font-weight: 600;
        }

        .form-control::placeholder {
            color: var(--muted-soft);
            opacity: 1;
        }

        .form-control:focus {
            background: #000000;
            color: var(--white);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .18);
        }

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: #ffffff;
            -webkit-box-shadow: 0 0 0px 1000px #000000 inset;
            border: 1px solid var(--primary);
            transition: background-color 5000s ease-in-out 0s;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 24px;
        }

        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
            margin: 0;
        }

        .remember-label input {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
        }

        .btn-submit {
            min-height: 48px;
            border: 0;
            border-radius: 12px;
            background: var(--primary);
            color: var(--white);
            font-weight: 900;
            font-size: 14px;
            transition: .2s ease;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            color: var(--white);
            transform: translateY(-1px);
        }

        .alert-danger-custom {
            background: rgba(239, 68, 68, .16);
            border: 1px solid rgba(239, 68, 68, .45);
            color: #fecaca;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 700;
            padding: 13px 15px;
        }

        .security-note {
            margin-top: 18px;
            padding: 14px;
            border-radius: 14px;
            background: #000000;
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
            font-weight: 600;
        }

        .security-note i {
            color: var(--success);
        }

        @media (max-width: 850px) {
            .login-shell {
                grid-template-columns: 1fr;
            }

            .login-info {
                min-height: auto;
                border-right: 0;
                border-bottom: 1px solid var(--border);
            }
        }

        @media (max-width: 575px) {
            body {
                padding: 14px;
            }

            .login-info,
            .login-form {
                padding: 26px;
            }
        }
    </style>
</head>

<body>
    <div class="login-shell">
        <aside class="login-info">
            <div>
                <a class="brand" href="{{ route('landing') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Kost AJ Lanraki">
                    <div>
                        <strong>Kost AJ Lanraki</strong>
                        <span>Admin Management</span>
                    </div>
                </a>

                <h1>Masuk ke sistem pengelolaan kost.</h1>

                <p>
                    Gunakan akun admin untuk mengakses dashboard, data kamar, data penghuni, transaksi, laporan, backup data, dan pengaturan profil.
                </p>

            </div>

            <div class="info-footer">
                © {{ date('Y') }} Kost AJ Lanraki
            </div>
        </aside>

        <main class="login-form">
            <a href="{{ route('landing') }}" class="back-link">
                <i class="bi bi-arrow-left"></i>
                Kembali ke landing page
            </a>

            <h2>Login Admin</h2>
            <p>Silakan masuk untuk membuka panel manajemen.</p>

            @if($errors->any())
                <div class="alert-danger-custom mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <label for="email">Email Admin</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-control"
                        value="{{ old('email', 'admin@kostaj.com') }}"
                        placeholder="Masukkan email admin"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>
                </div>

                <button type="submit" class="btn btn-submit w-100">
                    <i class="bi bi-box-arrow-in-right me-1"></i>
                    Masuk Dashboard
                </button>
            </form>

            <div class="security-note">
                <i class="bi bi-shield-check me-1"></i>
                Halaman ini khusus admin. Data operasional hanya bisa dibuka setelah login.
            </div>
        </main>
    </div>
</body>
</html>