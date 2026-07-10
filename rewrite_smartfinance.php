<?php
$filepath = __DIR__ . '/resources/views/livewire/smart-finance.blade.php';

$content = <<<'EOT'
<div class="module-page" data-page-title="{{ __('finance.page_title') }}">
    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $formatPercent = fn ($value) => number_format($value, 1, ',', '.') . '%';
        $formatRupiahInput = function ($value) {
            if ($value === null || $value === '' || (float) preg_replace('/[^0-9]/', '', (string) $value) === 0.0) {
                return '';
            }
            return number_format((float) preg_replace('/[^0-9]/', '', (string) $value), 0, ',', '.');
        };

        $translatePeriode = function ($value) {
            $monthsId = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $monthsEn = [
                '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
            ];

            if (preg_match('/^(\d{4})-(\d{2})$/', $value, $matches)) {
                $locale = app()->getLocale();
                $months = $locale === 'en' ? $monthsEn : $monthsId;
                return $months[$matches[2]] . ' ' . $matches[1];
            }
            return $value;
        };
    @endphp

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary": "#ffffff",
                        "inverse-primary": "#bec6e0",
                        "on-primary-fixed-variant": "#3f465c",
                        "on-primary": "#ffffff",
                        "secondary-container": "#316bf3",
                        "on-surface": "#191c1e",
                        "on-primary-container": "#7c839b",
                        "outline": "#76777d",
                        "surface-container": "#eceef0",
                        "surface-tint": "#565e74",
                        "tertiary-fixed": "#6ffbbe",
                        "on-error-container": "#93000a",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-fixed": "#131b2e",
                        "on-tertiary-container": "#009668",
                        "inverse-surface": "#2d3133",
                        "surface-dim": "#d8dadc",
                        "on-secondary": "#ffffff",
                        "secondary-fixed": "#dbe1ff",
                        "surface-variant": "#e0e3e5",
                        "on-secondary-fixed": "#00174b",
                        "tertiary-container": "#002113",
                        "surface-container-highest": "#e0e3e5",
                        "secondary": "#0051d5",
                        "surface": "#f7f9fb",
                        "on-surface-variant": "#45464d",
                        "primary-container": "#131b2e",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "primary": "#000000",
                        "error": "#ba1a1a",
                        "secondary-fixed-dim": "#b4c5ff",
                        "primary-fixed-dim": "#bec6e0",
                        "outline-variant": "#c6c6cd",
                        "on-tertiary-fixed": "#002113",
                        "tertiary": "#000000",
                        "on-error": "#ffffff",
                        "on-tertiary-fixed-variant": "#005236",
                        "on-secondary-fixed-variant": "#003ea8",
                        "surface-container-lowest": "#ffffff",
                        "background": "#f7f9fb",
                        "on-secondary-container": "#fefcff",
                        "on-background": "#191c1e",
                        "primary-fixed": "#dae2fd",
                        "tertiary-fixed-dim": "#4edea3",
                        "surface-bright": "#f7f9fb"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "stack-sm": "8px",
                        "stack-md": "16px",
                        "container-max": "1280px",
                        "margin-mobile": "16px",
                        "stack-lg": "32px",
                        "margin-desktop": "40px",
                        "gutter": "24px",
                        "base": "4px"
                    },
                    "fontFamily": {
                        "body-sm": ["Inter"],
                        "body-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "label-md": ["Inter"],
                        "stats-num": ["Inter"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "display-lg-mobile": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "body-sm": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-sm": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "stats-num": ["28px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "display-lg-mobile": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --text-main: #191c1e;
            --accent-primary: #0051d5;
            --border-color: #c6c6cd;
            --nav-bg: #f2f4f6;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .icon-fill {
            font-variation-settings: 'FILL' 1;
        }
        .money-field { position: relative; }
        .money-prefix { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: theme('colors.on-surface-variant'); font-size: 0.875rem; font-weight: 600; pointer-events: none; }
        .money-field input { padding-left: 42px; }
    </style>

    @include('partials.module-shell-styles')

    <!-- Scrollable Content Area -->
    <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop bg-background pb-32">
        <div class="max-w-container-max mx-auto space-y-stack-lg">
            
            <div class="workspace-topbar">
                @include('partials.module-switcher')
            </div>

            @if (session('success'))
                <div class="p-4 bg-tertiary-fixed-dim/20 border border-tertiary-fixed-dim text-on-tertiary-fixed rounded-lg font-bold mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h2 class="font-display-lg-mobile md:font-display-lg text-primary">{{ __('finance.title') }}</h2>
                    <p class="font-body-lg text-on-surface-variant mt-2">{{ __('finance.hero_desc') }}</p>
                    <a class="text-secondary font-label-md hover:underline mt-2 inline-block" href="{{ route('dashboard.user') }}">{{ __('finance.back_to_selector') }}</a>
                </div>
                <div class="flex gap-3">
                    @if ($result && isset($request) && $request->has('load_id'))
                        <a href="{{ route('finance.export-pdf', $request->input('load_id')) }}" class="px-4 py-2 border border-outline-variant rounded-lg font-label-md text-on-surface hover:bg-surface-variant transition-colors flex items-center gap-2" target="_blank">
                            <span class="material-symbols-outlined text-[18px]">download</span> Export PDF
                        </a>
                    @elseif ($result && $history->last())
                        <a href="{{ route('finance.export-pdf', $history->last()->id) }}" class="px-4 py-2 border border-outline-variant rounded-lg font-label-md text-on-surface hover:bg-surface-variant transition-colors flex items-center gap-2" target="_blank">
                            <span class="material-symbols-outlined text-[18px]">download</span> Export PDF
                        </a>
                    @endif
                </div>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-gutter">
                
                <!-- Left Column (Col 1-4): Form & Asset Distribution -->
                <div class="md:col-span-4 flex flex-col gap-gutter">
                    
                    <!-- INPUT FORM -->
                    <form class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col gap-4" wire:submit="analyze">
                        <div class="mb-2">
                            <h3 class="font-headline-sm text-primary">{{ __('finance.monthly_input') }}</h3>
                            <p class="text-body-sm text-on-surface-variant">{{ __('finance.use_monthly_average') }}</p>
                        </div>
                        
                        <div class="grid gap-4">
                            <label class="flex flex-col gap-1">
                                <span class="font-label-md text-on-surface-variant">{{ __('finance.period_label') }}</span>
                                <input type="month" wire:model="periode" value="{{ old('periode', $result['periode'] ?? date('Y-m')) }}" required class="h-11 rounded border border-outline-variant px-3 bg-surface-container-lowest text-on-surface focus:border-secondary focus:ring-secondary">
                            </label>
                            <label class="flex flex-col gap-1">
                                <span class="font-label-md text-on-surface-variant">{{ __('finance.total_income') }}</span>
                                <div class="money-field">
                                    <span class="money-prefix">Rp</span>
                                    <input type="text" x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" wire:model="pemasukan" value="{{ $formatRupiahInput(old('pemasukan', $result['income'] ?? '')) }}" inputmode="numeric" autocomplete="off" required class="w-full h-11 rounded border border-outline-variant pl-10 pr-3 bg-surface-container-lowest text-on-surface focus:border-secondary focus:ring-secondary">
                                </div>
                            </label>
                        </div>

                        <!-- Expense Section -->
                        <div class="mt-4 border-t border-outline-variant pt-4">
                            <div class="flex justify-between items-center mb-4">
                                <span class="font-label-md text-on-surface-variant">{{ __('finance.expense_category') }}</span>
                            </div>
                            
                            <div class="flex flex-col gap-3">
                                @if(count($expenses) > 0)
                                    @foreach($expenses as $index => $expense)
                                        <div class="flex flex-col gap-2 p-3 border border-outline-variant rounded-lg bg-surface-container-low">
                                            <input type="text" wire:model="expenses.{{ $index }}.name" placeholder="{{ __('finance.category_name') }}" autocomplete="off" required class="h-10 rounded border border-outline-variant px-3 bg-surface-container-lowest text-on-surface focus:border-secondary text-sm">
                                            <div class="flex gap-2">
                                                <div class="money-field flex-1">
                                                    <span class="money-prefix text-xs">Rp</span>
                                                    <input type="text" wire:model="expenses.{{ $index }}.amount" x-data x-init="$el.value = String($el.value).replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" inputmode="numeric" autocomplete="off" required class="w-full h-10 rounded border border-outline-variant pl-8 pr-2 bg-surface-container-lowest text-on-surface focus:border-secondary text-sm">
                                                </div>
                                                <button type="button" wire:click.prevent="removeExpenseRow({{ $index }})" class="w-10 h-10 rounded bg-error-container text-error flex items-center justify-center hover:bg-error hover:text-on-error transition-colors"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                                            </div>
                                            <label class="flex items-center gap-2 mt-1 cursor-pointer">
                                                <input type="checkbox" wire:model="expenses.{{ $index }}.is_debt" class="rounded text-secondary focus:ring-secondary">
                                                <span class="text-xs font-semibold text-on-surface-variant">{{ __('finance.installment_label') }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            
                            <button type="button" wire:click.prevent="addExpenseRow" class="w-full mt-3 py-2 border border-dashed border-outline-variant rounded-lg text-on-surface-variant font-label-md hover:border-secondary hover:text-secondary transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add</span> {{ __('finance.add_category') }}
                            </button>
                        </div>

                        <!-- Savings / Investments -->
                        <div class="mt-4 border-t border-outline-variant pt-4 grid gap-4">
                            <label class="flex flex-col gap-1">
                                <span class="font-label-md text-on-surface-variant">{{ __('finance.monthly_savings') }}</span>
                                <div class="money-field"><span class="money-prefix">Rp</span><input type="text" x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" wire:model="tabungan" value="{{ $formatRupiahInput(old('tabungan', $result['saving'] ?? '')) }}" inputmode="numeric" autocomplete="off" required class="w-full h-11 rounded border border-outline-variant pl-10 pr-3 bg-surface-container-lowest text-on-surface focus:border-secondary focus:ring-secondary"></div>
                            </label>
                            <label class="flex flex-col gap-1">
                                <span class="font-label-md text-on-surface-variant">{{ __('finance.investment') }}</span>
                                <div class="money-field"><span class="money-prefix">Rp</span><input type="text" x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" wire:model="investasi" value="{{ $formatRupiahInput(old('investasi', $result['investment'] ?? '')) }}" inputmode="numeric" autocomplete="off" required class="w-full h-11 rounded border border-outline-variant pl-10 pr-3 bg-surface-container-lowest text-on-surface focus:border-secondary focus:ring-secondary"></div>
                            </label>
                            <label class="flex flex-col gap-1">
                                <span class="font-label-md text-on-surface-variant">{{ __('finance.emergency_fund') }}</span>
                                <div class="money-field"><span class="money-prefix">Rp</span><input type="text" x-data x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" wire:model="dana_darurat" value="{{ $formatRupiahInput(old('dana_darurat', $result['emergency_fund'] ?? '')) }}" inputmode="numeric" autocomplete="off" required class="w-full h-11 rounded border border-outline-variant pl-10 pr-3 bg-surface-container-lowest text-on-surface focus:border-secondary focus:ring-secondary"></div>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3 bg-secondary text-on-secondary font-label-md rounded-lg shadow-sm hover:opacity-90 mt-4">{{ __('finance.calculate') }}</button>
                    </form>

                    <!-- Asset Distribution (Only if result exists) -->
                    @if($result)
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-headline-sm text-primary">Asset Distribution</h3>
                            <button class="text-on-surface-variant hover:text-secondary"><span class="material-symbols-outlined">more_horiz</span></button>
                        </div>
                        <div class="flex-1 flex flex-col items-center justify-center relative my-4">
                            <canvas id="assetDistributionChart" width="200" height="200"></canvas>
                        </div>
                        <div id="donutLegend" class="grid grid-cols-1 gap-2 mt-4 text-label-md"></div>
                    </div>
                    @endif

                </div> <!-- End Left Column -->

                <!-- Right Column (Col 5-12): Budget & Planning -->
                <div class="md:col-span-8 flex flex-col gap-gutter">
                    
                    @if ($result)
                        <!-- Top Row: High Level Stats -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-gutter">
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-sm">
                                <span class="font-label-md text-on-surface-variant uppercase">{{ __('finance.metric_income') }}</span>
                                <div class="font-stats-num text-primary mt-2">{{ $formatRupiah($result['income']) }}</div>
                                <div class="mt-4 w-full h-2 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-secondary w-[100%] rounded-full"></div>
                                </div>
                            </div>
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-sm">
                                <span class="font-label-md text-on-surface-variant uppercase">{{ __('finance.metric_expenses') }}</span>
                                <div class="font-stats-num text-primary mt-2">{{ $formatRupiah($result['total_expenses']) }}</div>
                                <div class="mt-4 w-full h-2 bg-surface-container-high rounded-full overflow-hidden">
                                    <div class="h-full bg-tertiary-fixed-dim rounded-full" style="width: {{ min($result['expense_ratio'], 100) }}%"></div>
                                </div>
                            </div>
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-sm">
                                <span class="font-label-md text-on-surface-variant uppercase">{{ __('finance.metric_net_cashflow') }}</span>
                                <div class="font-stats-num text-primary mt-2">{{ $formatRupiah($result['net_cashflow']) }}</div>
                                <div class="mt-4 flex items-center gap-2 font-label-md text-on-tertiary-container bg-tertiary-fixed/20 px-2 py-1 rounded w-fit">
                                    @if($result['net_cashflow'] > 0)
                                        <span class="material-symbols-outlined text-[16px]">trending_up</span> Safe
                                    @else
                                        <span class="material-symbols-outlined text-[16px] text-error">trending_down</span> Warning
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Mid Row: Emergency & Ratios -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-gutter">
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-sm">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-headline-sm text-primary">Key Ratios</h3>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <div class="flex justify-between font-label-md mb-2"><span>{{ __('finance.ratio_expense') }}</span><strong class="text-primary">{{ $formatPercent($result['expense_ratio']) }}</strong></div>
                                        <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden"><div class="h-full bg-secondary rounded-full" style="width: {{ min($result['expense_ratio'], 100) }}%"></div></div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between font-label-md mb-2"><span>{{ __('finance.saving_ratio') }}</span><strong class="text-primary">{{ $formatPercent($result['saving_ratio']) }}</strong></div>
                                        <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden"><div class="h-full bg-tertiary-fixed-dim rounded-full" style="width: {{ min($result['saving_ratio'], 100) }}%"></div></div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between font-label-md mb-2"><span>{{ __('finance.ratio_installment') }}</span><strong class="text-primary">{{ $formatPercent($result['debt_ratio']) }}</strong></div>
                                        <div class="w-full h-2 bg-surface-container-high rounded-full overflow-hidden"><div class="h-full bg-error rounded-full" style="width: {{ min($result['debt_ratio'], 100) }}%"></div></div>
                                    </div>
                                </div>
                            </div>

                            <!-- What If Simulation -->
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-sm" x-data="{
                                expPct: 0, debtPct: 0,
                                baseCashflow: {{ (float) $result['net_cashflow'] }},
                                baseExpense: {{ (float) $result['total_expenses'] }},
                                baseDebt: {{ (float) array_sum(array_column(array_filter($result['expense_items'], fn($i) => !empty($i['is_debt'])), 'amount')) }},
                                get baseNonDebtExpense() { return this.baseExpense - this.baseDebt; },
                                get savedFromExpense() { return this.baseNonDebtExpense * (this.expPct / 100); },
                                get savedFromDebt() { return this.baseDebt * (this.debtPct / 100); },
                                get totalSaved() { return this.savedFromExpense + this.savedFromDebt; },
                                get projectedCashflow() { return this.baseCashflow + this.totalSaved; },
                                formatRp(num) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(num)); }
                            }">
                                <h3 class="font-headline-sm text-primary mb-2">{{ __('finance.what_if_simulation') }}</h3>
                                <p class="text-body-sm text-on-surface-variant mb-4">{{ __('finance.simulation_intro') }}</p>
                                
                                <div class="flex flex-col gap-4">
                                    <div>
                                        <div class="flex justify-between font-label-md mb-2"><span>{{ __('finance.reduce_expenses') }}</span><span class="text-secondary" x-text="expPct + '%'">0%</span></div>
                                        <input type="range" x-model="expPct" min="0" max="50" step="5" class="w-full accent-secondary">
                                    </div>
                                    <div>
                                        <div class="flex justify-between font-label-md mb-2"><span>{{ __('finance.reduce_installments') }}</span><span class="text-secondary" x-text="debtPct + '%'">0%</span></div>
                                        <input type="range" x-model="debtPct" min="0" max="100" step="10" class="w-full accent-secondary">
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-secondary-container/10 border border-secondary-container/30 rounded-lg text-center">
                                    <span class="text-label-md text-on-surface-variant">{{ __('finance.projected_cashflow') }}</span>
                                    <div class="text-stats-num text-secondary mt-1" x-text="formatRp(projectedCashflow)"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Row: Recommendations & Expense Pos -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter flex-1">
                            <!-- Recommendations -->
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="font-headline-sm text-primary">{{ __('finance.recommendations') }}</h3>
                                </div>
                                <div class="flex flex-col gap-3 overflow-y-auto max-h-[300px] pr-2">
                                    @foreach ($result['recommendations'] as $recommendation)
                                        <div class="p-3 bg-surface-container-low border border-outline-variant rounded-lg text-body-sm text-on-surface-variant">
                                            {{ $recommendation }}
                                        </div>
                                    @endforeach
                                    @if (!empty($recommendations))
                                        @foreach ($recommendations as $rec)
                                            <div class="p-3 bg-surface-container-low border-l-4 {{ $rec['status'] == 'ok' ? 'border-tertiary-fixed-dim' : ($rec['status'] == 'warning' ? 'border-secondary' : 'border-error') }} rounded-r-lg text-body-sm">
                                                <strong class="text-primary block">{{ __($rec['category_name']) }}</strong>
                                                <span class="text-on-surface-variant">{{ $rec['reason'] }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <!-- Expense Pos Breakdown -->
                            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-6 shadow-sm flex flex-col">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="font-headline-sm text-primary">Expense Pos</h3>
                                </div>
                                <div class="flex flex-col gap-5 overflow-y-auto max-h-[300px] pr-2">
                                    @foreach($result['expense_items'] as $item)
                                        <div class="flex flex-col gap-2">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-on-surface">
                                                        <span class="material-symbols-outlined text-[16px]">{{ !empty($item['is_debt']) ? 'credit_card' : 'receipt_long' }}</span>
                                                    </div>
                                                    <span class="font-body-sm font-semibold text-primary">{{ $item['name'] }}</span>
                                                </div>
                                                <span class="font-label-md text-on-surface-variant">{{ $formatRupiah($item['amount']) }}</span>
                                            </div>
                                            <div class="w-full h-1.5 bg-surface-container-high rounded-full overflow-hidden">
                                                @php $pct = $result['total_expenses'] > 0 ? ($item['amount'] / $result['total_expenses']) * 100 : 0; @endphp
                                                <div class="h-full rounded-full {{ !empty($item['is_debt']) ? 'bg-error' : 'bg-secondary' }}" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    @else
                        <!-- Empty State -->
                        <div class="flex-1 bg-surface-container-lowest rounded-xl border border-outline-variant p-10 shadow-sm flex flex-col items-center justify-center text-center">
                            <span class="material-symbols-outlined text-[48px] text-outline mb-4">analytics</span>
                            <h3 class="font-headline-md text-primary">{{ __('finance.no_analysis') }}</h3>
                            <p class="text-body-lg text-on-surface-variant mt-2">{{ __('finance.fill_form') }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </main>
</div>

@if ($result)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var labels = [
            '{{ __('finance.basic_needs') }}',
            '{{ __('finance.transportation') }}',
            '{{ __('finance.debt_installment') }}',
            '{{ __('finance.lifestyle') }}',
            '{{ __('finance.savings_plus_investment') }}',
            '{{ __('finance.remaining_cash') }}'
        ];
        var amounts = [
            {{ $result['basic_needs'] }},
            {{ $result['transportation'] }},
            {{ $result['debt_installment'] }},
            {{ $result['lifestyle'] }},
            {{ $result['savings_plus_investment'] }},
            {{ $result['remaining_cash'] }}
        ];

        var palette = ['#0051d5','#6366f1','#fb7185','#f97316','#10b981','#d8dadc'];
        
        var ctx = document.getElementById('assetDistributionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: amounts,
                    backgroundColor: palette,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#191c1e',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        callbacks: {
                            label: function(ctx) {
                                var amt = new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(ctx.parsed);
                                return ' ' + amt;
                            }
                        }
                    }
                }
            }
        });

        // Legend
        var legendHtml = '';
        var total = amounts.reduce((a,b)=>a+b,0);
        labels.forEach((label, i) => {
            if(amounts[i] > 0) {
                var pct = ((amounts[i]/total)*100).toFixed(1);
                legendHtml += `<div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full" style="background:${palette[i]}"></div><div class="flex flex-col"><span class="text-on-surface-variant text-xs">${label} (${pct}%)</span></div></div>`;
            }
        });
        document.getElementById('donutLegend').innerHTML = legendHtml;
    });
</script>
@endif
EOT;

file_put_contents($filepath, $content);
echo "Rewrite complete.\n";
