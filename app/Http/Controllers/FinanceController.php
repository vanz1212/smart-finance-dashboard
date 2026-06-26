<?php

namespace App\Http\Controllers;

use App\Models\FinancialAnalysis;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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
        if ($request->has('load_id')) {
            $loadedAnalysis = FinancialAnalysis::where('user_id', $userId)->find($request->input('load_id'));
            if ($loadedAnalysis) {
                $result = $this->calculateResults($loadedAnalysis->toArray());
            }
        }

        return view('smart_finance', [
            'result' => $result,
            'categories' => $this->expenseCategories(),
            'history' => $history,
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

        return view('smart_finance', [
            'result'     => $result,
            'categories' => $this->expenseCategories(),
            'history'    => $history,
        ]);
    }

    public function destroy($id)
    {
        $userId = auth()->id();
        $analysis = FinancialAnalysis::where('user_id', $userId)->findOrFail($id);
        $analysis->delete();

        return redirect()->route('finance.index')->with('success', 'Riwayat analisis berhasil dihapus.');
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
            'kebutuhan_pokok' => 'Kebutuhan pokok',
            'transportasi' => 'Transportasi',
            'cicilan' => 'Cicilan/utang',
            'gaya_hidup' => 'Gaya hidup',
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
            $recommendations[] = 'Arus kas negatif. Kurangi pengeluaran variabel atau susun ulang cicilan agar saldo bulanan kembali positif.';
        }

        if ($expenseRatio > 70) {
            $score -= 25;
            $recommendations[] = 'Rasio pengeluaran melewati 70% pemasukan. Prioritaskan kebutuhan pokok dan tekan biaya gaya hidup.';
        } elseif ($expenseRatio > 60) {
            $score -= 15;
            $recommendations[] = 'Rasio pengeluaran mulai tinggi. Sisihkan ruang untuk tabungan sebelum menambah komitmen baru.';
        }

        if ($savingRatio < 10) {
            $score -= 20;
            $recommendations[] = 'Rasio tabungan dan investasi masih di bawah 10%. Target awal yang sehat adalah minimal 10% dari pemasukan.';
        } elseif ($savingRatio < 20) {
            $score -= 10;
            $recommendations[] = 'Rasio tabungan cukup, tetapi masih bisa ditingkatkan menuju 20% untuk mempercepat target finansial.';
        }

        if ($debtRatio > 30) {
            $score -= 25;
            $recommendations[] = 'Rasio cicilan di atas 30%. Pertimbangkan pelunasan bertahap atau refinancing sebelum mengambil utang baru.';
        } elseif ($debtRatio > 20) {
            $score -= 10;
            $recommendations[] = 'Rasio cicilan perlu dipantau. Jaga agar cicilan tidak melewati 30% pemasukan.';
        }

        if ($emergencyMonths < 3) {
            $score -= 15;
            $recommendations[] = 'Dana darurat belum mencapai 3 bulan pengeluaran. Bangun cadangan kas sebelum meningkatkan risiko investasi.';
        }

        if (empty($recommendations)) {
            $recommendations[] = 'Keuangan terlihat sehat. Pertahankan disiplin anggaran dan evaluasi ulang setiap akhir periode.';
        }

        if ($score >= 80) {
            $status = 'Sehat';
            $class = 'success';
        } elseif ($score >= 55) {
            $status = 'Waspada';
            $class = 'warning';
        } else {
            $status = 'Berisiko';
            $class = 'danger';
        }

        return compact('status', 'class', 'recommendations');
    }
}
