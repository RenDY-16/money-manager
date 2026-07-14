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
            --danger: #ef4444;
            --success: #10b981;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 12px -2px rgba(9, 9, 11, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .login-shell {
            width: 100%;
            max-width: 1040px;
            display: grid;
            grid-template-columns: .95fr 1.05fr;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .mac-dots {
            display: flex;
            gap: 6px;
            align-items: center;
            margin-bottom: 32px;
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

        .login-info {
            background: var(--card-light);
            color: var(--text);
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
            color: var(--text);
        }

        .brand img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--white);
        }

        .brand strong {
            display: block;
            font-size: 17px;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
        }

        .brand span {
            display: block;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            margin-top: 1px;
        }

        .login-info h1 {
            margin: 46px 0 0;
            font-size: clamp(30px, 3.5vw, 40px);
            line-height: 1.15;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
        }

        .login-info p {
            margin: 16px 0 0;
            color: var(--muted);
            line-height: 1.65;
            font-size: 14px;
            font-weight: 400;
        }

        .credential-box {
            margin-top: 30px;
            padding: 18px;
            border-radius: 8px;
            background: var(--white);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }

        .credential-box span {
            display: block;
            color: var(--muted-soft);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 8px;
        }

        .credential-box code {
            display: block;
            color: var(--text);
            background: var(--card-light);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 13px;
            margin-top: 6px;
            white-space: normal;
        }

        .info-footer {
            color: var(--muted-soft);
            font-size: 12px;
            font-weight: 500;
        }

        .login-form {
            padding: 42px;
            background: var(--white);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            width: fit-content;
            color: var(--text);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 26px;
            transition: all .2s ease;
            box-shadow: var(--shadow-sm);
        }

        .back-link:hover {
            background: var(--card-light);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .login-form h2 {
            margin: 0;
            color: var(--text);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .login-form > p {
            margin: 8px 0 28px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
            font-weight: 400;
        }

        label {
            color: var(--text);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 7px;
            display: block;
        }

        .form-control {
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
            box-shadow: var(--shadow-sm);
        }

        .form-control::placeholder {
            color: var(--muted-soft);
            opacity: 1;
        }

        .form-control:focus {
            background: var(--white);
            color: var(--text);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(9, 9, 11, 0.05);
        }

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text);
            -webkit-box-shadow: 0 0 0px 1000px var(--white) inset;
            border: 1px solid var(--border);
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
            font-weight: 600;
            margin: 0;
            cursor: pointer;
        }

        .remember-label input {
            width: 15px;
            height: 15px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .btn-submit {
            min-height: 44px;
            border: 0;
            border-radius: 8px;
            background: var(--primary);
            color: var(--white);
            font-weight: 700;
            font-size: 14px;
            transition: all .2s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            color: var(--white);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .alert-danger-custom {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 12px 14px;
        }

        .security-note {
            margin-top: 24px;
            padding: 14px;
            border-radius: 8px;
            background: var(--card-light);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
            font-weight: 500;
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
                gap: 32px;
            }
        }

        @media (max-width: 575px) {
            body {
                padding: 14px;
            }

            .login-info,
            .login-form {
                padding: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-shell">
        <aside class="login-info">
            <div>
                <div class="mac-dots">
                    <span class="dot-mac red"></span>
                    <span class="dot-mac yellow"></span>
                    <span class="dot-mac green"></span>
                </div>
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