@extends('layouts.app')

@section('title', 'Target Finansial')
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
            'tabungan' => '#14b8a6',
            'investasi' => '#6366f1',
            'asuransi' => '#f59e0b',
            'properti' => '#8b5cf6',
            'pendidikan' => '#06b6d4',
            'lainnya' => '#64748b',
        ];
    @endphp

    <style>
        .targets-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .workspace-inner {
            width: min(1280px, 100%);
            margin: 0 auto;
        }

        .workspace-topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: center;
            margin-bottom: 34px;
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
        }

        .workspace-hero p {
            max-width: 680px;
            margin: 16px 0 0;
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }

        .stat-card {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            background: rgba(13, 47, 51, 0.6);
        }

        .stat-label {
            color: rgba(248, 250, 252, 0.62);
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .stat-value {
            margin-top: 8px;
            color: #f3c969;
            font-size: 1.8rem;
            font-weight: 900;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }

        .btn-primary {
            padding: 12px 24px;
            border-radius: 999px;
            background: #f3c969;
            color: #052e2b;
            text-decoration: none;
            font-weight: 900;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #f0c041;
            transform: translateY(-2px);
        }

        .targets-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        .target-card {
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.78), rgba(6, 24, 32, 0.84));
            box-shadow: 0 28px 80px rgba(0, 0, 0, 0.34);
            backdrop-filter: blur(16px);
            transition: all 0.3s;
        }

        .target-card:hover {
            border-color: rgba(20, 184, 166, 0.3);
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.88), rgba(6, 24, 32, 0.92));
        }

        .target-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .target-category-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            color: #ffffff;
        }

        .target-title {
            margin: 0 0 8px;
            font-size: 1.1rem;
            font-weight: 800;
        }

        .target-progress-section {
            margin: 16px 0;
        }

        .progress-bar {
            height: 8px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            overflow: hidden;
            margin-bottom: 8px;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: #14b8a6;
            transition: width 0.3s;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.82rem;
            color: rgba(248, 250, 252, 0.72);
        }

        .progress-info strong {
            color: #f3c969;
            font-weight: 800;
        }

        .target-amount {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 12px 0;
            font-size: 0.88rem;
        }

        .amount-item {
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
        }

        .amount-label {
            color: rgba(248, 250, 252, 0.6);
            font-size: 0.75rem;
            margin-bottom: 4px;
        }

        .amount-value {
            color: #14b8a6;
            font-weight: 800;
            font-size: 0.95rem;
        }

        .target-deadline {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 8px;
            margin: 12px 0;
            font-size: 0.82rem;
        }

        .deadline-label {
            color: rgba(248, 250, 252, 0.6);
        }

        .deadline-value {
            color: #f3c969;
            font-weight: 800;
        }

        .target-performance {
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.04);
            margin: 12px 0;
            font-size: 0.82rem;
        }

        .performance-label {
            color: rgba(248, 250, 252, 0.6);
            margin-bottom: 4px;
        }

        .performance-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
        }

        .performance-status.on-track {
            color: #14b8a6;
        }

        .performance-status.at-risk {
            color: #f3c969;
        }

        .performance-status.behind {
            color: #fb7185;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .target-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .btn-action {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            color: rgba(248, 250, 252, 0.78);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 700;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-action:hover {
            border-color: rgba(20, 184, 166, 0.4);
            background: rgba(20, 184, 166, 0.1);
            color: #14b8a6;
        }

        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state h3 {
            font-size: 1.3rem;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: rgba(248, 250, 252, 0.62);
            margin-bottom: 20px;
        }

        @media (max-width: 900px) {
            .workspace-hero {
                flex-direction: column;
                align-items: flex-start;
            }

            .targets-container {
                grid-template-columns: 1fr;
            }
        }

        html, body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 82% 0%, rgba(24, 191, 117, .16), transparent 34%),
                linear-gradient(135deg, #06191b 0%, #071f22 48%, #091011 100%) !important;
            color: #f8fafc;
        }

        body::before {
            opacity: .16 !important;
        }

        .page-shell, .container, .dashboard-shell, .finance-shell, main {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell, .container, .dashboard-shell, .finance-shell {
            padding-left: clamp(18px, 4vw, 56px) !important;
            padding-right: clamp(18px, 4vw, 56px) !important;
        }

        header, .topbar, .navbar, nav {
            max-width: none !important;
            width: 100% !important;
            box-sizing: border-box;
        }
    </style>

    <main class="targets-workspace">
        <div class="workspace-inner">
            <section class="workspace-hero module-hero">
                <div class="module-hero-panel module-hero-copy">
                    <span class="workspace-kicker">Financial Goals</span>
                    <h1>Target Finansial</h1>
                    <p>Tetapkan dan monitor target finansial Anda dengan tracking progres real-time, rekomendasi setoran, dan dashboard komprehensif.</p>
                </div>
                <aside class="module-hero-panel module-hero-summary">
                    <div style="display:flex; gap: 8px; margin-bottom: 12px;">
                        <span style="padding: 6px 12px; background: #14b8a6; color: #042f2e; border-radius: 999px; font-weight: 900; font-size: 0.8rem;">{{ $stats['active_targets'] }} Aktif</span>
                        <span style="padding: 6px 12px; background: rgba(255,255,255,0.1); color: rgba(248,250,252,0.78); border-radius: 999px; font-weight: 900; font-size: 0.8rem;">{{ $stats['completed_targets'] }} Tercapai</span>
                    </div>
                    <strong>{{ $formatPercent($stats['overall_progress']) }}</strong>
                    <span>Progres keseluruhan dari semua target finansial Anda.</span>
                </aside>
            </section>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Target</div>
                    <div class="stat-value">{{ $stats['total_targets'] }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Target Nominal</div>
                    <div class="stat-value" style="font-size: 1.2rem;">{{ $formatRupiah($stats['total_target_amount']) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Terkumpul</div>
                    <div class="stat-value" style="font-size: 1.2rem;">{{ $formatRupiah($stats['total_collected']) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Sisa Target</div>
                    <div class="stat-value" style="font-size: 1.2rem;">{{ $formatRupiah($stats['total_target_amount'] - $stats['total_collected']) }}</div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('targets.create') }}" class="btn-primary">+ Buat Target Baru</a>
                <a href="{{ route('finance.index') }}" style="padding: 12px 24px; border-radius: 999px; background: rgba(255,255,255,0.08); color: rgba(248,250,252,0.78); text-decoration: none; font-weight: 900; border: 1px solid rgba(255,255,255,0.12); transition: all 0.2s;">Kembali ke Analisis</a>
            </div>

            @if ($targets->count() > 0)
                <div class="targets-container">
                    @foreach ($targets as $target)
                        <div class="target-card">
                            <div class="target-header">
                                <div style="flex: 1;">
                                    <h3 class="target-title">{{ $target->name }}</h3>
                                    <span class="target-category-badge" style="background-color: {{ $categoryColors[$target->category] ?? '#64748b' }};">
                                        {{ $categoryLabels[$target->category] ?? 'Lainnya' }}
                                    </span>
                                </div>
                                @if ($target->status !== 'active')
                                    <span style="padding: 4px 8px; border-radius: 6px; background: rgba(255,255,255,0.1); font-size: 0.7rem; font-weight: 800; white-space: nowrap;">
                                        {{ ucfirst($target->status) }}
                                    </span>
                                @endif
                            </div>

                            @if ($target->description)
                                <p style="margin: 8px 0; font-size: 0.85rem; color: rgba(248,250,252,0.62); line-height: 1.4;">{{ $target->description }}</p>
                            @endif

                            <div class="target-progress-section">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ min($target->progress, 100) }}%;"></div>
                                </div>
                                <div class="progress-info">
                                    <span>{{ $formatPercent($target->progress) }} tercapai</span>
                                    <strong>{{ $formatRupiah($target->current_amount) }} / {{ $formatRupiah($target->target_amount) }}</strong>
                                </div>
                            </div>

                            <div class="target-amount">
                                <div class="amount-item">
                                    <div class="amount-label">Target</div>
                                    <div class="amount-value">{{ $formatRupiah($target->target_amount) }}</div>
                                </div>
                                <div class="amount-item">
                                    <div class="amount-label">Sisa</div>
                                    <div class="amount-value">{{ $formatRupiah($target->remaining) }}</div>
                                </div>
                            </div>

                            <div class="target-deadline">
                                <span class="deadline-label">📅 Tenggat:</span>
                                <span class="deadline-value">{{ $target->target_date->format('d M Y') }} ({{ $target->days_remaining }} hari)</span>
                            </div>

                            <div class="target-performance">
                                <div class="performance-label">Status Setoran:</div>
                                <div class="performance-status {{ $target->performance['status'] }}">
                                    <span class="status-dot" style="background-color: currentColor;"></span>
                                    {{ $target->performance['message'] }}
                                </div>
                                <p style="margin: 8px 0 0; font-size: 0.75rem; color: rgba(248,250,252,0.5);">
                                    Target: {{ $formatRupiah($target->performance['recommended']) }}/bln | Realisasi: {{ $formatRupiah($target->performance['average']) }}/bln
                                </p>
                            </div>

                            <div class="target-actions">
                                <a href="{{ route('targets.show', $target->id) }}" class="btn-action">Lihat Detail</a>
                                <a href="{{ route('targets.edit', $target->id) }}" class="btn-action">Edit</a>
                                <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="flex: 1; margin: 0;" onsubmit="return confirm('Hapus target ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action" style="width: 100%; height: 100%;">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <h3>Belum Ada Target</h3>
                    <p>Mulai dengan membuat target finansial pertama Anda untuk merencanakan masa depan yang lebih baik.</p>
                    <a href="{{ route('targets.create') }}" class="btn-primary">Buat Target Pertama</a>
                </div>
            @endif
        </div>
    </main>
@endsection
