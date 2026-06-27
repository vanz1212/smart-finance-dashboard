<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMART FINANCE ANALYTICS DASHBOARD')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css" id="flatpickr-theme">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --font-main: 'Inter', sans-serif;
            --bg-primary: #071316;
            --bg-secondary: #061418;
            --bg-panel: #071b20;
            --text-main: #f8fafc;
            --text-muted: rgba(248, 250, 252, 0.72);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-primary: #f3c969;
            --accent-hover: #052e2b;
            --nav-bg: rgba(255, 255, 255, 0.12);
        }

        [data-theme="light"] {
            --bg-primary: #f1f5f9;
            --bg-secondary: #ffffff;
            --bg-panel: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: rgba(0, 0, 0, 0.1);
            --accent-primary: #14b8a6;
            --accent-hover: #ffffff;
            --nav-bg: rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-primary);
            color: var(--text-main);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .theme-toggle {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            padding: 5px 12px;
            color: var(--text-main);
            cursor: pointer;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .theme-toggle:hover {
            background: var(--accent-primary);
            color: var(--accent-hover);
        }
    </style>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
</head>
<body class="@yield('body-class')">
    <div class="container">
        <header class="site-header">
            <a class="brand" href="{{ route('dashboard.user') }}" aria-label="Buka selector Smart Finance">
                <span class="brand-symbol" aria-hidden="true">S</span>
                <span>SMART FINANCE</span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <button class="theme-toggle" aria-label="Toggle Theme">
                    <span class="theme-icon">🌙</span>
                </button>
                <a class="{{ request()->routeIs('dashboard.user', 'page.selector') ? 'is-active' : '' }}" href="{{ route('dashboard.user') }}">Beranda</a>
                <a class="module-nav-link {{ request()->routeIs('finance.*') ? 'is-active' : '' }}" data-module-nav href="{{ route('finance.index') }}">
                    <span class="module-tab-label">Smart Finance</span>
                    @if (request()->routeIs('finance.*'))
                        <span class="module-active-pill" aria-hidden="true"></span>
                    @endif
                </a>
                <a class="module-nav-link {{ request()->routeIs('perpajakan.*') ? 'is-active' : '' }}" data-module-nav href="{{ route('perpajakan.index') }}">
                    <span class="module-tab-label">Perpajakan</span>
                    @if (request()->routeIs('perpajakan.*'))
                        <span class="module-active-pill" aria-hidden="true"></span>
                    @endif
                </a>
                <a class="module-nav-link {{ request()->routeIs('stata') ? 'is-active' : '' }}" data-module-nav href="{{ route('stata') }}">
                    <span class="module-tab-label">Stata</span>
                    @if (request()->routeIs('stata'))
                        <span class="module-active-pill" aria-hidden="true"></span>
                    @endif
                </a>
                @auth
                    <a class="{{ request()->routeIs('profile') ? 'is-active' : '' }}" href="{{ route('profile') }}">Profil</a>
                    <form action="{{ route('logout') }}" method="POST" class="nav-form">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endauth
            </nav>
        </header>

        <main class="content">
            @yield('content')
        </main>

        <footer class="site-footer">
            <p>© 2026 Smart Finance Analytics Dashboard. Dibuat untuk mahasiswa ekonomi dan pengguna data ekonomi.</p>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('page-ready');

            // Theme Toggle Logic
            const themeBtns = document.querySelectorAll('.theme-toggle');
            const flatpickrTheme = document.getElementById('flatpickr-theme');
            
            function updateThemeUI(theme) {
                const isLight = theme === 'light';
                themeBtns.forEach(btn => {
                    const icon = btn.querySelector('.theme-icon');
                    if(icon) icon.textContent = isLight ? '☀️' : '🌙';
                });
                flatpickrTheme.href = isLight ? 'https://npmcdn.com/flatpickr/dist/themes/light.css' : 'https://npmcdn.com/flatpickr/dist/themes/dark.css';
            }
            
            updateThemeUI(document.documentElement.getAttribute('data-theme'));

            themeBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    let currentTheme = document.documentElement.getAttribute('data-theme');
                    let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    document.documentElement.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    updateThemeUI(newTheme);
                });
            });

            // Initialize Flatpickr
            flatpickr("input[type='date']", {
                dateFormat: "Y-m-d",
                allowInput: true
            });

            document.querySelectorAll('a[href]').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    if (event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                        return;
                    }

                    var url = new URL(link.href, window.location.href);

                    if (url.origin !== window.location.origin || link.target || link.hasAttribute('download')) {
                        return;
                    }

                    if (document.body.classList.contains('module-page') && link.matches('[data-module-nav]')) {
                        return;
                    }

                    if (url.pathname === window.location.pathname && url.hash) {
                        var target = document.querySelector(url.hash);

                        if (target) {
                            event.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            window.history.pushState(null, '', url.hash);
                        }

                        return;
                    }

                    event.preventDefault();
                    document.body.classList.add('page-leaving');

                    window.setTimeout(function () {
                        window.location.href = link.href;
                    }, 180);
                });
            });
        });
    </script>
</body>
</html>
