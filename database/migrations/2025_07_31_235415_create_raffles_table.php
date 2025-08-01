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
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->unique()->constrained()->onDelete('cascade');
            $table->timestamp('draw_date');
            $table->foreignId('winning_coupon_id')->nullable()->constrained('coupons')->onDelete('set null');
            $table->foreignId('drawn_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raffles');
    }
};
