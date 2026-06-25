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
        $moneyFields = [
            'pemasukan',
            'kebutuhan_pokok',
            'transportasi',
            'cicilan',
            'gaya_hidup',
            'tabungan',
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

        $validated = $request->validate([
            'periode' => ['required', 'string', 'max:100'],
            'pemasukan' => ['required', 'numeric', 'min:0'],
            'kebutuhan_pokok' => ['required', 'numeric', 'min:0'],
            'transportasi' => ['required', 'numeric', 'min:0'],
            'cicilan' => ['required', 'numeric', 'min:0'],
            'gaya_hidup' => ['required', 'numeric', 'min:0'],
            'tabungan' => ['required', 'numeric', 'min:0'],
            'investasi' => ['required', 'numeric', 'min:0'],
            'dana_darurat' => ['required', 'numeric', 'min:0'],
            'target_tabungan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $userId = auth()->id();

        // Save or update to database
        $analysis = FinancialAnalysis::updateOrCreate(
            [
                'user_id' => $userId,
                'periode' => $validated['periode'],
            ],
            [
                'pemasukan' => (float) $validated['pemasukan'],
                'kebutuhan_pokok' => (float) $validated['kebutuhan_pokok'],
                'transportasi' => (float) $validated['transportasi'],
                'cicilan' => (float) $validated['cicilan'],
                'gaya_hidup' => (float) $validated['gaya_hidup'],
                'tabungan' => (float) $validated['tabungan'],
                'investasi' => (float) $validated['investasi'],
                'dana_darurat' => (float) $validated['dana_darurat'],
                'target_tabungan' => $validated['target_tabungan'] !== null ? (float) $validated['target_tabungan'] : null,
            ]
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
            'result' => $result,
            'categories' => $this->expenseCategories(),
            'history' => $history,
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
        $expenses = [
            'Kebutuhan pokok' => (float) $data['kebutuhan_pokok'],
            'Transportasi' => (float) $data['transportasi'],
            'Cicilan/utang' => (float) $data['cicilan'],
            'Gaya hidup' => (float) $data['gaya_hidup'],
        ];
        $saving = (float) $data['tabungan'];
        $investment = (float) $data['investasi'];
        $emergencyFund = (float) $data['dana_darurat'];
        $targetSaving = (float) ($data['target_tabungan'] ?? 0);

        $totalExpenses = array_sum($expenses);
        $totalAllocation = $totalExpenses + $saving + $investment;
        $netCashflow = $income - $totalAllocation;
        $expenseRatio = $this->ratio($totalExpenses, $income);
        $savingRatio = $this->ratio($saving + $investment, $income);
        $debtRatio = $this->ratio($expenses['Cicilan/utang'], $income);
        $emergencyMonths = $totalExpenses > 0 ? $emergencyFund / $totalExpenses : 0;
        $monthsToTarget = $saving > 0 && $targetSaving > 0
            ? max(ceil(($targetSaving - $saving) / $saving), 0)
            : null;

        $assessment = $this->assessFinancialHealth(
            $netCashflow,
            $expenseRatio,
            $savingRatio,
            $debtRatio,
            $emergencyMonths
        );

        return [
            'periode' => $data['periode'],
            'income' => $income,
            'expenses' => $expenses,
            'total_expenses' => $totalExpenses,
            'saving' => $saving,
            'investment' => $investment,
            'total_saving_investment' => $saving + $investment,
            'emergency_fund' => $emergencyFund,
            'target_saving' => $targetSaving,
            'total_allocation' => $totalAllocation,
            'net_cashflow' => $netCashflow,
            'expense_ratio' => $expenseRatio,
            'saving_ratio' => $savingRatio,
            'debt_ratio' => $debtRatio,
            'emergency_months' => $emergencyMonths,
            'months_to_target' => $monthsToTarget,
            'status' => $assessment['status'],
            'status_class' => $assessment['class'],
            'recommendations' => $assessment['recommendations'],
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
