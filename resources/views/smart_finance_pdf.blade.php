<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Nexio</title>
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
            border-bottom: 2px solid #6366f1;
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
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            color: #fff;
        }
        .status-success { background-color: #6366f1; }
        .status-warning { background-color: #f59e0b; }
        .status-danger { background-color: #ef4444; }
        
        .recommendation {
            margin-bottom: 15px;
            padding: 10px;
            border-left: 3px solid #d1d5db;
        }
        .recommendation.ok { border-left-color: #10b981; }
        .recommendation.warning { border-left-color: #f59e0b; }
        .recommendation.critical { border-left-color: #ef4444; }
    </style>
</head>
<body>
    @php
        $formatRupiah = fn ($value) => 'Rp ' . number_format($value, 0, ',', '.');
    @endphp

    <div class="header">
        <h1>Laporan Nexio</h1>
        <p>Nama Pengguna: {{ $user->name ?? 'User' }}</p>
        <p>Periode Analisis: {{ $result['periode'] }}</p>
    </div>

    <div class="summary-box">
        <h3 style="margin-top: 0;">Ringkasan Keuangan</h3>
        <table style="margin-bottom: 0;">
            <tr>
                <td><strong>Status Kesehatan:</strong></td>
                <td>
                    <span class="status-badge status-{{ $result['status_class'] }}">
                        {{ $result['status'] }}
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Total Pemasukan:</strong></td>
                <td>{{ $formatRupiah($result['income']) }}</td>
            </tr>
            <tr>
                <td><strong>Total Pengeluaran:</strong></td>
                <td>{{ $formatRupiah($result['total_expenses']) }}</td>
            </tr>
            <tr>
                <td><strong>Arus Kas Bersih:</strong></td>
                <td style="color: {{ $result['net_cashflow'] < 0 ? '#ef4444' : '#10b981' }}">
                    <strong>{{ $formatRupiah($result['net_cashflow']) }}</strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Detail Pengeluaran</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="text-right">Nominal</th>
                <th class="text-right">% dari Pemasukan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result['expense_items'] as $item)
            <tr>
                <td>
                    {{ $item['name'] }}
                    @if(!empty($item['is_debt']))
                        <small style="color: #ef4444;">(Cicilan/Utang)</small>
                    @endif
                </td>
                <td class="text-right">{{ $formatRupiah($item['amount']) }}</td>
                <td class="text-right">
                    {{ $result['income'] > 0 ? number_format(($item['amount'] / $result['income']) * 100, 1) : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Rasio Keuangan</div>
    <table>
        <tr>
            <td>Rasio Pengeluaran (Max 70%)</td>
            <td class="text-right">{{ number_format($result['expense_ratio'], 1) }}%</td>
        </tr>
        <tr>
            <td>Rasio Cicilan/Utang (Max 30%)</td>
            <td class="text-right">{{ number_format($result['debt_ratio'], 1) }}%</td>
        </tr>
        <tr>
            <td>Rasio Tabungan/Investasi (Min 10%)</td>
            <td class="text-right">{{ number_format($result['saving_ratio'], 1) }}%</td>
        </tr>
        <tr>
            <td>Kecukupan Dana Darurat</td>
            <td class="text-right">{{ number_format($result['emergency_months'], 1) }} bulan</td>
        </tr>
    </table>

    <div class="section-title">Rekomendasi</div>
    @foreach ($recommendations as $rec)
        <div class="recommendation {{ $rec['status'] }}">
            <strong>{{ $rec['category_name'] }}</strong><br>
            {{ $rec['reason'] }}<br>
            <small>Aktual: {{ $formatRupiah($rec['actual_amount']) }} ({{ number_format($rec['actual_ratio'], 1) }}%) | Ideal: {{ $formatRupiah($rec['recommended_amount']) }} ({{ number_format($rec['ideal_ratio'], 1) }}%)</small>
        </div>
    @endforeach

    <div class="section-title">Catatan Sistem</div>
    <ul style="padding-left: 20px;">
        @foreach ($result['recommendations'] as $note)
            <li>{{ $note }}</li>
        @endforeach
    </ul>
    
    <div style="margin-top: 50px; text-align: center; color: #9ca3af; font-size: 12px;">
        Dicetak pada: {{ date('d-m-Y H:i') }} | Nexio Dashboard
    </div>
</body>
</html>
