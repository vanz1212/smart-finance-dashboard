@extends('layouts.app')

@section('title', 'Smart Finance Dashboard')

@section('content')
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
            min-height: 100vh;
            color: #f8fafc;
            background:
                radial-gradient(circle at 50% 0%, rgba(243, 201, 105, 0.18), transparent 28%),
                linear-gradient(180deg, rgba(5, 12, 15, 0.88), rgba(5, 12, 15, 0.98)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .landing-nav {
            width: min(1160px, calc(100% - 40px));
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 26px 0;
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

        .landing-logout {
            margin: 0;
        }

        .landing-logout button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 0 20px;
            border: 0;
            border-radius: 999px;
            background: #f3c969;
            color: #052e2b;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
        }

        .landing-hero {
            width: min(1120px, calc(100% - 40px));
            margin: 0 auto;
            padding: 84px 0 72px;
            text-align: center;
        }

        .hero-frame {
            width: min(760px, 100%);
            margin: 0 auto;
            position: relative;
            padding: 46px 28px 38px;
            border: 1px solid rgba(243, 201, 105, 0.32);
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.38), rgba(6, 24, 32, 0.26));
            box-shadow: 0 28px 90px rgba(0, 0, 0, 0.38);
            backdrop-filter: blur(10px);
            animation: frameRise 0.8s ease-out both;
        }

        .hero-frame::before,
        .hero-frame::after {
            content: "";
            width: 9px;
            height: 9px;
            position: absolute;
            background: #f3c969;
            box-shadow: 0 0 18px rgba(243, 201, 105, 0.7);
        }

        .hero-frame::before {
            top: -5px;
            left: -5px;
        }

        .hero-frame::after {
            right: -5px;
            bottom: -5px;
        }

        .hero-kicker {
            display: inline-flex;
            margin-bottom: 22px;
            color: #14b8a6;
            font-size: 0.78rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            animation: softReveal 0.7s ease-out 0.15s both;
        }

        .hero-frame h1 {
            max-width: 680px;
            margin: 0 auto;
            font-size: clamp(3rem, 8vw, 5.8rem);
            line-height: 0.95;
            font-weight: 800;
            letter-spacing: 0;
            animation: softReveal 0.75s ease-out 0.25s both;
        }

        .hero-frame h1 em {
            color: #f3c969;
            font-family: Georgia, serif;
            font-style: italic;
            font-weight: 500;
            display: inline-block;
            animation: wordFloat 3.2s ease-in-out infinite;
        }

        .hero-frame p {
            max-width: 620px;
            margin: 24px auto 0;
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
            animation: softReveal 0.75s ease-out 0.38s both;
        }

        .landing-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-height: 48px;
            margin-top: 32px;
            padding: 0 22px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
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

        .landing-about {
            width: min(960px, calc(100% - 40px));
            margin: 0 auto;
            padding: 72px 0 40px;
            text-align: center;
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
            width: min(1160px, calc(100% - 40px));
            margin: 0 auto;
            padding: 56px 0 88px;
        }

        .section-title {
            display: flex;
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

        .service-card:nth-child(4) {
            animation-delay: 0.24s;
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

        .company-card {
            width: min(100%, 980px);
            margin: 24px auto 0;
            padding: 28px 30px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.82), rgba(7, 27, 32, 0.92));
            box-shadow: 0 22px 60px rgba(0, 0, 0, 0.18);
        }

        .company-card h3 {
            margin: 0 0 10px;
            font-size: 1.35rem;
            line-height: 1.1;
        }

        .company-card > p {
            margin: 0 0 22px;
            max-width: 760px;
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
        }

        .company-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px 22px;
        }

        .company-item {
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .company-item span {
            display: block;
            margin-bottom: 6px;
            color: rgba(248, 250, 252, 0.46);
            font-size: 0.92rem;
            font-weight: 700;
        }

        .company-item strong {
            color: #ffffff;
            font-size: 1rem;
            line-height: 1.5;
            font-weight: 800;
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
            .hero-frame,
            .hero-kicker,
            .hero-frame h1,
            .hero-frame h1 em,
            .hero-frame p,
            .landing-cta,
            .landing-cta::after,
            .landing-about p,
            .service-card {
                animation: none;
            }
        }

        @media (max-width: 900px) {
            .landing-links a:not(.landing-login) {
                display: none;
            }

            .service-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .landing-nav,
            .landing-hero,
            .landing-about,
            .landing-services {
                width: min(100% - 28px, 1160px);
            }

            .hero-frame {
                padding: 36px 18px 30px;
            }

            .section-title {
                align-items: start;
                flex-direction: column;
            }

            .service-grid {
                grid-template-columns: 1fr;
            }

            .company-info {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="landing-shell">
        <nav class="landing-nav">
            <a class="landing-brand" href="{{ route('home') }}">SmartFinance.</a>
            <div class="landing-links">
                @auth
                    <a class="landing-login" href="{{ route('profile') }}">Profile</a>
                @endauth
                <a class="landing-login" href="{{ route('login') }}">Login</a>
            </div>
        </nav>

        <section class="landing-hero">
            <div class="hero-frame">
                <span class="hero-kicker">Finance Intelligence</span>
                <h1>Designing Financial <em>futures</em></h1>
                <p>Kelola analisa keuangan, estimasi pajak, dan insight finansial dalam dashboard yang sederhana, fokus, dan siap dipakai.</p>
                <a class="landing-cta" href="#about"><span>→</span> Pelajari lebih lanjut tentang kami</a>
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

            <div class="company-card">
                <h3>Informasi Perusahaan</h3>
                <p>Smart Finance Analytics Dashboard adalah platform untuk membantu analisa keuangan, perpajakan, dan statistik dalam satu tempat.</p>
                <div class="company-info">
                    <div class="company-item">
                        <span>Nama Perusahaan</span>
                        <strong>Smart Finance Analytics</strong>
                    </div>
                    <div class="company-item">
                        <span>Telepon</span>
                        <strong>+62 812-3456-7890</strong>
                    </div>
                    <div class="company-item">
                        <span>Email</span>
                        <strong>support@smartfinance.id</strong>
                    </div>
                    <div class="company-item">
                        <span>Alamat</span>
                        <strong>Jl. Finansial No. 12, Jakarta, Indonesia</strong>
                    </div>
                    <div class="company-item">
                        <span>Jam Operasional</span>
                        <strong>Senin - Jumat, 09.00 - 17.00 WIB</strong>
                    </div>
                    <div class="company-item">
                        <span>Website</span>
                        <strong>smartfinance.local</strong>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
