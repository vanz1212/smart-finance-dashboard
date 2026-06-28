<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class FinancialTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'category',
        'target_amount',
        'current_amount',
        'target_date',
        'recommended_monthly',
        'actual_monthly',
        'status',
        'priority',
    ];

    protected $casts = [
        'target_amount' => 'float',
        'current_amount' => 'float',
        'recommended_monthly' => 'float',
        'actual_monthly' => 'float',
        'target_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(FinancialTargetDeposit::class);
    }

    /**
     * Calculate progress percentage
     */
    public function getProgressPercentage(): float
    {
        return $this->target_amount > 0 
            ? ($this->current_amount / $this->target_amount) * 100 
            : 0;
    }

    /**
     * Calculate remaining amount
     */
    public function getRemainingAmount(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    /**
     * Calculate days remaining until target date
     */
    public function getDaysRemaining(): int
    {
        return Carbon::now()->diffInDays($this->target_date, false);
    }

    /**
     * Check if target is achieved
     */
    public function isAchieved(): bool
    {
        return $this->current_amount >= $this->target_amount;
    }

    /**
     * Check if target is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && Carbon::now()->isAfter($this->target_date);
    }

    /**
     * Calculate recommended monthly deposit
     */
    public function calculateRecommendedMonthly(): float
    {
        $remaining = $this->getRemainingAmount();
        $monthsRemaining = max(1, round(Carbon::now()->diffInMonths($this->target_date)));
        
        return $remaining / $monthsRemaining;
    }

    /**
     * Calculate average monthly deposit
     */
    public function getAverageMonthlyDeposit(): float
    {
        $deposits = $this->deposits()->get();
        
        if ($deposits->isEmpty()) {
            return 0;
        }

        $firstDate = $deposits->min('date');
        $lastDate = $deposits->max('date');
        $monthsDiff = Carbon::parse($firstDate)->diffInMonths(Carbon::parse($lastDate));
        $monthsDiff = max(1, $monthsDiff);

        $totalAmount = $deposits->sum('amount');
        return $totalAmount / $monthsDiff;
    }

    /**
     * Get performance status compared to recommendation
     */
    public function getPerformanceStatus(): array
    {
        $recommended = $this->recommended_monthly ?? $this->calculateRecommendedMonthly();
        $average = $this->getAverageMonthlyDeposit();

        if ($average >= $recommended * 0.9) {
            $status = 'on-track';
            $message = __('targets.performance_on_track');
        } elseif ($average >= $recommended * 0.7) {
            $status = 'at-risk';
            $message = __('targets.performance_at_risk');
        } else {
            $status = 'behind';
            $message = __('targets.performance_behind');
        }

        return [
            'status' => $status,
            'message' => $message,
            'recommended' => $recommended,
            'average' => $average,
        ];
    }
}
