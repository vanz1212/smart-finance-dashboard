@extends('layouts.app')

@section('title', 'Analisa Keuangan - Smart Finance Dashboard')

@section('content')
    @php
        $formatRupiah = function ($value) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        };

        $formatPercent = function ($value) {
            return number_format($value, 1, ',', '.') . '%';
        };
    @endphp

    <section class="page-section finance-page">
        <div class="section-header finance-heading">
            <div>
                <span class="eyebrow">Smart Finance Dashboard</span>
                <h1>Analisa Keuangan</h1>
                <p>Hitung arus kas, rasio pengeluaran, rasio tabungan, rasio cicilan, dan kesiapan dana darurat dalam satu tampilan.</p>
            </div>

            @if ($result)
                <div class="status-pill status-{{ $result['status_class'] }}">
                    {{ $result['status'] }}
                </div>
            @endif
        </div>

        <div class="finance-layout">
            <form class="finance-panel finance-form" action="{{ route('finance.analyze') }}" method="POST">
                @csrf

                <div class="panel-title">
                    <h2>Input Bulanan</h2>
                    <p>Gunakan angka rata-rata bulanan agar rasio lebih mudah dibaca.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-box">
                        Periksa kembali input analisa keuangan.
                    </div>
                @endif

                <div class="form-grid">
                    <label>
                        <span>Periode</span>
                        <input type="text" name="periode" value="{{ old('periode', $result['periode'] ?? date('F Y')) }}" placeholder="Contoh: Mei 2026" required>
                    </label>

                    <label>
                        <span>Total pemasukan</span>
                        <input type="number" name="pemasukan" value="{{ old('pemasukan', $result['income'] ?? '') }}" min="0" step="1000" placeholder="15000000" required>
                    </label>

                    <label>
                        <span>Kebutuhan pokok</span>
                        <input type="number" name="kebutuhan_pokok" value="{{ old('kebutuhan_pokok', $result['expenses']['Kebutuhan pokok'] ?? '') }}" min="0" step="1000" placeholder="4500000" required>
                    </label>

                    <label>
                        <span>Transportasi</span>
                        <input type="number" name="transportasi" value="{{ old('transportasi', $result['expenses']['Transportasi'] ?? '') }}" min="0" step="1000" placeholder="1000000" required>
                    </label>

                    <label>
                        <span>Cicilan/utang</span>
                        <input type="number" name="cicilan" value="{{ old('cicilan', $result['expenses']['Cicilan/utang'] ?? '') }}" min="0" step="1000" placeholder="2500000" required>
                    </label>

                    <label>
                        <span>Gaya hidup</span>
                        <input type="number" name="gaya_hidup" value="{{ old('gaya_hidup', $result['expenses']['Gaya hidup'] ?? '') }}" min="0" step="1000" placeholder="1500000" required>
                    </label>

                    <label>
                        <span>Tabungan</span>
                        <input type="number" name="tabungan" value="{{ old('tabungan', $result['saving'] ?? '') }}" min="0" step="1000" placeholder="2000000" required>
                    </label>

                    <label>
                        <span>Investasi</span>
                        <input type="number" name="investasi" value="{{ old('investasi', $result['investment'] ?? '') }}" min="0" step="1000" placeholder="1000000" required>
                    </label>

                    <label>
                        <span>Dana darurat saat ini</span>
                        <input type="number" name="dana_darurat" value="{{ old('dana_darurat', $result['emergency_fund'] ?? '') }}" min="0" step="1000" placeholder="25000000" required>
                    </label>

                    <label>
                        <span>Target tabungan</span>
                        <input type="number" name="target_tabungan" value="{{ old('target_tabungan', $result['target_saving'] ?? '') }}" min="0" step="1000" placeholder="50000000">
                    </label>
                </div>

                <button type="submit" class="primary-action">Hitung Analisa</button>
            </form>

            <div class="finance-panel finance-output">
                <div class="panel-title">
                    <h2>Ringkasan Hasil</h2>
                    <p>{{ $result ? 'Periode ' . $result['periode'] : 'Hasil analisa akan muncul setelah form dihitung.' }}</p>
                </div>

                @if ($result)
                    <div class="metric-grid">
                        <div class="metric-card">
                            <span>Pemasukan</span>
                            <strong>{{ $formatRupiah($result['income']) }}</strong>
                        </div>
                        <div class="metric-card">
                            <span>Total pengeluaran</span>
                            <strong>{{ $formatRupiah($result['total_expenses']) }}</strong>
                        </div>
                        <div class="metric-card {{ $result['net_cashflow'] < 0 ? 'metric-danger' : 'metric-success' }}">
                            <span>Arus kas bersih</span>
                            <strong>{{ $formatRupiah($result['net_cashflow']) }}</strong>
                        </div>
                        <div class="metric-card">
                            <span>Dana darurat</span>
                            <strong>{{ number_format($result['emergency_months'], 1, ',', '.') }} bulan</strong>
                        </div>
                    </div>

                    <div class="ratio-list">
                        <div>
                            <div class="ratio-row">
                                <span>Rasio pengeluaran</span>
                                <strong>{{ $formatPercent($result['expense_ratio']) }}</strong>
                            </div>
                            <div class="progress-track">
                                <span style="width: {{ min($result['expense_ratio'], 100) }}%"></span>
                            </div>
                        </div>
                        <div>
                            <div class="ratio-row">
                                <span>Rasio tabungan + investasi</span>
                                <strong>{{ $formatPercent($result['saving_ratio']) }}</strong>
                            </div>
                            <div class="progress-track progress-good">
                                <span style="width: {{ min($result['saving_ratio'], 100) }}%"></span>
                            </div>
                        </div>
                        <div>
                            <div class="ratio-row">
                                <span>Rasio cicilan</span>
                                <strong>{{ $formatPercent($result['debt_ratio']) }}</strong>
                            </div>
                            <div class="progress-track progress-debt">
                                <span style="width: {{ min($result['debt_ratio'], 100) }}%"></span>
                            </div>
                        </div>
                    </div>

                    <div class="recommendation-box">
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
                        <p>Isi form bulanan di sebelah kiri untuk melihat status keuangan dan rekomendasi otomatis.</p>
                    </div>
                @endif
            </div>
        </div>

        @if ($result)
            <div class="finance-panel finance-breakdown">
                <div class="panel-title">
                    <h2>Breakdown Anggaran</h2>
                    <p>Komposisi pengeluaran dibandingkan dengan pemasukan bulanan.</p>
                </div>

                <div class="breakdown-layout">
                    <div class="breakdown-table">
                        @foreach ($result['expenses'] as $category => $amount)
                            <div class="breakdown-row">
                                <span>{{ $category }}</span>
                                <strong>{{ $formatRupiah($amount) }}</strong>
                                <em>{{ $formatPercent($result['income'] > 0 ? ($amount / $result['income']) * 100 : 0) }}</em>
                            </div>
                        @endforeach
                        <div class="breakdown-row highlight">
                            <span>Tabungan + investasi</span>
                            <strong>{{ $formatRupiah($result['total_saving_investment']) }}</strong>
                            <em>{{ $formatPercent($result['saving_ratio']) }}</em>
                        </div>
                    </div>

                    <div class="goal-card">
                        <span>Estimasi target tabungan</span>
                        @if ($result['months_to_target'] !== null)
                            <strong>{{ $result['months_to_target'] }} bulan</strong>
                            <p>Dengan tabungan bulanan saat ini dan target {{ $formatRupiah($result['target_saving']) }}.</p>
                        @else
                            <strong>Belum tersedia</strong>
                            <p>Isi target tabungan dan nilai tabungan bulanan untuk menghitung estimasi waktu.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection
