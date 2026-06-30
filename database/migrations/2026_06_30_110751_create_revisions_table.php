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
        Schema::create('revisions', function (Blueprint $table) {
    $table->id();

    $table->foreignId('creative_job_id')
        ->constrained('creative_jobs')
        ->cascadeOnDelete();

    $table->integer('revision_number')->default(1);

    $table->foreignId('requested_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->text('request_notes')->nullable();
    $table->text('internal_notes')->nullable();

    $table->timestamp('requested_at')->nullable();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->decimal('estimated_hours', 8, 2)->default(0);
    $table->decimal('actual_hours', 8, 2)->default(0);

    $table->enum('status', [
        'OPEN',
        'IN_PROGRESS',
        'COMPLETED',
        'CANCELLED'
    ])->default('OPEN');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};
