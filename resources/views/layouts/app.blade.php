<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SMART FINANCE ANALYTICS DASHBOARD')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <header class="site-header">
            <div class="brand">SMART FINANCE ANALYTICS DASHBOARD</div>
            <nav class="main-nav">
                <a href="{{ route('home') }}">Beranda</a>
                <a href="{{ url('/smart-finance') }}">Smart Finance</a>
                <a href="{{ route('perpajakan.index') }}">Perpajakan</a>
                <a href="{{ url('/stata') }}">Stata</a>
                @auth
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
