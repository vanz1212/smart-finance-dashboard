@extends('layouts.app')

@section('title', 'Perpajakan - Smart Finance Dashboard')
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
    @endphp

    <style>
        .tax-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.76), rgba(5, 12, 15, 0.97)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .tax-inner { width: min(1180px, 100%); margin: 0 auto; }
        .tax-topbar, .tax-hero { display: flex; justify-content: space-between; gap: 18px; align-items: center; }
        .tax-topbar { margin-bottom: 34px; }
        .tax-nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .tax-nav a { padding: 10px 14px; border: 1px solid rgba(255,255,255,.12); border-radius: 999px; color: rgba(248,250,252,.78); text-decoration: none; font-weight: 800; background: rgba(255,255,255,.05); }
        .tax-nav a.is-active, .tax-nav a:hover { color: #052e2b; background: #f3c969; border-color: #f3c969; }
        .tax-hero { align-items: flex-end; margin-bottom: 28px; }
        .tax-kicker { color: #f3c969; font-size: .8rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .tax-hero h1 { margin: 12px 0 0; font-size: clamp(2.2rem, 5vw, 4.8rem); line-height: .98; letter-spacing: 0; }
        .tax-hero p { max-width: 720px; margin: 16px 0 0; color: rgba(248,250,252,.72); line-height: 1.7; }
        .tax-badge { min-width: 144px; padding: 12px 16px; border-radius: 999px; color: #052e2b; text-align: center; font-weight: 900; background: #f3c969; }
        .tax-badge.neutral { background: rgba(255,255,255,.16); color: #fff; }
        .tax-badge.success { background: #14b8a6; color: #042f2e; }
        .tax-badge.warning { background: #f3c969; color: #422006; }
        .tax-badge.danger { background: #fb7185; color: #4c0519; }

        .tax-grid { display: grid; grid-template-columns: minmax(320px,.92fr) minmax(380px,1.08fr); gap: 22px; align-items: start; }
        .tax-panel { border: 1px solid rgba(255,255,255,.14); border-radius: 14px; background: linear-gradient(180deg, rgba(13,47,51,.78), rgba(6,24,32,.84)); box-shadow: 0 28px 80px rgba(0,0,0,.34); backdrop-filter: blur(16px); }
        .tax-panel-inner { padding: 24px; }
        .tax-panel h2 { margin: 0; font-size: 1.18rem; }
        .tax-panel p { color: rgba(248,250,252,.66); line-height: 1.6; }
        .tax-form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 15px; }
        .tax-form label { display: grid; gap: 8px; }
        .tax-form label.full { grid-column: 1 / -1; }
        .tax-form span { color: rgba(248,250,252,.72); font-size: .84rem; font-weight: 800; }
        .tax-form input, .tax-form select { min-height: 46px; width: 100%; border: 1px solid rgba(255,255,255,.18); border-radius: 10px; padding: 10px 12px; background: rgba(255,255,255,.06); color: #fff; font: inherit; }
        .tax-form select option {
            background: #f8fafc;
            color: #0f172a;
        }

        .tax-form select option:checked,
        .tax-form select option:hover {
            background: #d1fae5;
            color: #052e2b;
        }
        .tax-form input:focus, .tax-form select:focus { outline: 3px solid rgba(20,184,166,.18); border-color: #14b8a6; }
        .tax-button { width: 100%; min-height: 50px; margin-top: 18px; border: 0; border-radius: 999px; background: #f3c969; color: #052e2b; cursor: pointer; font: inherit; font-weight: 900; }

        .tax-metrics { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 14px; }
        .tax-metric, .tax-note { padding: 16px; border: 1px solid rgba(255,255,255,.12); border-radius: 12px; background: rgba(255,255,255,.06); }
        .tax-metric span { display: block; margin-bottom: 8px; color: rgba(248,250,252,.62); font-size: .82rem; font-weight: 800; }
        .tax-metric strong { color: #fff; font-size: 1.08rem; }
        .tax-table { width: 100%; border-collapse: collapse; margin-top: 22px; overflow: hidden; border-radius: 12px; }
        .tax-table th, .tax-table td { padding: 12px; border-bottom: 1px solid rgba(255,255,255,.1); text-align: left; color: rgba(248,250,252,.78); }
        .tax-table th { color: #fff; background: rgba(255,255,255,.06); }
        .tax-table td:last-child, .tax-table th:last-child { text-align: right; }
        .tax-reference { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 22px; margin-top: 22px; }

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

            <section class="tax-hero module-hero">
                <div class="module-hero-panel module-hero-copy">
                    <span class="tax-kicker">Tax Estimator</span>
                    <h1>Perpajakan</h1>
                    <p>Estimasi PPh orang pribadi dengan PTKP, PKP, pembulatan ribuan, dan tarif progresif Indonesia.</p>
                    <a class="module-hero-action" href="{{ route('dashboard.user') }}">Kembali ke Selector</a>
                </div>
                <aside class="module-hero-panel module-hero-summary">
                    @if ($result)
                        <div class="tax-badge {{ $statusClass }}">{{ $result['status_pajak'] }}</div>
                        <strong>{{ $formatRupiah($result['estimasi_pajak']) }}</strong>
                        <span>Estimasi PPh tahunan berdasarkan penghasilan, pengurang, dan status PTKP.</span>
                    @else
                        <strong>5</strong>
                        <span>Lapisan tarif progresif PPh orang pribadi dengan perhitungan PTKP dan PKP.</span>
                    @endif
                </aside>
            </section>

            <section class="tax-grid">
                <form class="tax-panel tax-panel-inner" action="{{ route('perpajakan.calculate') }}" method="POST">
                    @csrf
                    <h2>Input Pajak</h2>
                    <p>Masukkan data bulanan untuk mendapatkan estimasi PPh tahunan.</p>
                    <div class="tax-form">
                        <label class="full"><span>Nama wajib pajak</span><input type="text" name="nama_wajib_pajak" value="{{ old('nama_wajib_pajak', $result['nama_wajib_pajak'] ?? '') }}" required></label>
                        <label><span>Penghasilan bulanan</span><input type="number" name="penghasilan_bulanan" value="{{ old('penghasilan_bulanan', $result['penghasilan_bulanan'] ?? '') }}" min="0" step="1000" required></label>
                        <label><span>Biaya/pengurang bulanan</span><input type="number" name="pengeluaran_bulanan" value="{{ old('pengeluaran_bulanan', $result['pengurang_bulanan'] ?? '') }}" min="0" step="1000" required></label>
                        <label class="full">
                            <span>Status wajib pajak</span>
                            @php($selectedStatus = old('status_wajib_pajak', $result['status_wajib_pajak'] ?? ''))
                            <select name="status_wajib_pajak" required>
                                <option value="" disabled {{ $selectedStatus ? '' : 'selected' }}>Pilih status</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>{{ $status }} - PTKP {{ $formatRupiah($ptkpTable[$status]) }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <button class="tax-button" type="submit">Hitung Pajak</button>
                </form>

                <div class="tax-panel tax-panel-inner">
                    <h2>Output Perhitungan</h2>
                    <p>{{ $result ? 'Ringkasan pajak untuk ' . $result['nama_wajib_pajak'] : 'Hasil estimasi akan tampil setelah form dihitung.' }}</p>
                    @if ($result)
                        <div class="tax-metrics">
                            <div class="tax-metric"><span>Penghasilan neto</span><strong>{{ $formatRupiah($result['penghasilan_neto']) }}</strong></div>
                            <div class="tax-metric"><span>PTKP {{ $result['status_wajib_pajak'] }}</span><strong>{{ $formatRupiah($result['ptkp']) }}</strong></div>
                            <div class="tax-metric"><span>PKP dibulatkan</span><strong>{{ $formatRupiah($result['pkp']) }}</strong></div>
                            <div class="tax-metric"><span>PPh tahunan</span><strong>{{ $formatRupiah($result['estimasi_pajak']) }}</strong></div>
                            <div class="tax-metric"><span>PPh bulanan</span><strong>{{ $formatRupiah($result['estimasi_pajak_bulanan']) }}</strong></div>
                            <div class="tax-metric"><span>Status</span><strong>{{ $result['status_pajak'] }}</strong></div>
                        </div>

                        <table class="tax-table">
                            <thead><tr><th>Lapisan PKP</th><th>Tarif</th><th>Pajak</th></tr></thead>
                            <tbody>
                                @forelse ($result['breakdown'] as $row)
                                    <tr><td>{{ $row['label'] }}</td><td>{{ $row['rate'] * 100 }}%</td><td>{{ $formatRupiah($row['tax']) }}</td></tr>
                                @empty
                                    <tr><td colspan="3">PKP nihil, tidak ada pajak terutang.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="tax-note">Isi form pajak untuk melihat PKP, PTKP, dan rincian tarif progresif.</div>
                    @endif
                </div>
            </section>

            <section class="tax-reference">
                <div class="tax-panel tax-panel-inner">
                    <h2>Referensi PTKP</h2>
                    <table class="tax-table">
                        <tbody>
                            @foreach ($ptkpTable as $status => $amount)
                                <tr><td>{{ $status }}</td><td>{{ $formatRupiah($amount) }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tax-panel tax-panel-inner">
                    <h2>Tarif Progresif</h2>
                    <table class="tax-table">
                        <tbody>
                            @foreach ($taxBrackets as $bracket)
                                <tr><td>{{ $bracket['label'] }}</td><td>{{ $bracket['rate'] * 100 }}%</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
@endsection
