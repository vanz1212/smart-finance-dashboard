<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategoryTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'categories',
        'type',
        'is_default',
        'order',
    ];

    protected $casts = [
        'categories' => 'array',
        'is_default' => 'boolean',
    ];

    public static function getDefaults()
    {
        return [
            [
                'name' => 'Profesional Muda',
                'description' => 'Template untuk profesional muda dengan pengeluaran moderate',
                'type' => 'general',
                'categories' => [
                    ['name' => 'Kebutuhan pokok', 'ratio_percent' => 35, 'is_debt' => false],
                    ['name' => 'Transportasi', 'ratio_percent' => 10, 'is_debt' => false],
                    ['name' => 'Cicilan/utang', 'ratio_percent' => 15, 'is_debt' => true],
                    ['name' => 'Gaya hidup', 'ratio_percent' => 10, 'is_debt' => false],
                ]
            ],
            [
                'name' => 'Hemat & Investasi',
                'description' => 'Template untuk fokus tabungan dan investasi',
                'type' => 'premium',
                'categories' => [
                    ['name' => 'Kebutuhan pokok', 'ratio_percent' => 30, 'is_debt' => false],
                    ['name' => 'Transportasi', 'ratio_percent' => 5, 'is_debt' => false],
                    ['name' => 'Cicilan/utang', 'ratio_percent' => 10, 'is_debt' => true],
                    ['name' => 'Gaya hidup', 'ratio_percent' => 5, 'is_debt' => false],
                ]
            ],
            [
                'name' => 'Minimal',
                'description' => 'Template untuk pengeluaran minimal dan terjangkau',
                'type' => 'minimal',
                'categories' => [
                    ['name' => 'Kebutuhan pokok', 'ratio_percent' => 40, 'is_debt' => false],
                    ['name' => 'Transportasi', 'ratio_percent' => 8, 'is_debt' => false],
                    ['name' => 'Cicilan/utang', 'ratio_percent' => 12, 'is_debt' => true],
                ]
            ],
            [
                'name' => 'Keluarga',
                'description' => 'Template untuk kepala keluarga dengan tanggungan',
                'type' => 'general',
                'categories' => [
                    ['name' => 'Kebutuhan pokok', 'ratio_percent' => 40, 'is_debt' => false],
                    ['name' => 'Pendidikan anak', 'ratio_percent' => 12, 'is_debt' => false],
                    ['name' => 'Transportasi', 'ratio_percent' => 10, 'is_debt' => false],
                    ['name' => 'Kesehatan', 'ratio_percent' => 8, 'is_debt' => false],
                    ['name' => 'Cicilan/utang', 'ratio_percent' => 15, 'is_debt' => true],
                ]
            ],
        ];
    }
}
