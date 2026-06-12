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
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'plan')) {
                $table->string('plan')->default('basic')->after('notes');
            }
            if (!Schema::hasColumn('students', 'password')) {
                $table->string('password')->nullable()->after('plan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'plan')) {
                $table->dropColumn('plan');
            }
            if (Schema::hasColumn('students', 'password')) {
                $table->dropColumn('password');
            }
        });
    }
};
