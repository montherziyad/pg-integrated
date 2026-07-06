<?php

namespace Database\Seeders;

use App\Models\CrmCompany;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrmTestCompanySeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $owner = User::where('email', 'mziyad@pgintegrated.com')->first() ?? User::first();

            $company = CrmCompany::updateOrCreate(
                ['name' => 'Test LinkedIn Prospect'],
                [
                    'industry' => 'Retail & E-commerce',
                    'country' => 'Saudi Arabia',
                    'website' => 'https://example.com',
                    'linkedin_url' => 'https://www.linkedin.com/company/example',
                    'source' => 'LinkedIn Test',
                    'status' => 'new',
                    'lead_score' => 40,
                    'expected_value' => 120000,
                    'owner_id' => $owner?->id,
                    'last_contacted_at' => now(),
                    'next_follow_up_at' => now()->addDays(3),
                ]
            );

            $contact = $company->contacts()->updateOrCreate(
                ['email' => 'sara.ahmed@example.com'],
                [
                    'name' => 'Sara Ahmed',
                    'position' => 'Marketing Manager',
                    'phone' => '+966500000000',
                    'whatsapp' => '+966500000000',
                    'is_primary' => true,
                ]
            );

            $company->activities()->firstOrCreate(
                ['summary' => 'Initial LinkedIn research'],
                [
                    'contact_id' => $contact->id,
                    'user_id' => $owner?->id,
                    'type' => 'research',
                    'channel' => 'linkedin',
                    'body' => 'Test company added to review the complete CRM journey. Verify all company information before real outreach.',
                    'activity_at' => now(),
                    'meta' => ['test_data' => true],
                ]
            );

            $company->tasks()->firstOrCreate(
                ['title' => 'Contact marketing manager'],
                [
                    'assigned_to' => $owner?->id,
                    'description' => 'Review the test lead, verify company details, and prepare an approved first contact.',
                    'status' => 'pending',
                    'due_at' => now()->addDays(3),
                ]
            );

            $this->command?->info("CRM test company ready: /crm/{$company->id}");
        });
    }
}
