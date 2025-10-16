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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'awaiting_verification', 'paid', 'failed', 'cancelled'])->default('pending')->change();

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

            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending')->change();
        });
    }
};
