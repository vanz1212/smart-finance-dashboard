<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Rule;
use App\Models\TaxAnalysis;
use Barryvdh\DomPDF\Facade\Pdf;

class TaxController extends BaseController
{
    private const PTKP = [
        'TK/0' => 54000000,
        'TK/1' => 58500000,
        'TK/2' => 63000000,
        'TK/3' => 67500000,
        'K/0' => 58500000,
        'K/1' => 63000000,
        'K/2' => 67500000,
        'K/3' => 72000000,
        'K/I/0' => 112500000,
        'K/I/1' => 117000000,
        'K/I/2' => 121500000,
        'K/I/3' => 126000000,
    ];

    private const PTKP_TER_CATEGORY = [
        'TK/0' => 'A', 'TK/1' => 'A', 'K/0' => 'A',
        'TK/2' => 'B', 'TK/3' => 'B', 'K/1' => 'B', 'K/2' => 'B',
        'K/3' => 'C',
        'K/I/0' => 'A', // Simplified for demo
        'K/I/1' => 'B',
        'K/I/2' => 'B',
        'K/I/3' => 'C',
    ];

    private const TAX_BRACKETS = [
        ['label' => 's.d. Rp60.000.000', 'upper_limit' => 60000000, 'rate' => 0.05],
        ['label' => '> Rp60.000.000 - Rp250.000.000', 'upper_limit' => 250000000, 'rate' => 0.15],
        ['label' => '> Rp250.000.000 - Rp500.000.000', 'upper_limit' => 500000000, 'rate' => 0.25],
        ['label' => '> Rp500.000.000 - Rp5.000.000.000', 'upper_limit' => 5000000000, 'rate' => 0.30],
        ['label' => '> Rp5.000.000.000', 'upper_limit' => null, 'rate' => 0.35],
    ];

    public function index(Request $request)
    {
        $userId = auth()->id();
        $history = TaxAnalysis::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $result = null;
        if ($request->has('load_id')) {
            $loaded = TaxAnalysis::where('user_id', $userId)->find($request->input('load_id'));
            if ($loaded) {
                $result = $loaded->hasil_json;
                $result['id'] = $loaded->id; // Untuk keperluan export PDF
            }
        } elseif ($history->count() > 0) {
            $latest = $history->first();
            $result = $latest->hasil_json;
            $result['id'] = $latest->id;
        }

        return view('perpajakan', [
            'result' => $result,
            'history' => $history,
            'statuses' => $this->taxStatuses(),
            'ptkpTable' => self::PTKP,
            'taxBrackets' => self::TAX_BRACKETS,
        ]);
    }

    public function calculate(Request $request)
    {
        $fieldsToNormalize = ['penghasilan_bulanan', 'penghasilan_tidak_teratur', 'iuran_pensiun', 'zakat', 'kredit_pajak'];
        foreach ($fieldsToNormalize as $field) {
            $request->merge([
                $field => $this->normalizeRupiah($request->input($field, 0)),
            ]);
        }

        $validated = $request->validate([
            'tahun_pajak' => ['required', 'integer'],
            'metode_perhitungan' => ['required', 'in:ter,tahunan'],
            'status_wajib_pajak' => ['required', Rule::in(array_keys(self::PTKP))],
            'penghasilan_bulanan' => ['required', 'numeric', 'min:0'],
            'penghasilan_tidak_teratur' => ['required', 'numeric', 'min:0'],
            'iuran_pensiun' => ['required', 'numeric', 'min:0'],
            'zakat' => ['required', 'numeric', 'min:0'],
            'kredit_pajak' => ['required', 'numeric', 'min:0'],
        ]);

        $metode = $validated['metode_perhitungan'];
        $tahun = $validated['tahun_pajak'];
        $status = $validated['status_wajib_pajak'];
        $penghasilanBulanan = (float) $validated['penghasilan_bulanan'];
        $penghasilanTidakTeratur = (float) $validated['penghasilan_tidak_teratur']; // THR/Bonus
        $iuranPensiun = (float) $validated['iuran_pensiun'];
        $zakat = (float) $validated['zakat'];
        $kreditPajak = (float) $validated['kredit_pajak'];

        // Biaya Jabatan 5% dari Bruto, Max 500rb/bulan atau 6jt/tahun
        $biayaJabatanBulanan = min($penghasilanBulanan * 0.05, 500000);
        
        $penghasilanTahunan = $penghasilanBulanan * 12;
        $biayaJabatanTahunan = min(($penghasilanTahunan + $penghasilanTidakTeratur) * 0.05, 6000000);
        $pengurangTahunan = $biayaJabatanTahunan + ($iuranPensiun * 12) + $zakat; // Zakat dihitung tahunan di sini sbg contoh
        
        $penghasilanNeto = max(($penghasilanTahunan + $penghasilanTidakTeratur) - $pengurangTahunan, 0);
        $ptkp = self::PTKP[$status];
        $pkpSebelumPembulatan = max($penghasilanNeto - $ptkp, 0);
        $pkp = floor($pkpSebelumPembulatan / 1000) * 1000;
        
        // PPh Tahunan
        $calculationTahunan = $this->calculateProgressiveTax($pkp);
        $estimasiPajakTahunan = $calculationTahunan['total_tax'];
        
        // PPh Kurang/Lebih Bayar
        $pajakTerutang = max($estimasiPajakTahunan - $kreditPajak, 0);

        // Jika metode TER (Bulanan)
        $terCategory = self::PTKP_TER_CATEGORY[$status];
        $terRate = $this->getTerRate($terCategory, $penghasilanBulanan); // Simplification of lookup table
        $estimasiPajakBulananTER = $penghasilanBulanan * $terRate;

        // Tentukan output berdasarkan metode
        if ($metode === 'ter' && $tahun >= 2024) {
            $estimasiPajakBulanan = $estimasiPajakBulananTER;
            $catatan = __('tax.ter_note', [
                'category' => $terCategory,
                'rate' => number_format($terRate * 100, 2, ',', '.'),
            ]);
        } else {
            $estimasiPajakBulanan = $estimasiPajakTahunan / 12;
            $catatan = __('tax.annual_note');
        }

        $hasilJson = [
            'tahun_pajak' => $tahun,
            'metode' => $metode,
            'status_wajib_pajak' => $status,
            'penghasilan_bulanan' => $penghasilanBulanan,
            'penghasilan_tidak_teratur' => $penghasilanTidakTeratur,
            'biaya_jabatan_bulanan' => $biayaJabatanBulanan,
            'iuran_pensiun' => $iuranPensiun,
            'zakat' => $zakat,
            'kredit_pajak' => $kreditPajak,
            'penghasilan_tahunan' => $penghasilanTahunan,
            'pengurang_tahunan' => $pengurangTahunan,
            'penghasilan_neto' => $penghasilanNeto,
            'ptkp' => $ptkp,
            'pkp' => $pkp,
            'estimasi_pajak_tahunan' => $estimasiPajakTahunan,
            'estimasi_pajak_bulanan' => $estimasiPajakBulanan,
            'pajak_kurang_bayar' => $pajakTerutang,
            'status_pajak' => $this->taxLevel($estimasiPajakTahunan),
            'catatan' => $catatan,
            'breakdown' => $calculationTahunan['breakdown'],
            'ter_category' => $terCategory,
            'ter_rate' => $terRate * 100,
        ];

        $userId = auth()->id();
        $analysis = TaxAnalysis::create([
            'user_id' => $userId,
            'tahun_pajak' => $tahun,
            'penghasilan_bulanan' => $penghasilanBulanan,
            'penghasilan_tidak_teratur' => $penghasilanTidakTeratur,
            'biaya_jabatan' => $biayaJabatanBulanan,
            'iuran_pensiun' => $iuranPensiun,
            'zakat' => $zakat,
            'kredit_pajak' => $kreditPajak,
            'status_wajib_pajak' => $status,
            'metode_perhitungan' => $metode,
            'estimasi_pajak' => $estimasiPajakTahunan,
            'hasil_json' => $hasilJson,
        ]);

        return redirect()->route('perpajakan.index')->with('success', __('tax.saved_success'));
    }

