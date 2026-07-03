import os
import re

controller_path = 'app/Http/Controllers/FinanceController.php'
with open(controller_path, 'r', encoding='utf-8') as f:
    controller_code = f.read()

livewire_code = """<?php

namespace App\\Livewire;

use Livewire\\Component;
use Livewire\\Attributes\\Layout;
use App\\Models\\FinancialAnalysis;
use App\\Models\\ExpenseCategoryTemplate;
use App\\Models\\ExpenseCategoryRecommendation;
use Illuminate\\Support\\Facades\\Auth;

class SmartFinance extends Component
{
    public $periode;
    public $pemasukan = 0;
    public $tabungan = 0;
    public $saldo_tabungan = null;
    public $setoran_tabungan = null;
    public $investasi = 0;
    public $dana_darurat = 0;
    public $target_tabungan = null;
    
    // Legacy fixed fields
    public $kebutuhan_pokok = 0;
    public $transportasi = 0;
    public $cicilan = 0;
    public $gaya_hidup = 0;

    // Dynamic expenses
    public $expenses = [];
    public $usingDynamic = true;

    // Results
    public $result = null;
    public $recommendations = [];
    public $categoryHistory = [];
    public $history = [];
    public $templates = [];
    public $categories = [];

    public function mount()
    {
        $this->periode = date('F Y');
        $this->loadData();
    }

    public function loadData($load_id = null)
    {
        $userId = Auth::id();
        
        $historyQuery = FinancialAnalysis::where('user_id', $userId)->orderBy('periode', 'asc')->get();
        $this->history = $historyQuery->map(function ($item) {
            $item->calculated = $this->calculateResults($item->toArray());
            return $item;
        });

        if ($load_id) {
            $loadedAnalysis = FinancialAnalysis::where('user_id', $userId)->find($load_id);
            if ($loadedAnalysis) {
                $this->result = $this->calculateResults($loadedAnalysis->toArray());
                $this->recommendations = $this->generateCategoryRecommendations($this->result);
                $this->categoryHistory = $this->getCategoryHistory($userId, 6);
                $this->fillForm($loadedAnalysis);
            }
        } elseif ($this->history->count() > 0) {
            $latestAnalysis = $this->history->last();
            $this->result = $latestAnalysis->calculated;
            $this->recommendations = $this->generateCategoryRecommendations($this->result);
            $this->categoryHistory = $this->getCategoryHistory($userId, 6);
            $this->fillForm($latestAnalysis);
        }

        $this->templates = $this->getCategoryTemplates();
        $this->categories = $this->expenseCategories();
    }

    private function fillForm($analysis)
    {
        $this->periode = $analysis->periode;
        $this->pemasukan = $analysis->pemasukan;
        $this->tabungan = $analysis->tabungan;
        $this->saldo_tabungan = $analysis->saldo_tabungan;
        $this->setoran_tabungan = $analysis->setoran_tabungan;
        $this->investasi = $analysis->investasi;
        $this->dana_darurat = $analysis->dana_darurat;
        $this->target_tabungan = $analysis->target_tabungan;

        if ($analysis->expenses_json) {
            $this->expenses = $analysis->expenses_json;
            $this->usingDynamic = true;
        } else {
            $this->kebutuhan_pokok = $analysis->kebutuhan_pokok;
            $this->transportasi = $analysis->transportasi;
            $this->cicilan = $analysis->cicilan;
            $this->gaya_hidup = $analysis->gaya_hidup;
            $this->usingDynamic = false;
        }
    }

    public function normalize($value)
    {
        if (is_string($value)) {
            return (float) preg_replace('/[^0-9]/', '', $value);
        }
        return (float) $value;
    }

    public function addExpenseRow()
    {
        $this->expenses[] = ['name' => '', 'amount' => 0, 'is_debt' => false];
    }

    public function removeExpenseRow($index)
    {
        unset($this->expenses[$index]);
        $this->expenses = array_values($this->expenses);
    }

    public function analyze()
    {
        $this->pemasukan = $this->normalize($this->pemasukan);
        $this->tabungan = $this->normalize($this->tabungan);
        $this->saldo_tabungan = $this->normalize($this->saldo_tabungan);
        $this->setoran_tabungan = $this->normalize($this->setoran_tabungan);
        $this->investasi = $this->normalize($this->investasi);
        $this->dana_darurat = $this->normalize($this->dana_darurat);
        $this->target_tabungan = $this->normalize($this->target_tabungan);

        $saveData = [
            'pemasukan'        => $this->pemasukan,
            'tabungan'         => $this->tabungan,
            'saldo_tabungan'   => $this->saldo_tabungan,
            'setoran_tabungan' => $this->setoran_tabungan,
            'investasi'        => $this->investasi,
            'dana_darurat'     => $this->dana_darurat,
            'target_tabungan'  => $this->target_tabungan,
            'user_id'          => Auth::id(),
            'periode'          => $this->periode,
        ];

        if ($this->usingDynamic) {
            $totalDebt = 0;
            $totalNonDebt = 0;
            foreach ($this->expenses as &$expense) {
                $expense['amount'] = $this->normalize($expense['amount']);
                if (!empty($expense['is_debt'])) {
                    $totalDebt += $expense['amount'];
                } else {
                    $totalNonDebt += $expense['amount'];
                }
            }
            $saveData['kebutuhan_pokok'] = $totalNonDebt;
            $saveData['cicilan'] = $totalDebt;
            $saveData['transportasi'] = 0;
            $saveData['gaya_hidup'] = 0;
            $saveData['expenses_json'] = $this->expenses;
        } else {
            $this->kebutuhan_pokok = $this->normalize($this->kebutuhan_pokok);
            $this->transportasi = $this->normalize($this->transportasi);
            $this->cicilan = $this->normalize($this->cicilan);
            $this->gaya_hidup = $this->normalize($this->gaya_hidup);
            
            $saveData['kebutuhan_pokok'] = $this->kebutuhan_pokok;
            $saveData['transportasi'] = $this->transportasi;
            $saveData['cicilan'] = $this->cicilan;
            $saveData['gaya_hidup'] = $this->gaya_hidup;
        }

        FinancialAnalysis::updateOrCreate(
            ['user_id' => Auth::id(), 'periode' => $this->periode],
            $saveData
        );

        $this->loadData();
        $this->dispatch('analysisUpdated'); // Trigger alpine/js to update charts
    }
"""

