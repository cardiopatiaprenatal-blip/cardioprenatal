<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Cardioprenatal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap');

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #7f0c1a;
            --accent:       #c0392b;
            --accent-mid:   #e74c3c;
            --accent-light: #fdf0f0;
            --text:         #1c1a1a;
            --muted:        #8a6f6f;
            --border:       #f0d5d5;
            --surface:      #ffffff;
            --bg:           #faf4f4;
            --nav-bg:       #7f0c1a;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: var(--text);
        }

        /* ── HEADER ── */
        header {
            background: linear-gradient(135deg, var(--primary) 0%, #a01020 100%);
            box-shadow: 0 2px 16px rgba(127,12,26,.25);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Logo */
        .header-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .header-logo-icon {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        .header-logo-icon svg {
            width: 20px; height: 20px;
            color: #fff;
        }

        .header-logo-text {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: #fff;
            letter-spacing: -.2px;
        }

        /* Nav desktop */
        .nav-desktop {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            transition: background .18s, color .18s;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }

        .nav-link svg { width: 16px; height: 16px; }

        /* Separador vertical */
        .nav-sep {
            width: 1px;
            height: 20px;
            background: rgba(255,255,255,.2);
            margin: 0 6px;
        }

        /* Botão sair */
        .btn-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,.8);
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            cursor: pointer;
            transition: background .18s, color .18s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,.18);
            color: #fff;
        }

        .btn-logout svg { width: 15px; height: 15px; }

        /* Botão mobile */
        .btn-mobile-menu {
            display: none;
            background: rgba(255,255,255,.12);
            border: none;
            border-radius: 8px;
            padding: 8px;
            cursor: pointer;
            color: #fff;
        }

        .btn-mobile-menu svg { width: 22px; height: 22px; display: block; }

        /* Menu mobile */
        .nav-mobile {
            display: none;
            background: var(--primary);
            border-top: 1px solid rgba(255,255,255,.1);
            padding: 12px 16px 16px;
            flex-direction: column;
            gap: 4px;
        }

        .nav-mobile.open { display: flex; }

        .nav-mobile .nav-link { justify-content: flex-start; }

        .nav-mobile-sep {
            height: 1px;
            background: rgba(255,255,255,.12);
            margin: 8px 0;
        }

        /* ── MAIN ── */
        main {
            flex: 1;
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* ── FOOTER ── */
        footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 16px 24px;
            text-align: center;
            font-size: 13px;
            color: var(--muted);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        footer svg { width: 13px; height: 13px; color: var(--accent); }

        @media (max-width: 768px) {
            .nav-desktop { display: none; }
            .btn-mobile-menu { display: flex; }
            main { padding: 20px 16px; }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="header-inner">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="header-logo">
                <div class="header-logo-icon">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <span class="header-logo-text">Cardioprenatal</span>
            </a>

            <!-- Nav desktop -->
            <nav class="nav-desktop">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('gestantes.index') }}" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                    Gestantes
                </a>
                <a href="{{ route('consultas.import') }}" class="nav-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Importar CSV
                </a>

                <div class="nav-sep"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Sair
                    </button>
                </form>
            </nav>

            <!-- Botão mobile -->
            <button class="btn-mobile-menu" id="menuButton" aria-label="Abrir menu">
                <svg id="menuIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
        </div>

        <!-- Nav mobile -->
        <nav class="nav-mobile" id="mobileMenu">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('gestantes.index') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                Gestantes
            </a>
            <a href="{{ route('consultas.import') }}" class="nav-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Importar CSV
            </a>
            <div class="nav-mobile-sep"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout" style="width:100%; justify-content:center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Sair
                </button>
            </form>
        </nav>
    </header>

    <!-- Conteúdo -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
        </svg>
        © {{ date('Y') }} Cardioprenatal
    </footer>

    <script>
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });

        // Marca o link ativo
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    </script>

    @stack('scripts')

</body>

</html>