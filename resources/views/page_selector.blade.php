@extends('layouts.app')

@section('title', 'Dashboard Selector - Smart Finance')

@section('content')
    @php
        $isLoggedIn = auth()->check();
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $ptkpTable = [
            'TK/0' => 54000000,
            'TK/1' => 58500000,
            'TK/2' => 63000000,
            'TK/3' => 67500000,
            'K/0' => 58500000,
            'K/1' => 63000000,
            'K/2' => 67500000,
            'K/3' => 72000000,
            'K/I/0' => 112500000,
            'K/I/1' => 117000000,
            'K/I/2' => 121500000,
            'K/I/3' => 126000000,
        ];
        $taxBrackets = [
            ['label' => 's.d. Rp60.000.000', 'rate' => '5%'],
            ['label' => '> Rp60.000.000 - Rp250.000.000', 'rate' => '15%'],
            ['label' => '> Rp250.000.000 - Rp500.000.000', 'rate' => '25%'],
            ['label' => '> Rp500.000.000 - Rp5.000.000.000', 'rate' => '30%'],
            ['label' => '> Rp5.000.000.000', 'rate' => '35%'],
        ];
    @endphp

    <style>
        .site-header,
        .site-footer {
            display: none !important;
        }

        body {
            background: #071316;
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

        .selector-shell {
            min-height: 100vh;
            padding: 0;
            color: #f8fafc;
            background: #061418;
        }

        .selector-head {
            min-height: 72px;
            margin: 0;
            padding: 0 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: #071b20;
        }

        .selector-logo {
            display: inline-flex;
            align-items: center;
        }

        .selector-logo span {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: #ffffff;
            color: #0f172a;
            font-size: 0.78rem;
            font-weight: 900;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.2);
        }

        .selector-logo span + span {
            margin-left: -8px;
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.55);
            backdrop-filter: blur(8px);
        }

        .selector-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: clamp(1.6rem, 4vw, 2.8rem);
            font-weight: 900;
            letter-spacing: 0.08em;
        }

        .selector-title .brand-mark {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #14b86f;
        }

        .case-link {
            padding: 10px 18px;
            border: 2px solid rgba(255, 255, 255, 0.78);
            border-radius: 999px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 900;
        }

        .head-actions {
            display: flex;
            justify-self: end;
            gap: 10px;
            align-items: center;
        }

        .head-actions form,
        .side-footer form {
            margin: 0;
        }

        .head-actions button,
        .side-footer button {
            padding: 10px 18px;
            border: 2px solid rgba(255, 255, 255, 0.78);
            border-radius: 999px;
            background: transparent;
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-weight: 900;
        }

        .head-actions button:hover,
        .side-footer button:hover {
            background: #f3c969;
            border-color: #f3c969;
            color: #052e2b;
        }

        .dashboard-frame {
            width: 100%;
            min-height: calc(100vh - 72px);
            margin: 0;
            overflow: hidden;
            border: 0;
            border-radius: 0;
            background: #08191e;
            box-shadow: none;
        }

        .browser-bar {
            display: none;
        }

        .window-dots {
            display: flex;
            gap: 8px;
        }

        .window-dots span {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }

        .window-dots span:nth-child(1) { background: #ff6b5f; }
        .window-dots span:nth-child(2) { background: #f3c969; }
        .window-dots span:nth-child(3) { background: #4ade80; }

        .address-bar {
            min-height: 28px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.09);
            color: rgba(248, 250, 252, 0.72);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .browser-actions {
            justify-self: end;
            color: rgba(248, 250, 252, 0.76);
            font-weight: 900;
        }

        .selector-app {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr);
            min-height: calc(100vh - 72px);
        }

        .selector-sidebar {
            display: flex;
            flex-direction: column;
            padding: 28px 20px;
            background: #173c45;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .app-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            color: #f8fafc;
            font-size: 0.95rem;
            font-weight: 900;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .brand-mark {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #14b86f;
        }

        .side-menu {
            display: grid;
            gap: 10px;
        }

        .side-menu button,
        .side-menu a {
            min-height: 44px;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0 14px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: rgba(248, 250, 252, 0.72);
            cursor: pointer;
            font: inherit;
            font-weight: 900;
            text-align: left;
            text-decoration: none;
        }

        .side-menu button.is-active,
        .side-menu button:hover,
        .side-menu a:hover {
            background: rgba(20, 184, 111, 0.18);
            color: #ffffff;
            box-shadow: inset 3px 0 0 #14b86f;
        }

        .side-footer {
            margin-top: auto;
            display: grid;
            gap: 10px;
        }

        .selector-content {
            padding: 34px 36px 48px;
            background: #061418;
            overflow: auto;
        }

        .content-panel {
            display: none;
            animation: panelIn 0.24s ease-out both;
        }

        .content-panel.is-active {
            display: block;
        }

        .panel-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: center;
            margin-bottom: 24px;
        }

        .panel-toolbar h1 {
            margin: 0;
            font-size: clamp(1.45rem, 3vw, 2.1rem);
        }

        .module-table {
            overflow: hidden;
            border: 1px solid rgba(91, 130, 142, 0.64);
            border-radius: 12px;
        }

        .table-head,
        .table-row {
            display: grid;
            grid-template-columns: 90px minmax(160px, 1.2fr) minmax(140px, 0.9fr) 100px minmax(150px, 0.9fr) 120px;
            gap: 16px;
            align-items: center;
            padding: 15px 18px;
        }

        .table-head {
            background: rgba(81, 124, 137, 0.72);
            color: rgba(248, 250, 252, 0.72);
            font-size: 0.84rem;
            font-weight: 900;
        }

        .table-row {
            min-height: 78px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            color: rgba(248, 250, 252, 0.75);
            text-decoration: none;
        }

        .table-row:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .table-row strong {
            display: block;
            color: #14b86f;
            margin-bottom: 4px;
        }

        .status-pill {
            width: fit-content;
            padding: 7px 10px;
            border-radius: 999px;
            color: #052e2b;
            background: #f3c969;
            font-size: 0.78rem;
            font-weight: 900;
        }

        .quick-stats,
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .full-grid {
            display: grid;
            grid-template-columns: minmax(300px, 0.9fr) minmax(360px, 1.1fr);
            gap: 16px;
            margin-top: 18px;
        }

        .tool-panel {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.11);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
        }

        .tool-panel h2 {
            margin: 0 0 12px;
            color: #ffffff;
            font-size: 1.08rem;
        }

        .tool-panel p {
            color: rgba(248, 250, 252, 0.68);
            line-height: 1.65;
        }

        .selector-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .selector-form label {
            display: grid;
            gap: 7px;
        }

        .selector-form label.full {
            grid-column: 1 / -1;
        }

        .selector-form span {
            color: rgba(248, 250, 252, 0.68);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .selector-form input,
        .selector-form select {
            width: 100%;
            min-height: 44px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 8px;
            padding: 9px 11px;
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            font: inherit;
        }

        .selector-form select option {
            color: #111827;
        }

        .selector-submit {
            grid-column: 1 / -1;
            min-height: 48px;
            border: 0;
            border-radius: 10px;
            background: #14b86f;
            color: #052e2b;
            cursor: pointer;
            font: inherit;
            font-weight: 900;
        }

        .tax-overview {
            display: grid;
            grid-template-columns: minmax(280px, 0.9fr) minmax(360px, 1.1fr);
            gap: 16px;
            margin-top: 18px;
            align-items: start;
        }

        .tax-form-panel {
            position: sticky;
            top: 18px;
        }

        .tax-reference-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .tax-summary-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 14px;
        }

        .tax-summary-cards .data-card {
            min-height: 98px;
        }

        .mini-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }

        .mini-table th,
        .mini-table td {
            padding: 11px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(248, 250, 252, 0.76);
            text-align: left;
        }

        .mini-table th {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.06);
        }

        .mini-table td:last-child,
        .mini-table th:last-child {
            text-align: right;
        }

        .quick-card,
        .feature-card,
        .data-card {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.11);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
        }

        .quick-card span,
        .feature-card span,
        .data-card span {
            color: rgba(248, 250, 252, 0.58);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .quick-card strong,
        .data-card strong {
            display: block;
            margin-top: 8px;
            color: #ffffff;
            font-size: 1.4rem;
        }

        .feature-card h2 {
            margin: 14px 0 10px;
            color: #14b86f;
        }

        .feature-card p {
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.65;
        }

        .panel-copy {
            max-width: 760px;
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        @keyframes panelIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 980px) {
            .selector-head {
                flex-direction: column;
                padding-block: 18px;
            }

            .case-link {
                justify-self: center;
            }

            .selector-app {
                grid-template-columns: 1fr;
            }

            .selector-sidebar {
                position: sticky;
                top: 0;
                z-index: 2;
                padding: 14px;
            }

            .side-menu {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .side-footer {
                display: none;
            }

            .table-head {
                display: none;
            }

            .table-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .quick-stats,
            .feature-grid,
            .data-grid,
            .full-grid,
            .tax-overview,
            .tax-summary-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .panel-toolbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .side-menu {
                grid-template-columns: 1fr;
            }

            .selector-form {
                grid-template-columns: 1fr;
            }
        }
        /* Responsive sidebar hardening: keep every menu label readable at any browser size. */
        .selector-app {
            grid-template-columns: clamp(220px, 20vw, 280px) minmax(0, 1fr);
        }

        .selector-sidebar {
            min-width: 0;
            overflow-x: hidden;
            position: sticky;
            top: 0;
            height: 100vh;
            max-height: 100vh;
            padding-left: clamp(14px, 1.6vw, 22px);
            padding-right: clamp(14px, 1.6vw, 22px);
        }

        .side-menu {
            flex: 1 1 auto;
            align-content: start;
        }

        .side-footer {
            flex: 0 0 auto;
            margin-top: auto;
        }

        .selector-content {
            min-width: 0;
        }

        .app-brand {
            min-width: 0;
            line-height: 1.2;
            overflow-wrap: anywhere;
        }

        .side-menu button,
        .side-menu a,
        .side-footer a {
            min-width: 0;
            height: auto;
            min-height: 44px;
            padding-top: 10px;
            padding-bottom: 10px;
            line-height: 1.25;
            white-space: normal;
            overflow-wrap: anywhere;
        }

        @media (max-width: 1180px) {
            .selector-app {
                grid-template-columns: minmax(190px, 22vw) minmax(0, 1fr);
            }
        }

        @media (max-width: 980px) {
            .selector-app {
                grid-template-columns: 1fr;
            }

            .selector-sidebar {
                position: sticky;
                top: 0;
                z-index: 5;
                height: auto;
                max-height: none;
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, .12);
            }

            .app-brand {
                margin-bottom: 14px;
            }

            .side-menu {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }

            .side-footer {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                margin-top: 12px;
            }
        }

        @media (max-width: 620px) {
            .side-menu,
            .side-footer {
                grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            }
        }
        /* Fixed desktop sidebar: it stays visible while long panels scroll or browser zoom changes. */
        @media (min-width: 981px) {
            .selector-app {
                display: block;
                min-height: 100vh;
            }

            .selector-sidebar {
                position: fixed;
                inset: 0 auto 0 0;
                z-index: 20;
                width: clamp(220px, 20vw, 280px);
                height: 100vh;
                height: 100dvh;
                max-height: 100vh;
                max-height: 100dvh;
                overflow-y: auto;
                overscroll-behavior: contain;
            }

            .selector-content {
                margin-left: clamp(220px, 20vw, 280px);
                min-height: 100vh;
            }

            .side-menu {
                flex: 1 1 auto;
                align-content: start;
            }

            .side-footer {
                flex: 0 0 auto;
                margin-top: auto;
            }
        }

        @media (min-width: 981px) and (max-height: 680px) {
            .selector-sidebar {
                padding-top: 18px;
                padding-bottom: 18px;
            }

            .app-brand {
                margin-bottom: 18px;
            }

            .side-menu {
                gap: 6px;
            }

            .side-footer {
                gap: 6px;
                margin-top: 18px;
            }
        }
        /* Lock the shell: only the main content scrolls, the left sidebar stays still. */
        @media (min-width: 981px) {
            html,
            body {
                height: 100%;
                overflow: hidden;
            }

            .selector-app {
                height: 100vh;
                height: 100dvh;
                min-height: 0;
                overflow: hidden;
            }

            .selector-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                z-index: 50;
                display: flex;
                flex-direction: column;
                width: clamp(220px, 20vw, 280px);
                height: 100vh;
                height: 100dvh;
                max-height: 100vh;
                max-height: 100dvh;
                overflow-y: auto;
                transform: none !important;
            }

            .selector-content {
                height: 100vh;
                height: 100dvh;
                min-height: 0;
                margin-left: clamp(220px, 20vw, 280px);
                overflow-y: auto;
                overflow-x: hidden;
                scroll-behavior: smooth;
            }

            .side-menu {
                flex: 1 1 auto;
            }

            .side-footer {
                margin-top: auto;
                padding-bottom: max(0px, env(safe-area-inset-bottom));
            }
        }
    </style>

    <main class="selector-shell">
        <header class="selector-head">
            <div class="selector-logo"><span>SF</span><span>UI</span></div>
            <div class="selector-title"><span class="brand-mark" aria-hidden="true"></span> SMART FINANCE</div>
            <div class="head-actions">
                <a class="case-link" href="{{ route('profile') }}">Profile</a>
                @if ($isLoggedIn)
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a class="case-link" href="{{ route('login') }}">Login</a>
                @endif
            </div>
        </header>

        <section class="dashboard-frame">
            <div class="selector-app">
                <aside class="selector-sidebar">
                    <div class="app-brand"><span class="brand-mark"></span> SMART FINANCE</div>
                    <nav class="side-menu" aria-label="Dashboard selector">
                        <button type="button" class="is-active" data-panel-target="dashboard">Dashboard</button>
                    </nav>
                    <nav class="side-footer side-menu">
                        <a href="{{ route('profile') }}">Profile</a>
                        @if ($isLoggedIn)
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}">Login</a>
                        @endif
                    </nav>
                </aside>

                <section class="selector-content">
                    <div id="panel-dashboard" class="content-panel is-active">
                        <div class="panel-toolbar">
                            <h1>Dashboard Selector</h1>
                        </div>

                        <div class="module-table">
                            <div class="table-head">
                                <span>Kode</span><span>Halaman</span><span>Kategori</span><span>Fitur</span><span>Prioritas</span><span>Status</span>
                            </div>
                            <a class="table-row" href="{{ route('finance.index') }}">
                                <span>SFD-01</span>
                                <span><strong>Smart Finance</strong> Analisa arus kas, rasio tabungan, cicilan, dan dana darurat.</span>
                                <span>Financial Analysis</span><span>6 tools</span><span>Core dashboard</span><span class="status-pill">ACTIVE</span>
                            </a>
                            <a class="table-row" href="{{ route('perpajakan.index') }}">
                                <span>TAX-02</span>
                                <span><strong>Perpajakan</strong> Estimasi PPh orang pribadi dengan PTKP dan tarif progresif.</span>
                                <span>Tax Calculator</span><span>PTKP + PKP</span><span>Compliance</span><span class="status-pill">READY</span>
                            </a>
                            <a class="table-row" href="{{ route('stata') }}">
                                <span>STA-03</span>
                                <span><strong>Stata</strong> Korelasi, regresi linear, dan statistik deskriptif.</span>
                                <span>Economic Stats</span><span>3 modules</span><span>Research</span><span class="status-pill">DRAFT</span>
                            </a>
                        </div>

                        <div class="quick-stats">
                            <div class="quick-card"><span>Total Halaman</span><strong>3</strong></div>
                            <div class="quick-card"><span>Dashboard Aktif</span><strong>2</strong></div>
                            <div class="quick-card"><span>Analisis Statistik</span><strong>1</strong></div>
                        </div>
                    </div>

                    <div id="panel-finance" class="content-panel">
                        <div class="panel-toolbar">
                            <h1>Smart Finance</h1>
                        </div>
                        <p class="panel-copy">Modul ini membantu membaca kesehatan keuangan dari arus kas, rasio pengeluaran, tabungan, cicilan, dan kesiapan dana darurat.</p>

                        <div class="full-grid">
                            <form class="tool-panel selector-form" action="{{ route('finance.analyze') }}" method="POST">
                                @csrf
                                <label class="full"><span>Periode</span><input type="text" name="periode" value="{{ date('F Y') }}" required></label>
                                <label><span>Total pemasukan</span><input type="number" name="pemasukan" min="0" step="1000" placeholder="15000000" required></label>
                                <label><span>Kebutuhan pokok</span><input type="number" name="kebutuhan_pokok" min="0" step="1000" placeholder="4500000" required></label>
                                <label><span>Transportasi</span><input type="number" name="transportasi" min="0" step="1000" placeholder="1000000" required></label>
                                <label><span>Cicilan/utang</span><input type="number" name="cicilan" min="0" step="1000" placeholder="2500000" required></label>
                                <label><span>Gaya hidup</span><input type="number" name="gaya_hidup" min="0" step="1000" placeholder="1500000" required></label>
                                <label><span>Tabungan</span><input type="number" name="tabungan" min="0" step="1000" placeholder="2000000" required></label>
                                <label><span>Investasi</span><input type="number" name="investasi" min="0" step="1000" placeholder="1000000" required></label>
                                <label><span>Dana darurat</span><input type="number" name="dana_darurat" min="0" step="1000" placeholder="25000000" required></label>
                                <label><span>Target tabungan</span><input type="number" name="target_tabungan" min="0" step="1000" placeholder="50000000"></label>
                                <button class="selector-submit" type="submit">Hitung Analisa Lengkap</button>
                            </form>

                            <div class="tool-panel">
                                <h2>Data dan Output yang Tersedia</h2>
                                <div class="feature-grid" style="grid-template-columns: 1fr; margin-top: 0;">
                                    <article class="feature-card"><span>01</span><h2>Cashflow</h2><p>Bandingkan pemasukan, pengeluaran, tabungan, investasi, dan sisa saldo.</p></article>
                                    <article class="feature-card"><span>02</span><h2>Rasio</h2><p>Rasio pengeluaran, rasio cicilan, savings rate, dan ketahanan dana darurat.</p></article>
                                    <article class="feature-card"><span>03</span><h2>Rekomendasi</h2><p>Saran otomatis berdasarkan skor kesehatan finansial: Sehat, Waspada, atau Berisiko.</p></article>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="panel-tax" class="content-panel">
                        <div class="panel-toolbar">
                            <h1>Perpajakan</h1>
                        </div>
                        <p class="panel-copy">Modul perpajakan menghitung estimasi PPh orang pribadi menggunakan PTKP, PKP yang dibulatkan, dan tarif progresif.</p>

                        <div class="tax-overview">
                            <form class="tool-panel selector-form tax-form-panel" action="{{ route('perpajakan.calculate') }}" method="POST">
                                @csrf
                                <h2 style="grid-column: 1 / -1;">Input Perhitungan</h2>
                                <label class="full"><span>Nama wajib pajak</span><input type="text" name="nama_wajib_pajak" placeholder="Nama wajib pajak" required></label>
                                <label><span>Penghasilan bulanan</span><input type="number" name="penghasilan_bulanan" min="0" step="1000" placeholder="15000000" required></label>
                                <label><span>Biaya/pengurang bulanan</span><input type="number" name="pengeluaran_bulanan" min="0" step="1000" placeholder="2000000" required></label>
                                <label class="full">
                                    <span>Status wajib pajak</span>
                                    <select name="status_wajib_pajak" required>
                                        <option value="" selected disabled>Pilih status</option>
                                        @foreach ($ptkpTable as $status => $amount)
                                            <option value="{{ $status }}">{{ $status }} - PTKP {{ $formatRupiah($amount) }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <button class="selector-submit" type="submit">Hitung Pajak Lengkap</button>
                            </form>

                            <div class="tax-reference-grid">
                                <div class="tax-summary-cards">
                                    <div class="data-card"><span>Status PTKP</span><strong>12</strong></div>
                                    <div class="data-card"><span>Tarif</span><strong>5 Layer</strong></div>
                                    <div class="data-card"><span>Output</span><strong>PPh OP</strong></div>
                                </div>
                                <div class="tool-panel">
                                    <h2>PTKP</h2>
                                    <table class="mini-table">
                                        <thead><tr><th>Status</th><th>PTKP</th></tr></thead>
                                        <tbody>
                                            @foreach ($ptkpTable as $status => $amount)
                                                <tr><td>{{ $status }}</td><td>{{ $formatRupiah($amount) }}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tool-panel">
                                    <h2>Tarif Progresif</h2>
                                    <table class="mini-table">
                                        <thead><tr><th>Lapisan PKP</th><th>Tarif</th></tr></thead>
                                        <tbody>
                                            @foreach ($taxBrackets as $bracket)
                                                <tr><td>{{ $bracket['label'] }}</td><td>{{ $bracket['rate'] }}</td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="panel-stata" class="content-panel">
                        <div class="panel-toolbar">
                            <h1>Stata</h1>
                        </div>
                        <p class="panel-copy">Data contoh tetap tersedia: GDP, inflasi, pengangguran, dan investasi untuk preview statistik ekonomi.</p>
                        <div class="data-grid">
                            <div class="data-card"><span>Observasi</span><strong>5 tahun</strong></div>
                            <div class="data-card"><span>GDP rata-rata</span><strong>1.156</strong></div>
                            <div class="data-card"><span>Inflasi rata-rata</span><strong>2,78%</strong></div>
                            <div class="data-card"><span>Pengangguran</span><strong>5,36%</strong></div>
                        </div>
                        <div class="tool-panel" style="margin-top: 18px;">
                            <h2>Tabel Statistik Deskriptif</h2>
                            <table class="mini-table">
                                <thead><tr><th>Variabel</th><th>Obs</th><th>Mean</th><th>Min</th><th>Max</th></tr></thead>
                                <tbody>
                                    <tr><td>GDP</td><td>5</td><td>1.156</td><td>1.080</td><td>1.250</td></tr>
                                    <tr><td>Inflasi</td><td>5</td><td>2,78</td><td>1,80</td><td>3,50</td></tr>
                                    <tr><td>Pengangguran</td><td>5</td><td>5,36</td><td>5,00</td><td>6,00</td></tr>
                                    <tr><td>Investasi</td><td>5</td><td>273</td><td>250</td><td>300</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="feature-grid">
                            <article class="feature-card"><span>01</span><h2>Korelasi</h2><p>Analisa hubungan antar variabel numerik.</p></article>
                            <article class="feature-card"><span>02</span><h2>Regresi</h2><p>Fondasi model linear untuk data ekonomi.</p></article>
                            <article class="feature-card"><span>03</span><h2>Deskriptif</h2><p>Mean, standar deviasi, minimum, maksimum, dan observasi.</p></article>
                        </div>
                    </div>

                    <div id="panel-home" class="content-panel">
                        <div class="panel-toolbar">
                            <h1>Beranda Awal</h1>
                        </div>
                        <p class="panel-copy">Beranda awal adalah halaman publik sebelum login, berisi pengenalan Smart Finance dan CTA menuju halaman login.</p>
                        <div class="feature-grid">
                            <article class="feature-card"><span>Landing</span><h2>Public Page</h2><p>Halaman pertama untuk mengenalkan aplikasi.</p></article>
                            <article class="feature-card"><span>Login</span><h2>Access Gate</h2><p>Arahkan user ke halaman login modern.</p></article>
                            <article class="feature-card"><span>Selector</span><h2>Dashboard Hub</h2><p>Setelah login user memilih modul yang dibutuhkan.</p></article>
                        </div>
                    </div>
                </section>
            </div>
        </section>
    </main>

    <script>
        document.querySelectorAll('[data-panel-target]').forEach((button) => {
            button.addEventListener('click', () => {
                const target = button.dataset.panelTarget;

                document.querySelectorAll('[data-panel-target]').forEach((item) => {
                    item.classList.toggle('is-active', item === button);
                });

                document.querySelectorAll('.content-panel').forEach((panel) => {
                    panel.classList.toggle('is-active', panel.id === `panel-${target}`);
                });
            });
        });
    </script>
@endsection