# Extract the private methods from FinanceController
import re
methods_to_extract = ['calculateResults', 'expenseCategories', 'generateCategoryRecommendations', 'getCategoryHistory', 'getCategoryTemplates']

for method in methods_to_extract:
    # Match the method signature and its full body block
    match = re.search(r'(private|protected|public)\s+function\s+' + method + r'\s*\([^)]*\)\s*\{(?:[^{}]*|(?R))*\}', controller_code, re.DOTALL)
    if match:
        livewire_code += "\n    " + match.group(0).replace("\n", "\n    ") + "\n"
    else:
        # Fallback regex if (?R) isn't supported in python re (it isn't)
        # We'll use a simpler brace matching
        start_idx = controller_code.find(f'function {method}(')
        if start_idx != -1:
            # find opening brace
            brace_idx = controller_code.find('{', start_idx)
            count = 1
            idx = brace_idx + 1
            while count > 0 and idx < len(controller_code):
                if controller_code[idx] == '{':
                    count += 1
                elif controller_code[idx] == '}':
                    count -= 1
                idx += 1
            method_code = controller_code[start_idx-8:idx]
            livewire_code += "\n    " + method_code.replace("\n", "\n    ") + "\n"

livewire_code += """
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.smart-finance');
    }
}
"""

with open('app/Livewire/SmartFinance.php', 'w', encoding='utf-8') as f:
    f.write(livewire_code)

print("SmartFinance.php generated.")
