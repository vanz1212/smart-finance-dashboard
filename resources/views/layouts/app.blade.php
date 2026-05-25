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
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/smart-finance') }}">Smart Finance</a>
                <a href="{{ route('perpajakan.index') }}">Perpajakan</a>
                <a href="{{ url('/stata') }}">Stata</a>
            </nav>
        </header>

        <main class="content">
            @yield('content')
        </main>

        <footer class="site-footer">
            <p>© 2026 Smart Finance Analytics Dashboard. Dibuat untuk mahasiswa ekonomi dan pengguna data ekonomi.</p>
        </footer>
    </div>
</body>
</html>
