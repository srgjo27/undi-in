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
        Schema::table('properties', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
            $table->dropColumn(['verification_status', 'verification_notes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Restore verification columns
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('verification_notes')->nullable()->after('verification_status');

            // Drop notes column
            $table->dropColumn('notes');
        });
    }
};
