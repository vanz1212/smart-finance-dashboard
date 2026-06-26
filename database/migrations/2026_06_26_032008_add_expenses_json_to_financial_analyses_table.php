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
    public function up()
    {
        Schema::table('financial_analyses', function (Blueprint $table) {
            // Stores dynamic expense categories as [{name, amount, is_debt}]
            // When present, calculateResults() prefers this over fixed expense columns
            $table->json('expenses_json')->nullable()->after('target_tabungan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_analyses', function (Blueprint $table) {
            $table->dropColumn('expenses_json');
        });
    }
};
