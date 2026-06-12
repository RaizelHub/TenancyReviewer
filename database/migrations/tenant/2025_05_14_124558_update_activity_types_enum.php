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
        // First, convert any existing 'question' type activities to 'assignment'
        DB::statement("UPDATE activities SET type = 'assignment' WHERE type = 'question'");

        // Then modify the enum to remove 'question'
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM('assignment', 'material') DEFAULT 'assignment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add 'question' back to the enum
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM('assignment', 'material', 'question') DEFAULT 'assignment'");
    }
};
