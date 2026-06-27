<?php

namespace App\Http\Controllers;

use App\Models\FinancialTarget;
use App\Models\FinancialTargetDeposit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class FinancialTargetController extends BaseController
{
    public function index()
    {
        $userId = auth()->id();
        
        $targets = FinancialTarget::where('user_id', $userId)
            ->orderBy('priority')
            ->orderBy('target_date')
            ->get()
            ->map(function ($target) {
                $target->progress = $target->getProgressPercentage();
                $target->remaining = $target->getRemainingAmount();
                $target->days_remaining = $target->getDaysRemaining();
                $target->is_achieved = $target->isAchieved();
                $target->is_overdue = $target->isOverdue();
                $target->performance = $target->getPerformanceStatus();
                $target->recommended_monthly = $target->recommended_monthly ?? $target->calculateRecommendedMonthly();
                return $target;
            });

        $summaryStats = [
            'total_targets' => $targets->count(),
            'active_targets' => $targets->where('status', 'active')->count(),
            'completed_targets' => $targets->where('status', 'completed')->count(),
            'total_target_amount' => $targets->sum('target_amount'),
            'total_collected' => $targets->sum('current_amount'),
            'overall_progress' => $targets->sum('target_amount') > 0 
                ? ($targets->sum('current_amount') / $targets->sum('target_amount')) * 100 
                : 0,
        ];

        return view('financial_targets.index', [
            'targets' => $targets,
            'stats' => $summaryStats,
        ]);
    }

    public function create()
    {
        $categories = [
            'tabungan' => 'Tabungan',
            'investasi' => 'Investasi',
            'asuransi' => 'Asuransi',
            'properti' => 'Properti',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
        ];

        return view('financial_targets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'category' => ['required', 'in:tabungan,investasi,asuransi,properti,pendidikan,lainnya'],
            'target_amount' => ['required', 'numeric', 'min:1000'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['required', 'date', 'after:today'],
            'priority' => ['nullable', 'in:1,2,3'],
        ]);

        // Normalize currency input
        $validated['target_amount'] = (float) preg_replace('/[^0-9]/', '', (string) $validated['target_amount']);
        $validated['current_amount'] = isset($validated['current_amount']) 
            ? (float) preg_replace('/[^0-9]/', '', (string) $validated['current_amount'])
            : 0;

        $target = new FinancialTarget($validated);
        $target->user_id = $userId;
        
        // Calculate recommended monthly
        $monthsUntilTarget = max(1, Carbon::now()->diffInMonths($validated['target_date']));
        $remaining = $validated['target_amount'] - $target->current_amount;
        $target->recommended_monthly = $remaining / $monthsUntilTarget;
        
        $target->save();

        return redirect()->route('targets.show', $target->id)->with('success', 'Target finansial berhasil dibuat.');
    }

    public function show(FinancialTarget $target)
    {
        if ($target->user_id !== auth()->id()) {
            abort(403);
        }

        $target->progress = $target->getProgressPercentage();
        $target->remaining = $target->getRemainingAmount();
        $target->days_remaining = $target->getDaysRemaining();
        $target->is_achieved = $target->isAchieved();
        $target->is_overdue = $target->isOverdue();
        $target->performance = $target->getPerformanceStatus();
        $target->recommended_monthly = $target->recommended_monthly ?? $target->calculateRecommendedMonthly();

        $deposits = $target->deposits()
            ->orderBy('date', 'desc')
            ->get();

        $monthlyBreakdown = $this->getMonthlyBreakdown($target);

        return view('financial_targets.show', [
            'target' => $target,
            'deposits' => $deposits,
            'monthlyBreakdown' => $monthlyBreakdown,
        ]);
    }

    public function edit(FinancialTarget $target)
    {
        if ($target->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = [
            'tabungan' => 'Tabungan',
            'investasi' => 'Investasi',
            'asuransi' => 'Asuransi',
            'properti' => 'Properti',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
        ];

        return view('financial_targets.edit', compact('target', 'categories'));
    }

    public function update(Request $request, FinancialTarget $target)
    {
        if ($target->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'category' => ['required', 'in:tabungan,investasi,asuransi,properti,pendidikan,lainnya'],
            'target_amount' => ['required', 'numeric', 'min:1000'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'target_date' => ['required', 'date'],
            'status' => ['required', 'in:active,completed,paused,abandoned'],
            'priority' => ['nullable', 'in:1,2,3'],
        ]);

        $validated['target_amount'] = (float) preg_replace('/[^0-9]/', '', (string) $validated['target_amount']);
        $validated['current_amount'] = isset($validated['current_amount']) 
            ? (float) preg_replace('/[^0-9]/', '', (string) $validated['current_amount'])
            : 0;

        // Recalculate recommended monthly
        $monthsUntilTarget = max(1, Carbon::now()->diffInMonths($validated['target_date']));
        $remaining = $validated['target_amount'] - $validated['current_amount'];
        $validated['recommended_monthly'] = $remaining / $monthsUntilTarget;

        $target->update($validated);

        return redirect()->route('targets.show', $target->id)->with('success', 'Target finansial berhasil diperbarui.');
    }

    public function destroy(FinancialTarget $target)
    {
        if ($target->user_id !== auth()->id()) {
            abort(403);
        }

        $target->delete();

        return redirect()->route('targets.index')->with('success', 'Target finansial berhasil dihapus.');
    }

    public function addDeposit(Request $request, FinancialTarget $target)
    {
        if ($target->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1000'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['amount'] = (float) preg_replace('/[^0-9]/', '', (string) $validated['amount']);

        $deposit = new FinancialTargetDeposit($validated);
        $deposit->financial_target_id = $target->id;
        $deposit->user_id = auth()->id();
        $deposit->save();

        // Update target current_amount
        $target->current_amount += $deposit->amount;
        
        // Mark as completed if achieved
        if ($target->current_amount >= $target->target_amount && $target->status === 'active') {
            $target->status = 'completed';
        }
        
        $target->save();

        return redirect()->back()->with('success', 'Setoran berhasil dicatat.');
    }

    public function removeDeposit(FinancialTargetDeposit $deposit)
    {
        if ($deposit->user_id !== auth()->id()) {
            abort(403);
        }

        $target = $deposit->target;
        $target->current_amount -= $deposit->amount;
        
        // Mark as active if was completed
        if ($target->status === 'completed' && $target->current_amount < $target->target_amount) {
            $target->status = 'active';
        }
        
        $target->save();
        $deposit->delete();

        return redirect()->back()->with('success', 'Setoran berhasil dihapus.');
    }

    private function getMonthlyBreakdown(FinancialTarget $target): array
    {
        $deposits = $target->deposits()
            ->where('date', '>=', Carbon::now()->subMonths(12))
            ->get()
            ->groupBy(function ($deposit) {
                return $deposit->date->format('Y-m');
            });

        $breakdown = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $key = $date->format('Y-m');
            $breakdown[$date->format('M Y')] = isset($deposits[$key]) ? $deposits[$key]->sum('amount') : 0;
        }

        return $breakdown;
    }
}
