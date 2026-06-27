<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('periode');
            
            // Financial inputs
            $table->decimal('pemasukan', 15, 2);
            $table->decimal('kebutuhan_pokok', 15, 2);
            $table->decimal('transportasi', 15, 2);
            $table->decimal('cicilan', 15, 2);
            $table->decimal('gaya_hidup', 15, 2);
            $table->decimal('tabungan', 15, 2);
            $table->decimal('investasi', 15, 2);
            $table->decimal('dana_darurat', 15, 2);
            $table->decimal('target_tabungan', 15, 2)->nullable();

            $table->timestamps();

            // Set unique constraint so each user has only one analysis per period
            $table->unique(['user_id', 'periode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_analyses');
    }
};
