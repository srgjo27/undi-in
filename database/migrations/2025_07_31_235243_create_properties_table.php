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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('address');
            $table->string('city');
            $table->string('province');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('land_area');
            $table->integer('building_area');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->json('facilities')->nullable();
            $table->decimal('coupon_price', 15, 2);
            $table->integer('max_coupons')->nullable();
            $table->timestamp('sale_start_date');
            $table->timestamp('sale_end_date');
            $table->enum('status', ['draft', 'active', 'pending_draw', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
