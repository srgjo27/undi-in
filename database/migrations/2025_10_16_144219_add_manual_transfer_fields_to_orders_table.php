<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        DB::statement("ALTER TABLE orders ALTER COLUMN status TYPE VARCHAR(255)");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending', 'awaiting_verification', 'paid', 'failed', 'cancelled'))");

        Schema::table('orders', function (Blueprint $table) {
            $table->string('transfer_proof')->nullable()->after('payment_url');
            $table->json('seller_bank_info')->nullable()->after('transfer_proof');
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('seller_bank_info');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('verification_notes')->nullable()->after('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'transfer_proof',
                'seller_bank_info',
                'verified_by',
                'verified_at',
                'verification_notes'
            ]);
        });

        // Revert the status enum to the original values
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_status_check");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status IN ('pending', 'paid', 'failed', 'cancelled'))");
    }
};
