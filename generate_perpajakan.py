import os

controller_path = 'app/Http/Controllers/TaxController.php'
with open(controller_path, 'r', encoding='utf-8') as f:
    controller_code = f.read()

livewire_code = """<?php

namespace App\\Livewire;

use Livewire\\Component;
use Livewire\\Attributes\\Layout;
use App\\Models\\TaxAnalysis;
use Illuminate\\Support\\Facades\\Auth;

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

        if ($metode === 'ter') {
            $hasil = $this->calculateTER($penghasilanBulanan, $status, $penghasilanTidakTeratur, $iuranPensiun, $zakat, $ptkp_tahunan);
        } else {
            $hasil = $this->calculateTahunanLama($penghasilanBulanan, $penghasilanTidakTeratur, $iuranPensiun, $zakat, $ptkp_tahunan, $kreditPajak);
        }

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
            'hasil_json' => $hasil,
        ]);

        $this->loadData();
        $this->dispatch('taxUpdated');
    }
"""

methods_to_extract = ['calculateTER', 'calculateTahunanLama', 'getTerRate', 'taxStatuses']

for method in methods_to_extract:
    start_idx = controller_code.find(f'function {method}(')
    if start_idx != -1:
        brace_idx = controller_code.find('{', start_idx)
        count = 1
        idx = brace_idx + 1
        while count > 0 and idx < len(controller_code):
            if controller_code[idx] == '{':
                count += 1
            elif controller_code[idx] == '}':
                count -= 1
            idx += 1
        method_def_start = controller_code.rfind('\n', 0, start_idx)
        method_code = controller_code[method_def_start:idx]
        livewire_code += "\n    " + method_code.replace("\n", "\n    ") + "\n"

livewire_code += """
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.perpajakan');
    }
}
"""

with open('app/Livewire/Perpajakan.php', 'w', encoding='utf-8') as f:
    f.write(livewire_code)

print("Perpajakan.php generated successfully.")
