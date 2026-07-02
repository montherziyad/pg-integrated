<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Modules\Projects\Requests\StoreProjectRequest;
use App\Modules\Projects\Requests\UpdateProjectRequest;
use App\Modules\Projects\Services\ProjectService;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}

    public function index()
    {
        return view('admin.projects.index', ['projects' => $this->service->all()]);
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
