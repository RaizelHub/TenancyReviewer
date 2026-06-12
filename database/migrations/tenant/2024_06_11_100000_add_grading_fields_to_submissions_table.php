<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('submissions', 'grade')) {
                $table->float('grade')->nullable()->after('status');
            }
            if (!Schema::hasColumn('submissions', 'feedback')) {
                $table->text('feedback')->nullable()->after('grade');
            }
            // Add more grading-related fields here if needed
        });
    }

    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (Schema::hasColumn('submissions', 'grade')) {
                $table->dropColumn('grade');
            }
            if (Schema::hasColumn('submissions', 'feedback')) {
                $table->dropColumn('feedback');
            }
            // Drop more grading-related fields here if needed
        });
    }
}; 