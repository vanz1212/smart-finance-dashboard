@extends('layouts.app')

@section('title', 'Stata-like Analysis - Smart Finance Dashboard')

@section('content')
    <style>
        .stata-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .stata-inner { width: min(1180px, 100%); margin: 0 auto; }
        .stata-topbar { display: flex; justify-content: space-between; gap: 18px; align-items: center; margin-bottom: 34px; }
        .stata-nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .stata-nav a { padding: 10px 14px; border: 1px solid rgba(255,255,255,.12); border-radius: 999px; color: rgba(248,250,252,.78); text-decoration: none; font-weight: 800; background: rgba(255,255,255,.05); }
        .stata-nav a.is-active, .stata-nav a:hover { color: #052e2b; background: #f3c969; border-color: #f3c969; }

        .stata-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, .45fr);
            gap: 24px;
            align-items: stretch;
            margin-bottom: 24px;
        }

        .stata-panel {
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13,47,51,.78), rgba(6,24,32,.84));
            box-shadow: 0 28px 80px rgba(0,0,0,.34);
            backdrop-filter: blur(16px);
        }

        .stata-panel-inner { padding: 26px; }
        .stata-kicker { color: #f3c969; font-size: .8rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .stata-hero h1 { margin: 14px 0 0; font-size: clamp(2.4rem, 6vw, 5rem); line-height: .98; letter-spacing: 0; }
        .stata-hero p { max-width: 720px; margin: 18px 0 0; color: rgba(248,250,252,.72); line-height: 1.7; }
        .stata-action { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; margin-top: 26px; padding: 0 20px; border-radius: 999px; background: #f3c969; color: #052e2b; text-decoration: none; font-weight: 900; }
        .stata-stat { display: grid; align-content: center; gap: 18px; }
        .stata-stat strong { display: block; font-size: 2.4rem; color: #fff; }
        .stata-stat span { color: rgba(248,250,252,.66); line-height: 1.5; }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .feature-card {
            min-height: 230px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(255,255,255,.07), rgba(243,201,105,.07));
            box-shadow: 0 22px 60px rgba(0,0,0,.24);
        }

        .feature-card small { color: #f3c969; font-weight: 900; }
        .feature-card h2 { margin: 42px 0 12px; font-size: 1.45rem; line-height: 1.05; }
        .feature-card p { margin: 0; color: rgba(248,250,252,.68); line-height: 1.65; }

        .stata-console {
            margin-top: 22px;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: rgba(0,0,0,.34);
            font-family: Consolas, 'Courier New', monospace;
            color: rgba(248,250,252,.82);
            line-height: 1.7;
        }

        .stata-console span { color: #14b8a6; }

        .stata-data-panel {
            margin-top: 22px;
        }

        .stata-data-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .stata-data-card {
            padding: 16px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 12px;
            background: rgba(255,255,255,.06);
        }

        .stata-data-card span {
            display: block;
            color: rgba(248,250,252,.56);
            font-size: .82rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stata-data-card strong {
            color: #fff;
            font-size: 1.25rem;
        }

        .stata-output-table {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
        }

        .stata-output-table th,
        .stata-output-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            text-align: left;
            color: rgba(248,250,252,.76);
        }

        .stata-output-table th {
            color: #fff;
            background: rgba(255,255,255,.06);
        }

        @media (max-width: 900px) {
            .stata-topbar { align-items: flex-start; flex-direction: column; }
            .stata-hero, .feature-grid, .stata-data-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 620px) {
            .stata-workspace { margin: -24px; padding-inline: 14px; }
        }
        /* Full-page refinement: keep the standalone Stata page aligned with the main selector UI. */
        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 82% 0%, rgba(24, 191, 117, .16), transparent 34%),
                linear-gradient(135deg, #06191b 0%, #071f22 48%, #091011 100%) !important;
            color: #f8fafc;
        }

        body {
            display: block;
        }

        body::before {
            opacity: .16 !important;
        }

        .page-shell,
        .container,
        .dashboard-shell,
        .stata-shell,
        main {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell,
        .container,
        .dashboard-shell,
        .stata-shell {
            padding-left: clamp(18px, 4vw, 56px) !important;
            padding-right: clamp(18px, 4vw, 56px) !important;
        }

        header,
        .topbar,
        .navbar,
        nav {
            max-width: none !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        .hero,
        .hero-grid,
        .stats-hero,
        .content-grid,
        .feature-grid,
        .cards-grid,
        .module-grid {
            width: 100% !important;
            max-width: none !important;
            box-sizing: border-box;
        }

        .hero,
        .stats-hero {
            min-height: auto !important;
            margin-top: clamp(18px, 3vw, 36px) !important;
        }

        .hero h1,
        .stats-hero h1,
        h1 {
            max-width: 100%;
            font-size: clamp(42px, 8vw, 96px) !important;
            line-height: .95 !important;
            letter-spacing: -.06em;
        }

        .panel,
        .card,
        .feature-card,
        .dataset-card,
        .metric-card,
        .hero-card {
            border: 1px solid rgba(148, 163, 184, .22) !important;
            background: rgba(12, 34, 36, .88) !important;
            box-shadow: none !important;
            backdrop-filter: blur(12px);
        }

        .nav-links,
        .menu,
        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .nav-links a,
        .menu a,
        .tabs a,
        .btn,
        button,
        [role="button"] {
            white-space: normal;
        }

        table {
            width: 100%;
        }

        .table-wrap,
        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 900px) {
            .hero,
            .hero-grid,
            .stats-hero,
            .content-grid {
                grid-template-columns: 1fr !important;
            }

            header,
            .topbar,
            .navbar {
                align-items: flex-start !important;
                gap: 14px !important;
            }

            .nav-links,
            .menu,
            .tabs {
                justify-content: flex-start;
            }
        }

        @media (max-width: 620px) {
            .page-shell,
            .container,
            .dashboard-shell,
            .stata-shell {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }

            .hero h1,
            .stats-hero h1,
            h1 {
                font-size: clamp(34px, 13vw, 56px) !important;
            }
        }
    </style>

    <main class="stata-workspace">
        <div class="stata-inner">
            <div class="stata-topbar">
                <strong>SmartFinance.</strong>
                <nav class="stata-nav">
                    <a href="{{ route('page.selector') }}">Beranda</a>
                    <a href="{{ route('finance.index') }}">Smart Finance</a>
                    <a href="{{ route('perpajakan.index') }}">Perpajakan</a>
                    <a class="is-active" href="{{ route('stata') }}">Stata</a>
                    <a href="{{ route('login') }}">Login</a>
                </nav>
            </div>

            <section class="stata-hero">
                <div class="stata-panel stata-panel-inner">
                    <span class="stata-kicker">Economic Analysis</span>
                    <h1>Stata-like Analysis</h1>
                    <p>Ruang analisis statistik untuk data ekonomi: korelasi, regresi linear, statistik deskriptif, dan interpretasi output yang mudah dibaca.</p>
                    <a class="stata-action" href="{{ route('page.selector') }}">Kembali ke Selector</a>
                </div>

                <aside class="stata-panel stata-panel-inner stata-stat">
                    <div>
                        <strong>3+</strong>
                        <span>Modul analisis utama yang siap dikembangkan untuk workflow akademik dan ekonomi.</span>
                    </div>
                </aside>
            </section>

            <section class="feature-grid">
                <article class="feature-card">
                    <small>01</small>
                    <h2>Korelasi</h2>
                    <p>Membantu membaca hubungan antar variabel numerik dan mengidentifikasi pola awal.</p>
                </article>
                <article class="feature-card">
                    <small>02</small>
                    <h2>Regresi Linear</h2>
                    <p>Fondasi analisis model ekonomi untuk melihat pengaruh variabel independen terhadap dependen.</p>
                </article>
                <article class="feature-card">
                    <small>03</small>
                    <h2>Statistik Deskriptif</h2>
                    <p>Ringkasan mean, standar deviasi, minimum, maksimum, dan jumlah observasi.</p>
                </article>
            </section>

            <section class="stata-panel stata-panel-inner stata-data-panel">
                <span class="stata-kicker">Dataset Preview</span>
                <h2>Data Analisis Ekonomi</h2>
                <p>Ringkasan data contoh tetap tersedia sebagai dasar pengembangan modul Stata-like: GDP, inflasi, pengangguran, dan investasi.</p>

                <div class="stata-data-grid">
                    <div class="stata-data-card"><span>Observasi</span><strong>5 tahun</strong></div>
                    <div class="stata-data-card"><span>GDP rata-rata</span><strong>1.156</strong></div>
                    <div class="stata-data-card"><span>Inflasi rata-rata</span><strong>2,78%</strong></div>
                    <div class="stata-data-card"><span>Pengangguran rata-rata</span><strong>5,36%</strong></div>
                </div>

                <table class="stata-output-table">
                    <thead>
                        <tr>
                            <th>Variabel</th>
                            <th>Obs</th>
                            <th>Mean</th>
                            <th>Min</th>
                            <th>Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>GDP</td><td>5</td><td>1.156</td><td>1.080</td><td>1.250</td></tr>
                        <tr><td>Inflasi</td><td>5</td><td>2,78</td><td>1,80</td><td>3,50</td></tr>
                        <tr><td>Pengangguran</td><td>5</td><td>5,36</td><td>5,00</td><td>6,00</td></tr>
                        <tr><td>Investasi</td><td>5</td><td>273</td><td>250</td><td>300</td></tr>
                    </tbody>
                </table>
            </section>

            <section class="stata-console">
                <div><span>.</span> summarize income expense saving</div>
                <div><span>.</span> correlate gdp inflation unemployment</div>
                <div><span>.</span> regress growth investment inflation</div>
            </section>
        </div>
    </main>
@endsection
