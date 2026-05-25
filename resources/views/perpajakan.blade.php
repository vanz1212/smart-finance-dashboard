@extends('layouts.app')

@section('title', 'Perpajakan - Smart Finance Dashboard')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @php
        $formatRupiah = function ($value) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        };

        $statusClass = 'bg-success';
        if (($result['status_pajak'] ?? '') === 'Tidak kena pajak') {
            $statusClass = 'bg-secondary';
        } elseif (($result['status_pajak'] ?? '') === 'Pajak normal') {
            $statusClass = 'bg-primary';
        } elseif (($result['status_pajak'] ?? '') === 'Pajak tinggi') {
            $statusClass = 'bg-danger';
        }
    @endphp

    <section class="py-4" style="background: #eef5ff; min-height: 72vh;">
        <div class="container-fluid px-0">
            <div class="mb-4">
                <span class="badge text-bg-primary mb-2">Smart Finance Dashboard</span>
                <h1 class="h3 fw-bold text-primary mb-1">Perpajakan Orang Pribadi</h1>
                <p class="text-secondary mb-0">Estimasi PPh tahunan memakai PTKP, PKP, pembulatan ribuan, dan tarif progresif Indonesia.</p>
            </div>

            <div class="row g-4">
                <div class="col-12 col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h2 class="h5 mb-0">Form Input Pajak</h2>
                        </div>
                        <div class="card-body p-4">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Periksa kembali input pajak.</strong>
                                </div>
                            @endif

                            <form action="{{ route('perpajakan.calculate') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="nama_wajib_pajak" class="form-label fw-semibold">Nama wajib pajak</label>
                                    <input
                                        type="text"
                                        class="form-control @error('nama_wajib_pajak') is-invalid @enderror"
                                        id="nama_wajib_pajak"
                                        name="nama_wajib_pajak"
                                        value="{{ old('nama_wajib_pajak', $result['nama_wajib_pajak'] ?? '') }}"
                                        placeholder="Masukkan nama wajib pajak"
                                        required
                                    >
                                    @error('nama_wajib_pajak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="penghasilan_bulanan" class="form-label fw-semibold">Penghasilan bulanan</label>
                                    <input
                                        type="number"
                                        class="form-control @error('penghasilan_bulanan') is-invalid @enderror"
                                        id="penghasilan_bulanan"
                                        name="penghasilan_bulanan"
                                        value="{{ old('penghasilan_bulanan') }}"
                                        min="0"
                                        step="1000"
                                        placeholder="Contoh: 7500000"
                                        required
                                    >
                                    @error('penghasilan_bulanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="pengeluaran_bulanan" class="form-label fw-semibold">Biaya/pengurang bulanan</label>
                                    <input
                                        type="number"
                                        class="form-control @error('pengeluaran_bulanan') is-invalid @enderror"
                                        id="pengeluaran_bulanan"
                                        name="pengeluaran_bulanan"
                                        value="{{ old('pengeluaran_bulanan', $result['pengurang_bulanan'] ?? '') }}"
                                        min="0"
                                        step="1000"
                                        placeholder="Contoh: 3000000"
                                        required
                                    >
                                    <div class="form-text">Isi dengan pengurang yang diasumsikan boleh mengurangi penghasilan bruto.</div>
                                    @error('pengeluaran_bulanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="status_wajib_pajak" class="form-label fw-semibold">Status wajib pajak</label>
                                    <select
                                        class="form-select @error('status_wajib_pajak') is-invalid @enderror"
                                        id="status_wajib_pajak"
                                        name="status_wajib_pajak"
                                        required
                                    >
                                        @php
                                            $selectedStatus = old('status_wajib_pajak', $result['status_wajib_pajak'] ?? '');
                                        @endphp
                                        <option value="" disabled {{ $selectedStatus ? '' : 'selected' }}>Pilih status</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>
                                                {{ $status }} - PTKP {{ $formatRupiah($ptkpTable[$status]) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_wajib_pajak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                    Hitung Pajak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-7">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h2 class="h5 fw-bold text-primary mb-1">Output Perhitungan PPh</h2>
                            <p class="text-secondary mb-0">Hasil estimasi akan tampil setelah form dihitung.</p>
                        </div>
                        <div class="card-body p-4">
                            @if ($result)
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4 p-3 rounded bg-primary-subtle">
                                    <div>
                                        <div class="text-secondary small">Nama wajib pajak</div>
                                        <div class="h5 mb-0 text-primary fw-bold">{{ $result['nama_wajib_pajak'] }}</div>
                                    </div>
                                    <div class="text-md-end">
                                        <div class="text-secondary small">Status pajak</div>
                                        <span class="badge {{ $statusClass }} px-3 py-2">{{ $result['status_pajak'] }}</span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-white">
                                            <div class="text-secondary small">Penghasilan tahunan</div>
                                            <div class="fw-bold text-dark">{{ $formatRupiah($result['penghasilan_tahunan']) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-white">
                                            <div class="text-secondary small">Biaya/pengurang tahunan</div>
                                            <div class="fw-bold text-dark">{{ $formatRupiah($result['pengurang_tahunan']) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-white">
                                            <div class="text-secondary small">Penghasilan neto</div>
                                            <div class="fw-bold text-dark">{{ $formatRupiah($result['penghasilan_neto']) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-white">
                                            <div class="text-secondary small">PTKP {{ $result['status_wajib_pajak'] }}</div>
                                            <div class="fw-bold text-dark">{{ $formatRupiah($result['ptkp']) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-white">
                                            <div class="text-secondary small">PKP setelah pembulatan</div>
                                            <div class="fw-bold text-dark">{{ $formatRupiah($result['pkp']) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-primary text-white">
                                            <div class="small opacity-75">Estimasi PPh tahunan</div>
                                            <div class="fw-bold">{{ $formatRupiah($result['estimasi_pajak']) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="border rounded p-3 bg-success text-white">
                                            <div class="small opacity-75">Estimasi PPh bulanan</div>
                                            <div class="fw-bold">{{ $formatRupiah($result['estimasi_pajak_bulanan']) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-4">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Lapisan PKP</th>
                                                <th class="text-end">Tarif</th>
                                                <th class="text-end">PKP lapisan</th>
                                                <th class="text-end">Pajak</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($result['breakdown'] as $row)
                                                <tr>
                                                    <td>{{ $row['label'] }}</td>
                                                    <td class="text-end">{{ $row['rate'] * 100 }}%</td>
                                                    <td class="text-end">{{ $formatRupiah($row['taxable_amount']) }}</td>
                                                    <td class="text-end fw-semibold">{{ $formatRupiah($row['tax']) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-secondary">PKP nihil, tidak ada pajak terutang.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-center bg-light rounded p-5 h-100">
                                    <div>
                                        <div class="h5 text-primary fw-bold mb-2">Belum ada hasil perhitungan</div>
                                        <p class="text-secondary mb-0">Isi form pajak, lalu klik tombol Hitung Pajak.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h2 class="h6 fw-bold mb-0 text-primary">PTKP</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        @foreach ($ptkpTable as $status => $amount)
                                            <tr>
                                                <td class="fw-semibold">{{ $status }}</td>
                                                <td class="text-end">{{ $formatRupiah($amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h2 class="h6 fw-bold mb-0 text-primary">Tarif Progresif PPh OP</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        @foreach ($taxBrackets as $bracket)
                                            <tr>
                                                <td>{{ $bracket['label'] }}</td>
                                                <td class="text-end fw-semibold">{{ $bracket['rate'] * 100 }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
