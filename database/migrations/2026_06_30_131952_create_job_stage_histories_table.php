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
        Schema::create('job_stage_histories', function (Blueprint $table) {

            $table->id();

            $table->foreignId('creative_job_id')
                ->constrained('creative_jobs')
                ->cascadeOnDelete();

            $table->foreignId('workflow_stage_id')
                ->constrained('workflow_stages')
                ->cascadeOnDelete();

            $table->foreignId('entered_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('entered_at')->nullable();

            $table->timestamp('left_at')->nullable();

            $table->decimal('duration_hours', 8, 2)->default(0);

            $table->text('notes')->nullable();

            $table->boolean('is_current')->default(false);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_stage_histories');
    }
};