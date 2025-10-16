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
            // Remove Midtrans-specific columns that are no longer needed
            $table->dropColumn([
                'payment_gateway',
                'payment_token',
                'gateway_order_id',
                'payment_url',
                'token_created_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Restore Midtrans columns if rollback is needed
            $table->string('payment_gateway')->nullable()->after('status');
            $table->string('payment_token')->nullable()->after('payment_gateway');
            $table->string('gateway_order_id')->nullable()->after('payment_token');
            $table->text('payment_url')->nullable()->after('gateway_order_id');
            $table->timestamp('token_created_at')->nullable()->after('paid_at');
        });
    }
};
