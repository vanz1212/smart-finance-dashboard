<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tax_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('tahun_pajak')->default(2024);
            $table->decimal('penghasilan_bulanan', 15, 2)->default(0);
            $table->decimal('penghasilan_tidak_teratur', 15, 2)->default(0); // THR/Bonus
            $table->decimal('biaya_jabatan', 15, 2)->default(0);
            $table->decimal('iuran_pensiun', 15, 2)->default(0);
            $table->decimal('zakat', 15, 2)->default(0);
            $table->decimal('kredit_pajak', 15, 2)->default(0);
            $table->string('status_wajib_pajak');
            $table->string('metode_perhitungan')->default('ter'); // 'ter' or 'tahunan'
            $table->decimal('estimasi_pajak', 15, 2)->default(0);
            $table->json('hasil_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_analyses');
    }
};
