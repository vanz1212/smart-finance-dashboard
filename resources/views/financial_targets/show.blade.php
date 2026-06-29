@extends('layouts.app')

@section('title', $target->name . ' - Target Finansial')
@section('body-class', 'module-page')

@section('content')
    @php
        $formatRupiah = fn($value) => 'Rp ' . number_format($value, 0, ',', '.');
        $formatPercent = fn($value) => number_format($value, 1, ',', '.') . '%';
        $categoryLabels = [
            'tabungan' => 'Tabungan',
            'investasi' => 'Investasi',
            'asuransi' => 'Asuransi',
            'properti' => 'Properti',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
        ];
        $categoryColors = [
            'tabungan' => var(--accent-primary),
            'investasi' => '#6366f1',
            'asuransi' => '#f59e0b',
            'properti' => '#8b5cf6',
            'pendidikan' => '#06b6d4',
            'lainnya' => '#64748b',
        ];
    @endphp

    <style>
        .detail-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97));
        }

        .workspace-inner {
            width: min(1200px, 100%);
            margin: 0 auto;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(248, 250, 252, 0.72);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(-4px);
        }

        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 28px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.78), rgba(6, 24, 32, 0.84));
        }

        .header-info h1 {
            margin: 0 0 8px;
            font-size: 2rem;
        }

        .header-meta {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-category {
            background-color: var(--category-color);
            color: #ffffff;
        }

        .badge-status {
            background: rgba(255, 255, 255, 0.1);
            color: rgba(248, 250, 252, 0.78);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            margin-bottom: 28px;
        }

        .panel {
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.78), rgba(6, 24, 32, 0.84));
            padding: 24px;
        }

        .panel-title {
            margin: 0 0 20px;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .progress-section {
            margin-bottom: 28px;
        }

        .progress-bar {
            height: 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            overflow: hidden;
            margin-bottom: 12px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--accent-primary);
            transition: width 0.3s;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .info-item {
            padding: 14px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 8px;
        }

        .info-label {
            color: rgba(248, 250, 252, 0.6);
            font-size: 0.8rem;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .info-value {
            color: var(--accent-hover);
            font-size: 1.1rem;
            font-weight: 900;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
            margin-bottom: 20px;
            font-weight: 700;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-on-track { color: var(--accent-primary); }
        .status-at-risk { color: var(--accent-hover); }
        .status-behind { color: #fb7185; }

        .deposits-section {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .deposit-form {
            background: rgba(255, 255, 255, 0.04);
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 10px;
            margin-bottom: 12px;
        }

        .form-input {
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            font: inherit;
            font-size: 0.9rem;
        }

        .form-input:focus {
            outline: 3px solid rgba(20, 184, 166, 0.18);
            border-color: var(--accent-primary);
        }

        .btn-add {
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            background: var(--accent-primary);
            color: #ffffff;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #0d9488;
        }

        .deposits-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .deposit-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
            border-left: 4px solid var(--accent-primary);
            margin-bottom: 8px;
        }

        .deposit-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .deposit-date {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: rgba(248, 250, 252, 0.5);
        }

        .deposit-note {
            font-size: 0.95rem;
            color: rgba(248, 250, 252, 0.9);
            line-height: 1.4;
        }

        .deposit-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .deposit-amount {
            font-weight: 900;
            font-size: 1.05rem;
            color: var(--accent-primary);
        }

        .btn-delete {
            padding: 4px 8px;
            background: rgba(251, 113, 133, 0.15);
            color: #fb7185;
            border: 1px solid rgba(251, 113, 133, 0.3);
            border-radius: 4px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-delete:hover {
            background: rgba(251, 113, 133, 0.3);
        }

        .monthly-breakdown {
            margin-top: 20px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 10px;
        }

        .breakdown-label {
            font-size: 0.9rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .breakdown-chart {
            display: flex;
            align-items: flex-end;
            gap: 4px;
            height: 100px;
        }

        .breakdown-bar {
            flex: 1;
            background: var(--accent-primary);
            border-radius: 4px 4px 0 0;
            opacity: 0.7;
            transition: all 0.2s;
            cursor: pointer;
            min-height: 2px;
        }

        .breakdown-bar:hover {
            opacity: 1;
            transform: scaleY(1.1);
        }

        .sidebar-panel {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .quick-stat {
            padding: 14px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 8px;
        }

        .quick-stat-label {
            font-size: 0.75rem;
            color: rgba(248, 250, 252, 0.6);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .quick-stat-value {
            color: var(--accent-hover);
            font-size: 1.3rem;
            font-weight: 900;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-action {
            flex: 1;
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(248, 250, 252, 0.78);
            text-decoration: none;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-action:hover {
            border-color: rgba(20, 184, 166, 0.4);
            background: rgba(20, 184, 166, 0.1);
            color: var(--accent-primary);
        }

        @media (max-width: 900px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .header-section {
                flex-direction: column;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        html, body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
            background:
                linear-gradient(135deg, #06191b 0%, #071f22 48%, #091011 100%) !important;
            color: #f8fafc;
        }

        [data-theme="light"],
        [data-theme="light"] body {
            background: var(--bg-primary) !important;
            color: var(--text-main) !important;
        }

        body::before {
            opacity: .16 !important;
        }

        .page-shell, .container {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell, .container {
            padding-left: clamp(18px, 4vw, 56px) !important;
            padding-right: clamp(18px, 4vw, 56px) !important;
        }

        header, .topbar, .navbar, nav {
            max-width: none !important;
            width: 100% !important;
            box-sizing: border-box;
        }
    </style>

    <main class="detail-workspace">
        <div class="workspace-inner">
            <a href="{{ route('targets.index') }}" class="back-link">← Kembali ke Target Finansial</a>

            <div class="header-section" style="--category-color: {{ $categoryColors[$target->category] ?? '#64748b' }};">
                <div class="header-info">
                    <h1>{{ $target->name }}</h1>
                    @if ($target->description)
                        <p style="margin: 12px 0 16px; color: rgba(248, 250, 252, 0.72); line-height: 1.6; font-size: 1.05rem; max-width: 600px;">{{ $target->description }}</p>
                    @endif
                    <div class="header-meta">
                        <span class="badge badge-category">{{ $categoryLabels[$target->category] ?? 'Lainnya' }}</span>
                        <span class="badge badge-status">{{ ucfirst($target->status) }}</span>
                    </div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 2.5rem; font-weight: 900; color: var(--accent-hover);">{{ $formatPercent($target->progress) }}</div>
                    <div style="color: rgba(248, 250, 252, 0.6); font-size: 0.85rem;">Tercapai</div>
                </div>
            </div>

            <div class="content-grid">
                <div class="panel">
                    <div class="progress-section">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ min($target->progress, 100) }}%;"></div>
                        </div>
                        <div class="progress-info">
                            <span>{{ $formatRupiah($target->current_amount) }} / {{ $formatRupiah($target->target_amount) }}</span>
                            <span style="color: var(--accent-primary);">{{ $formatRupiah($target->remaining) }} lagi</span>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Target Nominal</div>
                            <div class="info-value">{{ $formatRupiah($target->target_amount) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Terkumpul</div>
                            <div class="info-value">{{ $formatRupiah($target->current_amount) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Sisa Butuh</div>
                            <div class="info-value" style="color: var(--accent-primary);">{{ $formatRupiah($target->remaining) }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tenggat</div>
                            <div class="info-value">{{ $target->target_date->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="status-indicator" style="color: var(--status-color);">
                        <span class="status-dot" style="background-color: currentColor;"></span>
                        <div>
                            <strong>{{ $target->performance['message'] }}</strong>
                            <p style="margin: 4px 0 0; font-size: 0.8rem; color: inherit; opacity: 0.8;">Target: {{ $formatRupiah($target->performance['recommended']) }}/bln | Realisasi: {{ $formatRupiah($target->performance['average']) }}/bln</p>
                        </div>
                    </div>

                    <div class="deposits-section">
                        <h3 style="margin: 0 0 16px; font-size: 1rem; font-weight: 800;">Riwayat Setoran</h3>

                        <div class="deposit-form">
                            <form action="{{ route('targets.add-deposit', $target->id) }}" method="POST" id="deposit-form">
                                @csrf
                                <div class="form-row">
                                    <input type="text" name="amount" placeholder="Jumlah (Rp)" data-rupiah-input required class="form-input">
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required class="form-input">
                                    <button type="submit" class="btn-add">+ Catat</button>
                                </div>
                                <input type="text" name="note" placeholder="Catatan (opsional)" class="form-input" style="margin-top: 8px; width: 100%;">
                            </form>
                        </div>

                        @if ($deposits->count() > 0)
                            <div class="deposits-list">
                                @foreach ($deposits as $deposit)
                                    <div class="deposit-item">
                                        <div class="deposit-info">
                                            <div class="deposit-date">{{ $deposit->date->format('d M Y') }}</div>
                                            @if ($deposit->note)
                                                <div class="deposit-note">{{ $deposit->note }}</div>
                                            @endif
                                        </div>
                                        <div class="deposit-right">
                                            <div class="deposit-amount">{{ $formatRupiah($deposit->amount) }}</div>
                                            <form action="{{ route('targets.remove-deposit', $deposit->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus setoran ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if (!empty($monthlyBreakdown))
                            <div class="monthly-breakdown">
                                <div class="breakdown-label">Setoran 12 Bulan Terakhir</div>
                                <div class="breakdown-chart">
                                    @php
                                        $maxAmount = max(array_values($monthlyBreakdown)) ?: 1;
                                    @endphp
                                    @foreach ($monthlyBreakdown as $month => $amount)
                                        <div class="breakdown-bar" style="height: {{ ($amount / $maxAmount) * 100 }}%;" title="{{ $month }}: {{ $formatRupiah($amount) }}"></div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @else
                            <div style="padding: 20px; text-align: center; color: rgba(248, 250, 252, 0.5);">
                                <p>Belum ada setoran tercatat</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="sidebar-panel">
                    <div class="panel">
                        <h3 class="panel-title" style="margin-bottom: 16px;">Ringkasan</h3>

                        <div class="quick-stat">
                            <div class="quick-stat-label">Hari Tersisa</div>
                            <div class="quick-stat-value">{{ $target->days_remaining }}</div>
                        </div>

                        <div class="quick-stat">
                            <div class="quick-stat-label">Setoran/Bulan</div>
                            <div class="quick-stat-value" style="font-size: 1rem;">{{ $formatRupiah($target->recommended_monthly) }}</div>
                        </div>

                        <div class="quick-stat">
                            <div class="quick-stat-label">Realisasi/Bulan</div>
                            <div class="quick-stat-value" style="font-size: 1rem;">{{ $formatRupiah($target->performance['average']) }}</div>
                        </div>

                        <div class="quick-stat">
                            <div class="quick-stat-label">Status</div>
                            <div style="margin-top: 8px;">
                                <span class="badge badge-status">
                                    @if ($target->is_achieved)
                                        ✅ Tercapai
                                    @elseif ($target->is_overdue)
                                        ⏰ Terlewat
                                    @elseif ($target->days_remaining < 30)
                                        ⚠️ Mendesak
                                    @else
                                        ✓ On Track
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('targets.edit', $target->id) }}" class="btn-action">Edit</a>
                            <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="flex: 1; margin: 0;" onsubmit="return confirm('Hapus target ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action" style="width: 100%; height: 100%; color: #fb7185;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var fields = document.querySelectorAll('[data-rupiah-input]');

            function formatRupiah(value) {
                var digits = String(value || '').replace(/[^0-9]/g, '');
                if (!digits) return '';
                return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function normalizeField(input) {
                var digits = input.value.replace(/[^0-9]/g, '');
                if (digits) {
                    input.value = 'Rp ' + formatRupiah(digits);
                } else {
                    input.value = '';
                }
            }

            fields.forEach(function (field) {
                normalizeField(field);
                field.addEventListener('input', function () {
                    normalizeField(field);
                });
            });
        });

        document.getElementById('deposit-form')?.addEventListener('submit', function(e) {
            var amountInput = this.querySelector('input[name="amount"]');
            var cleanValue = amountInput.value.replace(/[^0-9]/g, '');
            var amount = parseInt(cleanValue || '0');
            
            if (amount < 1000) {
                e.preventDefault();
                alert('Setoran minimal Rp 1.000');
                return false;
            }
            
            amountInput.value = cleanValue;
        });
    </script>

    <script>
        @if ($target->performance['status'] === 'on-track')
            document.querySelectorAll('[style*="--status-color"]').forEach(el => {
                el.style.setProperty('--status-color', var(--accent-primary), 'important');
            });
        @elseif ($target->performance['status'] === 'at-risk')
            document.querySelectorAll('[style*="--status-color"]').forEach(el => {
                el.style.setProperty('--status-color', var(--accent-hover), 'important');
            });
        @else
            document.querySelectorAll('[style*="--status-color"]').forEach(el => {
                el.style.setProperty('--status-color', '#fb7185', 'important');
            });
        @endif
    </script>
    @if(session('success'))
        <div id="success-popup" style="position: fixed; bottom: 30px; right: 30px; background: var(--accent-primary); color: #ffffff; padding: 16px 24px; border-radius: 12px; font-weight: 800; box-shadow: 0 10px 25px rgba(20, 184, 166, 0.3); z-index: 9999; display: flex; align-items: center; gap: 12px; animation: slideUp 0.4s ease forwards;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            {{ session('success') }}
        </div>
        <style>
            @keyframes slideUp {
                from { transform: translateY(100px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        </style>
        <script>
            setTimeout(() => {
                const popup = document.getElementById('success-popup');
                if (popup) {
                    popup.style.animation = 'fadeOut 0.4s ease forwards';
                    setTimeout(() => popup.remove(), 400);
                }
            }, 4000);
        </script>
    @endif
    @include('partials.module-shell-styles')
@endsection
