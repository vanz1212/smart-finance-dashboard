<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\Rule;

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

    private const TAX_BRACKETS = [
        ['label' => 's.d. Rp60.000.000', 'upper_limit' => 60000000, 'rate' => 0.05],
        ['label' => '> Rp60.000.000 - Rp250.000.000', 'upper_limit' => 250000000, 'rate' => 0.15],
        ['label' => '> Rp250.000.000 - Rp500.000.000', 'upper_limit' => 500000000, 'rate' => 0.25],
        ['label' => '> Rp500.000.000 - Rp5.000.000.000', 'upper_limit' => 5000000000, 'rate' => 0.30],
        ['label' => '> Rp5.000.000.000', 'upper_limit' => null, 'rate' => 0.35],
    ];

    public function index()
    {
        return view('perpajakan', [
            'result' => null,
            'statuses' => $this->taxStatuses(),
            'ptkpTable' => self::PTKP,
            'taxBrackets' => self::TAX_BRACKETS,
        ]);
    }

    public function calculate(Request $request)
    {
        $request->merge([
            'penghasilan_bulanan' => $this->normalizeRupiah($request->input('penghasilan_bulanan')),
            'pengeluaran_bulanan' => $this->normalizeRupiah($request->input('pengeluaran_bulanan')),
        ]);

        $validated = $request->validate([
            'nama_wajib_pajak' => ['required', 'string', 'max:100'],
            'penghasilan_bulanan' => ['required', 'numeric', 'min:0'],
            'pengeluaran_bulanan' => ['required', 'numeric', 'min:0'],
            'status_wajib_pajak' => ['required', Rule::in(array_keys(self::PTKP))],
        ]);

        $penghasilanTahunan = (float) $validated['penghasilan_bulanan'] * 12;
        $pengurangTahunan = (float) $validated['pengeluaran_bulanan'] * 12;
        $penghasilanNeto = max($penghasilanTahunan - $pengurangTahunan, 0);
        $ptkp = self::PTKP[$validated['status_wajib_pajak']];
        $pkpSebelumPembulatan = max($penghasilanNeto - $ptkp, 0);
        $pkp = floor($pkpSebelumPembulatan / 1000) * 1000;
        $calculation = $this->calculateProgressiveTax($pkp);
        $estimasiPajak = $calculation['total_tax'];

        return view('perpajakan', [
            'result' => [
                'nama_wajib_pajak' => $validated['nama_wajib_pajak'],
                'status_wajib_pajak' => $validated['status_wajib_pajak'],
                'penghasilan_bulanan' => (float) $validated['penghasilan_bulanan'],
                'pengurang_bulanan' => (float) $validated['pengeluaran_bulanan'],
                'penghasilan_tahunan' => $penghasilanTahunan,
                'pengurang_tahunan' => $pengurangTahunan,
                'penghasilan_neto' => $penghasilanNeto,
                'ptkp' => $ptkp,
                'pkp_sebelum_pembulatan' => $pkpSebelumPembulatan,
                'pkp' => $pkp,
                'estimasi_pajak' => $estimasiPajak,
                'estimasi_pajak_bulanan' => $estimasiPajak / 12,
                'status_pajak' => $this->taxLevel($estimasiPajak),
                'breakdown' => $calculation['breakdown'],
            ],
            'statuses' => $this->taxStatuses(),
            'ptkpTable' => self::PTKP,
            'taxBrackets' => self::TAX_BRACKETS,
        ]);
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
        if ($tax <= 0) {
            return 'Tidak kena pajak';
        }

        if ($tax < 1000000) {
            return 'Pajak rendah';
        }

        if ($tax <= 5000000) {
            return 'Pajak normal';
        }

        return 'Pajak tinggi';
    }
}
