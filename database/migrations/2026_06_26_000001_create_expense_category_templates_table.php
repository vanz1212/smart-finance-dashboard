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
        Schema::create('expense_category_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Profesional Muda", "Keluarga"
            $table->text('description')->nullable();
            $table->json('categories'); // Array of {name, ratio_percent, is_debt}
            $table->string('type')->default('general'); // general, minimal, premium
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('expense_category_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category_name');
            $table->decimal('recommended_amount', 15, 2);
            $table->decimal('actual_amount', 15, 2);
            $table->string('status'); // ok, warning, critical
            $table->text('reason')->nullable();
            $table->string('periode');
            $table->timestamps();

            $table->unique(['user_id', 'periode', 'category_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_category_recommendations');
        Schema::dropIfExists('expense_category_templates');
    }
};
