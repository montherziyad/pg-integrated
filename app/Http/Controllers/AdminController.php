<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Client;
use App\Models\Project;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'usersCount' => User::count(),
            'teamsCount' => Team::count(),
            'rolesCount' => Role::count(),
            'branchesCount' => Branch::count(),
            'clientsCount' => Client::count(),
            'projectsCount' => Project::count(),
        ]);
    }
}