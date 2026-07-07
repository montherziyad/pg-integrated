<?php

namespace App\Support;

class RoleScreenPermissions
{
    public const ADMIN_ROLE_CODES = [
        'SUPER_ADMIN',
        'GENERAL_MANAGER',
    ];

    public static function groups(): array
    {
        return [
            'Operations' => [
                'dashboard' => ['label' => 'Dashboard', 'description' => 'Main operations overview and alerts.'],
                'email_intake' => ['label' => 'Email Intake', 'description' => 'Outlook intake, review, accept, and reject emails.'],
                'traffic_board' => ['label' => 'Traffic Board', 'description' => 'Traffic workflow and stage overview.'],
                'jobs' => ['label' => 'Jobs', 'description' => 'Create, edit, assign, and follow production jobs.'],
                'deliveries' => ['label' => 'Delivery / Handover', 'description' => 'Final delivery links, client service review, and handover.'],
                'team_workload' => ['label' => 'Team Workload', 'description' => 'Employee workload, availability, and assigned jobs.'],
                'employee_leaves' => ['label' => 'Employee Leaves', 'description' => 'Leave requests, approvals, and availability checks.'],
                'employee_handover' => ['label' => 'Employee Handover', 'description' => 'Assigned employees submit files and delivery links to traffic for review.'],
                'archive' => ['label' => 'Archive', 'description' => 'Archived jobs, assets, and completed delivery records.'],
                'reports' => ['label' => 'Reports', 'description' => 'Operational reports and summaries.'],
            ],
            'Clients' => [
                'clients' => ['label' => 'Clients', 'description' => 'Client profiles, account ownership, and company details.'],
                'projects' => ['label' => 'Projects', 'description' => 'Client projects and project setup.'],
                'client_requests' => ['label' => 'Client Requests', 'description' => 'Client portal requests, briefs, quotation requests, and follow-up.'],
                'crm' => ['label' => 'CRM', 'description' => 'Leads, companies, activities, and opportunities.'],
                'support' => ['label' => 'Customer Support', 'description' => 'Support tickets and customer replies.'],
            ],
            'Website' => [
                'website_pages' => ['label' => 'Website Pages', 'description' => 'Public website CMS pages and content.'],
                'careers' => ['label' => 'Careers / Join Us', 'description' => 'Published jobs and career applications.'],
                'website_chat' => ['label' => 'Website Chat', 'description' => 'Website assistant conversations and knowledge review.'],
            ],
            'Growth' => [
                'ai_employee' => ['label' => 'AI Employee', 'description' => 'AI employee actions, approvals, and suggestions.'],
                'ai_workspace' => ['label' => 'AI Workspace', 'description' => 'Assistant workspace and drafts.'],
                'marketing' => ['label' => 'Marketing', 'description' => 'Marketing campaigns, approvals, and outreach.'],
            ],
            'System' => [
                'users' => ['label' => 'Users / Employees', 'description' => 'Employee accounts, roles, teams, and activation.'],
                'teams' => ['label' => 'Teams', 'description' => 'Team definitions and structure.'],
                'roles' => ['label' => 'Roles & Permissions', 'description' => 'Role setup and screen access permissions.'],
                'branches' => ['label' => 'Branches', 'description' => 'Company branches and locations.'],
                'categories' => ['label' => 'Categories', 'description' => 'Job categories and subcategories.'],
                'settings' => ['label' => 'Settings', 'description' => 'System settings, Outlook, domains, and cache.'],
                'events' => ['label' => 'Events', 'description' => 'Company calendar events and role-based visibility.'],
            ],
        ];
    }

    public static function keys(): array
    {
        return collect(self::groups())
            ->flatMap(fn (array $screens) => array_keys($screens))
            ->values()
            ->all();
    }

    public static function labels(): array
    {
        return collect(self::groups())
            ->flatMap(fn (array $screens) => collect($screens)->map(fn ($screen) => $screen['label']))
            ->all();
    }

