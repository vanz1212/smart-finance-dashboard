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
        Schema::create('financial_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "Liburan ke Bali", "Beli Motor"
            $table->text('description')->nullable();
            $table->enum('category', ['tabungan', 'investasi', 'asuransi', 'properti', 'pendidikan', 'lainnya'])->default('tabungan');
            
            // Financial details
            $table->decimal('target_amount', 15, 2); // Target nominal
            $table->decimal('current_amount', 15, 2)->default(0); // Amount collected so far
            $table->date('target_date'); // Deadline/target date
            
            // Recommendation
            $table->decimal('recommended_monthly', 15, 2)->nullable(); // Auto-calculated recommended setoran
            $table->decimal('actual_monthly', 15, 2)->nullable(); // User's actual setoran
            
            // Status
            $table->enum('status', ['active', 'completed', 'paused', 'abandoned'])->default('active');
            $table->integer('priority')->default(0); // 1=high, 2=medium, 3=low
            
            $table->timestamps();
        });

        Schema::create('financial_target_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_target_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_target_deposits');
        Schema::dropIfExists('financial_targets');
    }
};
