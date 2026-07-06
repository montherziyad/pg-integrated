<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('job_title');
            $table->string('department');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->boolean('approval_required')->default(true);
            $table->json('capabilities')->nullable();
            $table->json('guardrails')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });

        DB::table('ai_employees')->insert([
            'name' => 'PG Traffic AI',
            'code' => 'AI_TRAFFIC_COORDINATOR',
            'job_title' => 'AI Traffic Coordinator',
            'department' => 'Traffic & Operations',
            'description' => 'Reviews accepted Outlook intake, client requests, active jobs, deadlines, and workload, then prepares recommendations for human approval.',
            'status' => 'active',
            'approval_required' => true,
            'capabilities' => json_encode([
                'Review validated Outlook intake',
                'Analyze briefs and attachments',
                'Suggest client, project, and category',
                'Suggest responsible employee and priority',
                'Monitor delayed jobs and deadlines',
                'Prepare response drafts',
            ]),
            'guardrails' => json_encode([
                'Never create or modify a job without approval',
                'Never send email or support replies without approval',
                'Never publish client delivery links without approval',
                'Never archive or delete records',
                'Only use approved company data and domains',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_employees');
    }
};
