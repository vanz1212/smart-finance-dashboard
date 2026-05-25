<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class TaxController extends BaseController
{
    public function index()
    {
        return view('perpajakan', [
            'result' => null,
            'statuses' => $this->taxStatuses(),
        ]);
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'nama_wajib_pajak' => ['required', 'string', 'max:100'],
            'penghasilan_bulanan' => ['required', 'numeric', 'min:0'],
            'pengeluaran_bulanan' => ['required', 'numeric', 'min:0'],
            'status_wajib_pajak' => ['required', 'in:' . implode(',', $this->taxStatuses())],
        ]);

        $penghasilanTahunan = (float) $validated['penghasilan_bulanan'] * 12;
        $pengeluaranTahunan = (float) $validated['pengeluaran_bulanan'] * 12;
        $penghasilanBersih = $penghasilanTahunan - $pengeluaranTahunan;
        $estimasiPajak = $penghasilanBersih > 0 ? $penghasilanBersih * 0.05 : 0;

        return view('perpajakan', [
            'result' => [
                'nama_wajib_pajak' => $validated['nama_wajib_pajak'],
                'penghasilan_tahunan' => $penghasilanTahunan,
                'pengeluaran_tahunan' => $pengeluaranTahunan,
                'penghasilan_bersih' => $penghasilanBersih,
                'estimasi_pajak' => $estimasiPajak,
                'status_pajak' => $this->taxLevel($estimasiPajak),
            ],
            'statuses' => $this->taxStatuses(),
        ]);
    }

    private function taxStatuses(): array
    {
        return ['TK/0', 'K/0', 'K/1', 'K/2', 'K/3'];
    }

    private function taxLevel(float $tax): string
    {
        if ($tax < 1000000) {
            return 'Pajak rendah';
        }

        if ($tax <= 5000000) {
            return 'Pajak normal';
        }

        return 'Pajak tinggi';
    }
}
