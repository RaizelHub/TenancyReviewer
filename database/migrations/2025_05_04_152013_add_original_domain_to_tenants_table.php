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
            // Add original_domain column if it doesn't exist
            if (!Schema::hasColumn('tenants', 'original_domain')) {
                $table->string('original_domain')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Drop the original_domain column if it exists
            if (Schema::hasColumn('tenants', 'original_domain')) {
                $table->dropColumn('original_domain');
            }
        });
    }
};
