<?php

namespace App\Http\Controllers;

use App\Models\FinancialAnalysis;
use App\Models\ExpenseCategoryTemplate;
use App\Models\ExpenseCategoryRecommendation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceController extends BaseController
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        $history = FinancialAnalysis::where('user_id', $userId)
            ->orderBy('periode', 'asc')
            ->get()
            ->map(function ($item) {
                $item->calculated = $this->calculateResults($item->toArray());
                return $item;
            });

        $result = null;
        $recommendations = [];
        $categoryHistory = [];
        
        if ($request->has('load_id')) {
            $loadedAnalysis = FinancialAnalysis::where('user_id', $userId)->find($request->input('load_id'));
            if ($loadedAnalysis) {
                $result = $this->calculateResults($loadedAnalysis->toArray());
                $recommendations = $this->generateCategoryRecommendations($result);
                $categoryHistory = $this->getCategoryHistory($userId, 6);
            }
        } elseif ($history->count() > 0) {
            // If no specific load_id, use the latest analysis
            $latestAnalysis = $history->last();
            if ($latestAnalysis) {
                $result = $latestAnalysis->calculated;
                $recommendations = $this->generateCategoryRecommendations($result);
                $categoryHistory = $this->getCategoryHistory($userId, 6);
            }
        }

        $templates = $this->getCategoryTemplates();

        return view('smart_finance', [
            'result' => $result,
            'categories' => $this->expenseCategories(),
            'history' => $history,
            'templates' => $templates,
            'recommendations' => $recommendations,
            'categoryHistory' => $categoryHistory,
        ]);
    }

    public function analyze(Request $request)
    {
        // Normalize fixed money fields (non-expense)
        $moneyFields = [
            'pemasukan',
            'tabungan',
            'saldo_tabungan',
            'setoran_tabungan',
            'investasi',
            'dana_darurat',
            'target_tabungan',
        ];

        foreach ($moneyFields as $field) {
            if ($request->filled($field)) {
                $request->merge([
                    $field => $this->normalizeRupiah($request->input($field)),
                ]);
            }
        }

        // Process dynamic expense categories from expenses[] array
        $expenseItems = [];
        foreach ((array) $request->input('expenses', []) as $item) {
            $name = trim(strip_tags((string) ($item['name'] ?? '')));
            if ($name === '') {
                continue;
            }
            $amount = (float) preg_replace('/[^0-9]/', '', (string) ($item['amount'] ?? 0));
            $isDebt = isset($item['is_debt']) && $item['is_debt'] !== '0' && $item['is_debt'] !== '';
            $expenseItems[] = ['name' => $name, 'amount' => $amount, 'is_debt' => $isDebt];
        }

        $usingDynamic = !empty($expenseItems);

        if ($usingDynamic) {
            // Dynamic mode: validate only non-expense fields
            $validated = $request->validate([
                'periode'          => ['required', 'string', 'max:100'],
                'pemasukan'        => ['required', 'numeric', 'min:0'],
                'tabungan'         => ['required', 'numeric', 'min:0'],
                'saldo_tabungan'   => ['nullable', 'numeric', 'min:0'],
                'setoran_tabungan' => ['nullable', 'numeric', 'min:0'],
                'investasi'        => ['required', 'numeric', 'min:0'],
                'dana_darurat'     => ['required', 'numeric', 'min:0'],
                'target_tabungan'  => ['nullable', 'numeric', 'min:0'],
            ]);

            // Compute totals for legacy columns (satisfies NOT NULL constraint)
            $totalDebt    = array_sum(array_column(array_filter($expenseItems, fn($i) => $i['is_debt']), 'amount'));
            $totalNonDebt = array_sum(array_column(array_filter($expenseItems, fn($i) => !$i['is_debt']), 'amount'));

            $saveData = [
                'pemasukan'        => (float) $validated['pemasukan'],
                'kebutuhan_pokok'  => $totalNonDebt,
                'transportasi'     => 0,
                'cicilan'          => $totalDebt,
                'gaya_hidup'       => 0,
                'tabungan'         => (float) $validated['tabungan'],
                'saldo_tabungan'   => isset($validated['saldo_tabungan'])   ? (float) $validated['saldo_tabungan']   : null,
                'setoran_tabungan' => isset($validated['setoran_tabungan']) ? (float) $validated['setoran_tabungan'] : null,
                'investasi'        => (float) $validated['investasi'],
                'dana_darurat'     => (float) $validated['dana_darurat'],
                'target_tabungan'  => $validated['target_tabungan'] !== null ? (float) $validated['target_tabungan'] : null,
                'expenses_json'    => $expenseItems,
            ];
        } else {
            // Legacy mode: fixed expense fields
            $legacyExpenseFields = ['kebutuhan_pokok', 'transportasi', 'cicilan', 'gaya_hidup'];
            foreach ($legacyExpenseFields as $field) {
                $request->merge([$field => $this->normalizeRupiah($request->input($field, '0'))]);
            }

            $validated = $request->validate([
                'periode'          => ['required', 'string', 'max:100'],
                'pemasukan'        => ['required', 'numeric', 'min:0'],
                'kebutuhan_pokok'  => ['required', 'numeric', 'min:0'],
                'transportasi'     => ['required', 'numeric', 'min:0'],
                'cicilan'          => ['required', 'numeric', 'min:0'],
                'gaya_hidup'       => ['required', 'numeric', 'min:0'],
                'tabungan'         => ['required', 'numeric', 'min:0'],
                'saldo_tabungan'   => ['nullable', 'numeric', 'min:0'],
                'setoran_tabungan' => ['nullable', 'numeric', 'min:0'],
                'investasi'        => ['required', 'numeric', 'min:0'],
                'dana_darurat'     => ['required', 'numeric', 'min:0'],
                'target_tabungan'  => ['nullable', 'numeric', 'min:0'],
            ]);

            $saveData = [
                'pemasukan'        => (float) $validated['pemasukan'],
                'kebutuhan_pokok'  => (float) $validated['kebutuhan_pokok'],
                'transportasi'     => (float) $validated['transportasi'],
                'cicilan'          => (float) $validated['cicilan'],
                'gaya_hidup'       => (float) $validated['gaya_hidup'],
                'tabungan'         => (float) $validated['tabungan'],
                'saldo_tabungan'   => isset($validated['saldo_tabungan'])   ? (float) $validated['saldo_tabungan']   : null,
                'setoran_tabungan' => isset($validated['setoran_tabungan']) ? (float) $validated['setoran_tabungan'] : null,
                'investasi'        => (float) $validated['investasi'],
                'dana_darurat'     => (float) $validated['dana_darurat'],
                'target_tabungan'  => $validated['target_tabungan'] !== null ? (float) $validated['target_tabungan'] : null,
                'expenses_json'    => null,
            ];
        }

        $userId = auth()->id();

        $analysis = FinancialAnalysis::updateOrCreate(
            [
                'user_id' => $userId,
                'periode' => $validated['periode'],
            ],
            $saveData
        );

        $result = $this->calculateResults($analysis->toArray());

        $history = FinancialAnalysis::where('user_id', $userId)
            ->orderBy('periode', 'asc')
            ->get()
            ->map(function ($item) {
                $item->calculated = $this->calculateResults($item->toArray());
                return $item;
            });

        $recommendations = $this->generateCategoryRecommendations($result);
        $categoryHistory = $this->getCategoryHistory($userId, 6);
        $templates = $this->getCategoryTemplates();

        return view('smart_finance', [
            'result'     => $result,
            'categories' => $this->expenseCategories(),
            'history'    => $history,
            'templates'  => $templates,
            'recommendations' => $recommendations,
            'categoryHistory' => $categoryHistory,
        ]);
    }

    public function getTemplates()
    {
        $templates = $this->getCategoryTemplates();
        return response()->json($templates);
    }

    public function applyTemplate(Request $request)
    {
        $templateId = $request->input('template_id');
        $template = ExpenseCategoryTemplate::find($templateId);
        
        if (!$template) {
            return response()->json(['error' => __('finance.template_not_found')], 404);
        }

        $income = (float) ($request->input('income') ?? 0);
        
        // Generate expense items based on template ratios and income
        $expenseItems = [];
        foreach ($template->categories as $category) {
            $amount = ($income * $category['ratio_percent']) / 100;
            $expenseItems[] = [
                'name' => $category['name'],
                'amount' => round($amount, 2),
                'is_debt' => $category['is_debt'] ?? false,
            ];
        }

        return response()->json(['expenses' => $expenseItems]);
    }

    public function destroy($id)
    {
        $userId = auth()->id();
        $analysis = FinancialAnalysis::where('user_id', $userId)->findOrFail($id);
        $analysis->delete();

        return redirect()->route('finance.index')->with('success', 'Riwayat analisis berhasil dihapus.');
    }

    public function exportPdf($id)
    {
        $userId = auth()->id();
        $analysis = FinancialAnalysis::where('user_id', $userId)->findOrFail($id);
        
        $result = $this->calculateResults($analysis->toArray());
        $recommendations = $this->generateCategoryRecommendations($result);

        $pdf = Pdf::loadView('smart_finance_pdf', [
            'result' => $result,
            'recommendations' => $recommendations,
            'user' => auth()->user()
        ]);

        return $pdf->download('SmartFinance_Report_' . $result['periode'] . '.pdf');
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

    private function ratio(float $value, float $base): float
    {
        return $base > 0 ? ($value / $base) * 100 : 0;
    }

    private function normalizeRupiah($value): string
    {
        return preg_replace('/[^0-9]/', '', (string) $value) ?: '0';
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
}

