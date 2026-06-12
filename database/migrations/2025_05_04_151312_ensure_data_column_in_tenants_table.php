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
        Schema::table('tenants', function (Blueprint $table) {
            // Check if data column exists
            if (!Schema::hasColumn('tenants', 'data')) {
                $table->json('data')->nullable();
            } else {
                // If it exists but is not JSON type, modify it
                $table->json('data')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to drop the data column in the down migration
        // as it might contain important information
    }
};
