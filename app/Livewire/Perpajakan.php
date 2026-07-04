<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\TaxAnalysis;
use Illuminate\Support\Facades\Auth;

class Perpajakan extends Component
{
    public $tahun_pajak;
    public $metode_perhitungan = 'ter';
    public $status_wajib_pajak = 'TK/0';
    public $penghasilan_bulanan = 0;
    public $penghasilan_tidak_teratur = 0;
    public $iuran_pensiun = 0;
    public $zakat = 0;
    public $kredit_pajak = 0;
    
    public $result = null;
    public $history = [];
    public $statuses = [];
    public $ptkpTable = [];
    public $taxBrackets = [];

    // Constraints from controller
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
        'K/I/0' => 'A',
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

    public function mount()
    {
        $this->tahun_pajak = date('Y');
        $this->statuses = $this->taxStatuses();
        $this->ptkpTable = self::PTKP;
        $this->taxBrackets = self::TAX_BRACKETS;
        $this->loadData();
    }

    public function loadData($load_id = null)
    {
        $userId = Auth::id();
        
        $this->history = TaxAnalysis::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($load_id) {
            $loaded = TaxAnalysis::where('user_id', $userId)->find($load_id);
            if ($loaded) {
                $this->result = $loaded->hasil_json;
                $this->result['id'] = $loaded->id;
                $this->fillForm($loaded->hasil_json);
            }
        } elseif ($this->history->count() > 0) {
            $latest = $this->history->first();
            $this->result = $latest->hasil_json;
            $this->result['id'] = $latest->id;
            $this->fillForm($latest->hasil_json);
        }
    }

    private function fillForm($result)
    {
        $this->tahun_pajak = $result['tahun_pajak'] ?? date('Y');
        $this->metode_perhitungan = $result['metode_perhitungan'] ?? 'ter';
        $this->status_wajib_pajak = $result['status_wajib_pajak'] ?? 'TK/0';
        $this->penghasilan_bulanan = $result['input']['penghasilan_bulanan'] ?? 0;
        $this->penghasilan_tidak_teratur = $result['input']['penghasilan_tidak_teratur'] ?? 0;
        $this->iuran_pensiun = $result['input']['iuran_pensiun'] ?? 0;
        $this->zakat = $result['input']['zakat'] ?? 0;
        $this->kredit_pajak = $result['input']['kredit_pajak'] ?? 0;
    }

    public function normalize($value)
    {
        if (is_string($value)) {
            return (float) preg_replace('/[^0-9]/', '', $value);
        }
        return (float) $value;
    }

    public function calculate()
    {
        $this->penghasilan_bulanan = $this->normalize($this->penghasilan_bulanan);
        $this->penghasilan_tidak_teratur = $this->normalize($this->penghasilan_tidak_teratur);
        $this->iuran_pensiun = $this->normalize($this->iuran_pensiun);
        $this->zakat = $this->normalize($this->zakat);
        $this->kredit_pajak = $this->normalize($this->kredit_pajak);

        $metode = $this->metode_perhitungan;
        $tahun = $this->tahun_pajak;
        $status = $this->status_wajib_pajak;
        $penghasilanBulanan = $this->penghasilan_bulanan;
        $penghasilanTidakTeratur = $this->penghasilan_tidak_teratur;
        $iuranPensiun = $this->iuran_pensiun;
        $zakat = $this->zakat;
        $kreditPajak = $this->kredit_pajak;
        
        $ptkp_tahunan = self::PTKP[$status] ?? self::PTKP['TK/0'];
        $hasil = [];

        $biayaJabatanBulanan = min($penghasilanBulanan * 0.05, 500000);
        
        $penghasilanTahunan = $penghasilanBulanan * 12;
        $biayaJabatanTahunan = min(($penghasilanTahunan + $penghasilanTidakTeratur) * 0.05, 6000000);
        $pengurangTahunan = $biayaJabatanTahunan + ($iuranPensiun * 12) + $zakat; // Zakat dihitung tahunan di sini sbg contoh
        
        $penghasilanNeto = max(($penghasilanTahunan + $penghasilanTidakTeratur) - $pengurangTahunan, 0);
        $ptkp = $ptkp_tahunan;
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

        $hasil = [
            'biaya_jabatan_bulanan' => $biayaJabatanBulanan,
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

        $hasil['tahun_pajak'] = $tahun;
        $hasil['metode_perhitungan'] = $metode;
        $hasil['status_wajib_pajak'] = $status;
        $hasil['input'] = [
            'penghasilan_bulanan' => $penghasilanBulanan,
            'penghasilan_tidak_teratur' => $penghasilanTidakTeratur,
            'iuran_pensiun' => $iuranPensiun,
            'zakat' => $zakat,
            'kredit_pajak' => $kreditPajak,
        ];

        $analysis = TaxAnalysis::create([
            'user_id' => Auth::id(),
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
            'hasil_json' => $hasil,
        ]);

        $this->loadData();
        $this->dispatch('taxUpdated');
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

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.perpajakan');
    }
}
