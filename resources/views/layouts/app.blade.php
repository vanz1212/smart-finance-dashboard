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
    <link rel="stylesheet" href="{{ asset('css/nexio.css') }}">
    @livewireStyles
    <script>
        (function () {
            var savedTheme = localStorage.getItem('nexio-theme') || localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body class="@yield('body-class') {{ request()->routeIs('finance.*', 'perpajakan.*', 'stata', 'targets.*') ? 'module-page' : '' }}">
    <div class="nexio-background" aria-hidden="true"></div>

    <div class="container">
        <main class="content">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        (function () {
            function ready(callback) {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', callback, { once: true });
                } else {
                    callback();
                }
            }

            function initNexioLite() {
                document.body.classList.remove('page-leaving');
                document.body.classList.add('page-ready');

                var flatpickrTheme = document.getElementById('flatpickr-theme');
                var themeButtons = document.querySelectorAll('[data-theme-toggle], .theme-toggle');

                function applyTheme(theme) {
                    document.documentElement.setAttribute('data-theme', theme);
                    localStorage.setItem('nexio-theme', theme);
                    localStorage.setItem('theme', theme);

                    if (flatpickrTheme) {
                        flatpickrTheme.href = theme === 'light'
                            ? 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'
                            : 'https://npmcdn.com/flatpickr/dist/themes/dark.css';
                    }
                }

                themeButtons.forEach(function (button) {
                    if (button.dataset.nexioBound === 'theme') return;
                    button.dataset.nexioBound = 'theme';
                    button.addEventListener('click', function () {
                        var nextTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                        applyTheme(nextTheme);
                    });
                });

                if (window.flatpickr) {
                    flatpickr("input[type='date']", {
                        dateFormat: "Y-m-d",
                        allowInput: true
                    });
                }

                document.querySelectorAll('a[href]').forEach(function (link) {
                    if (link.dataset.nexioBound === 'link') return;
                    link.dataset.nexioBound = 'link';
                    link.addEventListener('click', function (event) {
                        if (event.defaultPrevented || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                            return;
                        }

                        var url = new URL(link.href, window.location.href);
                        if (
                            link.hasAttribute('data-no-spa') ||
                            url.origin !== window.location.origin ||
                            link.target ||
                            link.hasAttribute('download') ||
                            link.hasAttribute('wire:navigate')
                        ) {
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

                        if (window.Livewire && typeof window.Livewire.navigate === 'function') {
                            event.preventDefault();
                            document.body.classList.add('page-leaving');
                            window.Livewire.navigate(link.href);
                        }
                    });
                });
            }

            ready(initNexioLite);
            document.addEventListener('livewire:navigated', initNexioLite);
        })();
    </script>
    @livewireScripts
</body>
</html>
