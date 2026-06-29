<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NEXIO DASHBOARD')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nexio_logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css" id="flatpickr-theme">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        :root {
            --font-main: 'Inter', sans-serif;
            --bg-primary: #0f172a;
            --bg-secondary: #0b1120;
            --bg-panel: #1e293b;
            --text-main: #f8fafc;
            --text-muted: rgba(248, 250, 252, 0.72);
            --border-color: rgba(255, 255, 255, 0.08);
            --accent-primary: #6366f1;
            --accent-hover: #3b82f6;
            --nav-bg: rgba(255, 255, 255, 0.12);
        }

        [data-theme="light"] {
            --bg-primary: #f8fafc;
            --bg-secondary: #ffffff;
            --bg-panel: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: rgba(0, 0, 0, 0.1);
            --accent-primary: #4f46e5;
            --accent-hover: #2563eb;
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
            <a class="brand" href="{{ route('dashboard.user') }}" aria-label="{{ __('app.home') }}">
                <img src="{{ asset('images/nexio_logo.png') }}" alt="Nexio Logo" style="height: 38px; border-radius: 8px; object-fit: contain;">
                <span>NEXIO</span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <div style="display: flex; gap: 8px; align-items: center; font-weight: bold; font-size: 0.9rem;">
                    <a href="{{ url('/lang/id') }}" style="text-decoration: none; color: {{ App::getLocale() === 'id' ? 'var(--accent-primary)' : 'var(--text-main)' }};">ID</a>
                    <span style="color: var(--text-muted);">|</span>
                    <a href="{{ url('/lang/en') }}" style="text-decoration: none; color: {{ App::getLocale() === 'en' ? 'var(--accent-primary)' : 'var(--text-main)' }};">EN</a>
                </div>
                <button class="theme-toggle" aria-label="Toggle Theme">
                    <span class="theme-icon">🌙</span>
                </button>
                <a class="{{ request()->routeIs('dashboard.user', 'page.selector') ? 'is-active' : '' }}" href="{{ route('dashboard.user') }}">{{ __('app.home') }}</a>
                <a class="module-nav-link {{ request()->routeIs('finance.*') ? 'is-active' : '' }}" data-module-nav href="{{ route('finance.index') }}">
                    <span class="module-tab-label">{{ __('app.module_smart_finance') }}</span>
                    @if (request()->routeIs('finance.*'))
                        <span class="module-active-pill" aria-hidden="true"></span>
                    @endif
                </a>
                <a class="module-nav-link {{ request()->routeIs('perpajakan.*') ? 'is-active' : '' }}" data-module-nav href="{{ route('perpajakan.index') }}">
                    <span class="module-tab-label">{{ __('app.module_perpajakan') }}</span>
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
                <a class="module-nav-link {{ request()->routeIs('targets.*') ? 'is-active' : '' }}" data-module-nav href="{{ route('targets.index') }}">
                    <span class="module-tab-label">{{ __('targets.page_title') }}</span>
                    @if (request()->routeIs('targets.*'))
                        <span class="module-active-pill" aria-hidden="true"></span>
                    @endif
                </a>
                @auth
                    <a class="{{ request()->routeIs('profile') ? 'is-active' : '' }}" href="{{ route('profile') }}" style="display:flex; align-items:center; gap:6px;">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar" style="width:18px; height:18px; border-radius:50%; object-fit:cover;">
                        @endif
                        {{ __('app.profile') }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="nav-form">
                        @csrf
                        <button type="submit">{{ __('app.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">{{ __('app.login') }}</a>
                @endauth
            </nav>
        </header>

        <main class="content">
            @yield('content')
        </main>

        <footer class="site-footer">
            <p>{{ __('app.footer') }}</p>
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
