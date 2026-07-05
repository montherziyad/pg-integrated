<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_project_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('type')->default('project');
            $table->string('title');
            $table->text('brief')->nullable();
            $table->string('target_country')->nullable();
            $table->date('desired_launch_date')->nullable();
            $table->string('budget_range')->nullable();
            $table->string('priority')->default('normal');
            $table->string('status')->default('new');
            $table->json('deliverables')->nullable();
            $table->json('attachments')->nullable();
            $table->json('external_links')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_project_requests');
    }
};
