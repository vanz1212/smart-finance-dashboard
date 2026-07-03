<div class="module-page" data-page-title="{{ __('tax.page_title') }}">
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
                radial-gradient(ellipse at 20% 0%, rgba(99, 102, 241, 0.12), transparent 50%),
                radial-gradient(ellipse at 80% 100%, rgba(139, 92, 246, 0.08), transparent 50%),
                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));
        }

        .tax-inner { width: min(1180px, 100%); margin: 0 auto; }
        .tax-topbar, .tax-hero { display: flex; justify-content: space-between; gap: 18px; align-items: center; }
        .tax-topbar { margin-bottom: 34px; }
        .tax-nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .tax-nav a { padding: 10px 14px; border: 1px solid rgba(255,255,255,.12); border-radius: 999px; color: rgba(248,250,252,.78); text-decoration: none; font-weight: 800; background: rgba(255,255,255,.05); transition: all 0.25s ease; }
        .tax-nav a.is-active, .tax-nav a:hover { color: #fff; background: var(--accent-primary); border-color: var(--accent-primary); box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3); }
        .tax-hero { align-items: flex-end; margin-bottom: 28px; }
        .tax-kicker { color: var(--accent-primary); font-size: .8rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .tax-hero h1 { margin: 12px 0 0; font-size: clamp(2.2rem, 5vw, 4.8rem); line-height: .98; letter-spacing: 0; }
        .tax-hero p { max-width: 720px; margin: 16px 0 0; color: rgba(248,250,252,.72); line-height: 1.7; }

        .tax-orbit {
            position: absolute;
            right: -42px;
            bottom: -44px;
            width: 190px;
            height: 190px;
            border: 1px solid rgba(129, 140, 248, .25);
            border-radius: 50%;
            opacity: .85;
            pointer-events: none;
        }

        .tax-orbit::before,
        .tax-orbit::after {
            content: "";
            position: absolute;
            border-radius: 50%;
        }

        .tax-orbit::before {
            inset: 34px;
            border: 1px dashed rgba(34, 211, 238, .32);
        }

        .tax-orbit::after {
            width: 18px;
            height: 18px;
            right: 24px;
            top: 42px;
            background: linear-gradient(135deg, #60a5fa, #22d3ee);
            box-shadow: 0 0 26px rgba(34, 211, 238, .58);
        }
        
        .disclaimer-alert {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            border-left: 4px solid #f59e0b;
            color: #fcd34d;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            line-height: 1.6;
            backdrop-filter: blur(8px);
        }

        .tax-badge { min-width: 144px; padding: 12px 16px; border-radius: 999px; text-align: center; font-weight: 900; transition: transform 0.2s ease; }
        .tax-badge:hover { transform: scale(1.05); }
        .tax-badge.neutral { background: rgba(255,255,255,.12); color: #cbd5e1; border: 1px solid rgba(255,255,255,.15); }
        .tax-badge.success { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3); }
        .tax-badge.warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); }
        .tax-badge.danger { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); }

        .tax-grid { display: grid; grid-template-columns: minmax(320px,.92fr) minmax(380px,1.08fr); gap: 22px; align-items: start; }
        .tax-panel { 
            border: 1px solid rgba(255,255,255,.1); 
            border-radius: 20px; 
            background: rgba(15, 23, 42, 0.6); 
            box-shadow: 0 20px 60px rgba(0,0,0,.25); 
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .tax-panel:hover {
            border-color: rgba(255,255,255,.15);
            box-shadow: 0 25px 70px rgba(0,0,0,.3);
        }
        .tax-panel-inner { padding: 28px; }
        .tax-panel h2 { margin: 0; font-size: 1.2rem; font-weight: 800; }
        .tax-panel p { color: rgba(248,250,252,.6); line-height: 1.6; font-size: 0.9rem; }

        .tax-card-head {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
        }

        .tax-card-head p {
            margin: 7px 0 0;
        }

        .tax-icon {
            width: 44px;
            height: 44px;
            position: relative;
            flex: 0 0 auto;
            display: grid;
            place-items: center;
            border: 1px solid rgba(129, 140, 248, .28);
            border-radius: 14px;
            color: #dbeafe;
            background:
                linear-gradient(135deg, rgba(99, 102, 241, .34), rgba(34, 211, 238, .12)),
                rgba(255, 255, 255, .05);
            box-shadow: 0 12px 30px rgba(99, 102, 241, .18);
        }

        .tax-icon::before,
        .tax-icon::after,
        .field-icon::before,
        .field-icon::after {
            content: "";
            position: absolute;
            display: block;
        }

        .tax-icon.calculator::before {
            width: 20px;
            height: 24px;
            border: 2px solid currentColor;
            border-radius: 5px;
        }

        .tax-icon.calculator::after {
            width: 12px;
            height: 2px;
            top: 16px;
            background: currentColor;
            box-shadow: 0 6px 0 currentColor, -5px 12px 0 -1px currentColor, 0 12px 0 -1px currentColor, 5px 12px 0 -1px currentColor;
        }

        .tax-icon.report::before {
            width: 23px;
            height: 26px;
            border: 2px solid currentColor;
            border-radius: 6px;
        }

        .tax-icon.report::after {
            width: 13px;
            height: 2px;
            left: 15px;
            top: 16px;
            background: currentColor;
            box-shadow: 0 6px 0 currentColor, 0 12px 0 currentColor;
        }

        .tax-icon.history::before {
            width: 24px;
            height: 24px;
            border: 2px solid currentColor;
            border-radius: 50%;
        }

        .tax-icon.history::after {
            width: 9px;
            height: 7px;
            border-left: 2px solid currentColor;
            border-bottom: 2px solid currentColor;
            transform: translate(2px, -2px);
        }

        .tax-section-chip {
            grid-column: 1 / -1;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-top: 8px;
            padding: 8px 11px;
            border: 1px solid rgba(129, 140, 248, .22);
            border-radius: 999px;
            color: #c7d2fe;
            background: rgba(99, 102, 241, .1);
            font-size: .78rem;
            font-weight: 900;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        
        .tax-form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 15px; margin-top: 20px;}
        .tax-form label { display: grid; gap: 8px; }
        .tax-form label.full { grid-column: 1 / -1; }
        .tax-form span { color: rgba(248,250,252,.7); font-size: .84rem; font-weight: 700; display: flex; align-items: center; gap: 7px; }
        .tax-form input, .tax-form select { 
            min-height: 48px; width: 100%; 
            border: 1px solid rgba(255,255,255,.12); 
            border-radius: 12px; 
            padding: 10px 14px; 
            background: rgba(255,255,255,.05); 
            color: #fff; 
            font: inherit; 
            transition: all 0.25s ease;
        }
        .tax-form select {
            appearance: none;
            padding-right: 42px;
            cursor: pointer;
        }
        .select-shell {
            position: relative;
        }
        .select-shell::after {
            content: "";
            position: absolute;
            right: 16px;
            top: 50%;
            width: 9px;
            height: 9px;
            border-right: 2px solid rgba(191, 219, 254, .9);
            border-bottom: 2px solid rgba(191, 219, 254, .9);
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
        }
        .field-icon {
            width: 20px;
            height: 20px;
            position: relative;
            flex: 0 0 auto;
            display: inline-grid;
            place-items: center;
            border-radius: 7px;
            color: #93c5fd;
            background: rgba(96, 165, 250, .1);
        }
        .field-icon.calendar::before { inset: 5px 4px 4px; border: 2px solid currentColor; border-radius: 4px; }
        .field-icon.calendar::after { left: 5px; right: 5px; top: 8px; height: 2px; background: currentColor; }
        .field-icon.method::before { inset: 4px; border: 2px solid currentColor; border-radius: 50%; }
        .field-icon.method::after { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .field-icon.user::before { left: 7px; top: 4px; width: 6px; height: 6px; border: 2px solid currentColor; border-radius: 50%; }
        .field-icon.user::after { left: 4px; right: 4px; bottom: 4px; height: 7px; border: 2px solid currentColor; border-radius: 8px 8px 4px 4px; }
        .field-icon.money::before { inset: 5px 3px; border: 2px solid currentColor; border-radius: 5px; }
        .field-icon.money::after { right: 5px; top: 9px; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .field-icon.minus::before { left: 5px; right: 5px; top: 9px; height: 2px; background: currentColor; }
        .field-icon.credit::before { inset: 5px 3px; border: 2px solid currentColor; border-radius: 4px; }
        .field-icon.credit::after { left: 6px; right: 6px; top: 10px; height: 2px; background: currentColor; }
        .tax-form select option { background: #0f172a; color: #fff; }
        .tax-form input:focus, .tax-form select:focus { 
            outline: none; 
            border-color: var(--accent-primary); 
            background: rgba(255,255,255,.08);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }
        .tax-button { 
            width: 100%; min-height: 52px; margin-top: 18px; border: 0; 
            border-radius: 14px; 
            background: linear-gradient(135deg, var(--accent-primary), #4f46e5); 
            color: #fff; 
            cursor: pointer; font: inherit; font-weight: 800; font-size: 1rem;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            transition: all 0.25s ease;
        }
        .tax-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.4);
        }
        .tax-button:active { transform: translateY(0); }

        .tax-button span {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .tax-button span::before {
            content: "";
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 2px solid currentColor;
            box-shadow: inset 0 -6px 0 rgba(255,255,255,.18);
        }

        .tax-metrics { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 14px; margin-top: 15px;}
        .tax-metric, .tax-note { 
            padding: 18px; 
            border: 1px solid rgba(255,255,255,.08); 
            border-radius: 14px; 
            background: rgba(255,255,255,.04); 
            transition: all 0.25s ease;
        }
        .tax-metric:hover { 
            background: rgba(255,255,255,.07); 
            border-color: rgba(255,255,255,.15);
            transform: translateY(-2px); 
        }
        .tax-metric span { display: block; margin-bottom: 8px; color: rgba(248,250,252,.55); font-size: .82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.03em; }
        .tax-metric strong { color: #fff; font-size: 1.1rem; }
        .tax-metric.featured {
            background: linear-gradient(135deg, rgba(99, 102, 241, .18), rgba(34, 211, 238, .08));
            border-color: rgba(129, 140, 248, .28);
        }
        .tax-result-cards {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .tax-result-card {
            position: relative;
            overflow: hidden;
            padding: 18px;
            border-radius: 14px;
            border: 1px solid rgba(129, 140, 248, .26);
            background: linear-gradient(135deg, rgba(99, 102, 241, .16), rgba(15, 23, 42, .6));
        }
        .tax-result-card.green {
            border-color: rgba(34, 211, 238, .3);
            background: linear-gradient(135deg, rgba(34, 211, 238, .12), rgba(15, 23, 42, .6));
        }
        .tax-result-card::after {
            content: "";
            position: absolute;
            right: -22px;
            top: -22px;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.14);
        }
        .tax-result-card span {
            position: relative;
            z-index: 1;
            color: #bfdbfe;
            font-size: .78rem;
            font-weight: 900;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .tax-result-card h3 {
            position: relative;
            z-index: 1;
            margin: 8px 0 0;
            font-size: clamp(1.25rem, 2vw, 1.7rem);
        }
        .tax-table { width: 100%; border-collapse: collapse; margin-top: 22px; overflow: hidden; border-radius: 14px; font-size: 0.9rem;}
        .tax-table th, .tax-table td { padding: 14px; border-bottom: 1px solid rgba(255,255,255,.07); text-align: left; color: rgba(248,250,252,.78); }
        .tax-table th { color: #fff; background: rgba(255,255,255,.05); font-weight: 700; }
        .tax-table tbody tr { transition: background 0.2s ease; }
        .tax-table tbody tr:hover { background: rgba(255,255,255,.04); }
        .tax-table td:last-child, .tax-table th:last-child { text-align: right; }
        .tax-reference { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 22px; margin-top: 22px; }

        .btn-use { 
            padding: 8px 16px; border-radius: 10px; font-weight: 700; text-decoration: none; display: inline-block; 
            transition: all 0.2s ease; font-size: 0.85rem;
        }
        .btn-use:hover { transform: translateY(-1px); filter: brightness(1.1); }
        
        .history-list { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
        .history-item { 
            background: rgba(255,255,255,0.04); 
            border: 1px solid rgba(255,255,255,0.08); 
            padding: 14px 16px; border-radius: 12px; 
            display: flex; justify-content: space-between; align-items: center; 
            transition: all 0.25s ease;
        }
        .history-item:hover { 
            background: rgba(255,255,255,0.07); 
            border-color: rgba(255,255,255,.15);
            transform: translateX(4px); 
        }

        .history-empty {
            margin-top: 15px;
            padding: 18px;
            border: 1px dashed rgba(129, 140, 248, .25);
            border-radius: 14px;
            color: rgba(248,250,252,.64);
            background: rgba(99, 102, 241, .06);
        }

        .tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: linear-gradient(135deg, #60a5fa, #818cf8);
            color: transparent;
            font-size: 0;
            vertical-align: middle;
        }
        .tooltip::before {
            content: "i";
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            color: #ffffff;
            font-size: 11px;
            font-weight: 900;
            font-style: normal;
        }
        .tooltip .tooltiptext {
            visibility: hidden;
            width: 250px;
            background-color: rgba(15, 23, 42, 0.95);
            color: #fff;
            text-align: left;
            border-radius: 10px;
            padding: 12px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -125px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(4px);
            font-size: 0.75rem;
            font-weight: normal;
            border: 1px solid rgba(255,255,255,.12);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(12px);
        }
        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 900px) { .tax-topbar, .tax-hero { align-items: flex-start; flex-direction: column; } .tax-grid, .tax-reference { grid-template-columns: 1fr; } }
        @media (max-width: 620px) { .tax-workspace { margin: -24px; padding-inline: 14px; } .tax-form, .tax-metrics, .tax-result-cards { grid-template-columns: 1fr; } }
            </style>

    @include('partials.module-shell-styles')

    <main class="tax-workspace">
        <div class="tax-inner">
            <div class="tax-topbar">
                @include('partials.module-switcher')
            </div>
            
            <div class="disclaimer-alert">
                <strong>{{ __('tax.important') }}</strong> {{ __('tax.disclaimer_desc') }}
            </div>

            <section class="tax-hero module-hero">
                <div class="module-hero-panel module-hero-copy">
                    <div class="tax-orbit" aria-hidden="true"></div>
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
                    <form class="workspace-panel workspace-panel-inner" wire:submit="calculate">
                        
                        <div class="tax-card-head">
                            <span class="tax-icon calculator" aria-hidden="true"></span>
                            <div>
                                <h2>{{ __('tax.input_data') }}</h2>
                                <p>{{ __('tax.input_desc') }}</p>
                            </div>
                        </div>
                        <div class="tax-form">
                            <label>
                                <span><i class="field-icon calendar" aria-hidden="true"></i>{{ __('tax.tax_year') }}</span>
                                <div class="select-shell">
                                    <select wire:model="tahun_pajak" required>
                                        <option value="2024" {{ old('tahun_pajak') == '2024' ? 'selected' : '' }}>2024 ({{ __('tax.using_ter') }})</option>
                                        <option value="2023" {{ old('tahun_pajak') == '2023' ? 'selected' : '' }}>2023 ({{ __('tax.old_pph_21') }})</option>
                                    </select>
                                </div>
                            </label>
                            <label>
                                <span><i class="field-icon method" aria-hidden="true"></i>{{ __('tax.calc_method') }}</span>
                                <div class="select-shell">
                                    <select wire:model="metode_perhitungan" required>
                                        <option value="ter" {{ old('metode_perhitungan') == 'ter' ? 'selected' : '' }}>{{ __('tax.monthly_ter') }}</option>
                                        <option value="tahunan" {{ old('metode_perhitungan') == 'tahunan' ? 'selected' : '' }}>{{ __('tax.annual_pph_21') }}</option>
                                    </select>
                                </div>
                            </label>
                            <label class="full">
                                <span><i class="field-icon user" aria-hidden="true"></i>{{ __('tax.taxpayer_status') }} 
                                    <div class="tooltip">ℹ️
                                        <span class="tooltiptext">{!! __('tax.ptkp_tooltip') !!}</span>
                                    </div>
                                </span>
                                <div class="select-shell">
                                <select wire:model="status_wajib_pajak" required>
                                    <option value="" disabled {{ !old('status_wajib_pajak') ? 'selected' : '' }}>{{ __('tax.choose_status') }}</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ old('status_wajib_pajak') === $status ? 'selected' : '' }}>{{ $status }} - PTKP {{ $formatRupiah($ptkpTable[$status]) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </label>

                            <div class="tax-section-chip"><i class="field-icon money" aria-hidden="true"></i>{{ __('tax.income_section') }}</div>
                            <label><span><i class="field-icon money" aria-hidden="true"></i>{{ __('tax.monthly_salary') }}</span><input type="number" wire:model="penghasilan_bulanan" value="{{ old('penghasilan_bulanan') }}" min="0" step="1000" placeholder="0" required></label>
                            <label><span><i class="field-icon money" aria-hidden="true"></i>{{ __('tax.annual_bonus') }}</span><input type="number" wire:model="penghasilan_tidak_teratur" value="{{ old('penghasilan_tidak_teratur') }}" min="0" step="1000" placeholder="0" required></label>

                            <div class="tax-section-chip"><i class="field-icon minus" aria-hidden="true"></i>{{ __('tax.deduction_section') }}</div>
                            <label><span><i class="field-icon minus" aria-hidden="true"></i>{{ __('tax.pension_bpjs') }}</span><input type="number" wire:model="iuran_pensiun" value="{{ old('iuran_pensiun') }}" min="0" step="1000" placeholder="0" required></label>
                            <label><span><i class="field-icon minus" aria-hidden="true"></i>{{ __('tax.official_zakat') }}</span><input type="number" wire:model="zakat" value="{{ old('zakat') }}" min="0" step="1000" placeholder="0" required></label>
                            <label class="full"><span><i class="field-icon credit" aria-hidden="true"></i>{{ __('tax.tax_credit') }}</span><input type="number" wire:model="kredit_pajak" value="{{ old('kredit_pajak') }}" min="0" step="1000" placeholder="0" required></label>
                        </div>
                        <button class="tax-button" type="submit"><span>{{ __('tax.calculate_tax') }}</span></button>
                    </form>

                    <div class="tax-panel tax-panel-inner" style="margin-top: 22px;">
                        <div class="tax-card-head">
                            <span class="tax-icon history" aria-hidden="true"></span>
                            <div>
                                <h2>{{ __('tax.calculation_history') }}</h2>
                                <p>{{ $history->count() > 0 ? $history->count() . ' saved simulations' : __('tax.empty_history') }}</p>
                            </div>
                        </div>
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
                                            <form class="workspace-panel workspace-panel-inner" wire:submit="calculate">
                                                 @method('DELETE')
                                                <button type="submit" class="btn-use" style="background: #ef4444; color: #fff; border:none; cursor:pointer;">{{ __('tax.delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="history-empty">{{ __('tax.empty_history') }}</div>
                        @endif
                    </div>
                </div>

                <div class="tax-panel tax-panel-inner">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 14px;">
                        <div class="tax-card-head" style="margin-bottom: 0;">
                            <span class="tax-icon report" aria-hidden="true"></span>
                            <div>
                                <h2>{{ __('tax.calc_output') }}</h2>
                                <p>{{ $result ? __('tax.tax_summary') : __('tax.results_will_appear') }}</p>
                            </div>
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
                            <div class="tax-metric featured"><span>{{ __('tax.annual_gross_income') }}</span><strong>{{ $formatRupiah($result['penghasilan_tahunan'] + $result['penghasilan_tidak_teratur']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.annual_deductions') }}</span><strong>{{ $formatRupiah($result['pengurang_tahunan']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.net_income') }}</span><strong>{{ $formatRupiah($result['penghasilan_neto']) }}</strong></div>
                            <div class="tax-metric"><span>PTKP ({{ $result['status_wajib_pajak'] }})</span><strong>{{ $formatRupiah($result['ptkp']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.rounded_pkp') }}</span><strong>{{ $formatRupiah($result['pkp']) }}</strong></div>
                            <div class="tax-metric"><span>{{ __('tax.tax_underpaid') }}</span><strong style="color: #fb7185;">{{ $formatRupiah($result['pajak_kurang_bayar']) }}</strong></div>
                        </div>

                        <div class="tax-result-cards">
                            <div class="tax-result-card">
                                <span>{{ __('tax.annual_pph_article_17') }}</span>
                                <h3>{{ $formatRupiah($result['estimasi_pajak_tahunan']) }}</h3>
                            </div>
                            <div class="tax-result-card green">
                                <span>{{ __('tax.est_monthly_tax') }}</span>
                                <h3>{{ $formatRupiah($result['estimasi_pajak_bulanan']) }}</h3>
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
</div>
