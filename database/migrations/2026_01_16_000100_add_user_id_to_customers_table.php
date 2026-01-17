<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Keep nullable for smooth upgrade on existing databases.
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('user_id');
        });

        // Best-effort backfill: assign legacy customers to the first existing user.
        $firstUserId = DB::table('users')->min('id');
        if ($firstUserId) {
            DB::table('customers')->whereNull('user_id')->update(['user_id' => $firstUserId]);
        }

        // If there were no users yet, user_id stays null; new customers will be saved with user_id.
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
