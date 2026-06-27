<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Estimasi Pajak</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #14b8a6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #052e2b;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .section-title {
            background-color: #f3f4f6;
            padding: 10px;
            margin: 20px 0;
            font-weight: bold;
            border-left: 4px solid #f3c969;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background-color: #f9fafb;
            color: #4b5563;
        }
        .text-right {
            text-align: right;
        }
        .summary-box {
            background-color: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
    @endphp

    <div class="header">
        <h1>Laporan Estimasi PPh 21</h1>
        <p>Nama Pengguna: {{ $user->name ?? 'User' }}</p>
        <p>Tahun Pajak: {{ $result['tahun_pajak'] }}</p>
    </div>

    <div style="background-color: #fef3c7; border: 1px solid #fde68a; padding: 10px; border-radius: 5px; margin-bottom: 20px; font-size: 0.8rem; color: #b45309;">
        <strong>Disclaimer:</strong> Hasil kalkulasi ini merupakan estimasi dan simulasi pribadi. Harap tetap mengacu pada DJP Online untuk pelaporan pajak resmi.
    </div>

    <div class="summary-box">
        <h3 style="margin-top: 0;">Ringkasan Data Wajib Pajak</h3>
        <table style="margin-bottom: 0;">
            <tr>
                <td width="50%"><strong>Status PTKP:</strong></td>
                <td>{{ $result['status_wajib_pajak'] }} ({{ $formatRupiah($result['ptkp']) }})</td>
            </tr>
            <tr>
                <td><strong>Metode Perhitungan:</strong></td>
                <td>{{ strtoupper($result['metode']) }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Penghasilan & Pengurang (Tahunan)</div>
    <table>
        <tr>
            <td>Penghasilan Bruto (Gaji + THR/Bonus)</td>
            <td class="text-right">{{ $formatRupiah($result['penghasilan_tahunan'] + $result['penghasilan_tidak_teratur']) }}</td>
        </tr>
        <tr>
            <td>Biaya Jabatan (Tahunan)</td>
            <td class="text-right">{{ $formatRupiah($result['biaya_jabatan_bulanan'] * 12) }}</td>
        </tr>
        <tr>
            <td>Iuran Pensiun / BPJS (Tahunan)</td>
            <td class="text-right">{{ $formatRupiah($result['iuran_pensiun'] * 12) }}</td>
        </tr>
        <tr>
            <td>Zakat (Resmi)</td>
            <td class="text-right">{{ $formatRupiah($result['zakat']) }}</td>
        </tr>
        <tr style="background-color: #f3f4f6; font-weight: bold;">
            <td>Penghasilan Neto</td>
            <td class="text-right">{{ $formatRupiah($result['penghasilan_neto']) }}</td>
        </tr>
        <tr>
            <td>Penghasilan Tidak Kena Pajak (PTKP)</td>
            <td class="text-right">({{ $formatRupiah($result['ptkp']) }})</td>
        </tr>
        <tr style="background-color: #f3f4f6; font-weight: bold;">
            <td>Penghasilan Kena Pajak (PKP Dibulatkan)</td>
            <td class="text-right">{{ $formatRupiah($result['pkp']) }}</td>
        </tr>
    </table>

    <div class="section-title">Rincian Perhitungan Pajak</div>
    
    <div style="margin-bottom: 15px;">
        <strong>Kalkulasi Bulanan:</strong><br>
        {{ $result['catatan'] }}<br>
        Estimasi PPh Bulanan: <strong>{{ $formatRupiah($result['estimasi_pajak_bulanan']) }}</strong>
    </div>

    <table style="margin-top: 15px;">
        <thead>
            <tr>
                <th>Lapisan PKP (Progresif Pasal 17)</th>
                <th class="text-right">Tarif</th>
                <th class="text-right">Pajak</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result['breakdown'] as $row)
            <tr>
                <td>{{ $row['label'] }}</td>
                <td class="text-right">{{ $row['rate'] * 100 }}%</td>
                <td class="text-right">{{ $formatRupiah($row['tax']) }}</td>
            </tr>
            @endforeach
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="2" class="text-right">Total PPh Terutang Setahun</td>
                <td class="text-right">{{ $formatRupiah($result['estimasi_pajak_tahunan']) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">Kredit Pajak (Telah Dipotong)</td>
                <td class="text-right">({{ $formatRupiah($result['kredit_pajak']) }})</td>
            </tr>
            <tr style="background-color: #fee2e2; font-weight: bold; color: #b91c1c;">
                <td colspan="2" class="text-right">Pajak Kurang (Lebih) Bayar</td>
                <td class="text-right">{{ $formatRupiah($result['pajak_kurang_bayar']) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: center; color: #9ca3af; font-size: 12px;">
        Dicetak pada: {{ date('d-m-Y H:i') }} | Smart Finance Dashboard
    </div>
</body>
</html>
