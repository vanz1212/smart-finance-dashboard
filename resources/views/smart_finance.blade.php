@extends('layouts.app')

@section('title', 'Analisa Keuangan - Smart Finance Dashboard')

@section('content')
    @php
        $formatRupiah = function ($value) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        };

        $formatInputRupiah = function ($value) use ($formatRupiah) {
            if ($value === null || $value === '') {
                return '';
            }

            return $formatRupiah(preg_replace('/[^0-9]/', '', (string) $value));
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
                        <input type="text" name="pemasukan" value="{{ $formatInputRupiah(old('pemasukan', $result['income'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 15.000.000" required>
                    </label>

                    <label>
                        <span>Kebutuhan pokok</span>
                        <input type="text" name="kebutuhan_pokok" value="{{ $formatInputRupiah(old('kebutuhan_pokok', $result['expenses']['Kebutuhan pokok'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 4.500.000" required>
                    </label>

                    <label>
                        <span>Transportasi</span>
                        <input type="text" name="transportasi" value="{{ $formatInputRupiah(old('transportasi', $result['expenses']['Transportasi'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 1.000.000" required>
                    </label>

                    <label>
                        <span>Cicilan/utang</span>
                        <input type="text" name="cicilan" value="{{ $formatInputRupiah(old('cicilan', $result['expenses']['Cicilan/utang'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 2.500.000" required>
                    </label>

                    <label>
                        <span>Gaya hidup</span>
                        <input type="text" name="gaya_hidup" value="{{ $formatInputRupiah(old('gaya_hidup', $result['expenses']['Gaya hidup'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 1.500.000" required>
                    </label>

                    <label>
                        <span>Tabungan</span>
                        <input type="text" name="tabungan" value="{{ $formatInputRupiah(old('tabungan', $result['saving'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 2.000.000" required>
                    </label>

                    <label>
                        <span>Investasi</span>
                        <input type="text" name="investasi" value="{{ $formatInputRupiah(old('investasi', $result['investment'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 1.000.000" required>
                    </label>

                    <label>
                        <span>Dana darurat saat ini</span>
                        <input type="text" name="dana_darurat" value="{{ $formatInputRupiah(old('dana_darurat', $result['emergency_fund'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 25.000.000" required>
                    </label>

                    <label>
                        <span>Target tabungan</span>
                        <input type="text" name="target_tabungan" value="{{ $formatInputRupiah(old('target_tabungan', $result['target_saving'] ?? '')) }}" inputmode="numeric" autocomplete="off" data-rupiah-input placeholder="Rp 50.000.000">
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

    <script>
        document.querySelectorAll('[data-rupiah-input]').forEach((input) => {
            const formatRupiah = (value) => {
                const digits = value.replace(/\D/g, '');

                if (!digits) {
                    return '';
                }

                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(digits));
            };

            input.value = formatRupiah(input.value);

            input.addEventListener('input', () => {
                input.value = formatRupiah(input.value);
                input.setSelectionRange(input.value.length, input.value.length);
            });
        });
    </script>
@endsection