    public static function sanitize(?array $permissions): array
    {
        $allowed = array_flip(self::keys());

        return collect($permissions ?? [])
            ->filter(fn ($value, $key) => isset($allowed[$key]) && (bool) $value)
            ->map(fn () => true)
            ->all();
    }

    public static function allEnabled(): array
    {
        return collect(self::keys())->mapWithKeys(fn (string $key) => [$key => true])->all();
    }

    public static function defaultsForRole(?string $roleCode): array
    {
        $roleCode = strtoupper((string) $roleCode);

        if (in_array($roleCode, self::ADMIN_ROLE_CODES, true)) {
            return self::allEnabled();
        }

        $defaults = match ($roleCode) {
            'OPERATIONS_MANAGER' => [
                'dashboard', 'email_intake', 'traffic_board', 'jobs', 'deliveries', 'team_workload',
                'employee_leaves', 'archive', 'reports', 'clients', 'projects', 'client_requests',
                'support', 'categories', 'events',
            ],
            'TRAFFIC_MANAGER' => [
                'dashboard', 'email_intake', 'traffic_board', 'jobs', 'deliveries', 'team_workload',
                'employee_leaves', 'archive', 'reports', 'projects', 'client_requests', 'categories', 'events',
            ],
            'ACCOUNT_MANAGER', 'CLIENT_SERVICE_MANAGER' => [
                'dashboard', 'email_intake', 'jobs', 'deliveries', 'clients', 'projects', 'client_requests',
                'crm', 'support', 'reports', 'events',
            ],
            'HR' => [
                'dashboard', 'employee_leaves', 'users', 'teams', 'roles', 'branches', 'careers', 'events', 'reports',
            ],
            'DESIGNER', 'SENIOR_DESIGNER', 'CONTENT_WRITER', 'MOTION_DESIGNER', 'QA_REVIEWER' => [
                'dashboard', 'jobs', 'employee_handover', 'employee_leaves', 'events',
            ],
            'ARCHIVE_OFFICER' => [
                'dashboard', 'jobs', 'deliveries', 'employee_handover', 'archive', 'reports', 'employee_leaves', 'events',
            ],
            default => ['dashboard', 'jobs', 'employee_handover', 'employee_leaves', 'events'],
        };

        return collect($defaults)->mapWithKeys(fn (string $key) => [$key => true])->all();
    }


    public static function screenForRouteName(?string $routeName): ?string
    {
        if (! $routeName) {
            return null;
        }

        $map = [
            'dashboard' => 'dashboard',
            'admin.index' => 'dashboard',
            'email-intakes.' => 'email_intake',
            'traffic.' => 'traffic_board',
            'jobs.' => 'jobs',
            'handovers.' => 'employee_handover',
            'deliveries.' => 'deliveries',
            'workload.' => 'team_workload',
            'employee-leaves.' => 'employee_leaves',
            'archive.' => 'archive',
            'reports.' => 'reports',
            'admin.clients.' => 'clients',
            'admin.projects.' => 'projects',
            'admin.client-requests.' => 'client_requests',
            'crm.' => 'crm',
            'support.' => 'support',
            'admin.cms.' => 'website_pages',
            'admin.careers.' => 'careers',
            'admin.career-applications.' => 'careers',
            'admin.website-chat.' => 'website_chat',
            'ai-employee.' => 'ai_employee',
            'ai.' => 'ai_workspace',
            'marketing.' => 'marketing',
            'admin.users.' => 'users',
            'admin.teams.' => 'teams',
            'admin.roles.' => 'roles',
            'admin.branches.' => 'branches',
            'admin.categories.' => 'categories',
            'admin.settings.' => 'settings',
            'admin.events.' => 'events',
        ];

        foreach ($map as $prefix => $screen) {
            if ($routeName === $prefix || str_starts_with($routeName, $prefix)) {
                return $screen;
            }
        }

        return null;
    }

}
