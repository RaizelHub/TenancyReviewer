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
        Schema::create('tenant_applications', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('full_name');
            $table->string('email');
            $table->string('domain_name')->unique();
            $table->string('contact');
            $table->string('subscription_plan');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // Default to pending for admin approval
            $table->timestamp('reviewed_at')->nullable();
            $table->uuid('tenant_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_applications');
    }
};
