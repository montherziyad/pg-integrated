<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Modules\Users\Requests\StoreTeamRequest;
use App\Modules\Users\Requests\UpdateTeamRequest;
use App\Modules\Users\Services\TeamService;

class TeamController extends Controller
{
    public function __construct(
        protected TeamService $teamService
    ) {}

    public function index()
    {
        $teams = $this->teamService->all();

        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create', [
            'team' => new Team,
        ]);
    }

    public function store(StoreTeamRequest $request)
    {
        $team = $this->teamService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $team = $this->teamService->find($team->id);

        return view('admin.teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(UpdateTeamRequest $request, Team $team)
    {
        $this->teamService->update(
            $team,
            $request->validated()
        );

        return redirect()
            ->route('admin.teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $this->teamService->delete($team);

        return redirect()
            ->route('admin.teams.index')
            ->with('success', 'Team deleted successfully.');
    }
}
