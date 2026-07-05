<?php

use App\Models\User;

it('renders each main application page for an authenticated employee', function (string $routeName) {
    $this->actingAs(User::factory()->create())
        ->get(route($routeName))
        ->assertOk();
})->with([
    'dashboard' => 'dashboard',
    'administration' => 'admin.index',
    'users' => 'admin.users.index',
    'new user' => 'admin.users.create',
    'teams' => 'admin.teams.index',
    'new team' => 'admin.teams.create',
    'roles' => 'admin.roles.index',
    'new role' => 'admin.roles.create',
    'branches' => 'admin.branches.index',
    'new branch' => 'admin.branches.create',
    'clients' => 'admin.clients.index',
    'new client' => 'admin.clients.create',
    'projects' => 'admin.projects.index',
    'new project' => 'admin.projects.create',
    'categories' => 'admin.categories.index',
    'new category' => 'admin.categories.create',
    'settings' => 'admin.settings.index',
    'jobs' => 'jobs.index',
    'new job' => 'jobs.create',
    'email intake' => 'email-intakes.index',
    'traffic board' => 'traffic.index',
    'team workload' => 'workload.index',
    'archive' => 'archive.index',
    'archived assets' => 'archive.assets',
    'reports' => 'reports.index',
    'profile' => 'profile.edit',
]);
