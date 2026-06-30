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
        Schema::create('job_activities', function (Blueprint $table) {

            $table->id();

            $table->foreignId('creative_job_id')
                ->constrained('creative_jobs')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('activity');

            $table->string('activity_type')->default('SYSTEM');
            // SYSTEM
            // USER
            // EMAIL
            // AI
            // API

            $table->text('description')->nullable();

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip_address')->nullable();

            $table->string('device')->nullable();

            $table->timestamp('activity_at')->useCurrent();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_activities');
    }
};