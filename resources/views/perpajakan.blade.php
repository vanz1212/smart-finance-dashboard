@extends('layouts.app')

@section('title', __('tax.page_title'))
@section('body-class', 'module-page')

@section('content')
    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $statusClass = match ($result['status_pajak'] ?? '') {
            'Tidak kena pajak' => 'neutral',
            'Pajak rendah' => 'success',
            'Pajak normal' => 'warning',
            'Pajak tinggi' => 'danger',
            default => 'neutral',
        };
        $translateTaxStatus = function ($status) {
            return match ($status) {
                'Tidak kena pajak' => __('tax.status_not_taxable'),
                'Pajak rendah' => __('tax.status_low'),
                'Pajak normal' => __('tax.status_normal'),
                'Pajak tinggi' => __('tax.status_high'),
                default => $status,
            };
        };
        $translateTaxNote = function ($result) {
            if (! $result) {
                return '';
            }

            if (($result['metode'] ?? null) === 'ter' && ! empty($result['ter_category'])) {
                return __('tax.ter_note', [
                    'category' => $result['ter_category'],
                    'rate' => number_format((float) ($result['ter_rate'] ?? 0), 2, ',', '.'),
                ]);
            }

            return __('tax.annual_note');
        };
    @endphp

    <style>
        .tax-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: var(--text-main);
            background:
                linear-gradient(180deg, var(--bg-primary), var(--bg-primary)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .tax-inner { width: min(1180px, 100%); margin: 0 auto; }
        .tax-topbar, .tax-hero { display: flex; justify-content: space-between; gap: 18px; align-items: center; }
        .tax-topbar { margin-bottom: 34px; }
        .tax-nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .tax-nav a { padding: 10px 14px; border: 1px solid rgba(255,255,255,.12); border-radius: 999px; color: rgba(248,250,252,.78); text-decoration: none; font-weight: 800; background: rgba(255,255,255,.05); }
        .tax-nav a.is-active, .tax-nav a:hover { color: var(--accent-hover); background: var(--accent-primary); border-color: var(--accent-primary); }
        .tax-hero { align-items: flex-end; margin-bottom: 28px; }
        .tax-kicker { color: var(--accent-primary); font-size: .8rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .tax-hero h1 { margin: 12px 0 0; font-size: clamp(2.2rem, 5vw, 4.8rem); line-height: .98; letter-spacing: 0; }
        .tax-hero p { max-width: 720px; margin: 16px 0 0; color: rgba(248,250,252,.72); line-height: 1.7; }
        
        .disclaimer-alert {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.4);
            border-left: 4px solid #f59e0b;
            color: #fcd34d;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        .tax-badge { min-width: 144px; padding: 12px 16px; border-radius: 999px; color: var(--accent-hover); text-align: center; font-weight: 900; background: var(--accent-primary); }
        .tax-badge.neutral { background: rgba(255,255,255,.16); color: #fff; }
        .tax-badge.success { background: var(--accent-primary); color: #042f2e; }
        .tax-badge.warning { background: var(--accent-primary); color: #422006; }
        .tax-badge.danger { background: #fb7185; color: #4c0519; }

        .tax-grid { display: grid; grid-template-columns: minmax(320px,.92fr) minmax(380px,1.08fr); gap: 22px; align-items: start; }
        .tax-panel { border: 1px solid rgba(255,255,255,.14); border-radius: 14px; background: linear-gradient(180deg, rgba(13,47,51,.78), rgba(6,24,32,.84)); box-shadow: 0 28px 80px rgba(0,0,0,.34); backdrop-filter: blur(16px); }
        .tax-panel-inner { padding: 24px; }
        .tax-panel h2 { margin: 0; font-size: 1.18rem; }
        .tax-panel p { color: rgba(248,250,252,.66); line-height: 1.6; font-size: 0.9rem; }
        
        .tax-form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 15px; margin-top: 20px;}
        .tax-form label { display: grid; gap: 8px; }
        .tax-form label.full { grid-column: 1 / -1; }
        .tax-form span { color: rgba(248,250,252,.72); font-size: .84rem; font-weight: 800; display: flex; align-items: center; gap: 5px; }
        .tax-form input, .tax-form select { min-height: 46px; width: 100%; border: 1px solid rgba(255,255,255,.18); border-radius: 10px; padding: 10px 12px; background: rgba(255,255,255,.06); color: #fff; font: inherit; }
        .tax-form select option { background: #0f172a; color: #fff; }
        .tax-form input:focus, .tax-form select:focus { outline: 3px solid rgba(20,184,166,.18); border-color: var(--accent-primary); }
        .tax-button { width: 100%; min-height: 50px; margin-top: 18px; border: 0; border-radius: 999px; background: var(--accent-primary); color: var(--accent-hover); cursor: pointer; font: inherit; font-weight: 900; }

        .tax-metrics { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 14px; margin-top: 15px;}
        .tax-metric, .tax-note { padding: 16px; border: 1px solid rgba(255,255,255,.12); border-radius: 12px; background: rgba(255,255,255,.06); }
        .tax-metric span { display: block; margin-bottom: 8px; color: rgba(248,250,252,.62); font-size: .82rem; font-weight: 800; }
        .tax-metric strong { color: #fff; font-size: 1.08rem; }
        .tax-table { width: 100%; border-collapse: collapse; margin-top: 22px; overflow: hidden; border-radius: 12px; font-size: 0.9rem;}
        .tax-table th, .tax-table td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,.1); text-align: left; color: rgba(248,250,252,.78); }
        .tax-table th { color: #fff; background: rgba(255,255,255,.06); }
        .tax-table td:last-child, .tax-table th:last-child { text-align: right; }
        .tax-reference { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 22px; margin-top: 22px; }

        .btn-use { padding: 8px 14px; border-radius: 8px; font-weight: bold; text-decoration: none; display: inline-block; }
        
        .history-list { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .history-item { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; }
        .history-item:hover { background: rgba(255,255,255,0.08); }

        .tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
        }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 250px;
            background-color: #1e293b;
            color: #fff;
            text-align: left;
            border-radius: 6px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -125px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
            font-weight: normal;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        @media (max-width: 900px) { .tax-topbar, .tax-hero { align-items: flex-start; flex-direction: column; } .tax-grid, .tax-reference { grid-template-columns: 1fr; } }
        @media (max-width: 620px) { .tax-workspace { margin: -24px; padding-inline: 14px; } .tax-form, .tax-metrics { grid-template-columns: 1fr; } }
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
            color: var(--text-main);
        }

        [data-theme="light"],
        [data-theme="light"] body {
            background: var(--bg-primary) !important;
            color: var(--text-main) !important;
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
        .tax-shell,
        main {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell,
        .container,
        .dashboard-shell,
        .tax-shell {
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
        .form-card,
        .tax-card {
            border: 1px solid rgba(148, 163, 184, .22) !important;
            background: rgba(12, 34, 36, .88) !important;
            box-shadow: none !important;
            backdrop-filter: blur(12px);
        }

        input,
        select,
        textarea {
            min-height: 48px;
            border-radius: 14px !important;
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
            .tax-shell {
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

    <main class="tax-workspace">
        <div class="tax-inner">
            <div class="tax-topbar">
                <strong>SmartFinance.</strong>
            </div>
            
            <div class="disclaimer-alert">
                <strong>{{ __('tax.important') }}</strong> {{ __('tax.disclaimer_desc') }}
            </div>

            <section class="tax-hero module-hero">
                <div class="module-hero-panel module-hero-copy">
                    <span class="tax-kicker">{{ __('tax.kicker') }}</span>
                    <h1>{{ __('tax.title') }}</h1>
                    <p>{{ __('tax.hero_desc') }}</p>
                    <a class="module-hero-action" href="{{ route('dashboard.user') }}">{{ __('tax.back_to_selector') }}</a>
                </div>
                <aside class="module-hero-panel module-hero-summary">
                    @if ($result)
                        <div class="tax-badge {{ $statusClass }}">{{ $translateTaxStatus($result['status_pajak']) }}</div>
                        <strong style="font-size: 2rem;">{{ $formatRupiah($result['estimasi_pajak_tahunan']) }}</strong>
                        <span>{{ __('tax.est_annual_tax') }}</span>
                    @else
                        <strong>TER</strong>
                        <span>{{ __('tax.ter_desc') }}</span>
                    @endif
                </aside>
            </section>

            <section class="tax-grid">
                <div>
                    <form class="tax-panel tax-panel-inner" action="{{ route('perpajakan.calculate') }}" method="POST">
                        @csrf
                        <h2>{{ __('tax.input_data') }}</h2>
                        <p>{{ __('tax.input_desc') }}</p>
                        <div class="tax-form">
                            <label>
                                <span>{{ __('tax.tax_year') }}</span>
                                <select name="tahun_pajak" required>
                                    <option value="2024" {{ old('tahun_pajak') == '2024' ? 'selected' : '' }}>2024 ({{ __('tax.using_ter') }})</option>
                                    <option value="2023" {{ old('tahun_pajak') == '2023' ? 'selected' : '' }}>2023 ({{ __('tax.old_pph_21') }})</option>
                                </select>
                            </label>
                            <label>
                                <span>{{ __('tax.calc_method') }}</span>
                                <select name="metode_perhitungan" required>
                                    <option value="ter" {{ old('metode_perhitungan') == 'ter' ? 'selected' : '' }}>{{ __('tax.monthly_ter') }}</option>
                                    <option value="tahunan" {{ old('metode_perhitungan') == 'tahunan' ? 'selected' : '' }}>{{ __('tax.annual_pph_21') }}</option>
                                </select>
                            </label>
                            <label class="full">
                                <span>{{ __('tax.taxpayer_status') }} 
                                    <div class="tooltip">ℹ️
                                        <span class="tooltiptext">{!! __('tax.ptkp_tooltip') !!}</span>
                                    </div>
                                </span>
                                <select name="status_wajib_pajak" required>
                                    <option value="" disabled {{ !old('status_wajib_pajak') ? 'selected' : '' }}>{{ __('tax.choose_status') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status_wajib_pajak') === $status ? 'selected' : '' }}>{{ $status }} - PTKP {{ $formatRupiah($ptkpTable[$status]) }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <div class="full" style="margin-top: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px; color: var(--accent-primary); font-weight: bold;">{{ __('tax.income_section') }}</div>
                            <label><span>{{ __('tax.monthly_salary') }}</span><input type="number" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan') }}" min="0" step="1000" placeholder="0" required></label>
                            <label><span>{{ __('tax.annual_bonus') }}</span><input type="number" name="penghasilan_tidak_teratur" value="{{ old('penghasilan_tidak_teratur') }}" min="0" step="1000" placeholder="0" required></label>

                            <div class="full" style="margin-top: 10px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 5px; color: var(--accent-primary); font-weight: bold;">{{ __('tax.deduction_section') }}</div>
                            <label><span>{{ __('tax.pension_bpjs') }}</span><input type="number" name="iuran_pensiun" value="{{ old('iuran_pensiun') }}" min="0" step="1000" placeholder="0" required></label>
                            <label><span>{{ __('tax.official_zakat') }}</span><input type="number" name="zakat" value="{{ old('zakat') }}" min="0" step="1000" placeholder="0" required></label>
                            <label class="full"><span>{{ __('tax.tax_credit') }}</span><input type="number" name="kredit_pajak" value="{{ old('kredit_pajak') }}" min="0" step="1000" placeholder="0" required></label>
                        </div>
                        <button class="tax-button" type="submit">{{ __('tax.calculate_tax') }}</button>
                    </form>

                    <div class="tax-panel tax-panel-inner" style="margin-top: 22px;">
                        <h2>{{ __('tax.calculation_history') }}</h2>
                        @if($history->count() > 0)
                            <div class="history-list">
                                @foreach($history as $item)
                                    <div class="history-item">
                                        <div>
                                            <div style="font-weight: bold;">{{ $item->tahun_pajak }} - {{ $item->status_wajib_pajak }}</div>
                                            <div style="font-size: 0.8rem; color: #94a3b8;">{{ $item->created_at->format('d M Y') }}</div>
                                        </div>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="{{ route('perpajakan.index', ['load_id' => $item->id]) }}" class="btn-use" style="background: var(--accent-primary); color: #fff;">{{ __('tax.view') }}</a>
                                            <form action="{{ route('perpajakan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ __('tax.delete_confirm') }}');" style="margin:0;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-use" style="background: #ef4444; color: #fff; border:none; cursor:pointer;">{{ __('tax.delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p style="margin-top: 15px;">{{ __('tax.empty_history') }}</p>
                        @endif
                    </div>
                </div>

                <div class="tax-panel tax-panel-inner">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <h2>{{ __('tax.calc_output') }}</h2>
                            <p>{{ $result ? __('tax.tax_summary') : __('tax.results_will_appear') }}</p>
                        </div>
                        @if ($result)
                            <a href="{{ route('perpajakan.export-pdf', $result['id']) }}" class="btn-use" style="background: #ef4444; color: white;" target="_blank">{{ __('tax.export_pdf') }}</a>
                        @endif
                    </div>
                    
                    @if ($result)
                        <div style="margin-bottom: 15px; padding: 10px; background: rgba(20,184,166,0.1); border-left: 3px solid var(--accent-primary); border-radius: 4px; font-size: 0.9rem;">
                            <strong>{{ __('tax.method') }}</strong> {{ $translateTaxNote($result) }}
                        </div>

                        <div class="tax-metrics">
                            <div class="tax-metric"><span>{{ __('tax.annual_gross_income') }}</span><strong>{{ $formatRupiah($result['penghasilan_tahunan'] + $result['penghasilan_tidak_teratur']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.annual_deductions') }}</span><strong>{{ $formatRupiah($result['pengurang_tahunan']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.net_income') }}</span><strong>{{ $formatRupiah($result['penghasilan_neto']) }}</strong></div>
                            <div class="tax-metric"><span>PTKP ({{ $result['status_wajib_pajak'] }})</span><strong>{{ $formatRupiah($result['ptkp']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.rounded_pkp') }}</span><strong>{{ $formatRupiah($result['pkp']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.tax_underpaid') }}</span><strong style="color: #fb7185;">{{ $formatRupiah($result['pajak_kurang_bayar']) }}</strong></div>
                        </div>

                        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div style="background: rgba(243, 201, 105, 0.1); padding: 15px; border-radius: 8px; border: 1px solid rgba(243, 201, 105, 0.3);">
                                <span style="font-size: 0.8rem; color: var(--accent-primary); font-weight: bold;">{{ __('tax.annual_pph_article_17') }}</span>
                                <h3 style="margin: 5px 0 0; font-size: 1.5rem;">{{ $formatRupiah($result['estimasi_pajak_tahunan']) }}</h3>
                            </div>
                            <div style="background: rgba(20, 184, 166, 0.1); padding: 15px; border-radius: 8px; border: 1px solid rgba(20, 184, 166, 0.3);">
                                <span style="font-size: 0.8rem; color: var(--accent-primary); font-weight: bold;">{{ __('tax.est_monthly_tax') }}</span>
                                <h3 style="margin: 5px 0 0; font-size: 1.5rem;">{{ $formatRupiah($result['estimasi_pajak_bulanan']) }}</h3>
                            </div>
                        </div>

                        <h3 style="margin-top: 25px; font-size: 1rem;">{{ __('tax.progressive_tax_detail') }}</h3>
                        <table class="tax-table">
                            <thead><tr><th>{{ __('tax.pkp_layer') }}</th><th>{{ __('tax.rate') }}</th><th>{{ __('tax.tax') }}</th></tr></thead>
                            <tbody>
                                @forelse ($result['breakdown'] as $row)
                                    <tr><td>{{ $row['label'] }}</td><td>{{ $row['rate'] * 100 }}%</td><td>{{ $formatRupiah($row['tax']) }}</td></tr>
                                @empty
                                    <tr><td colspan="3">{{ __('tax.no_tax_due') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="tax-note">{{ __('tax.fill_form') }}</div>
                    @endif
                </div>
            </section>
        </div>
    </main>
@endsection
