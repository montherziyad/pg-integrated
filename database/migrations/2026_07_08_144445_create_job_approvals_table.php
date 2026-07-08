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
        Schema::create('job_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creative_job_id')->constrained('creative_jobs')->cascadeOnDelete();
            $table->foreignId('workflow_stage_id')->constrained('workflow_stages')->cascadeOnDelete();
            $table->foreignId('assigned_to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role')->comment('Role required for approval: traffic_manager, project_leader, client_service, etc');
            $table->enum('status', ['pending', 'approved', 'rejected', 'commented'])->default('pending');
            $table->text('comments')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->integer('approval_order')->default(0)->comment('Order of approval in the workflow');
            $table->boolean('is_required')->default(true);
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['creative_job_id', 'workflow_stage_id']);
            $table->index(['status', 'assigned_to_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_approvals');
    }
};
