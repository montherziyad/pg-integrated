<?php

use App\Models\Client;
use App\Models\ClientProjectRequest;
use App\Models\CreativeJob;
use App\Models\Project;

it('renders separate employee and client login pages', function () {
    $this->get(route('employee.login'))
        ->assertOk()
        ->assertSee('Employee login');

    $this->get(route('client.login'))
        ->assertOk()
        ->assertSee('Client sign in');
});

it('allows an enabled client to access only its portal data', function () {
    $client = Client::create([
        'client_code' => 'CLIENT-001',
        'name' => 'Portal Client',
        'email' => 'client@example.com',
        'password' => 'secure-password',
        'is_active' => true,
        'portal_enabled' => true,
    ]);

    $otherClient = Client::create([
        'client_code' => 'CLIENT-002',
        'name' => 'Other Client',
        'email' => 'other@example.com',
        'password' => 'secure-password',
        'is_active' => true,
        'portal_enabled' => true,
    ]);

    $project = Project::create([
        'project_code' => 'PROJECT-001',
        'client_id' => $client->id,
        'name' => 'Visible Project',
        'is_active' => true,
    ]);

    CreativeJob::create([
        'job_number' => 'JOB-001',
        'client_id' => $client->id,
        'project_id' => $project->id,
        'title' => 'Visible Job',
    ]);

    CreativeJob::create([
        'job_number' => 'JOB-002',
        'client_id' => $otherClient->id,
        'title' => 'Hidden Job',
    ]);

    $this->post(route('client.login.store'), [
        'email' => 'client@example.com',
        'password' => 'secure-password',
    ])->assertRedirect(route('client.portal'));

    $this->assertAuthenticatedAs($client, 'client');

    $this->get(route('client.portal'))
        ->assertOk()
        ->assertSee('Visible Project')
        ->assertSee('Visible Job')
        ->assertDontSee('Hidden Job');
});

it('rejects clients whose portal access is disabled', function () {
    Client::create([
        'client_code' => 'CLIENT-003',
        'name' => 'Disabled Client',
        'email' => 'disabled@example.com',
        'password' => 'secure-password',
        'is_active' => true,
        'portal_enabled' => false,
    ]);

    $this->post(route('client.login.store'), [
        'email' => 'disabled@example.com',
        'password' => 'secure-password',
    ])->assertSessionHasErrors('email');

    $this->assertGuest('client');
});

it('lets a client submit a new project request that appears for admins', function () {
    $client = Client::create([
        'client_code' => 'CLIENT-004',
        'name' => 'Request Client',
        'email' => 'request@example.com',
        'password' => 'secure-password',
        'is_active' => true,
        'portal_enabled' => true,
    ]);

    $this->actingAs($client, 'client')
        ->post(route('client.requests.store'), [
            'type' => 'campaign',
            'title' => 'National Day Campaign',
            'brief' => 'We need a campaign brief.',
            'target_country' => 'Saudi Arabia',
            'deliverables' => ['Key visual', 'Social posts'],
            'external_links' => ['https://wetransfer.com/example'],
        ])
        ->assertRedirect(route('client.projects'));

    expect(ClientProjectRequest::query()->where('title', 'National Day Campaign')->exists())->toBeTrue();

    $this->actingAs(\App\Models\User::factory()->create())
        ->get(route('admin.client-requests.index'))
        ->assertOk()
        ->assertSee('National Day Campaign');
});
