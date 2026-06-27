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
            // Separate current savings balance from monthly savings deposit
            $table->decimal('saldo_tabungan', 15, 2)->nullable()->after('tabungan');
            $table->decimal('setoran_tabungan', 15, 2)->nullable()->after('saldo_tabungan');
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
            $table->dropColumn(['saldo_tabungan', 'setoran_tabungan']);
        });
    }
};
