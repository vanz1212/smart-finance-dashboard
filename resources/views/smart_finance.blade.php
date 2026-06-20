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
    @endphp

    <style>
        .finance-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97)),
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
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            color: rgba(248, 250, 252, 0.78);
            text-decoration: none;
            font-weight: 800;
            background: rgba(255, 255, 255, 0.05);
        }

        .workspace-nav a.is-active,
        .workspace-nav a:hover {
            color: #052e2b;
            background: #f3c969;
            border-color: #f3c969;
        }

        .workspace-hero {
            display: flex;
            justify-content: space-between;
            gap: 22px;
            align-items: flex-end;
            margin-bottom: 28px;
        }

        .workspace-kicker {
            color: #f3c969;
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
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
        }

        .status-badge {
            min-width: 126px;
            padding: 12px 16px;
            border-radius: 999px;
            color: #052e2b;
            text-align: center;
            font-weight: 900;
            background: #f3c969;
        }

        .status-success { background: #14b8a6; color: #042f2e; }
        .status-warning { background: #f3c969; color: #422006; }
        .status-danger { background: #fb7185; color: #4c0519; }

        .workspace-grid {
            display: grid;
            grid-template-columns: minmax(320px, 0.92fr) minmax(380px, 1.08fr);
            gap: 22px;
            align-items: start;
        }

        .workspace-panel {
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.78), rgba(6, 24, 32, 0.84));
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
            color: rgba(248, 250, 252, 0.66);
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
            color: rgba(248, 250, 252, 0.72);
            font-size: 0.84rem;
            font-weight: 800;
        }

        .finance-form-grid input {
            min-height: 46px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
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
            color: rgba(248, 250, 252, 0.74);
            font-size: 0.88rem;
            font-weight: 800;
            pointer-events: none;
        }

        .money-field input {
            padding-left: 42px;
        }

        .finance-form-grid input:focus {
            outline: 3px solid rgba(20, 184, 166, 0.18);
            border-color: #14b8a6;
        }

        .workspace-button {
            width: 100%;
            min-height: 50px;
            margin-top: 18px;
            border: 0;
            border-radius: 999px;
            background: #f3c969;
            color: #052e2b;
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
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
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
            color: #ffffff;
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
            color: rgba(248, 250, 252, 0.74);
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
            background: #f3c969;
        }

        .track.good span { background: #14b8a6; }
        .track.debt span { background: #fb7185; }

        .insight-box,
        .empty-state,
        .goal-card {
            margin-top: 22px;
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
        }

        .insight-box h3,
        .empty-state h3 {
            margin: 0 0 10px;
        }

        .insight-box ul {
            margin: 0;
            padding-left: 20px;
            color: rgba(248, 250, 252, 0.72);
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
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
        }

        .breakdown-item em {
            color: #f3c969;
            font-style: normal;
            font-weight: 900;
            text-align: right;
        }

        .breakdown-item.highlight {
            border-color: rgba(20, 184, 166, 0.38);
            background: rgba(20, 184, 166, 0.09);
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
        }
        /* Full-page refinement shared with standalone module pages. */
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

            <section class="workspace-hero">
                <div>
                    <span class="workspace-kicker">Finance Intelligence</span>
                    <h1>Analisa Keuangan</h1>
                    <p>Evaluasi arus kas, pengeluaran, tabungan, cicilan, dan kesiapan dana darurat dalam satu dashboard yang ringkas.</p>
                </div>
                @if ($result)
                    <div class="status-badge status-{{ $result['status_class'] }}">{{ $result['status'] }}</div>
                @endif
            </section>

            <section class="workspace-grid">
                <form class="workspace-panel workspace-panel-inner" action="{{ route('finance.analyze') }}" method="POST">
                    @csrf
                    <div class="panel-heading">
                        <h2>Input Bulanan</h2>
                        <p>Gunakan angka rata-rata per bulan agar analisa mudah dibandingkan.</p>
                    </div>

                    <div class="finance-form-grid">
                        <label><span>Periode</span><input type="text" name="periode" value="{{ old('periode', $result['periode'] ?? date('F Y')) }}" required></label>
                        <label><span>Total pemasukan</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="pemasukan" value="{{ $formatRupiahInput(old('pemasukan', $result['income'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Kebutuhan pokok</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="kebutuhan_pokok" value="{{ $formatRupiahInput(old('kebutuhan_pokok', $result['expenses']['Kebutuhan pokok'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Transportasi</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="transportasi" value="{{ $formatRupiahInput(old('transportasi', $result['expenses']['Transportasi'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Cicilan/utang</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="cicilan" value="{{ $formatRupiahInput(old('cicilan', $result['expenses']['Cicilan/utang'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Gaya hidup</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="gaya_hidup" value="{{ $formatRupiahInput(old('gaya_hidup', $result['expenses']['Gaya hidup'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Tabungan</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="tabungan" value="{{ $formatRupiahInput(old('tabungan', $result['saving'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Investasi</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="investasi" value="{{ $formatRupiahInput(old('investasi', $result['investment'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Dana darurat</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="dana_darurat" value="{{ $formatRupiahInput(old('dana_darurat', $result['emergency_fund'] ?? '')) }}" inputmode="numeric" autocomplete="off" required></div></label>
                        <label><span>Target tabungan</span><div class="money-field"><span class="money-prefix">Rp</span><input type="text" data-rupiah-input name="target_tabungan" value="{{ $formatRupiahInput(old('target_tabungan', $result['target_saving'] ?? '')) }}" inputmode="numeric" autocomplete="off"></div></label>
                    </div>

                    <button class="workspace-button" type="submit">Hitung Analisa</button>
                </form>

                <div class="workspace-panel workspace-panel-inner">
                    <div class="panel-heading">
                        <h2>Ringkasan Hasil</h2>
                        <p>{{ $result ? 'Periode ' . $result['periode'] : 'Hasil akan tampil setelah data dihitung.' }}</p>
                    </div>

                    @if ($result)
                        <div class="metric-grid">
                            <div class="metric-tile"><span>Pemasukan</span><strong>{{ $formatRupiah($result['income']) }}</strong></div>
                            <div class="metric-tile"><span>Pengeluaran</span><strong>{{ $formatRupiah($result['total_expenses']) }}</strong></div>
                            <div class="metric-tile"><span>Arus kas bersih</span><strong>{{ $formatRupiah($result['net_cashflow']) }}</strong></div>
                            <div class="metric-tile"><span>Dana darurat</span><strong>{{ number_format($result['emergency_months'], 1, ',', '.') }} bulan</strong></div>
                        </div>

                        <div class="ratio-stack">
                            <div><div class="ratio-line"><span>Rasio pengeluaran</span><strong>{{ $formatPercent($result['expense_ratio']) }}</strong></div><div class="track"><span style="width: {{ min($result['expense_ratio'], 100) }}%"></span></div></div>
                            <div><div class="ratio-line"><span>Rasio tabungan + investasi</span><strong>{{ $formatPercent($result['saving_ratio']) }}</strong></div><div class="track good"><span style="width: {{ min($result['saving_ratio'], 100) }}%"></span></div></div>
                            <div><div class="ratio-line"><span>Rasio cicilan</span><strong>{{ $formatPercent($result['debt_ratio']) }}</strong></div><div class="track debt"><span style="width: {{ min($result['debt_ratio'], 100) }}%"></span></div></div>
                        </div>

                        <div class="insight-box">
                            <h3>Rekomendasi</h3>
                            <ul>
                                @foreach ($result['recommendations'] as $recommendation)
                                    <li>{{ $recommendation }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="empty-state">
                            <h3>Belum ada analisa</h3>
                            <p>Isi form di sebelah kiri untuk melihat status keuangan, rasio, dan rekomendasi otomatis.</p>
                        </div>
                    @endif
                </div>
            </section>

            @if ($result)
                <section class="workspace-panel workspace-panel-inner breakdown-panel">
                    <div class="panel-heading">
                        <h2>Breakdown Anggaran</h2>
                        <p>Komposisi pengeluaran dibandingkan dengan pemasukan bulanan.</p>
                    </div>

                    <div class="breakdown-layout">
                        <div class="breakdown-list">
                            @foreach ($result['expenses'] as $category => $amount)
                                <div class="breakdown-item">
                                    <span>{{ $category }}</span>
                                    <strong>{{ $formatRupiah($amount) }}</strong>
                                    <em>{{ $formatPercent($result['income'] > 0 ? ($amount / $result['income']) * 100 : 0) }}</em>
                                </div>
                            @endforeach
                            <div class="breakdown-item highlight">
                                <span>Tabungan + investasi</span>
                                <strong>{{ $formatRupiah($result['total_saving_investment']) }}</strong>
                                <em>{{ $formatPercent($result['saving_ratio']) }}</em>
                            </div>
                        </div>

                        <div class="goal-card">
                            <span>Estimasi target tabungan</span>
                            @if ($result['months_to_target'] !== null)
                                <strong>{{ $result['months_to_target'] }} bulan</strong>
                                <p>Dengan target {{ $formatRupiah($result['target_saving']) }}.</p>
                            @else
                                <strong>Belum tersedia</strong>
                                <p>Isi target tabungan untuk menghitung estimasi waktu.</p>
                            @endif
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </main>

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
@endsection
