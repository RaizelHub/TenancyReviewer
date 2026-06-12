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
        // Modify the enum to add 'announcement' type
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM('assignment', 'material', 'announcement') DEFAULT 'assignment'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'announcement' from the enum
        DB::statement("ALTER TABLE activities MODIFY COLUMN type ENUM('assignment', 'material') DEFAULT 'assignment'");
    }
};
