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
        Schema::create('creative_jobs', function (Blueprint $table) {
    $table->id();

    $table->string('job_number')->unique();

    $table->foreignId('client_id')
        ->constrained('clients');

    $table->foreignId('project_id')
        ->nullable()
        ->constrained('projects')
        ->nullOnDelete();

    $table->foreignId('job_category_id')
        ->nullable()
        ->constrained('job_categories')
        ->nullOnDelete();

    $table->foreignId('job_status_id')
        ->nullable()
        ->constrained('job_statuses')
        ->nullOnDelete();

    $table->foreignId('traffic_manager_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('project_manager_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->string('title');
    $table->text('brief')->nullable();

    $table->enum('priority', [
        'LOW',
        'MEDIUM',
        'HIGH',
        'URGENT',
        'CRITICAL'
    ])->default('MEDIUM');

    $table->timestamp('received_at')->nullable();
    $table->timestamp('first_draft_due_at')->nullable();
    $table->timestamp('final_due_at')->nullable();
    $table->timestamp('first_draft_sent_at')->nullable();
    $table->timestamp('final_delivered_at')->nullable();

    $table->decimal('estimated_hours', 8, 2)->default(0);
    $table->decimal('actual_hours', 8, 2)->default(0);

    $table->integer('revision_count')->default(0);
    $table->integer('reopened_count')->default(0);
    $table->integer('completion_percentage')->default(0);

    $table->text('internal_notes')->nullable();
    $table->text('client_notes')->nullable();

    $table->string('nas_folder_path')->nullable();
    $table->string('dropbox_folder_path')->nullable();
    $table->string('final_delivery_path')->nullable();

    $table->boolean('is_archived')->default(false);
    $table->timestamp('archived_at')->nullable();

    $table->foreignId('created_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('creative_jobs');
    }
};
