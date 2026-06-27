@extends('layouts.app')

@section('title', 'Smart Finance - Analisa Keuangan')
@section('body-class', 'module-page')

@section('content')
    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $formatPercent = fn ($value) => number_format($value, 1, ',', '.') . '%';
        $formatRupiahInput = function ($value) {
            if ($value === null || $value === '') {
                return '';
            }

            return number_format((float) preg_replace('/[^0-9]/', '', (string) $value), 0, ',', '.');
        };

        $translatePeriode = function($value) {
            if (preg_match('/^(\d{4})-(\d{2})$/', $value, $matches)) {
                $months = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                return $months[$matches[2]] . ' ' . $matches[1];
            }
            return $value; // Fallback
        };
    @endphp

    <style>
        .finance-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: var(--text-main);
            background:
                linear-gradient(180deg, var(--bg-primary), var(--bg-primary)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .workspace-inner {
            width: min(1180px, 100%);
            margin: 0 auto;
        }

        .workspace-topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: center;
            margin-bottom: 34px;
        }

        .workspace-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .workspace-nav a {
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 999px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 800;
            background: var(--nav-bg);
        }

        .workspace-nav a.is-active,
        .workspace-nav a:hover {
            color: var(--accent-hover);
            background: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .workspace-hero {
            display: flex;
            justify-content: space-between;
            gap: 22px;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        .workspace-kicker {
            color: var(--accent-primary);
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .workspace-hero h1 {
            margin: 12px 0 0;
            font-size: clamp(2.2rem, 5vw, 4.8rem);
            line-height: 0.98;
            letter-spacing: 0;
        }

        .workspace-hero p {
            max-width: 680px;
            margin: 16px 0 0;
            color: var(--text-muted);
            line-height: 1.7;
        }

        .status-badge {
            min-width: 126px;
            padding: 12px 16px;
            border-radius: 999px;
            color: var(--accent-hover);
            text-align: center;
            font-weight: 900;
            background: var(--accent-primary);
        }

        .status-success { background: var(--accent-primary); color: #042f2e; }
        .status-warning { background: var(--accent-primary); color: #422006; }
        .status-danger { background: #fb7185; color: #4c0519; }

        .workspace-grid {
            display: grid;
            grid-template-columns: minmax(320px, 0.92fr) minmax(380px, 1.08fr);
            gap: 22px;
            align-items: start;
        }

        .workspace-panel {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background: var(--bg-panel);
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.34);
            backdrop-filter: blur(16px);
        }

        .workspace-panel-inner {
            padding: 24px;
        }

        .panel-heading {
            margin-bottom: 20px;
        }

        .panel-heading h2 {
            margin: 0;
            font-size: 1.18rem;
        }

        .panel-heading p {
            margin: 8px 0 0;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .finance-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .finance-form-grid label {
            display: grid;
            gap: 8px;
        }

        .finance-form-grid span {
            color: var(--text-muted);
            font-size: 0.84rem;
            font-weight: 800;
        }

        .finance-form-grid input {
            min-height: 46px;
            width: 100%;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 10px 12px;
            background: var(--nav-bg);
            color: var(--text-main);
            font: inherit;
        }

        .money-field {
            position: relative;
        }

        .money-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.88rem;
            font-weight: 800;
            pointer-events: none;
        }

        .money-field input {
            padding-left: 42px;
        }

        .finance-form-grid input:focus {
            outline: 3px solid rgba(20, 184, 166, 0.18);
            border-color: var(--accent-primary);
        }

        .workspace-button {
            width: 100%;
            min-height: 50px;
            margin-top: 18px;
            border: 0;
            border-radius: 999px;
            background: var(--accent-primary);
            color: var(--accent-hover);
            cursor: pointer;
            font: inherit;
            font-weight: 900;
        }

        .metric-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .metric-tile {
            padding: 16px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--nav-bg);
        }

        .metric-tile span,
        .goal-card span {
            display: block;
            margin-bottom: 8px;
            color: rgba(248, 250, 252, 0.62);
            font-size: 0.82rem;
            font-weight: 800;
        }

        .metric-tile strong,
        .goal-card strong {
            color: var(--text-main);
            font-size: 1.1rem;
        }

        .ratio-stack {
            display: grid;
            gap: 16px;
            margin-top: 22px;
        }

        .ratio-line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        .track {
            height: 10px;
            overflow: hidden;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.13);
        }

        .track span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: var(--accent-primary);
        }

        .track.good span { background: var(--accent-primary); }
        .track.debt span { background: #fb7185; }

        .insight-box,
        .empty-state,
        .goal-card {
            margin-top: 22px;
            padding: 18px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--nav-bg);
        }

        .insight-box h3,
        .empty-state h3 {
            margin: 0 0 10px;
        }

        .insight-box ul {
            margin: 0;
            padding-left: 20px;
            color: var(--text-muted);
            line-height: 1.75;
        }

        .breakdown-panel {
            margin-top: 22px;
        }

        .breakdown-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(240px, 0.42fr);
            gap: 18px;
        }

        .breakdown-list {
            display: grid;
            gap: 10px;
        }

        .breakdown-item {
            display: grid;
            grid-template-columns: minmax(140px, 1fr) minmax(120px, auto) minmax(74px, auto);
            gap: 12px;
            padding: 13px 14px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: var(--nav-bg);
        }

        .breakdown-item em {
            color: var(--accent-primary);
            font-style: normal;
            font-weight: 900;
            text-align: right;
        }

        .breakdown-item.highlight {
            border-color: rgba(20, 184, 166, 0.38);
            background: rgba(20, 184, 166, 0.09);
        }

        /* Monthly comparison styles */
        .comparison-panel {
            margin-top: 34px;
            padding: 24px;
        }

        .chart-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .chart-filter-controls {
            display: flex;
            gap: 8px;
            background: var(--nav-bg);
            padding: 4px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .btn-filter {
            padding: 6px 14px;
            border-radius: 6px;
            background: transparent;
            color: var(--text-muted);
            border: none;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 800;
            transition: all 0.2s ease;
        }

        .btn-filter:hover {
            color: var(--text-main);
            background: var(--nav-bg);
        }

        .btn-filter.is-active {
            background: var(--accent-primary);
            color: var(--accent-hover);
        }

        .comparison-chart-wrapper {
            background: rgba(6, 24, 32, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 28px;
            position: relative;
            height: 320px;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .comparison-table th {
            color: rgba(248, 250, 252, 0.62);
            font-weight: 800;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: rgba(255, 255, 255, 0.02);
        }

        .comparison-table tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .comparison-table td {
            color: var(--text-main);
            vertical-align: middle;
        }

        .comparison-table td strong {
            color: var(--accent-primary);
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-use {
            padding: 6px 12px;
            border-radius: 6px;
            background: var(--accent-primary);
            color: #042f2e;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.78rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .btn-use:hover {
            background: #0d9488;
            transform: translateY(-1px);
        }

        .btn-delete {
            padding: 6px 12px;
            border-radius: 6px;
            background: rgba(244, 63, 94, 0.15);
            color: #fb7185;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.78rem;
            border: 1px solid rgba(244, 63, 94, 0.3);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete:hover {
            background: rgba(244, 63, 94, 0.3);
            color: var(--text-main);
            transform: translateY(-1px);
        }

        .alert-success-banner {
            padding: 14px 18px;
            border: 1px solid rgba(20, 184, 166, 0.3);
            background: rgba(20, 184, 166, 0.12);
            border-radius: 10px;
            margin-bottom: 24px;
            color: var(--accent-primary);
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .workspace-topbar,
            .workspace-hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .workspace-grid,
            .breakdown-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 620px) {
            .finance-workspace {
                margin: -24px;
                padding-inline: 14px;
            }

            .finance-form-grid,
            .metric-grid,
            .breakdown-item {
                grid-template-columns: 1fr;
            }

            .breakdown-item em {
                text-align: left;
            }

            .action-buttons {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        /* ── Dynamic expense categories ──────────────────── */
        .expense-section {
            margin: 14px 0 0;
        }

        .expense-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .expense-section-label {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .expense-section-hint {
            color: rgba(248, 250, 252, 0.4);
            font-size: 0.73rem;
        }

        .expense-row {
            display: grid;
            grid-template-columns: 1fr 1.1fr auto auto;
            gap: 9px;
            align-items: center;
            margin-bottom: 9px;
            animation: fadeInRow 0.18s ease;
        }

        @keyframes fadeInRow {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .expense-row input[type="text"] {
            min-height: 44px;
            width: 100%;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 10px 12px;
            background: var(--nav-bg);
            color: var(--text-main);
            font: inherit;
            font-size: 0.9rem;
        }

        .expense-row input[type="text"]:focus {
            outline: 3px solid rgba(20, 184, 166, 0.18);
            border-color: var(--accent-primary);
        }

        .debt-toggle {
            display: flex;
            align-items: center;
            gap: 5px;
            color: rgba(248, 250, 252, 0.62);
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
            cursor: pointer;
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--nav-bg);
            transition: all 0.18s;
        }

        .debt-toggle:has(input:checked) {
            border-color: rgba(251, 113, 133, 0.4);
            background: rgba(251, 113, 133, 0.1);
            color: #fb7185;
        }

        .debt-toggle input[type="checkbox"] {
            accent-color: #fb7185;
            width: 14px;
            height: 14px;
            cursor: pointer;
        }

        .btn-remove-expense {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(244, 63, 94, 0.22);
            border-radius: 8px;
            background: rgba(244, 63, 94, 0.07);
            color: #fb7185;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.18s;
            flex-shrink: 0;
        }

        .btn-remove-expense:hover {
            background: rgba(244, 63, 94, 0.22);
            border-color: rgba(244, 63, 94, 0.5);
        }

        .btn-add-expense {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            margin-top: 8px;
            border: 1px dashed rgba(255, 255, 255, 0.22);
            border-radius: 9px;
            background: transparent;
            color: rgba(248, 250, 252, 0.55);
            font: inherit;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add-expense:hover {
            border-color: rgba(20, 184, 166, 0.5);
            color: var(--accent-primary);
            background: rgba(20, 184, 166, 0.06);
        }

        /* ── Budget donut chart ───────────────────────────── */
        .breakdown-layout {
            grid-template-columns: minmax(0, 1fr) minmax(230px, 0.48fr) !important;
        }

        .chart-column {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .donut-chart-wrapper {
            position: relative;
            background: var(--nav-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
        }

        .donut-chart-wrapper canvas {
            max-height: 220px;
        }

        .donut-legend {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .donut-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.76rem;
            color: var(--text-muted);
        }

        .donut-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .breakdown-item.is-debt {
            border-color: rgba(251, 113, 133, 0.22);
            background: rgba(251, 113, 133, 0.06);
        }

        /* ── Category template selector ───────────────────── */
        .template-selector {
            margin-bottom: 16px;
        }

        .template-label {
            color: var(--text-muted);
            font-size: 0.82rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 8px;
            display: block;
        }

        .template-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 8px;
            margin-bottom: 10px;
        }

        .template-btn {
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: var(--nav-bg);
            color: var(--text-muted);
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 700;
            transition: all 0.18s;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .template-btn:hover {
            border-color: rgba(20, 184, 166, 0.4);
            background: rgba(20, 184, 166, 0.08);
            color: var(--accent-primary);
        }

        .template-name {
            font-weight: 800;
            display: block;
        }

        .template-desc {
            font-size: 0.65rem;
            color: rgba(248, 250, 252, 0.5);
            display: block;
        }

        /* ── Category recommendations panel ──────────────── */
        .recommendations-panel {
            margin-top: 22px;
            padding: 18px;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background: var(--nav-bg);
        }

        .recommendations-title {
            margin: 0 0 12px;
            font-size: 0.95rem;
            font-weight: 800;
        }

        .recommendation-item {
            display: grid;
            grid-template-columns: minmax(140px, 1fr) auto;
            gap: 12px;
            padding: 10px 12px;
            margin-bottom: 8px;
            border-left: 3px solid;
            border-radius: 6px;
            background: var(--nav-bg);
            font-size: 0.82rem;
        }

        .recommendation-item.ok {
            border-left-color: var(--accent-primary);
        }

        .recommendation-item.warning {
            border-left-color: var(--accent-primary);
        }

        .recommendation-item.critical {
            border-left-color: #fb7185;
        }

        .recommendation-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .recommendation-label {
            color: var(--text-muted);
            font-weight: 700;
        }

        .recommendation-text {
            color: rgba(248, 250, 252, 0.6);
            font-size: 0.75rem;
            line-height: 1.4;
        }

        .recommendation-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 800;
            white-space: nowrap;
            text-align: center;
        }

        .recommendation-badge.ok {
            background: rgba(20, 184, 166, 0.2);
            color: var(--accent-primary);
        }

        .recommendation-badge.warning {
            background: rgba(243, 201, 105, 0.2);
            color: var(--accent-primary);
        }

        .recommendation-badge.critical {
            background: rgba(251, 113, 133, 0.2);
            color: #fb7185;
        }

        /* ── Category trend chart ────────────────────────── */
        .category-trend-wrapper {
            background: rgba(6, 24, 32, 0.6);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            margin-top: 22px;
            position: relative;
            min-height: 300px;
        }

        .category-trend-wrapper canvas {
            max-height: 280px;
        }

        @media (max-width: 900px) {
            .template-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .recommendation-item {
                grid-template-columns: 1fr;
            }
        }
        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 82% 0%, rgba(24, 191, 117, .16), transparent 34%),
                linear-gradient(135deg, #06191b 0%, #071f22 48%, #091011 100%) !important;
            color: var(--text-main);
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
        .finance-shell,
        main {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell,
        .container,
        .dashboard-shell,
        .finance-shell {
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
        .content-grid,
        .feature-grid,
        .cards-grid,
        .module-grid,
        form {
            width: 100% !important;
            max-width: none !important;
            box-sizing: border-box;
        }

        .hero h1,
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
        .hero-card,
        .form-card {
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
            .finance-shell {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }

            .hero h1,
            h1 {
                font-size: clamp(34px, 13vw, 56px) !important;
            }
        }
    </style>

    @include('partials.module-shell-styles')

    <main class="finance-workspace">
        <div class="workspace-inner">
            <div class="workspace-topbar">
                <strong>SmartFinance.</strong>
            </div>

            @if (session('success'))
                <div class="alert-success-banner">
                    {{ session('success') }}
                </div>
            @endif

            <section class="workspace-hero module-hero">
                <div class="module-hero-panel module-hero-copy">
                    <span class="workspace-kicker">Finance Intelligence</span>
                    <h1>{{ __('finance.title') }}</h1>
                    <p>{{ __('finance.hero_desc') }}</p>
                    <a class="module-hero-action" href="{{ route('dashboard.user') }}">{{ __('finance.back_to_selector') }}</a>
                </div>
                <aside class="module-hero-panel module-hero-summary">
                    @if ($result)
                        <div class="status-badge status-{{ $result['status_class'] }}">{{ $result['status'] }}</div>
                        <strong>{{ $formatPercent($result['saving_ratio']) }}</strong>
                        <span>{{ __('finance.saving_ratio_desc') }}</span>
                    @else
                        <strong>6+</strong>
                        <span>{{ __('finance.main_indicators_desc') }}</span>
                    @endif
                </aside>
            </section>

            <section class="workspace-grid">
                <form class="workspace-panel workspace-panel-inner" action="{{ route('finance.analyze') }}" method="POST">
                    @csrf
                    @php
                        // Build initial expense rows for the dynamic form
                        if ($result && !empty($result['expense_items'])) {
                            $initialExpenses = array_values($result['expense_items']);
                        } else {
                            $initialExpenses = [
                                ['name' => __('finance.basic_needs'), 'amount' => '', 'is_debt' => false],
                                ['name' => __('finance.transportation'),    'amount' => '', 'is_debt' => false],
                                ['name' => __('finance.debt_installment'),   'amount' => '', 'is_debt' => true],
                                ['name' => __('finance.lifestyle'),      'amount' => '', 'is_debt' => false],
                            ];
                        }
                    @endphp

                    <div class="panel-heading">
                        <h2>{{ __('finance.monthly_input') }}</h2>
                        <p>{{ __('finance.use_monthly_average') }}</p>
                    </div>

                    <div class="finance-form-grid">
                        <label>
                            <span>Periode</span>
                            <input type="month" name="periode" value="{{ old('periode', $result['periode'] ?? date('Y-m')) }}" required>
                        </label>
                        <label><span>{{ __('finance.total_income') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="pemasukan" value="{{ $formatRupiahInput(old('pemasukan', $result['income'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                    </div>

                    {{-- Template selector for expense categories --}}
                    @if (isset($templates) && count($templates) > 0)
                    <div class="template-selector">
                        <label class="template-label">{{ __('finance.use_template') }}</label>
                        <div class="template-grid">
                            @foreach ($templates as $template)
                                <button type="button" class="template-btn" data-template-id="{{ $template['id'] ?? '' }}" title="{{ $template['description'] ?? '' }}">
                                    <span class="template-name">{{ __($template['name']) }}</span>
                                    <span class="template-desc">{{ __($template['type']) }}</span>
                                </button>
                            @endforeach
                        </div>
                        <span style="font-size: 0.75rem; color: rgba(248,250,252,0.5);">{{ __('finance.select_template') }}</span>
                    </div>
                    @endif

                    <div class="expense-section">
                        <div class="expense-section-header">
                            <span class="expense-section-label">{{ __('finance.expense_category') }}</span>
                            <span class="expense-section-hint">{!! __('finance.check_debt') !!}</span>
                        </div>
                        <div id="expense-list"></div>
                        <button type="button" id="add-expense-btn" class="btn-add-expense">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            {{ __('finance.add_category') }}
                        </button>
                    </div>

                    <div class="finance-form-grid" style="margin-top:16px;">
                        <label><span>{{ __('finance.monthly_savings') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="tabungan" value="{{ $formatRupiahInput(old('tabungan', $result['saving'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>{{ __('finance.current_savings_balance') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="saldo_tabungan" value="{{ $formatRupiahInput(old('saldo_tabungan', $result['saldo_tabungan'] ?? '')) }}" inputmode="numeric" autocomplete="off"></div></label>
                        <label><span>{{ __('finance.monthly_deposit') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="setoran_tabungan" value="{{ $formatRupiahInput(old('setoran_tabungan', $result['setoran_tabungan'] ?? '')) }}" inputmode="numeric" autocomplete="off"></div></label>
                        <label><span>{{ __('finance.investment') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="investasi" value="{{ $formatRupiahInput(old('investasi', $result['investment'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>{{ __('finance.emergency_fund') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="dana_darurat" value="{{ $formatRupiahInput(old('dana_darurat', $result['emergency_fund'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>{{ __('finance.savings_target') }}</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="target_tabungan" value="{{ $formatRupiahInput(old('target_tabungan', $result['target_saving'] ?? '')) }}" inputmode="numeric" autocomplete="off"></div></label>
                    </div>

                    {{-- Pass initial expense data to JavaScript --}}
                    <script id="initial-expenses-data" type="application/json">{!! json_encode($initialExpenses) !!}</script>

                    <button class="workspace-button" type="submit">{{ __('finance.calculate') }}</button>
                </form>

                <div class="workspace-panel workspace-panel-inner">
                    <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h2>{{ __('finance.results_summary') }}</h2>
                            <p>{{ $result ? 'Periode ' . $translatePeriode($result['periode']) : __('finance.results_will_appear') }}</p>
                        </div>
                        @if ($result && isset($request) && $request->has('load_id'))
                            <a href="{{ route('finance.export-pdf', $request->input('load_id')) }}" class="btn-use" style="background: #ef4444; color: white;" target="_blank">Export PDF</a>
                        @elseif ($result && $history->last())
                            <a href="{{ route('finance.export-pdf', $history->last()->id) }}" class="btn-use" style="background: #ef4444; color: white;" target="_blank">Export PDF</a>
                        @endif
                    </div>

                    @if ($result)
                        <div class="metric-grid">
                            <div class="metric-tile"><span>Pemasukan</span><strong>{{ $formatRupiah($result['income']) }}</strong></div>
                            <div class="metric-tile"><span>Pengeluaran</span><strong>{{ $formatRupiah($result['total_expenses']) }}</strong></div>
                            <div class="metric-tile"><span>Arus kas bersih</span><strong>{{ $formatRupiah($result['net_cashflow']) }}</strong></div>
                            <div class="metric-tile"><span>{{ __('finance.emergency_fund') }}</span><strong>{{ number_format($result['emergency_months'], 1, ',', '.') }} {{ __('finance.months') }}</strong></div>
                        </div>

                        <div class="ratio-stack">
                            <div><div class="ratio-line"><span>Rasio pengeluaran</span><strong>{{ $formatPercent($result['expense_ratio']) }}</strong></div><div class="track"><span style="width: {{ min($result['expense_ratio'], 100) }}%"></span></div></div>
                            <div><div class="ratio-line"><span>{{ __('finance.saving_ratio') }}</span><strong>{{ $formatPercent($result['saving_ratio']) }}</strong></div><div class="track good"><span style="width: {{ min($result['saving_ratio'], 100) }}%"></span></div></div>
                            <div><div class="ratio-line"><span>Rasio cicilan</span><strong>{{ $formatPercent($result['debt_ratio']) }}</strong></div><div class="track debt"><span style="width: {{ min($result['debt_ratio'], 100) }}%"></span></div></div>
                        </div>

                        <div class="insight-box">
                            <h3>{{ __('finance.recommendations') }}</h3>
                            <ul>
                                @foreach ($result['recommendations'] as $recommendation)
                                    <li>{{ $recommendation }}</li>
                                @endforeach
                            </ul>
                        </div>

                        @if (!empty($recommendations))
                        <div class="recommendations-panel">
                            <h3 class="recommendations-title">{{ __('finance.expense_recommendations') }}</h3>
                            @foreach ($recommendations as $rec)
                                <div class="recommendation-item {{ $rec['status'] }}">
                                    <div class="recommendation-content">
                                        <span class="recommendation-label">{{ __($rec['category_name']) }}</span>
                                        <span class="recommendation-text">{{ $rec['reason'] }}</span>
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 4px; align-items: flex-end; justify-content: center;">
                                        <span class="recommendation-badge {{ $rec['status'] }}">{{ ucfirst($rec['status']) }}</span>
                                        <span style="font-size: 0.75rem; color: rgba(248,250,252,0.6);">{{ number_format($rec['actual_ratio'], 1) }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <h3>{{ __('finance.no_analysis') }}</h3>
                            <p>{{ __('finance.fill_form') }}</p>
                        </div>
                    @endif

                    @if ($result)
                    <!-- What-If Simulation -->
                    <div class="insight-box" style="background: rgba(20, 184, 166, 0.05); border-color: rgba(20, 184, 166, 0.3);">
                        <h3 style="color: var(--accent-primary); margin-bottom: 15px;">{{ __('finance.what_if_simulation') }}</h3>
                        <p style="font-size: 0.85rem; color: rgba(248, 250, 252, 0.7); margin-bottom: 20px;">
                            Geser slider di bawah ini untuk melihat dampak mengurangi gaya hidup atau mempercepat cicilan terhadap sisa kas (arus kas bersih) Anda secara instan.
                        </p>
                        
                        <div style="display: grid; gap: 20px; margin-bottom: 20px;">
                            <!-- Slider 1: Kurangi Gaya Hidup -->
                            <div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <label style="font-size: 0.85rem; font-weight: bold;">{{ __('finance.reduce_expenses') }}</label>
                                    <span id="sim-expense-val" style="color: var(--accent-primary); font-weight: bold;">0%</span>
                                </div>
                                <input type="range" id="sim-expense-slider" min="0" max="50" step="5" value="0" style="width: 100%; accent-color: var(--accent-primary);">
                            </div>
                            
                            <!-- Slider 2: Kurangi Cicilan -->
                            <div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <label style="font-size: 0.85rem; font-weight: bold;">{{ __('finance.reduce_debt') }}</label>
                                    <span id="sim-debt-val" style="color: var(--accent-primary); font-weight: bold;">0%</span>
                                </div>
                                <input type="range" id="sim-debt-slider" min="0" max="50" step="5" value="0" style="width: 100%; accent-color: var(--accent-primary);">
                            </div>
                        </div>
                        
                        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; border: 1px dashed rgba(255,255,255,0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span style="font-size: 0.9rem; color: rgba(255,255,255,0.7);">Proyeksi Arus Kas Bersih:</span>
                                <strong id="sim-net-cashflow" style="font-size: 1.2rem; color: {{ $result['net_cashflow'] < 0 ? '#ef4444' : '#10b981' }}">{{ $formatRupiah($result['net_cashflow']) }}</strong>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--accent-primary);" id="sim-impact-text">
                                Sesuaikan slider untuk melihat simulasi.
                            </div>
                        </div>
                        
                        <input type="hidden" id="sim-base-cashflow" value="{{ $result['net_cashflow'] }}">
                        <input type="hidden" id="sim-base-expense" value="{{ $result['total_expenses'] }}">
                        <input type="hidden" id="sim-base-debt" value="{{ array_sum(array_column(array_filter($result['expense_items'], fn($i) => !empty($i['is_debt'])), 'amount')) }}">
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const expenseSlider = document.getElementById('sim-expense-slider');
                                const debtSlider = document.getElementById('sim-debt-slider');
                                const expenseVal = document.getElementById('sim-expense-val');
                                const debtVal = document.getElementById('sim-debt-val');
                                const netCashflowLabel = document.getElementById('sim-net-cashflow');
                                const impactText = document.getElementById('sim-impact-text');
                                
                                const baseCashflow = parseFloat(document.getElementById('sim-base-cashflow').value);
                                const baseExpense = parseFloat(document.getElementById('sim-base-expense').value);
                                const baseDebt = parseFloat(document.getElementById('sim-base-debt').value);
                                const baseNonDebtExpense = baseExpense - baseDebt;
                                
                                function formatRp(num) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num));
                                }
                                
                                function updateSimulation() {
                                    const expPct = parseInt(expenseSlider.value);
                                    const debtPct = parseInt(debtSlider.value);
                                    
                                    expenseVal.textContent = expPct + '%';
                                    debtVal.textContent = debtPct + '%';
                                    
                                    const savedFromExpense = baseNonDebtExpense * (expPct / 100);
                                    const savedFromDebt = baseDebt * (debtPct / 100);
                                    
                                    const totalSaved = savedFromExpense + savedFromDebt;
                                    const projectedCashflow = baseCashflow + totalSaved;
                                    
                                    netCashflowLabel.textContent = formatRp(projectedCashflow);
                                    netCashflowLabel.style.color = projectedCashflow < 0 ? '#ef4444' : '#10b981';
                                    
                                    if (totalSaved > 0) {
                                        impactText.textContent = `Hebat! Anda bisa menyelamatkan ${formatRp(totalSaved)} ekstra per {{ __('finance.months') }}. Anda bisa menggunakan ini untuk investasi atau mempercepat tabungan.`;
                                    } else {
                                        impactText.textContent = 'Sesuaikan slider untuk melihat simulasi.';
                                    }
                                }
                                
                                expenseSlider.addEventListener('input', updateSimulation);
                                debtSlider.addEventListener('input', updateSimulation);
                            });
                        </script>
                    </div>
                    @endif
                </div>
            </section>

            @if ($result)
                @php
                    // Build chart data for the budget donut
                    $chartLabels  = [];
                    $chartAmounts = [];
                    $chartColors  = ['var(--accent-primary)','#6366f1','#fb7185','var(--accent-primary)','#10b981','#38bdf8','#f97316','#a78bfa','#34d399','#fbbf24','#e879f9','#60a5fa'];
                    foreach ($result['expense_items'] as $i => $item) {
                        $chartLabels[]  = $item['name'];
                        $chartAmounts[] = (float) $item['amount'];
                    }
                    // Tabungan & Investasi
                    if ($result['saving'] > 0 || $result['investment'] > 0) {
                        $chartLabels[]  = 'Tabungan & Investasi';
                        $chartAmounts[] = $result['total_saving_investment'];
                    }
                    // Sisa kas (net cashflow positif)
                    if ($result['net_cashflow'] > 0) {
                        $chartLabels[]  = 'Sisa Kas';
                        $chartAmounts[] = $result['net_cashflow'];
                    }
                    $chartColorsJson = json_encode($chartColors);
                @endphp
                <section class="workspace-panel workspace-panel-inner breakdown-panel">
                    <div class="panel-heading">
                        <h2>{{ __('finance.budget_breakdown') }}</h2>
                        <p>Komposisi pengeluaran dibandingkan dengan pemasukan {{ __('finance.months') }}an.</p>
                    </div>

                    <div class="breakdown-layout">
                        <div class="breakdown-list">
                            @foreach ($result['expense_items'] as $item)
                                <div class="breakdown-item {{ !empty($item['is_debt']) ? 'is-debt' : '' }}">
                                    <span>
                                        {{ __($item['name']) }}
                                        @if (!empty($item['is_debt']))
                                            <span class="debt-tag">cicilan</span>
                                        @endif
                                    </span>
                                    <strong>{{ $formatRupiah((float) $item['amount']) }}</strong>
                                    <em>{{ $formatPercent($result['income'] > 0 ? ((float)$item['amount'] / $result['income']) * 100 : 0) }}</em>
                                </div>
                            @endforeach
                            <div class="breakdown-item highlight">
                                <span>Tabungan + investasi</span>
                                <strong>{{ $formatRupiah($result['total_saving_investment']) }}</strong>
                                <em>{{ $formatPercent($result['saving_ratio']) }}</em>
                            </div>
                        </div>

                        <div class="chart-column">
                            <div class="donut-chart-wrapper">
                                <canvas id="budgetDonutChart"></canvas>
                                <div class="donut-legend" id="donutLegend"></div>
                            </div>

                            <div class="goal-card">
                                <span>Estimasi target tabungan</span>
                                @if ($result['months_to_target'] !== null)
                                    <strong>{{ $result['months_to_target'] }} {{ __('finance.months') }}</strong>
                                    @if ($result['saldo_tabungan'] !== null || $result['setoran_tabungan'] !== null)
                                        <p style="margin-top:8px;line-height:1.6;color:rgba(248,250,252,0.66);font-size:0.84rem;">
                                            Target: {{ $formatRupiah($result['target_saving']) }}<br>
                                            Saldo saat ini: {{ $formatRupiah($result['effective_saldo']) }}<br>
                                            Setoran/{{ __('finance.months') }}: {{ $formatRupiah($result['effective_setoran']) }}
                                            @if ($result['saldo_tabungan'] === null)
                                                <br><em style="color:var(--accent-primary);font-size:0.78rem;">* Isi "Saldo saat ini" untuk estimasi lebih akurat</em>
                                            @endif
                                        </p>
                                    @else
                                        <p>Target: {{ $formatRupiah($result['target_saving']) }}. Isi saldo &amp; setoran untuk estimasi lebih akurat.</p>
                                    @endif
                                @else
                                    <strong>Belum tersedia</strong>
                                    <p>Isi target tabungan dan setoran {{ __('finance.months') }}an untuk menghitung estimasi waktu.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                @if (!empty($categoryHistory))
                <section class="workspace-panel workspace-panel-inner">
                    <div class="panel-heading">
                        <h2>{{ __('finance.expense_trends') }}</h2>
                        <p>Perbandingan perubahan kategori pengeluaran dalam 6 {{ __('finance.months') }} terakhir.</p>
                    </div>

                    <div class="category-trend-wrapper">
                        <canvas id="categoryTrendChart"></canvas>
                    </div>
                </section>
                @endif
            @endif

            @if (isset($history) && count($history) > 0)
                <section class="workspace-panel workspace-panel-inner comparison-panel">
                    <div class="chart-header-row">
                        <div class="panel-heading" style="margin-bottom: 0;">
                            <h2>{{ __('finance.monthly_progress') }}</h2>
                            <p>Bandingkan pemasukan, pengeluaran, tabungan, dan tren arus kas bersih dari {{ __('finance.months') }} ke {{ __('finance.months') }}.</p>
                        </div>
                        <div class="chart-filter-controls">
                            <button type="button" class="btn-filter is-active" data-range="6">{{ __('finance.six_months') }}</button>
                            <button type="button" class="btn-filter" data-range="12">{{ __('finance.twelve_months') }}</button>
                            <button type="button" class="btn-filter" data-range="all">{{ __('finance.all') }}</button>
                        </div>
                    </div>

                    <div class="comparison-chart-wrapper">
                        <canvas id="financeTrendChart"></canvas>
                    </div>

                    <div class="table-responsive">
                        <table class="comparison-table">
                            <thead>
                                <tr>
                                    <th>{{ __('finance.period') }}</th>
                                    <th>{{ __('finance.income') }}</th>
                                    <th>{{ __('finance.expenses') }}</th>
                                    <th>{{ __('finance.savings_investment') }}</th>
                                    <th>{{ __('finance.net_cash_flow') }}</th>
                                    <th>{{ __('finance.emergency_fund_col') }}</th>
                                    <th>{{ __('finance.status') }}</th>
                                    <th>{{ __('finance.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($history as $item)
                                    <tr>
                                        <td><strong>{{ $translatePeriode($item->periode) }}</strong></td>
                                        <td>{{ $formatRupiah($item->calculated['income']) }}</td>
                                        <td>{{ $formatRupiah($item->calculated['total_expenses']) }}</td>
                                        <td>{{ $formatRupiah($item->calculated['total_saving_investment']) }}</td>
                                        <td>{{ $formatRupiah($item->calculated['net_cashflow']) }}</td>
                                        <td>{{ number_format($item->calculated['emergency_months'], 1, ',', '.') }} bln</td>
                                        <td>
                                            <span class="status-badge status-{{ $item->calculated['status_class'] }}" style="padding: 4px 10px; font-size: 0.76rem; min-width: 80px; display: inline-block;">
                                                {{ __($item->calculated['status']) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('finance.index', ['load_id' => $item->id]) }}" class="btn-use">Gunakan</a>
                                                <form action="{{ route('finance.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat analisis untuk periode ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete">{{ __('finance.delete') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @endif
        </div>
    </main>

    @if (isset($history) && count($history) > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var ctx = document.getElementById('financeTrendChart').getContext('2d');
                
                var monthsMap = {
                    '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                    '05': 'Mei', '06': 'Jun', '07': 'Jul', '08': 'Agt',
                    '09': 'Sep', '10': 'Okt', '11': 'Nov', '12': 'Des'
                };

                var rawLabels = {!! json_encode($history->map(fn($item) => $item->periode)->toArray()) !!};
                var allLabels = rawLabels.map(function(val) {
                    var parts = val.split('-');
                    if (parts.length === 2 && monthsMap[parts[1]]) {
                        return monthsMap[parts[1]] + ' ' + parts[0];
                    }
                    return val;
                });

                var allIncome = {!! json_encode($history->map(fn($item) => (float)$item->calculated['income'])->toArray()) !!};
                var allExpense = {!! json_encode($history->map(fn($item) => (float)$item->calculated['total_expenses'])->toArray()) !!};
                var allSaving = {!! json_encode($history->map(fn($item) => (float)$item->calculated['total_saving_investment'])->toArray()) !!};
                var allCashflow = {!! json_encode($history->map(fn($item) => (float)$item->calculated['net_cashflow'])->toArray()) !!};

                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: 'Pemasukan',
                                data: [],
                                borderColor: '#10b981', // emerald
                                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Pengeluaran',
                                data: [],
                                borderColor: '#fb7185', // rose
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: false
                            },
                            {
                                label: 'Tabungan & Investasi',
                                data: [],
                                borderColor: '#6366f1', // indigo
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: false
                            },
                            {
                                label: 'Arus Kas Bersih',
                                data: [],
                                borderColor: '#38bdf8', // sky blue
                                backgroundColor: 'rgba(56, 189, 248, 0.05)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'var(--text-main)',
                                    font: {
                                        family: "'Outfit', 'Inter', sans-serif",
                                        weight: 'bold',
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(13, 47, 51, 0.95)',
                                titleColor: 'var(--accent-primary)',
                                bodyColor: 'var(--text-main)',
                                borderColor: 'var(--border-color)',
                                borderWidth: 1,
                                padding: 12,
                                callbacks: {
                                    label: function (context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'var(--nav-bg)'
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            },
                            y: {
                                grid: {
                                    color: 'var(--nav-bg)'
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    callback: function (value) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                                    }
                                }
                            }
                        }
                    }
                });

                function updateChartRange(range) {
                    var slicedLabels, slicedIncome, slicedExpense, slicedSaving, slicedCashflow;
                    if (range === 'all') {
                        slicedLabels = allLabels;
                        slicedIncome = allIncome;
                        slicedExpense = allExpense;
                        slicedSaving = allSaving;
                        slicedCashflow = allCashflow;
                    } else {
                        var count = parseInt(range, 10);
                        slicedLabels = allLabels.slice(-count);
                        slicedIncome = allIncome.slice(-count);
                        slicedExpense = allExpense.slice(-count);
                        slicedSaving = allSaving.slice(-count);
                        slicedCashflow = allCashflow.slice(-count);
                    }

                    chart.data.labels = slicedLabels;
                    chart.data.datasets[0].data = slicedIncome;
                    chart.data.datasets[1].data = slicedExpense;
                    chart.data.datasets[2].data = slicedSaving;
                    chart.data.datasets[3].data = slicedCashflow;
                    chart.update();
                }

                // Initial range logic
                var initialRange = '6';
                if (allLabels.length < 6) {
                    initialRange = 'all';
                    document.querySelectorAll('.btn-filter').forEach(function(btn) {
                        btn.classList.remove('is-active');
                        if (btn.getAttribute('data-range') === 'all') {
                            btn.classList.add('is-active');
                        }
                    });
                }
                updateChartRange(initialRange);

                // Setup listener
                document.querySelectorAll('.btn-filter').forEach(function (button) {
                    button.addEventListener('click', function () {
                        document.querySelectorAll('.btn-filter').forEach(function (btn) {
                            btn.classList.remove('is-active');
                        });
                        this.classList.add('is-active');
                        var range = this.getAttribute('data-range');
                        updateChartRange(range);
                    });
                });
            });
        </script>
    @endif

    {{-- ── Dynamic expense category manager ─────────────────────────────── --}}
    <script>
        (function () {
            var expenseList  = document.getElementById('expense-list');
            var addBtn       = document.getElementById('add-expense-btn');
            var dataEl       = document.getElementById('initial-expenses-data');
            var form         = expenseList && expenseList.closest('form');

            if (!expenseList || !addBtn) return;

            var rowIndex = 0;

            // ── Color palette for chart dots / row accents
            var PALETTE = [
                'var(--accent-primary)','#6366f1','#fb7185','var(--accent-primary)',
                '#10b981','#38bdf8','#f97316','#a78bfa',
                '#34d399','#fbbf24','#e879f9','#60a5fa'
            ];

            function formatRp(val) {
                var digits = String(val || '').replace(/[^0-9]/g, '');
                if (!digits) return '';
                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function normalizeAmountField(input) {
                var cursor  = input.selectionStart;
                var before  = input.value.length;
                input.value = formatRp(input.value);
                var diff    = input.value.length - before;
                try { input.setSelectionRange(cursor + diff, cursor + diff); } catch(e) {}
            }

            function createRow(data) {
                var idx    = rowIndex++;
                var row    = document.createElement('div');
                row.className = 'expense-row';
                row.dataset.index = idx;

                var color  = PALETTE[idx % PALETTE.length];
                var isDebt = data && data.is_debt;
                var name   = data ? (data.name || '')  : '';
                var amount = data ? formatRp(String(data.amount || '')) : '';

                row.innerHTML =
                    '<input type="text" name="expenses[' + idx + '][name]" value="' + escHtml(name) + '" placeholder="{{ __('finance.category_name') }}" autocomplete="off" required>' +
                    '<div class="money-field" style="position:relative;">' +
                        '<span class="money-prefix">Rp</span>' +
                        '<input type="text" name="expenses[' + idx + '][amount]" value="' + escHtml(amount) + '" class="expense-amount" inputmode="numeric" autocomplete="off" required style="padding-left:42px;">' +
                    '</div>' +
                    '<label class="debt-toggle" title="{{ __('finance.mark_as_debt') }}">' +
                        '<input type="checkbox" name="expenses[' + idx + '][is_debt]" value="1"' + (isDebt ? ' checked' : '') + '>' +
                        'Cicilan' +
                    '</label>' +
                    '<button type="button" class="btn-remove-expense" title="{{ __('finance.remove_category') }}">✕</button>';

                // Colorize left border by category index
                row.style.borderLeft = '3px solid ' + color;
                row.style.paddingLeft = '8px';

                // Amount formatting
                var amountInput = row.querySelector('.expense-amount');
                amountInput.addEventListener('input', function() { normalizeAmountField(this); });

                // Remove row
                row.querySelector('.btn-remove-expense').addEventListener('click', function () {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(-6px)';
                    row.style.transition = 'opacity 0.18s, transform 0.18s';
                    setTimeout(function() { row.remove(); }, 180);
                });

                return row;
            }

            function escHtml(s) {
                return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            }

            // Pre-populate from PHP-injected data
            var initial = [];
            try { initial = JSON.parse(dataEl ? dataEl.textContent : '[]'); } catch(e) {}
            if (!Array.isArray(initial) || initial.length === 0) {
                initial = [
                    { name: '{{ __('finance.basic_needs') }}', amount: '', is_debt: false },
                    { name: '{{ __('finance.transportation') }}',    amount: '', is_debt: false },
                    { name: '{{ __('finance.debt_installment') }}',   amount: '', is_debt: true  },
                    { name: '{{ __('finance.lifestyle') }}',      amount: '', is_debt: false },
                ];
            }
            initial.forEach(function(item) { expenseList.appendChild(createRow(item)); });

            // Add button
            addBtn.addEventListener('click', function () {
                expenseList.appendChild(createRow(null));
                expenseList.lastElementChild.querySelector('input[type="text"]').focus();
            });

            // Strip dots from amount fields before submit
            form && form.addEventListener('submit', function () {
                expenseList.querySelectorAll('.expense-amount').forEach(function (inp) {
                    inp.value = inp.value.replace(/[^0-9]/g, '');
                });
            });
        })();
    </script>

    {{-- ── Budget donut chart ──────────────────────────────────────────────── --}}
    @if ($result)
    <script>
        (function () {
            var canvas = document.getElementById('budgetDonutChart');
            if (!canvas) return;

            // Ensure Chart.js is loaded (it may come from the trend chart above or we load it here)
            function initDonut() {
                var labels  = {!! json_encode($chartLabels) !!};
                var amounts = {!! json_encode($chartAmounts) !!};
                var palette = {!! $chartColorsJson !!};

                // Extend palette by repeating if needed
                var colors = labels.map(function(_, i) {
                    // Tabungan & Investasi → emerald, Sisa Kas → dim white
                    if (labels[i] === 'Tabungan & Investasi') return '#10b981';
                    if (labels[i] === 'Sisa Kas')             return 'rgba(255,255,255,0.18)';
                    return palette[i % palette.length];
                });

                var total = amounts.reduce(function(s, v) { return s + v; }, 0);

                var chart = new Chart(canvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: amounts,
                            backgroundColor: colors,
                            borderColor: 'rgba(6,24,32,0.8)',
                            borderWidth: 2,
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '62%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(13,47,51,0.95)',
                                titleColor: 'var(--accent-primary)',
                                bodyColor: 'var(--text-main)',
                                borderColor: 'rgba(255,255,255,0.1)',
                                borderWidth: 1,
                                padding: 10,
                                callbacks: {
                                    label: function(ctx) {
                                        var pct = total > 0 ? (ctx.parsed / total * 100).toFixed(1) : '0.0';
                                        var amt = new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(ctx.parsed);
                                        return ' ' + amt + '  (' + pct + '%)';
                                    }
                                }
                            }
                        },
                        animation: { duration: 600, easing: 'easeOutQuart' },
                    }
                });

                // Build custom legend
                var legendEl = document.getElementById('donutLegend');
                if (legendEl) {
                    labels.forEach(function(lbl, i) {
                        var pct  = total > 0 ? (amounts[i] / total * 100).toFixed(1) : '0.0';
                        var item = document.createElement('div');
                        item.className = 'donut-legend-item';
                        item.innerHTML =
                            '<span class="donut-legend-dot" style="background:' + colors[i] + ';"></span>' +
                            '<span style="flex:1;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">' + lbl + '</span>' +
                            '<span style="font-weight:800;color:var(--text-main);white-space:nowrap;">' + pct + '%</span>';
                        legendEl.appendChild(item);
                    });
                }
            }

            // Chart.js may already be loaded (from the trend chart block above)
            if (typeof Chart !== 'undefined') {
                initDonut();
            } else {
                var s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                s.onload = initDonut;
                document.head.appendChild(s);
            }
        })();
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var fields = document.querySelectorAll('[data-rupiah-input]');

            function formatRupiah(value) {
                var digits = String(value || '').replace(/[^0-9]/g, '');

                if (!digits) {
                    return '';
                }

                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function normalizeField(input) {
                var digits = input.value.replace(/[^0-9]/g, '');
                input.value = formatRupiah(digits);
            }

            fields.forEach(function (field) {
                normalizeField(field);

                field.addEventListener('input', function () {
                    normalizeField(field);
                });

                field.form && field.form.addEventListener('submit', function () {
                    field.value = field.value.replace(/[^0-9]/g, '');
                });
            });
        });
    </script>

    {{-- ── Template selector and category trend chart ──────── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Template selector functionality
            var templateBtns = document.querySelectorAll('.template-btn');
            var incomeInput = document.querySelector('input[name="pemasukan"]');
            var expenseList = document.getElementById('expense-list');

            if (templateBtns.length > 0 && incomeInput && expenseList) {
                templateBtns.forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        
                        var templateId = this.getAttribute('data-template-id');
                        var income = parseFloat(incomeInput.value.replace(/[^0-9]/g, '')) || 0;

                        if (income <= 0) {
                            alert('Silakan masukkan total pemasukan terlebih dahulu');
                            return;
                        }

                        fetch('{{ route("finance.apply-template") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                template_id: templateId,
                                income: income
                            })
                        })
                        .then(function(res) { return res.json(); })
                        .then(function(data) {
                            if (data.error) {
                                alert(data.error);
                                return;
                            }

                            // Clear existing rows
                            expenseList.innerHTML = '';

                            // Add new rows from template
                            var rowIndex = 0;
                            var PALETTE = [
                                'var(--accent-primary)','#6366f1','#fb7185','var(--accent-primary)',
                                '#10b981','#38bdf8','#f97316','#a78bfa',
                                '#34d399','#fbbf24','#e879f9','#60a5fa'
                            ];

                            function formatRp(val) {
                                var digits = String(val || '').replace(/[^0-9]/g, '');
                                if (!digits) return '';
                                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            }

                            function escHtml(s) {
                                return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
                            }

                            data.expenses.forEach(function(expense) {
                                var idx = rowIndex++;
                                var row = document.createElement('div');
                                row.className = 'expense-row';
                                row.dataset.index = idx;

                                var color = PALETTE[idx % PALETTE.length];
                                var amount = formatRp(String(expense.amount || 0));
                                var isDebt = expense.is_debt ? ' checked' : '';

                                row.innerHTML =
                                    '<input type="text" name="expenses[' + idx + '][name]" value="' + escHtml(expense.name) + '" placeholder="{{ __('finance.category_name') }}" autocomplete="off" required>' +
                                    '<div class="money-field" style="position:relative;">' +
                                        '<span class="money-prefix">Rp</span>' +
                                        '<input type="text" name="expenses[' + idx + '][amount]" value="' + escHtml(amount) + '" class="expense-amount" inputmode="numeric" autocomplete="off" required style="padding-left:42px;">' +
                                    '</div>' +
                                    '<label class="debt-toggle" title="{{ __('finance.mark_as_debt') }}">' +
                                        '<input type="checkbox" name="expenses[' + idx + '][is_debt]" value="1"' + isDebt + '>' +
                                        'Cicilan' +
                                    '</label>' +
                                    '<button type="button" class="btn-remove-expense" title="{{ __('finance.remove_category') }}">✕</button>';

                                row.style.borderLeft = '3px solid ' + color;
                                row.style.paddingLeft = '8px';

                                var amountInput = row.querySelector('.expense-amount');
                                amountInput.addEventListener('input', function() {
                                    var cursor = this.selectionStart;
                                    var before = this.value.length;
                                    this.value = formatRp(this.value);
                                    var diff = this.value.length - before;
                                    try { this.setSelectionRange(cursor + diff, cursor + diff); } catch(e) {}
                                });

                                row.querySelector('.btn-remove-expense').addEventListener('click', function () {
                                    row.style.opacity = '0';
                                    row.style.transform = 'translateY(-6px)';
                                    row.style.transition = 'opacity 0.18s, transform 0.18s';
                                    setTimeout(function() { row.remove(); }, 180);
                                });

                                expenseList.appendChild(row);
                            });
                        })
                        .catch(function(err) {
                            console.error('Error applying template:', err);
                            alert('Gagal menerapkan template');
                        });
                    });
                });
            }

            // Category trend chart
            var categoryTrendCanvas = document.getElementById('categoryTrendChart');
            if (categoryTrendCanvas && typeof Chart !== 'undefined') {
                var categoryHistory = {!! json_encode($categoryHistory ?? []) !!};
                var monthsMap = {
                    '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr',
                    '05': 'Mei', '06': 'Jun', '07': 'Jul', '08': 'Agt',
                    '09': 'Sep', '10': 'Okt', '11': 'Nov', '12': 'Des'
                };

                if (Object.keys(categoryHistory).length > 0) {
                    var categories = Object.keys(categoryHistory);
                    var datasets = [];
                    var colors = [
                        'var(--accent-primary)','#6366f1','#fb7185','var(--accent-primary)',
                        '#10b981','#38bdf8','#f97316','#a78bfa',
                        '#34d399','#fbbf24','#e879f9','#60a5fa'
                    ];

                    // Get all periode labels from the first category
                    var labels = [];
                    if (categories.length > 0) {
                        labels = categoryHistory[categories[0]].map(function(item) {
                            var parts = item.periode.split('-');
                            if (parts.length === 2 && monthsMap[parts[1]]) {
                                return monthsMap[parts[1]] + ' ' + parts[0];
                            }
                            return item.periode;
                        });
                    }

                    // Create datasets for each category
                    categories.forEach(function(cat, idx) {
                        var amounts = categoryHistory[cat].map(function(item) { return item.amount; });
                        datasets.push({
                            label: cat,
                            data: amounts,
                            borderColor: colors[idx % colors.length],
                            backgroundColor: 'transparent',
                            borderWidth: 2.5,
                            tension: 0.3,
                            fill: false
                        });
                    });

                    new Chart(categoryTrendCanvas.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    labels: {
                                        color: 'var(--text-main)',
                                        font: {
                                            family: "'Outfit', 'Inter', sans-serif",
                                            weight: 'bold',
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(13, 47, 51, 0.95)',
                                    titleColor: 'var(--accent-primary)',
                                    bodyColor: 'var(--text-main)',
                                    borderColor: 'var(--border-color)',
                                    borderWidth: 1,
                                    padding: 12,
                                    callbacks: {
                                        label: function (context) {
                                            let label = context.dataset.label || '';
                                            if (label) {
                                                label += ': ';
                                            }
                                            if (context.parsed.y !== null) {
                                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: 'var(--nav-bg)'
                                    },
                                    ticks: {
                                        color: '#94a3b8'
                                    }
                                },
                                y: {
                                    grid: {
                                        color: 'var(--nav-bg)'
                                    },
                                    ticks: {
                                        color: '#94a3b8',
                                        callback: function (value) {
                                            return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        });
    </script>
@endsection
