<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMART FINANCE ANALYTICS DASHBOARD')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="@yield('body-class')">
    <div class="container">
        <header class="site-header">
            <a class="brand" href="{{ route('dashboard.user') }}" aria-label="Buka selector Smart Finance">
                <span class="brand-symbol" aria-hidden="true">S</span>
                <span>SMART FINANCE</span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('page-ready');

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
