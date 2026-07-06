<?php

namespace App\Http\Controllers;

use App\Models\ClientProjectRequest;
use App\Models\CreativeJob;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientPortalController extends Controller
{
    public function index(Request $request): View
    {
        $client = $request->user('client')->loadMissing(['accountManager', 'clientServiceUsers']);

        return view('client-portal.dashboard', [
            'client' => $client,
            'projects' => $client->projects()
                ->withCount('jobs')
                ->latest()
                ->get(),
            'jobs' => CreativeJob::query()
                ->whereBelongsTo($client)
                ->with(['project', 'currentWorkflowStage', 'responsibleUser'])
                ->latest()
                ->get(),
            'projectRequests' => $client->projectRequests()->latest()->take(6)->get(),
            'calendarEvents' => $this->calendarEvents($client->country ?: 'Saudi Arabia'),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('client-portal.profile', [
            'client' => $request->user('client'),
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $client = $request->user('client');

        $data = $request->validate([
            'contact_person' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'company_profile' => ['nullable', 'string', 'max:2000'],
            'avatar' => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = Storage::url($request->file('avatar')->store('client-profiles', 'public'));
        }

        $client->update($data);

        return back()->with('status', 'Profile updated.');
    }

    public function projects(Request $request): View
    {
        $client = $request->user('client');

        return view('client-portal.projects', [
            'client' => $client,
            'projects' => $client->projects()->withCount('jobs')->latest()->get(),
            'jobs' => CreativeJob::query()
                ->whereBelongsTo($client)
                ->with(['project', 'currentWorkflowStage', 'assets', 'responsibleUser'])
                ->latest()
                ->get(),
            'projectRequests' => $client->projectRequests()->latest()->get(),
        ]);
    }

    public function createRequest(Request $request): View
    {
        $client = $request->user('client');

        return view('client-portal.request', [
            'client' => $client,
            'projects' => $client->projects()->orderBy('name')->get(),
        ]);
    }

    public function storeRequest(Request $request): RedirectResponse
    {
        $client = $request->user('client');

        $data = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'service_name' => ['required', 'string', 'max:180'],
            'type' => ['required', Rule::in(['brief', 'campaign', 'project'])],
            'title' => ['required', 'string', 'max:255'],
            'brief' => ['nullable', 'string', 'max:5000'],
            'target_country' => ['nullable', 'string', 'max:255'],
            'desired_launch_date' => ['nullable', 'date'],
            'budget_range' => ['nullable', 'string', 'max:255'],
            'priority' => ['nullable', 'string', 'max:60'],
            'deliverables' => ['nullable', 'array'],
            'deliverables.*' => ['nullable', 'string', 'max:255'],
            'external_links' => ['nullable', 'array'],
            'external_links.*' => ['nullable', 'string', 'max:1000'],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:51200'],
        ]);

        if (! empty($data['project_id']) && ! $client->projects()->whereKey($data['project_id'])->exists()) {
            abort(403);
        }

        $attachments = [];
        foreach ($request->file('attachments', []) as $file) {
            $path = $file->store('client-requests', 'public');
            $attachments[] = [
                'name' => $file->getClientOriginalName(),
                'url' => Storage::url($path),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ];
        }

        ClientProjectRequest::create([
            'request_number' => 'CPR-'.now()->format('YmdHis').'-'.$client->id,
            'client_id' => $client->id,
            'project_id' => $data['project_id'] ?? null,
            'service_name' => $data['service_name'],
            'type' => $data['type'],
            'title' => $data['title'],
            'brief' => $data['brief'] ?? null,
            'target_country' => $data['target_country'] ?? null,
            'desired_launch_date' => $data['desired_launch_date'] ?? null,
            'budget_range' => $data['budget_range'] ?? null,
            'priority' => $data['priority'] ?? 'normal',
            'deliverables' => array_values(array_filter($data['deliverables'] ?? [])),
            'external_links' => array_values(array_filter($data['external_links'] ?? [])),
            'attachments' => $attachments,
        ]);

        return redirect()->route('client.projects')->with('status', 'Your request was submitted to PG Integrated.');
    }

    public function calendar(Request $request): View
    {
        $client = $request->user('client');

        return view('client-portal.calendar', [
            'client' => $client,
            'calendarEvents' => $this->calendarEvents($client->country ?: 'Saudi Arabia'),
        ]);
    }

    public function adminRequests(): View
    {
        return view('admin.client-requests.index', [
            'requests' => ClientProjectRequest::with(['client', 'project'])->latest()->paginate(20),
        ]);
    }

    public function adminRequestShow(ClientProjectRequest $clientRequest): View
    {
        return view('admin.client-requests.show', [
            'request' => $clientRequest->load(['client', 'project']),
        ]);
    }

    private function calendarEvents(string $country): array
    {
        $saudiEvents = [
            ['date' => '2026-01-01', 'title' => 'New Year Planning Window', 'country' => 'Saudi Arabia', 'type' => 'planning'],
            ['date' => '2026-02-22', 'title' => 'Saudi Founding Day', 'country' => 'Saudi Arabia', 'type' => 'national'],
            ['date' => '2026-02-18', 'title' => 'Ramadan Campaign Season', 'country' => 'Saudi Arabia', 'type' => 'seasonal'],
            ['date' => '2026-03-20', 'title' => 'Eid Al-Fitr Content Window', 'country' => 'Saudi Arabia', 'type' => 'seasonal'],
            ['date' => '2026-05-26', 'title' => 'Hajj Campaign Readiness', 'country' => 'Saudi Arabia', 'type' => 'seasonal'],
            ['date' => '2026-05-27', 'title' => 'Eid Al-Adha Content Window', 'country' => 'Saudi Arabia', 'type' => 'seasonal'],
            ['date' => '2026-09-23', 'title' => 'Saudi National Day', 'country' => 'Saudi Arabia', 'type' => 'national'],
        ];

        $globalEvents = [
            ['date' => '2026-01-01', 'title' => 'New Year', 'country' => 'Global', 'type' => 'global'],
            ['date' => '2026-12-31', 'title' => 'Year-end Campaign Review', 'country' => 'Global', 'type' => 'planning'],
        ];

        return str($country)->lower()->contains(['saudi', 'ksa', 'arabia'])
            ? $saudiEvents
            : $globalEvents;
    }
}
