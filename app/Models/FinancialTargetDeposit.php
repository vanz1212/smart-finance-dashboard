<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTargetDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_target_id',
        'user_id',
        'amount',
        'date',
        'note',
    ];

    protected $casts = [
        'amount' => 'float',
        'date' => 'date',
    ];

    public function target(): BelongsTo
    {
        return $this->belongsTo(FinancialTarget::class, 'financial_target_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
