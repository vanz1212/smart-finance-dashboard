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
            <a class="brand" href="{{ route('home') }}" aria-label="Smart Finance Beranda">
                <span class="brand-symbol" aria-hidden="true">S</span>
                <span>SMART FINANCE</span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="{{ request()->routeIs('home', 'dashboard.user', 'page.selector') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="{{ request()->routeIs('finance.*') ? 'is-active' : '' }}" href="{{ route('finance.index') }}">Smart Finance</a>
                <a class="{{ request()->routeIs('perpajakan.*') ? 'is-active' : '' }}" href="{{ route('perpajakan.index') }}">Perpajakan</a>
                <a class="{{ request()->routeIs('stata') ? 'is-active' : '' }}" href="{{ route('stata') }}">Stata</a>
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
