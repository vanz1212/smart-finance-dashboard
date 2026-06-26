<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseCategoryRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_name',
        'recommended_amount',
        'actual_amount',
        'status',
        'reason',
        'periode',
    ];

    protected $casts = [
        'recommended_amount' => 'float',
        'actual_amount' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
