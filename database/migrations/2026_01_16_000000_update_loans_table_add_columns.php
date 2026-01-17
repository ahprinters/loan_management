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
        Schema::table('loans', function (Blueprint $table) {
            // Only add columns if they don't exist (safe for repeated attempts).
            if (! Schema::hasColumn('loans', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }

            // Optional link to customer (recommended for loan management)
            if (! Schema::hasColumn('loans', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('user_id')->constrained('customers')->nullOnDelete();
            }

            if (! Schema::hasColumn('loans', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0)->after('customer_id');
            }

            if (! Schema::hasColumn('loans', 'interest_rate')) {
                $table->decimal('interest_rate', 5, 2)->default(0)->after('amount'); // e.g. 12.50
            }

            if (! Schema::hasColumn('loans', 'term_months')) {
                $table->unsignedInteger('term_months')->default(0)->after('interest_rate');
            }

            if (! Schema::hasColumn('loans', 'status')) {
                $table->string('status', 20)->default('active')->after('term_months');
            }

            if (! Schema::hasColumn('loans', 'disbursed_at')) {
                $table->date('disbursed_at')->nullable()->after('status');
            }

            if (! Schema::hasColumn('loans', 'due_date')) {
                $table->date('due_date')->nullable()->after('disbursed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Drop FKs first (names can vary across DBs), so we use try/catch style checks.
            if (Schema::hasColumn('loans', 'customer_id')) {
                $table->dropConstrainedForeignId('customer_id');
            }
            if (Schema::hasColumn('loans', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            foreach (['amount', 'interest_rate', 'term_months', 'status', 'disbursed_at', 'due_date'] as $col) {
                if (Schema::hasColumn('loans', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
