<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Client;
use App\Models\CreativeJob;
use App\Models\JobCategory;
use App\Models\JobStatus;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkflowStage;
use Illuminate\Database\Seeder;

class ClientPortalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::query()->first();
        $manager = User::query()->first();
        $category = JobCategory::query()->first();
        $status = JobStatus::query()->orderBy('sort_order')->first();
        $stages = WorkflowStage::query()->orderBy('sort_order')->get()->values();

        Client::query()
            ->where('email', 'demo@pgintegrated.com')
            ->where('client_code', '!=', 'PG-DEMO')
            ->get()
            ->each(function (Client $client): void {
                $client->forceFill([
                    'email' => "demo-archived-{$client->id}@pgintegrated.local",
                    'portal_enabled' => false,
                ])->save();
            });

        $client = Client::query()->updateOrCreate(
            ['client_code' => 'PG-DEMO'],
            [
                'name' => 'PG Integrated Demo Client',
                'company_name' => 'Demo Retail Company',
                'contact_person' => 'Demo Client Manager',
                'branch_id' => $branch?->id,
                'account_manager_id' => $manager?->id,
                'industry' => 'Retail & FMCG',
                'country' => 'Saudi Arabia',
                'city' => 'Jeddah',
                'website' => 'https://pgintegrated.com',
                'email' => 'demo@pgintegrated.com',
                'password' => 'Client@12345',
                'phone' => '+966500000000',
                'company_profile' => 'Demo account for reviewing the client journey, projects, jobs, requests, attachments, and campaign calendar.',
                'is_active' => true,
                'portal_enabled' => true,
            ],
        );

        $brandLaunch = Project::query()->updateOrCreate(
            ['project_code' => 'PG-DEMO-001'],
            [
                'client_id' => $client->id,
                'name' => 'Brand Launch Campaign',
                'description' => 'Client journey demo project covering strategy, key visual, adaptation, and delivery.',
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'project_manager_id' => $manager?->id,
                'is_active' => true,
            ],
        );

        $alwaysOn = Project::query()->updateOrCreate(
            ['project_code' => 'PG-DEMO-002'],
            [
                'client_id' => $client->id,
                'name' => 'Monthly Digital Content',
                'description' => 'Ongoing monthly social and digital content package for client portal review.',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addMonths(2)->toDateString(),
                'project_manager_id' => $manager?->id,
                'is_active' => true,
            ],
        );

        $jobs = [
            [
                'job_number' => 'PG-DEMO-JOB-001',
                'project' => $brandLaunch,
                'title' => 'Campaign Strategy & Brand Narrative',
                'brief' => 'Define the communication strategy, brand story, and campaign direction.',
                'priority' => 'HIGH',
                'completion_percentage' => 80,
                'stage_index' => 3,
                'client_notes' => 'Strategy route shared for client review.',
                'first_draft_due_at' => now()->subDays(2),
                'final_due_at' => now()->addDays(4),
            ],
            [
                'job_number' => 'PG-DEMO-JOB-002',
                'project' => $brandLaunch,
                'title' => 'Key Visual Design',
                'brief' => 'Develop main campaign key visual and visual system.',
                'priority' => 'URGENT',
                'completion_percentage' => 55,
                'stage_index' => 2,
                'client_notes' => 'Creative team preparing first visual options.',
                'first_draft_due_at' => now()->addDays(1),
                'final_due_at' => now()->addDays(8),
            ],
            [
                'job_number' => 'PG-DEMO-JOB-003',
                'project' => $alwaysOn,
                'title' => 'July Social Media Content',
                'brief' => 'Create monthly content calendar and design posts for social channels.',
                'priority' => 'MEDIUM',
                'completion_percentage' => 35,
                'stage_index' => 1,
                'client_notes' => 'Calendar structure is in progress.',
                'first_draft_due_at' => now()->addDays(3),
                'final_due_at' => now()->addDays(12),
            ],
        ];

        foreach ($jobs as $job) {
            CreativeJob::query()->updateOrCreate(
                ['job_number' => $job['job_number']],
                [
                    'client_id' => $client->id,
                    'project_id' => $job['project']->id,
                    'job_category_id' => $category?->id,
                    'job_status_id' => $status?->id,
                    'current_workflow_stage_id' => $stages->get($job['stage_index'])?->id ?? $stages->last()?->id,
                    'traffic_manager_id' => $manager?->id,
                    'project_manager_id' => $manager?->id,
                    'title' => $job['title'],
                    'brief' => $job['brief'],
                    'priority' => $job['priority'],
                    'received_at' => now()->subDays(7),
                    'first_draft_due_at' => $job['first_draft_due_at'],
                    'final_due_at' => $job['final_due_at'],
                    'estimated_hours' => 18,
                    'actual_hours' => 7,
                    'revision_count' => 1,
                    'completion_percentage' => $job['completion_percentage'],
                    'client_notes' => $job['client_notes'],
                    'dropbox_folder_path' => 'https://www.dropbox.com/',
                    'final_delivery_path' => $job['completion_percentage'] >= 80 ? 'https://wetransfer.com/' : null,
                    'is_archived' => false,
                    'created_by' => $manager?->id,
                ],
            );
        }
    }
}
