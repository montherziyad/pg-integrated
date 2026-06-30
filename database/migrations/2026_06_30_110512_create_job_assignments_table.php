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
        Schema::create('job_assignments', function (Blueprint $table) {
    $table->id();

    $table->foreignId('creative_job_id')
        ->constrained('creative_jobs')
        ->cascadeOnDelete();

    $table->foreignId('team_id')
        ->nullable()
        ->constrained('teams')
        ->nullOnDelete();

    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('assigned_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('assigned_at')->nullable();

    $table->decimal('estimated_hours', 8, 2)->default(0);
    $table->decimal('actual_hours', 8, 2)->default(0);

    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->enum('status', [
        'ASSIGNED',
        'IN_PROGRESS',
        'COMPLETED',
        'ON_HOLD',
        'CANCELLED'
    ])->default('ASSIGNED');

    $table->text('notes')->nullable();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('job_assignments');
    }
};
