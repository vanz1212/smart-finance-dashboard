<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FinancialAnalysis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialAnalysisTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_finance_dashboard(): void
    {
        $response = $this->get(route('finance.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_finance_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('finance.index'));
        $response->assertStatus(200);
        $response->assertSee('Input Bulanan');
    }

    public function test_user_can_submit_and_store_financial_analysis(): void
    {
        $user = User::factory()->create();

        $data = [
            'periode' => '2026-06',
            'pemasukan' => '10000000',
            'kebutuhan_pokok' => '3000000',
            'transportasi' => '500000',
            'cicilan' => '1000000',
            'gaya_hidup' => '1000000',
            'tabungan' => '1500000',
            'investasi' => '1000000',
            'dana_darurat' => '20000000',
            'target_tabungan' => '20000000',
        ];

        $response = $this->actingAs($user)->post(route('finance.analyze'), $data);

        $response->assertStatus(200);
        $response->assertSee('Sehat'); // Status should be Sehat based on input ratios

        $this->assertDatabaseHas('financial_analyses', [
            'user_id' => $user->id,
            'periode' => '2026-06',
            'pemasukan' => 10000000,
        ]);
    }

    public function test_user_can_load_past_financial_analysis(): void
    {
        $user = User::factory()->create();
        
        $analysis = FinancialAnalysis::create([
            'user_id' => $user->id,
            'periode' => '2026-05',
            'pemasukan' => 9000000,
            'kebutuhan_pokok' => 3000000,
            'transportasi' => 500000,
            'cicilan' => 1000000,
            'gaya_hidup' => 1500000,
            'tabungan' => 1000000,
            'investasi' => 1000000,
            'dana_darurat' => 6000000,
            'target_tabungan' => 15000000,
        ]);

        $response = $this->actingAs($user)->get(route('finance.index', ['load_id' => $analysis->id]));
        $response->assertStatus(200);
        $response->assertSee('9.000.000'); // Form should load values
    }

    public function test_user_can_delete_past_financial_analysis(): void
    {
        $user = User::factory()->create();
        
        $analysis = FinancialAnalysis::create([
            'user_id' => $user->id,
            'periode' => '2026-05',
            'pemasukan' => 9000000,
            'kebutuhan_pokok' => 3000000,
            'transportasi' => 500000,
            'cicilan' => 1000000,
            'gaya_hidup' => 1500000,
            'tabungan' => 1000000,
            'investasi' => 1000000,
            'dana_darurat' => 6000000,
            'target_tabungan' => 15000000,
        ]);

        $response = $this->actingAs($user)->delete(route('finance.destroy', $analysis->id));
        $response->assertRedirect(route('finance.index'));
        
        $this->assertDatabaseMissing('financial_analyses', [
            'id' => $analysis->id,
        ]);
    }
}
