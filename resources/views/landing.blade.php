@extends('layouts.app')

@section('title', 'Nexio Dashboard')

@section('content')
    @php
        $heroSlides = [
            [
                'kicker' => __('landing.slide1_kicker'),
                'title_before' => __('landing.slide1_title_before'),
                'title_accent' => __('landing.slide1_title_accent'),
                'description' => __('landing.slide1_description'),
                'button' => __('landing.slide1_button'),
                'url' => '#about',
                'image' => asset('images/slidev1.jpg'),
            ],
            [
                'kicker' => __('landing.slide2_kicker'),
                'title_before' => __('landing.slide2_title_before'),
                'title_accent' => __('landing.slide2_title_accent'),
                'description' => __('landing.slide2_description'),
                'button' => __('landing.slide2_button'),
                'url' => route('perpajakan.info'),
                'image' => asset('images/slidev2.jpg'),
            ],
            [
                'kicker' => __('landing.slide3_kicker'),
                'title_before' => __('landing.slide3_title_before'),
                'title_accent' => __('landing.slide3_title_accent'),
                'description' => __('landing.slide3_description'),
                'button' => __('landing.slide3_button'),
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
            background: var(--bg-primary);
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
            color: var(--text-main);
            background:
                radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.18), transparent 28%),
                linear-gradient(180deg, rgba(11, 17, 32, 0.9), rgba(11, 17, 32, 0.98));
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
                linear-gradient(90deg, rgba(99, 102, 241, 0.82) 0%, rgba(99, 102, 241, 0.55) 20%, rgba(8, 20, 24, 0.24) 56%, rgba(8, 20, 24, 0.58) 100%),
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
            color: #818cf8;
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

        [data-theme="light"] body {
            background: var(--bg-primary);
        }

        [data-theme="light"] .landing-shell {
            color: var(--text-main);
            background:
                radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.14), transparent 28%),
                linear-gradient(180deg, rgba(241, 245, 249, 0.96), rgba(248, 250, 252, 1));
        }

        [data-theme="light"] .landing-brand,
        [data-theme="light"] .landing-links a {
            color: rgba(15, 23, 42, 0.8);
        }

        [data-theme="light"] .landing-login {
            background: #6366f1;
            color: #ffffff !important;
        }

        [data-theme="light"] .landing-logout {
            border-color: rgba(15, 23, 42, .12);
            color: rgba(15, 23, 42, .8);
            background: rgba(15, 23, 42, .04);
        }

        [data-theme="light"] .landing-logout:hover {
            color: #0f172a;
            background: rgba(15, 23, 42, .08);
        }

        [data-theme="light"] .hero-slider {
            border-top-color: rgba(15, 23, 42, 0.08);
            border-bottom-color: rgba(15, 23, 42, 0.08);
            background: #f8fafc;
            box-shadow: 0 28px 90px rgba(15, 23, 42, 0.12);
        }

        [data-theme="light"] .hero-slide::before {
            background:
                linear-gradient(90deg, rgba(99, 102, 241, 0.28) 0%, rgba(99, 102, 241, 0.12) 22%, rgba(248, 250, 252, 0.18) 58%, rgba(248, 250, 252, 0.58) 100%),
                var(--slide-image) center / cover no-repeat;
        }

        [data-theme="light"] .hero-slide::after {
            background: linear-gradient(180deg, rgba(241, 245, 249, 0), rgba(241, 245, 249, 0.92));
        }

        [data-theme="light"] .hero-kicker,
        [data-theme="light"] .hero-copy p {
            color: rgba(15, 23, 42, 0.86);
        }

        [data-theme="light"] .hero-copy h1 {
            color: #0f172a;
        }

        [data-theme="light"] .hero-copy h1 em {
            color: #4338ca;
        }

        [data-theme="light"] .hero-visual {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0.48));
            border-color: rgba(15, 23, 42, 0.08);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.7);
        }

        [data-theme="light"] .landing-about small,
        [data-theme="light"] .service-card small {
            color: rgba(15, 23, 42, 0.48);
        }

        [data-theme="light"] .landing-about p,
        [data-theme="light"] .section-title h2,
        [data-theme="light"] .service-card h3 {
            color: #0f172a;
        }

        [data-theme="light"] .section-title h2 em {
            color: #6366f1;
        }

        [data-theme="light"] .service-card {
            border-color: rgba(15, 23, 42, 0.08);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(238, 242, 255, 0.84));
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.06);
        }

        [data-theme="light"] .service-card p {
            color: rgba(15, 23, 42, 0.66);
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
            background: #818cf8;
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
            background: #818cf8;
            border-color: #818cf8;
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
            color: #818cf8;
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
            color: #818cf8;
            font-family: Georgia, serif;
            font-weight: 500;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
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
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.08), rgba(99, 102, 241, 0.08));
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
            display: flex;
            flex-direction: column;
            gap: 16px;
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
            color: #818cf8;
        }

        .company-quick-links {
            display: grid;
            gap: 10px;
        }

        .company-quick-links a::after {
            content: ">";
            margin-left: 8px;
            color: #818cf8;
            font-size: 1.25rem;
            font-weight: 900;
        }

        .company-footer-bottom {
            padding: 22px max(20px, calc((100% - 1160px) / 2));
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            text-align: center;
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
                text-shadow: 0 0 0 rgba(99, 102, 241, 0);
            }
            50% {
                transform: translateY(-4px);
                text-shadow: 0 0 22px rgba(99, 102, 241, 0.42);
            }
        }

        @keyframes ctaPulse {
            0%, 100% {
                transform: scale(1);
                border-color: rgba(255, 255, 255, 0.16);
                box-shadow: 0 0 0 rgba(99, 102, 241, 0);
            }
            50% {
                transform: scale(1.018);
                border-color: rgba(99, 102, 241, 0.42);
                box-shadow: 0 0 26px rgba(99, 102, 241, 0.16);
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

        /* Language Toggle */
        .lang-toggle {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            border: 1px solid rgba(255, 255, 255, .16);
            border-radius: 999px;
            overflow: hidden;
            background: rgba(255, 255, 255, .06);
        }

        .lang-toggle a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            font-size: .8rem;
            font-weight: 800;
            text-decoration: none;
            color: rgba(248, 250, 252, .6);
            transition: background .2s ease, color .2s ease;
            white-space: nowrap;
        }

        .lang-toggle a:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, .08);
        }

        .lang-toggle a.is-active {
            background: rgba(99, 102, 241, .18);
            color: #818cf8;
        }

        /* Language Popup Overlay */
        .lang-popup-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(11, 17, 32, .82);
            backdrop-filter: blur(12px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .35s ease, visibility .35s ease;
        }

        .lang-popup-overlay.is-visible {
            opacity: 1;
            visibility: visible;
        }

        .lang-popup {
            width: min(420px, calc(100% - 40px));
            padding: 44px 38px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: linear-gradient(160deg, rgba(255, 255, 255, .1), rgba(99, 102, 241, .06));
            backdrop-filter: blur(32px);
            text-align: center;
            transform: translateY(24px) scale(.96);
            transition: transform .35s ease;
        }

        .lang-popup-overlay.is-visible .lang-popup {
            transform: translateY(0) scale(1);
        }

        .lang-popup h2 {
            margin: 0 0 8px;
            font-size: 1.55rem;
            color: #ffffff;
        }

        .lang-popup p {
            margin: 0 0 28px;
            color: rgba(248, 250, 252, .68);
            font-size: .95rem;
        }

        .lang-popup-options {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .lang-popup-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .12);
            background: rgba(255, 255, 255, .06);
            color: #f8fafc;
            font: inherit;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s ease, border-color .2s ease, transform .15s ease;
        }

        .lang-popup-btn:hover {
            background: rgba(99, 102, 241, .14);
            border-color: rgba(99, 102, 241, .3);
            transform: scale(1.02);
        }

        .lang-popup-btn .lang-flag {
            font-size: 1.4rem;
            line-height: 1;
        }

        /* Lang Toggle Light Theme */
        [data-theme="light"] .lang-toggle {
            border-color: rgba(15, 23, 42, .12);
            background: rgba(15, 23, 42, .04);
        }

        [data-theme="light"] .lang-toggle a:hover {
            background: rgba(15, 23, 42, .08);
            color: #0f172a;
        }

        [data-theme="light"] .lang-toggle a.is-active {
            background: rgba(99, 102, 241, .14);
            color: #4f46e5;
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .16);
            background: rgba(255, 255, 255, .06);
            color: rgba(248, 250, 252, .84);
            cursor: pointer;
            transition: all .2s ease;
            position: relative;
            margin-left: 12px;
        }

        .theme-toggle-btn:hover {
            background: rgba(255, 255, 255, .11);
            color: #ffffff;
        }

        [data-theme="light"] .theme-toggle-btn {
            border-color: rgba(15, 23, 42, .12);
            color: rgba(15, 23, 42, .8);
            background: rgba(15, 23, 42, .04);
        }

        [data-theme="light"] .theme-toggle-btn:hover {
            color: #0f172a;
            background: rgba(15, 23, 42, .08);
        }
        
        .theme-toggle-btn svg {
            width: 18px;
            height: 18px;
            position: absolute;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .theme-toggle-btn .sun-icon {
            opacity: 0;
            transform: rotate(-90deg);
        }

        .theme-toggle-btn .moon-icon {
            opacity: 1;
            transform: rotate(0);
        }

        [data-theme="light"] .theme-toggle-btn .sun-icon {
            opacity: 1;
            transform: rotate(0);
        }

        [data-theme="light"] .theme-toggle-btn .moon-icon {
            opacity: 0;
            transform: rotate(90deg);
        }
    </style>

    <main class="landing-shell">
        <nav class="landing-nav">
            <a class="landing-brand" href="{{ route('home') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <img src="{{ asset('images/nexio_logo.png') }}" alt="Nexio Logo" style="height: 24px; width: auto;">
                <span>NEXIO</span>
            </a>
            <div class="landing-links">
                <div style="display: flex; align-items: center;">
                    <div class="lang-toggle">
                        <a href="{{ route('lang.switch', 'id') }}" class="{{ app()->getLocale() === 'id' ? 'is-active' : '' }}">ID</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}">EN</a>
                    </div>
                    <button type="button" class="theme-toggle theme-toggle-btn" aria-label="Toggle Theme" data-theme-toggle>
                        <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                        <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    </button>
                </div>
                @auth
                    <div class="landing-auth-actions">
                        <a class="landing-login" style="margin-right: 12px; min-height: 42px; display: inline-flex; align-items: center; justify-content: center; padding: 0 20px; border-radius: 999px; background: #6366f1; color: #ffffff !important; font-weight: 700; text-decoration: none;" href="{{ route('dashboard') }}">Dashboard</a>
                        <form class="landing-logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="landing-logout" type="submit">{{ __('landing.logout') }}</button>
                        </form>
                    </div>
                @else
                    <a class="landing-login" href="{{ route('login') }}">{{ __('landing.login') }}</a>
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
            <small>{{ __('landing.about_label') }}</small>
            <p>{{ __('landing.about_text') }}</p>
        </section>

        <section class="landing-services">
            <div class="section-title">
                <h2>{{ __('landing.services_title_before') }} <em>{{ __('landing.services_title_accent') }}</em></h2>
            </div>

            <div class="service-grid">
                <article class="service-card">
                    <small>01</small>
                    <h3>{{ __('landing.service1_title') }}</h3>
                    <p>{{ __('landing.service1_desc') }}</p>
                </article>
                <article class="service-card">
                    <small>02</small>
                    <h3>{{ __('landing.service2_title') }}</h3>
                    <p>{{ __('landing.service2_desc') }}</p>
                </article>
                <article class="service-card">
                    <small>03</small>
                    <h3>{{ __('landing.service4_title') }}</h3>
                    <p>{{ __('landing.service4_desc') }}</p>
                </article>
                <article class="service-card">
                    <small>04</small>
                    <h3>{{ __('landing.service3_title') }}</h3>
                    <p>{{ __('landing.service3_desc') }}</p>
                </article>
            </div>
        </section>

        <footer class="company-footer">
            <div class="company-footer-main">
                <section class="company-footer-column">
                    <h3>Nexio</h3>
                    <p>{!! __('landing.footer_address') !!}</p>
                </section>

                <section class="company-footer-column">
                    <h3>{{ __('landing.footer_contact_title') }}</h3>
                    <div class="company-contact-list">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <a href="tel:+6287784070117" style="font-weight: bold; font-size: 1.1rem;">+62 877-8407-0117</a>
                            <span style="font-size: 0.9rem; opacity: 0.7;">{{ __('landing.footer_schedule_days') }}</span>
                        </div>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <a href="mailto:nexio.sf@gmail.com" style="font-weight: bold; font-size: 1.1rem;">nexio.sf@gmail.com</a>
                            <span style="font-size: 0.9rem; opacity: 0.7;">{{ __('landing.footer_schedule_hours') }}</span>
                        </div>
                    </div>
                </section>

                <section class="company-footer-column">
                    <h3>{{ __('landing.footer_info_title') }}</h3>
                    <div class="company-quick-links">
                        <a href="#about">{{ __('landing.footer_about_link') }}</a>
                        <a href="{{ route('login') }}">{{ __('landing.footer_dashboard_link') }}</a>
                    </div>
                </section>
            </div>

            <div class="company-footer-bottom">
                <p>{!! __('landing.footer_copyright', ['year' => date('Y')]) !!}</p>
            </div>
        </footer>
    </main>

    {{-- Language Selection Popup (first visit) --}}
    <div class="lang-popup-overlay" id="langPopup">
        <div class="lang-popup">
            <h2>🌐 {{ __('landing.lang_popup_title') }}</h2>
            <p>{{ __('landing.lang_popup_desc') }}</p>
            <div class="lang-popup-options">
                <a href="{{ route('lang.switch', 'id') }}" class="lang-popup-btn" data-lang-choice>
                    <span class="lang-flag">🇮🇩</span> {{ __('landing.lang_id') }}
                </a>
                <a href="{{ route('lang.switch', 'en') }}" class="lang-popup-btn" data-lang-choice>
                    <span class="lang-flag">🇬🇧</span> {{ __('landing.lang_en') }}
                </a>
            </div>
        </div>
    </div>

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

        // Language popup: show on first visit
        (() => {
            const popup = document.getElementById('langPopup');
            if (!popup) return;

            const hasChosen = localStorage.getItem('sf_lang_chosen');
            if (!hasChosen) {
                requestAnimationFrame(() => popup.classList.add('is-visible'));
            }

            popup.querySelectorAll('[data-lang-choice]').forEach(btn => {
                btn.addEventListener('click', () => {
                    localStorage.setItem('sf_lang_chosen', '1');
                });
            });

            popup.addEventListener('click', (e) => {
                if (e.target === popup) {
                    popup.classList.remove('is-visible');
                    localStorage.setItem('sf_lang_chosen', '1');
                }
            });
        })();
    </script>
@endsection


