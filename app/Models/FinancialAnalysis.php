<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialAnalysis extends Model
{
    use HasFactory;

    protected $table = 'financial_analyses';

    protected $fillable = [
        'user_id',
        'periode',
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

    protected $casts = [
        'pemasukan' => 'float',
        'kebutuhan_pokok' => 'float',
        'transportasi' => 'float',
        'cicilan' => 'float',
        'gaya_hidup' => 'float',
        'tabungan' => 'float',
        'investasi' => 'float',
        'dana_darurat' => 'float',
        'target_tabungan' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
