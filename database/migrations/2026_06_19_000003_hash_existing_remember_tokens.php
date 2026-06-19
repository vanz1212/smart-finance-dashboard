<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->select(['id', 'remember_token'])
            ->whereNotNull('remember_token')
            ->orderBy('id')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $token = (string) $user->remember_token;

                    if ($token === '') {
                        continue;
                    }

                    if (password_get_info($token)['algoName'] !== 'unknown') {
                        continue;
                    }

                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['remember_token' => Hash::make($token)]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
