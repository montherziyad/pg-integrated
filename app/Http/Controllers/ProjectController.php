<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Modules\Projects\Requests\StoreProjectRequest;
use App\Modules\Projects\Requests\UpdateProjectRequest;
use App\Modules\Projects\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $projects = Project::query()
            ->with(['client.accountManager', 'client.clientServiceUsers', 'projectManager'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('project_code', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%")
                        ->orWhereHas('client', fn ($client) => $client->where('name', 'ilike', "%{$search}%"))
                        ->orWhereHas('projectManager', fn ($user) => $user->where('name', 'ilike', "%{$search}%"));
                });
            })
            ->orderBy('name')
            ->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create', $this->formData(new Project));
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->service->create($request->validated());

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('admin.projects.show', ['project' => $this->service->find($project->id)]);
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', $this->formData($project));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->service->update($project, $request->validated());

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->service->delete($project);

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    private function formData(Project $project): array
    {
        return ['project' => $project, 'clients' => Client::orderBy('name')->get(), 'projectManagers' => User::orderBy('name')->get()];
    }
}
