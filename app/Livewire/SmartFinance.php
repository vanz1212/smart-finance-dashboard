<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\FinancialAnalysis;
use App\Models\ExpenseCategoryTemplate;
use App\Models\ExpenseCategoryRecommendation;
use Illuminate\Support\Facades\Auth;

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

    
        private function calculateResults(array $data): array
        {
            $income = (float) $data['pemasukan'];
    
            // Prefer dynamic categories from expenses_json; fall back to fixed columns
            $rawJson = $data['expenses_json'] ?? null;
            if (!empty($rawJson)) {
                $expenseItems = is_string($rawJson) ? json_decode($rawJson, true) : $rawJson;
            } else {
                $expenseItems = [
                    ['name' => 'Kebutuhan pokok', 'amount' => (float) ($data['kebutuhan_pokok'] ?? 0), 'is_debt' => false],
                    ['name' => 'Transportasi',    'amount' => (float) ($data['transportasi']    ?? 0), 'is_debt' => false],
                    ['name' => 'Cicilan/utang',   'amount' => (float) ($data['cicilan']         ?? 0), 'is_debt' => true],
                    ['name' => 'Gaya hidup',      'amount' => (float) ($data['gaya_hidup']      ?? 0), 'is_debt' => false],
                ];
            }
    
            // Build associative expenses array and compute debt total
            $expenses   = [];
            $totalDebt  = 0;
            foreach ($expenseItems as $item) {
                $expenses[$item['name']] = (float) ($item['amount'] ?? 0);
                if (!empty($item['is_debt'])) {
                    $totalDebt += (float) ($item['amount'] ?? 0);
                }
            }
    
            $saving       = (float) ($data['tabungan']  ?? 0);
            $investment   = (float) ($data['investasi'] ?? 0);
            $emergencyFund = (float) ($data['dana_darurat'] ?? 0);
            $targetSaving  = (float) ($data['target_tabungan'] ?? 0);
    
            $saldoTabungan   = isset($data['saldo_tabungan'])   && $data['saldo_tabungan']   !== null ? (float) $data['saldo_tabungan']   : null;
            $setoranTabungan = isset($data['setoran_tabungan']) && $data['setoran_tabungan'] !== null ? (float) $data['setoran_tabungan'] : null;
    
            $totalExpenses   = array_sum($expenses);
            $totalAllocation = $totalExpenses + $saving + $investment;
            $netCashflow     = $income - $totalAllocation;
            $expenseRatio    = $this->ratio($totalExpenses, $income);
            $savingRatio     = $this->ratio($saving + $investment, $income);
            $debtRatio       = $this->ratio($totalDebt, $income);
            $emergencyMonths = $totalExpenses > 0 ? $emergencyFund / $totalExpenses : 0;
    
            $effectiveSetoran = $setoranTabungan ?? $saving;
            $effectiveSaldo   = $saldoTabungan   ?? 0;
            $monthsToTarget   = $effectiveSetoran > 0 && $targetSaving > 0
                ? max(ceil(($targetSaving - $effectiveSaldo) / $effectiveSetoran), 0)
                : null;
    
            $assessment = $this->assessFinancialHealth(
                $netCashflow,
                $expenseRatio,
                $savingRatio,
                $debtRatio,
                $emergencyMonths
            );
    
            return [
                'periode'               => $data['periode'],
                'income'                => $income,
                'expense_items'         => $expenseItems,   // [{name, amount, is_debt}] for dynamic form + chart
                'expenses'              => $expenses,
                'total_expenses'        => $totalExpenses,
                'saving'                => $saving,
                'saldo_tabungan'        => $saldoTabungan,
                'setoran_tabungan'      => $setoranTabungan,
                'investment'            => $investment,
                'total_saving_investment' => $saving + $investment,
                'emergency_fund'        => $emergencyFund,
                'target_saving'         => $targetSaving,
                'total_allocation'      => $totalAllocation,
                'net_cashflow'          => $netCashflow,
                'expense_ratio'         => $expenseRatio,
                'saving_ratio'          => $savingRatio,
                'debt_ratio'            => $debtRatio,
                'emergency_months'      => $emergencyMonths,
                'months_to_target'      => $monthsToTarget,
                'effective_setoran'     => $effectiveSetoran,
                'effective_saldo'       => $effectiveSaldo,
                'status'                => $assessment['status'],
                'status_class'          => $assessment['class'],
                'recommendations'       => $assessment['recommendations'],
            ];
        }

    
        private function expenseCategories(): array
        {
            return [
                'kebutuhan_pokok' => __('finance.basic_needs'),
                'transportasi' => __('finance.transportation'),
                'cicilan' => __('finance.debt_installment'),
                'gaya_hidup' => __('finance.lifestyle'),
            ];
        }

    
        private function generateCategoryRecommendations(array $result): array
        {
            $income = $result['income'];
            $recommendations = [];
    
            // Define ideal ratios for each expense type
            $idealRatios = [
                'basic_needs' => ['min' => 0.25, 'max' => 0.40, 'ideal' => 0.35],
                'transportation' => ['min' => 0.05, 'max' => 0.15, 'ideal' => 0.10],
                'debt_installment' => ['min' => 0, 'max' => 0.30, 'ideal' => 0.15],
                'lifestyle' => ['min' => 0.05, 'max' => 0.15, 'ideal' => 0.10],
            ];
    
            foreach ($result['expense_items'] as $item) {
                $categoryName = $item['name'];
                $actualAmount = (float) $item['amount'];
                $actualRatio = $income > 0 ? ($actualAmount / $income) : 0;
    
                $categoryKey = $this->normalizeCategoryKey($categoryName);
                $ideal = $idealRatios[$categoryKey] ?? ['min' => 0, 'max' => 0.20, 'ideal' => 0.10];
                $recommendedAmount = $income * $ideal['ideal'];
    
                $status = 'ok';
                $idealPercent = $ideal['ideal'] * 100;
                $reason = __('finance.recommendation_category_within_standard', ['ideal' => $idealPercent]);
    
                if ($actualRatio > $ideal['max']) {
                    $status = 'critical';
                    $maxPercent = $ideal['max'] * 100;
                    $exceedPercent = ($actualRatio - $ideal['max']) * 100;
                    $reason = __('finance.recommendation_category_exceeds_max', ['max' => $maxPercent, 'exceed' => number_format($exceedPercent, 1)]);
                } elseif ($actualRatio > $ideal['ideal'] && $actualRatio <= $ideal['max']) {
                    $status = 'warning';
                    $idealPercent = $ideal['ideal'] * 100;
                    $reason = __('finance.recommendation_category_higher_than_ideal', ['ideal' => $idealPercent]);
                } elseif ($actualRatio < $ideal['min'] && $ideal['min'] > 0) {
                    // This is usually OK (spending less than min)
                    $status = 'ok';
                    $reason = __('finance.recommendation_category_lower_than_recommendation');
                }
    
                $recommendations[] = [
                    'category_name' => $categoryName,
                    'actual_amount' => $actualAmount,
                    'recommended_amount' => $recommendedAmount,
                    'actual_ratio' => $actualRatio * 100,
                    'ideal_ratio' => $ideal['ideal'] * 100,
                    'status' => $status,
                    'status_label' => match ($status) {
                        'ok' => 'finance.recommendation_status_ok',
                        'warning' => 'finance.recommendation_status_warning',
                        'critical' => 'finance.recommendation_status_critical',
                        default => 'finance.recommendation_status_ok',
                    },
                    'reason' => $reason,
                ];
            }
    
            return $recommendations;
        }

    
        private function getCategoryHistory($userId, $months = 6): array
        {
            $history = FinancialAnalysis::where('user_id', $userId)
                ->orderBy('periode', 'desc')
                ->limit($months)
                ->get()
                ->reverse()
                ->values();
    
            $categoryData = [];
    
            foreach ($history as $analysis) {
                $calculated = $this->calculateResults($analysis->toArray());
                
                foreach ($calculated['expense_items'] as $item) {
                    $categoryName = $item['name'];
                    if (!isset($categoryData[$categoryName])) {
                        $categoryData[$categoryName] = [];
                    }
                    
                    $categoryData[$categoryName][] = [
                        'periode' => $analysis->periode,
                        'amount' => (float) $item['amount'],
                    ];
                }
            }
    
            return $categoryData;
        }

    
        private function getCategoryTemplates(): array
        {
            $templates = ExpenseCategoryTemplate::where('is_default', true)
                ->orderBy('order')
                ->get()
                ->map(function ($template) {
                    return [
                        'id' => $template->id,
                        'name' => $template->name,
                        'description' => $template->description,
                        'type' => $template->type,
                        'categories' => $template->categories,
                    ];
                })
                ->toArray();
    
            // If no default templates exist, return predefined ones
            if (empty($templates)) {
                $defaults = ExpenseCategoryTemplate::getDefaults();
                return array_map(function ($default) {
                    return array_merge(['id' => null], $default);
                }, $defaults);
            }
    
            return $templates;
        }

    
        private function normalizeRupiah($value): string
    {
        return preg_replace('/[^0-9]/', '', (string) $value) ?: '0';
    }

    private function ratio(float $value, float $base): float
    {
        return $base > 0 ? ($value / $base) * 100 : 0;
    }

    private function assessFinancialHealth(
        float $netCashflow,
        float $expenseRatio,
        float $savingRatio,
        float $debtRatio,
        float $emergencyMonths
    ): array {
        $recommendations = [];
        $score = 100;

        if ($netCashflow < 0) {
            $score -= 30;
            $recommendations[] = __('finance.recommendation_cashflow_negative');
        }

        if ($expenseRatio > 70) {
            $score -= 25;
            $recommendations[] = __('finance.recommendation_expense_ratio_high');
        } elseif ($expenseRatio > 60) {
            $score -= 15;
            $recommendations[] = __('finance.recommendation_expense_ratio_warn');
        }

        if ($savingRatio < 10) {
            $score -= 20;
            $recommendations[] = __('finance.recommendation_saving_ratio_low');
        } elseif ($savingRatio < 20) {
            $score -= 10;
            $recommendations[] = __('finance.recommendation_saving_ratio_mid');
        }

        if ($debtRatio > 30) {
            $score -= 25;
            $recommendations[] = __('finance.recommendation_debt_ratio_high');
        } elseif ($debtRatio > 20) {
            $score -= 10;
            $recommendations[] = __('finance.recommendation_debt_ratio_warn');
        }

        if ($emergencyMonths < 3) {
            $score -= 15;
            $recommendations[] = __('finance.recommendation_emergency_low');
        }

        if (empty($recommendations)) {
            $recommendations[] = __('finance.recommendation_finance_healthy');
        }

        if ($score >= 80) {
            $status = __('finance.status_healthy');
            $class = 'success';
        } elseif ($score >= 55) {
            $status = __('finance.status_warning');
            $class = 'warning';
        } else {
            $status = __('finance.status_risky');
            $class = 'danger';
        }

        return compact('status', 'class', 'recommendations');
    }

    private function normalizeCategoryKey(string $name): string
    {
        return match ($name) {
            __('finance.basic_needs'), 'Kebutuhan pokok', 'Basic needs' => 'basic_needs',
            __('finance.transportation'), 'Transportasi', 'Transportation' => 'transportation',
            __('finance.debt_installment'), 'Cicilan/utang', 'Installment/Debt', 'Installment' => 'debt_installment',
            __('finance.lifestyle'), 'Gaya hidup', 'Lifestyle' => 'lifestyle',
            default => strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', $name), '_')),
        };
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.smart-finance');
    }
}
