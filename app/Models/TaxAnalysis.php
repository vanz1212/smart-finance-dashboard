<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tahun_pajak',
        'penghasilan_bulanan',
        'penghasilan_tidak_teratur',
        'biaya_jabatan',
        'iuran_pensiun',
        'zakat',
        'kredit_pajak',
        'status_wajib_pajak',
        'metode_perhitungan',
        'estimasi_pajak',
        'hasil_json',
    ];

    protected $casts = [
        'tahun_pajak' => 'integer',
        'penghasilan_bulanan' => 'float',
        'penghasilan_tidak_teratur' => 'float',
        'biaya_jabatan' => 'float',
        'iuran_pensiun' => 'float',
        'zakat' => 'float',
        'kredit_pajak' => 'float',
        'estimasi_pajak' => 'float',
        'hasil_json' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