    public function destroy($id)
    {
        $userId = auth()->id();
        $analysis = TaxAnalysis::where('user_id', $userId)->findOrFail($id);
        $analysis->delete();

        return redirect()->route('perpajakan.index')->with('success', __('tax.deleted_success'));
    }

    public function exportPdf($id)
    {
        $userId = auth()->id();
        $analysis = TaxAnalysis::where('user_id', $userId)->findOrFail($id);
        
        $result = $analysis->hasil_json;

        $pdf = Pdf::loadView('tax_pdf', [
            'result' => $result,
            'user' => auth()->user()
        ]);

        return $pdf->download('Kalkulasi_Pajak_' . $result['tahun_pajak'] . '.pdf');
    }

    private function getTerRate($category, $income)
    {
        // Simplification of TER table for demonstration purposes
        if ($income <= 5400000) return 0.0;
        if ($income <= 5650000) return 0.0025; // 0.25%
        if ($income <= 5950000) return 0.005;  // 0.5%
        if ($income <= 6300000) return 0.0075;
        if ($income <= 6750000) return 0.01;
        if ($income <= 7500000) return 0.0125;
        if ($income <= 8550000) return 0.015;
        if ($income <= 9650000) return 0.0175;
        if ($income <= 10050000) return 0.02;
        if ($income <= 10350000) return 0.0225;
        if ($income <= 10700000) return 0.025;
        if ($income <= 11050000) return 0.03;
        if ($income <= 11600000) return 0.035;
        if ($income <= 12500000) return 0.04;
        if ($income <= 13750000) return 0.05;
        if ($income <= 15100000) return 0.06;
        if ($income <= 16950000) return 0.07;
        if ($income <= 19750000) return 0.08;
        if ($income <= 24150000) return 0.09;
        return 0.1; // Max simplified TER rate fallback
    }

    private function taxStatuses(): array
    {
        return array_keys(self::PTKP);
    }

    private function normalizeRupiah($value): string
    {
        return preg_replace('/[^0-9]/', '', (string) $value) ?: '0';
    }

    private function calculateProgressiveTax(float $pkp): array
    {
        $remaining = $pkp;
        $previousLimit = 0;
        $totalTax = 0;
        $breakdown = [];

        foreach (self::TAX_BRACKETS as $bracket) {
            if ($remaining <= 0) {
                break;
            }

            $upperLimit = $bracket['upper_limit'];
            $layerAmount = $upperLimit === null
                ? $remaining
                : min($remaining, $upperLimit - $previousLimit);
            $layerTax = $layerAmount * $bracket['rate'];

            $breakdown[] = [
                'label' => $bracket['label'],
                'rate' => $bracket['rate'],
                'taxable_amount' => $layerAmount,
                'tax' => $layerTax,
            ];

            $totalTax += $layerTax;
            $remaining -= $layerAmount;
            $previousLimit = $upperLimit ?? $previousLimit;
        }

        return [
            'total_tax' => $totalTax,
            'breakdown' => $breakdown,
        ];
    }

    private function taxLevel(float $tax): string
    {
        if ($tax <= 0) return 'Tidak kena pajak';
        if ($tax < 1000000) return 'Pajak rendah';
        if ($tax <= 5000000) return 'Pajak normal';
        return 'Pajak tinggi';
    }
}
