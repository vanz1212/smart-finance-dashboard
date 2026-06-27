@extends('layouts.app')

@section('title', 'Smart Finance Dashboard')

@section('content')
    @php
        $heroSlides = [
            [
                'kicker' => 'Finance Intelligence',
                'title_before' => 'Designing Financial',
                'title_accent' => 'futures',
                'description' => 'Kelola analisa keuangan, estimasi pajak, dan insight finansial dalam dashboard yang sederhana, fokus, dan siap dipakai.',
                'button' => 'Pelajari lebih lanjut',
                'url' => '#about',
                'image' => asset('images/slidev1.jpg'),
            ],
            [
                'kicker' => 'Tax Planning',
                'title_before' => 'Pahami Pajak',
                'title_accent' => 'lebih cepat',
                'description' => 'Lihat estimasi PPh orang pribadi, status PTKP, dan skenario penghasilan dengan tampilan yang mudah dipahami pengguna non-teknis.',
                'button' => 'Buka Modul Pajak',
                'url' => route('perpajakan.info'),
                'image' => asset('images/slidev2.jpg'),
            ],
            [
                'kicker' => 'Academic Insight',
                'title_before' => 'Eksplorasi Data',
                'title_accent' => 'dengan Stata',
                'description' => 'Pelajari korelasi, regresi, dan statistik deskriptif melalui modul Stata yang dirancang untuk kebutuhan belajar dan riset ekonomi.',
                'button' => 'Lihat Tutorial Stata',
                'url' => route('stata.info'),
                'image' => asset('images/slidev3.jpg'),
            ],
        ];
    @endphp

    <style>
        .site-header,
        .site-footer {
            display: none !important;
        }

        body {
            background: #050c0f;
        }

        body > .container {
            max-width: none;
            width: 100%;
            min-height: 100vh;
            padding: 0;
        }

        .content {
            min-height: 100vh;
        }

        .landing-shell {
            --landing-pad: clamp(18px, 4vw, 72px);
            --landing-header-height: max(540px, 75svh);
            min-height: 100vh;
            color: #f8fafc;
            background:
                radial-gradient(circle at 50% 0%, rgba(243, 201, 105, 0.18), transparent 28%),
                linear-gradient(180deg, rgba(5, 12, 15, 0.9), rgba(5, 12, 15, 0.98));
        }

        .landing-nav {
            width: 100%;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: clamp(18px, 2.4vw, 30px) var(--landing-pad);
        }

        .landing-brand {
            color: #ffffff;
            font-weight: 800;
            letter-spacing: 0;
            text-decoration: none;
        }

        .landing-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .landing-links a {
            color: rgba(248, 250, 252, 0.72);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .landing-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 20px;
            border-radius: 999px;
            background: #ffffff;
            color: #0f172a !important;
        }

        .landing-auth-actions,
        .landing-logout-form {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .landing-logout {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 999px;
            color: rgba(248, 250, 252, .84);
            background: rgba(255, 255, 255, .06);
            font: inherit;
            font-size: .9rem;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
        }

        .landing-logout:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, .11);
        }

        .landing-hero {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .hero-slider {
            position: relative;
            overflow: hidden;
            min-height: var(--landing-header-height);
            border-radius: 0;
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: #081418;
            box-shadow: 0 28px 90px rgba(0, 0, 0, 0.34);
            animation: frameRise 0.8s ease-out both;
        }

        .hero-slides {
            display: grid;
            position: relative;
            min-height: var(--landing-header-height);
        }

        .hero-slide {
            grid-area: 1 / 1;
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(280px, 0.82fr);
            align-items: center;
            gap: clamp(24px, 4vw, 58px);
            padding: clamp(36px, 5vw, 64px) var(--landing-pad) clamp(82px, 8vw, 112px);
            opacity: 0;
            pointer-events: none;
            transform: translateX(36px);
            transition: opacity 0.45s ease, transform 0.45s ease;
        }

        .hero-slide.is-active {
            opacity: 1;
            pointer-events: auto;
            z-index: 2;
            transform: translateX(0);
        }

        .hero-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(137, 23, 23, 0.82) 0%, rgba(137, 23, 23, 0.55) 20%, rgba(8, 20, 24, 0.24) 56%, rgba(8, 20, 24, 0.58) 100%),
                var(--slide-image) center / cover no-repeat;
            z-index: -2;
        }

        .hero-slide::after {
            content: "";
            position: absolute;
            inset: auto 0 0;
            height: 92px;
            background: linear-gradient(180deg, rgba(122, 17, 17, 0), rgba(122, 17, 17, 0.92));
            z-index: -1;
        }

        .hero-copy {
            max-width: clamp(420px, 43vw, 690px);
            text-align: left;
            position: relative;
            z-index: 2;
        }

        .hero-kicker {
            display: inline-flex;
            margin-bottom: 24px;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            animation: softReveal 0.7s ease-out 0.15s both;
        }

        .hero-copy h1 {
            margin: 0;
            font-size: clamp(3rem, 6.6vw, 7rem);
            line-height: 1.04;
            font-weight: 800;
            letter-spacing: 0;
            animation: softReveal 0.75s ease-out 0.25s both;
        }

        .hero-copy h1 em {
            color: #f3c969;
            font-family: Georgia, serif;
            font-style: italic;
            font-weight: 500;
            display: block;
            animation: wordFloat 3.2s ease-in-out infinite;
        }

        .hero-copy p {
            max-width: 520px;
            margin: 24px 0 0;
            color: rgba(248, 250, 252, 0.88);
            line-height: 1.7;
            font-size: 1.04rem;
            animation: softReveal 0.75s ease-out 0.38s both;
        }

        .hero-visual {
            min-height: clamp(240px, 38vw, 420px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(18px, 3vw, 34px);
            border-radius: clamp(20px, 2.5vw, 32px);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.04));
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
            animation: softReveal 0.8s ease-out 0.3s both;
        }

        .hero-visual img {
            width: 100%;
            max-width: clamp(280px, 34vw, 540px);
            max-height: clamp(260px, 40vw, 520px);
            object-fit: contain;
            object-position: center;
            filter: drop-shadow(0 32px 52px rgba(0, 0, 0, 0.32));
        }

        .landing-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: clamp(46px, 4vw, 54px);
            margin-top: clamp(24px, 3vw, 36px);
            padding: 0 clamp(18px, 2vw, 26px);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.72);
            color: #ffffff;
            text-decoration: none;
            font-weight: 800;
            position: relative;
            overflow: hidden;
            animation: ctaPulse 3.4s cubic-bezier(0.45, 0, 0.25, 1) infinite;
        }

        .landing-cta span {
            display: inline-grid;
            place-items: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f3c969;
            color: #052e2b;
        }

        .landing-cta::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-120%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.28), transparent);
            animation: ctaShimmer 5.2s cubic-bezier(0.45, 0, 0.25, 1) infinite;
        }

        .hero-slider-controls {
            position: absolute;
            inset: auto 0 24px;
            z-index: 4;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 24px;
            padding: 0 var(--landing-pad);
        }

        .hero-slider-dots {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
        }

        .hero-slider-dot {
            width: 12px;
            height: 12px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 999px;
            background: rgba(248, 250, 252, 0.18);
            cursor: pointer;
            transition: transform 0.2s ease, background 0.2s ease, width 0.2s ease;
        }

        .hero-slider-dot.is-active {
            width: 34px;
            background: #f3c969;
            border-color: #f3c969;
        }

        .hero-slider-nav {
            width: clamp(48px, 4.8vw, 64px);
            height: clamp(48px, 4.8vw, 64px);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-size: 2rem;
            line-height: 0;
            font-weight: 800;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .hero-slider-nav span {
            display: block;
            line-height: 1;
            transform: translateY(-2px);
        }

        .hero-slider-nav:hover,
        .hero-slider-dot:hover {
            transform: scale(1.04);
        }

        .hero-slider-nav:hover {
            background: rgba(255, 255, 255, 0.16);
        }

        .hero-slider-help {
            width: 100%;
            margin: 0;
            padding: clamp(16px, 2.5vw, 24px) var(--landing-pad) 0;
            color: rgba(248, 250, 252, 0.68);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .hero-slider-help code {
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            color: #f3c969;
        }

        .landing-about {
            width: 100%;
            margin: 0;
            padding: clamp(56px, 7vw, 86px) var(--landing-pad) 40px;
            text-align: center;
            scroll-margin-top: 28px;
        }

        .landing-about small {
            color: rgba(248, 250, 252, 0.5);
            font-weight: 700;
        }

        .landing-about p {
            margin: 18px auto 0;
            max-width: 820px;
            font-size: clamp(1.35rem, 3vw, 2rem);
            line-height: 1.18;
            color: #ffffff;
            animation: softReveal 0.8s ease-out both;
            animation-timeline: view();
            animation-range: entry 0% cover 35%;
        }

        .landing-services {
            width: 100%;
            margin: 0;
            padding: 56px var(--landing-pad) 88px;
        }

        .section-title {
            display: flex;
            max-width: 1160px;
            margin-inline: auto;
            justify-content: space-between;
            gap: 20px;
            align-items: end;
            margin-bottom: 28px;
        }

        .section-title h2 {
            margin: 0;
            max-width: 520px;
            font-size: clamp(2.2rem, 5vw, 4rem);
            line-height: 0.98;
        }

        .section-title h2 em {
            display: block;
            color: #f3c969;
            font-family: Georgia, serif;
            font-weight: 500;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            width: min(100%, 980px);
            margin: 0 auto;
            justify-content: center;
            align-items: stretch;
        }

        .service-card {
            min-height: 210px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(243, 201, 105, 0.08));
            animation: cardReveal 0.75s ease-out both;
            animation-timeline: view();
            animation-range: entry 0% cover 34%;
        }

        .service-card:nth-child(2) {
            animation-delay: 0.08s;
        }

        .service-card:nth-child(3) {
            animation-delay: 0.16s;
        }

        .service-card small {
            color: rgba(248, 250, 252, 0.42);
            font-weight: 800;
        }

        .service-card h3 {
            margin: 34px 0 16px;
            font-size: 1.35rem;
            line-height: 1.05;
        }

        .service-card p {
            margin: 0;
            color: rgba(248, 250, 252, 0.66);
            line-height: 1.65;
        }

        .company-footer {
            width: 100%;
            margin-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(20, 25, 27, 0.96);
        }

        .company-footer-main {
            display: grid;
            grid-template-columns: 1.15fr 1fr 0.8fr;
            gap: 54px;
            width: min(1160px, calc(100% - 40px));
            margin: 0 auto;
            padding: 48px 0 44px;
        }

        .company-footer-column h3 {
            margin: 0 0 16px;
            color: #ffffff;
            font-size: 1.55rem;
            line-height: 1.15;
        }

        .company-footer-column p {
            margin: 0;
            color: rgba(248, 250, 252, 0.76);
            line-height: 1.65;
        }

        .company-contact-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px 22px;
        }

        .company-contact-list a,
        .company-contact-list span,
        .company-quick-links a {
            color: rgba(248, 250, 252, 0.88);
            line-height: 1.5;
            text-decoration: none;
        }

        .company-contact-list a:hover,
        .company-quick-links a:hover {
            color: #f3c969;
        }

        .company-quick-links {
            display: grid;
            gap: 10px;
        }

        .company-quick-links a::after {
            content: ">";
            margin-left: 8px;
            color: #f3c969;
            font-size: 1.25rem;
            font-weight: 900;
        }

        .company-footer-bottom {
            padding: 22px max(20px, calc((100% - 1160px) / 2));
            background: #14b86f;
            color: #052e2b;
            font-weight: 800;
        }

        .company-footer-bottom p {
            margin: 0;
        }

        @keyframes frameRise {
            from {
                opacity: 0;
                transform: translateY(18px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes softReveal {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes wordFloat {
            0%, 100% {
                transform: translateY(0);
                text-shadow: 0 0 0 rgba(243, 201, 105, 0);
            }
            50% {
                transform: translateY(-4px);
                text-shadow: 0 0 22px rgba(243, 201, 105, 0.42);
            }
        }

        @keyframes ctaPulse {
            0%, 100% {
                transform: scale(1);
                border-color: rgba(255, 255, 255, 0.16);
                box-shadow: 0 0 0 rgba(243, 201, 105, 0);
            }
            50% {
                transform: scale(1.018);
                border-color: rgba(243, 201, 105, 0.42);
                box-shadow: 0 0 26px rgba(243, 201, 105, 0.16);
            }
        }

        @keyframes ctaShimmer {
            0%, 55% {
                transform: translateX(-120%);
            }
            80%, 100% {
                transform: translateX(120%);
            }
        }

        @keyframes cardReveal {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .hero-slide,
            .hero-kicker,
            .hero-copy h1,
            .hero-copy h1 em,
            .hero-copy p,
            .hero-visual,
            .landing-cta,
            .landing-cta::after,
            .landing-about p,
            .service-card {
                animation: none;
                transition: none;
            }
        }

        @media (max-width: 900px) {
            .landing-links a:not(.landing-login) {
                display: none;
            }

            .landing-auth-actions .landing-dashboard {
                display: inline-flex;
            }

            .hero-slide {
                grid-template-columns: 1fr;
                gap: 24px;
                align-content: center;
                padding-top: 44px;
                padding-bottom: 118px;
            }

            .hero-copy {
                max-width: none;
                text-align: center;
            }

            .hero-copy p {
                margin-inline: auto;
            }

            .hero-visual {
                min-height: clamp(220px, 42vw, 320px);
                padding: 20px;
            }

            .hero-visual img {
                width: 100%;
                max-width: 280px;
                max-height: 280px;
            }

            .service-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .landing-dashboard,
            .landing-logout {
                min-height: 38px;
                padding-inline: 13px;
                font-size: .8rem;
            }

            .landing-shell {
                --landing-pad: 18px;
                --landing-header-height: 700px;
            }

            .hero-slider,
            .hero-slides {
                min-height: var(--landing-header-height);
            }

            .hero-copy h1 {
                font-size: clamp(2.5rem, 10vw, 3.6rem);
            }

            .hero-slider-controls {
                padding: 0 16px;
            }

            .hero-slider-nav {
                width: 48px;
                height: 48px;
                font-size: 1.65rem;
            }

            .section-title {
                align-items: start;
                flex-direction: column;
            }

            .service-grid {
                grid-template-columns: 1fr;
            }

            .company-footer-main {
                grid-template-columns: 1fr;
                gap: 30px;
                padding-block: 38px;
            }

            .company-contact-list {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="landing-shell">
        <nav class="landing-nav">
            <a class="landing-brand" href="{{ route('home') }}">SmartFinance.</a>
            <div class="landing-links">
                @auth
                    <form class="landing-logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="landing-logout" type="submit">Logout</button>
                    </form>
                @else
                    <a class="landing-login" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </nav>

        <section class="landing-hero">
            <div class="hero-slider" data-hero-slider>
                <div class="hero-slides">
                    @foreach ($heroSlides as $index => $slide)
                        <article class="hero-slide {{ $index === 0 ? 'is-active' : '' }}" style="--slide-image: url('{{ $slide['image'] }}')">
                            <div class="hero-copy">
                                <span class="hero-kicker">{{ $slide['kicker'] }}</span>
                                <h1>
                                    {{ $slide['title_before'] }}
                                    <em>{{ $slide['title_accent'] }}</em>
                                </h1>
                                <p>{{ $slide['description'] }}</p>
                                <a class="landing-cta" href="{{ $slide['url'] }}"><span>&rarr;</span> {{ $slide['button'] }}</a>
                            </div>
                            <div class="hero-visual">
                                <img src="{{ $slide['image'] }}" alt="{{ $slide['title_before'] }} {{ $slide['title_accent'] }}">
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="hero-slider-controls">
                    <button type="button" class="hero-slider-nav" data-slide-prev aria-label="Slide sebelumnya"><span>&lsaquo;</span></button>
                    <div class="hero-slider-dots" aria-label="Navigasi slide">
                        @foreach ($heroSlides as $index => $slide)
                            <button type="button" class="hero-slider-dot {{ $index === 0 ? 'is-active' : '' }}" data-slide-dot="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                    <button type="button" class="hero-slider-nav" data-slide-next aria-label="Slide berikutnya"><span>&rsaquo;</span></button>
                </div>
            </div>
        </section>

        <section id="about" class="landing-about">
            <small>About SmartFinance</small>
            <p>SmartFinance membantu membaca kondisi keuangan dengan lebih cepat: arus kas, rasio tabungan, utang, dana darurat, dan pajak dalam satu ekosistem.</p>
        </section>

        <section class="landing-services">
            <div class="section-title">
                <h2>Tools that <em>are tailored</em></h2>
            </div>

            <div class="service-grid">
                <article class="service-card">
                    <small>01</small>
                    <h3>Analisa Keuangan</h3>
                    <p>Hitung rasio pengeluaran, tabungan, cicilan, dan dana darurat. Cocok untuk memantau arus kas bulanan dan melihat posisi keuangan secara cepat.</p>
                </article>
                <article class="service-card">
                    <small>02</small>
                    <h3>Perpajakan</h3>
                    <p>Estimasi PPh orang pribadi memakai PTKP dan tarif progresif. Sertakan skenario penghasilan agar hasil pajak lebih mudah dibandingkan.</p>
                </article>
                <article class="service-card">
                    <small>03</small>
                    <h3>Stata</h3>
                    <p>Ruang analisis statistik dan ekonomi untuk kebutuhan akademik. Mendukung alur belajar, eksplorasi data, dan interpretasi hasil analisis.</p>
                </article>
            </div>
        </section>

        <footer class="company-footer">
            <div class="company-footer-main">
                <section class="company-footer-column">
                    <h3>Smart Finance Analytics</h3>
                    <p>
                        Jl. Finansial No. 12<br>
                        Jakarta, Indonesia<br>
                        Platform analisa keuangan, perpajakan, dan statistik.
                    </p>
                </section>

                <section class="company-footer-column">
                    <h3>Hubungi Kami</h3>
                    <div class="company-contact-list">
                        <a href="tel:+6281234567890">+62 812-3456-7890</a>
                        <span>Senin - Jumat</span>
                        <a href="mailto:support@smartfinance.id">support@smartfinance.id</a>
                        <span>09.00 - 17.00 WIB</span>
                    </div>
                </section>

                <section class="company-footer-column">
                    <h3>Informasi</h3>
                    <div class="company-quick-links">
                        <a href="#about">Tentang Kami</a>
                        <a href="{{ route('login') }}">Masuk ke Dashboard</a>
                    </div>
                </section>
            </div>

            <div class="company-footer-bottom">
                <p>&copy; {{ date('Y') }} Smart Finance Analytics | Kebijakan Privasi | Syarat dan Ketentuan</p>
            </div>
        </footer>
    </main>

    <script>
        (() => {
            const slider = document.querySelector("[data-hero-slider]");

            if (!slider) {
                return;
            }

            const slides = Array.from(slider.querySelectorAll(".hero-slide"));
            const dots = Array.from(slider.querySelectorAll("[data-slide-dot]"));
            const prevButton = slider.querySelector("[data-slide-prev]");
            const nextButton = slider.querySelector("[data-slide-next]");
            let currentIndex = 0;
            let autoplayId = null;

            const render = (index) => {
                currentIndex = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {
                    slide.classList.toggle("is-active", slideIndex === currentIndex);
                });

                dots.forEach((dot, dotIndex) => {
                    dot.classList.toggle("is-active", dotIndex === currentIndex);
                });
            };

            const startAutoplay = () => {
                clearInterval(autoplayId);
                autoplayId = setInterval(() => render(currentIndex + 1), 5200);
            };

            prevButton?.addEventListener("click", () => {
                render(currentIndex - 1);
                startAutoplay();
            });

            nextButton?.addEventListener("click", () => {
                render(currentIndex + 1);
                startAutoplay();
            });

            dots.forEach((dot, index) => {
                dot.addEventListener("click", () => {
                    render(index);
                    startAutoplay();
                });
            });

            slider.addEventListener("mouseenter", () => clearInterval(autoplayId));
            slider.addEventListener("mouseleave", startAutoplay);

            render(0);
            startAutoplay();
        })();
    </script>
@endsection
