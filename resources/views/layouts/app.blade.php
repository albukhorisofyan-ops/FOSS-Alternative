<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AlterFOSS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* ── Design System ─────────────────────────────────── */
        :root {
            --af-primary: #2563eb;
            --af-primary-dark: #1d4ed8;
            --af-primary-light: #dbeafe;
            --af-accent: #10b981;
            --af-accent-dark: #059669;
            --af-danger: #ef4444;
            --af-warning: #f59e0b;
            --af-surface: #ffffff;
            --af-surface-alt: #f8fafc;
            --af-border: #e2e8f0;
            --af-text: #0f172a;
            --af-text-secondary: #64748b;
            --af-radius: 0.75rem;
            --af-radius-lg: 1rem;
            --af-shadow-sm: 0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
            --af-shadow: 0 4px 6px -1px rgba(15,23,42,.07), 0 2px 4px -2px rgba(15,23,42,.05);
            --af-shadow-lg: 0 10px 25px -3px rgba(15,23,42,.08), 0 4px 6px -4px rgba(15,23,42,.04);
            --af-transition: 0.2s cubic-bezier(.4,0,.2,1);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--af-text);
            background: var(--af-surface-alt);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Navbar ────────────────────────────────────────── */
        .af-navbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-bottom: 1px solid rgba(255,255,255,.06);
            padding: .75rem 0;
        }
        .af-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.3rem;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .af-navbar .nav-link {
            color: rgba(255,255,255,.7);
            font-weight: 500;
            font-size: .9rem;
            padding: .5rem .85rem;
            border-radius: .5rem;
            transition: var(--af-transition);
        }
        .af-navbar .nav-link:hover,
        .af-navbar .nav-link:focus {
            color: #fff;
            background: rgba(255,255,255,.08);
        }
        .af-navbar .nav-link.active {
            color: #fff;
            background: rgba(99,102,241,.3);
        }
        .af-navbar .btn-nav-login {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            color: #fff;
            font-weight: 600;
            font-size: .85rem;
            padding: .4rem 1rem;
            border-radius: 2rem;
            transition: var(--af-transition);
        }
        .af-navbar .btn-nav-login:hover {
            background: rgba(255,255,255,.18);
            border-color: rgba(255,255,255,.25);
            color: #fff;
        }

        /* ── Cards ─────────────────────────────────────────── */
        .af-card {
            background: var(--af-surface);
            border: 1px solid var(--af-border);
            border-radius: var(--af-radius-lg);
            box-shadow: var(--af-shadow-sm);
            transition: box-shadow var(--af-transition), transform var(--af-transition);
        }
        .af-card:hover {
            box-shadow: var(--af-shadow-lg);
            transform: translateY(-2px);
        }
        .af-card-static {
            background: var(--af-surface);
            border: 1px solid var(--af-border);
            border-radius: var(--af-radius-lg);
            box-shadow: var(--af-shadow-sm);
        }

        /* ── Buttons ───────────────────────────────────────── */
        .af-btn {
            font-weight: 600;
            font-size: .875rem;
            padding: .6rem 1.25rem;
            border-radius: .6rem;
            transition: var(--af-transition);
            border: none;
        }
        .af-btn-primary {
            background: var(--af-primary);
            color: #fff;
        }
        .af-btn-primary:hover {
            background: var(--af-primary-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37,99,235,.3);
        }
        .af-btn-success {
            background: var(--af-accent);
            color: #fff;
        }
        .af-btn-success:hover {
            background: var(--af-accent-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16,185,129,.3);
        }
        .af-btn-outline {
            background: transparent;
            color: var(--af-text-secondary);
            border: 1px solid var(--af-border);
        }
        .af-btn-outline:hover {
            background: var(--af-surface-alt);
            color: var(--af-text);
            border-color: #cbd5e1;
        }

        /* ── Badges ────────────────────────────────────────── */
        .af-badge {
            font-weight: 600;
            font-size: .72rem;
            letter-spacing: 0.03em;
            padding: .3em .7em;
            border-radius: 2rem;
            text-transform: uppercase;
        }
        .af-badge-blue {
            background: var(--af-primary-light);
            color: var(--af-primary-dark);
        }
        .af-badge-green {
            background: #d1fae5;
            color: #065f46;
        }
        .af-badge-gray {
            background: #f1f5f9;
            color: #475569;
        }
        .af-badge-cyan {
            background: #cffafe;
            color: #155e75;
        }

        /* ── Forms ─────────────────────────────────────────── */
        .af-form .form-control,
        .af-form .form-select {
            border: 1px solid var(--af-border);
            border-radius: .6rem;
            padding: .65rem .9rem;
            font-size: .9rem;
            transition: var(--af-transition);
        }
        .af-form .form-control:focus,
        .af-form .form-select:focus {
            border-color: var(--af-primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.12);
        }
        .af-form .form-label {
            font-weight: 600;
            font-size: .82rem;
            color: var(--af-text);
            margin-bottom: .35rem;
        }

        /* ── Page header ───────────────────────────────────── */
        .af-page-header {
            text-align: center;
            padding: 2rem 0 1.5rem;
        }
        .af-page-header h1 {
            font-weight: 800;
            font-size: 2rem;
            letter-spacing: -0.025em;
            color: var(--af-text);
        }
        .af-page-header p {
            color: var(--af-text-secondary);
            font-size: 1.05rem;
            max-width: 540px;
            margin: .5rem auto 0;
        }

        /* ── Footer ────────────────────────────────────────── */
        .af-footer {
            background: var(--af-surface);
            border-top: 1px solid var(--af-border);
            padding: 1.5rem 0;
        }
        .af-footer p {
            color: var(--af-text-secondary);
            font-size: .82rem;
            margin: 0;
        }

        /* ── Misc ──────────────────────────────────────────── */
        .af-divider {
            border: none;
            height: 1px;
            background: var(--af-border);
            margin: 1.25rem 0;
        }

        /* Flash alert */
        .af-flash {
            border: none;
            border-radius: var(--af-radius);
            font-size: .9rem;
            padding: .85rem 1.25rem;
        }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Selection */
        ::selection {
            background: var(--af-primary-light);
            color: var(--af-primary-dark);
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg af-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-box-seam-fill me-1"></i>AlterFOSS
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Buka navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-grid-3x3-gap me-1"></i>Katalog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('kalkulator*') ? 'active' : '' }}" href="/kalkulator">
                            <i class="bi bi-calculator me-1"></i>Kalkulator TCO
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('saran*') ? 'active' : '' }}" href="/saran">
                            <i class="bi bi-lightbulb me-1"></i>Usulkan Alternatif
                        </a>
                    </li>
                </ul>

                @auth
                    <ul class="navbar-nav mb-2 mb-lg-0 gap-1">
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">
                                <i class="bi bi-gear me-1"></i>Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="/logout">
                                @csrf
                                <button class="btn-nav-login" type="submit">
                                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container py-4 flex-grow-1">
        @if (session('status'))
            <div class="alert alert-success af-flash alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger af-flash alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="af-footer mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p><strong>AlterFOSS</strong> — Direktori Alternatif Open Source</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p>
                        <span class="af-badge af-badge-blue me-1">MIT</span>
                        <span class="af-badge af-badge-green">CC BY-SA 4.0</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
