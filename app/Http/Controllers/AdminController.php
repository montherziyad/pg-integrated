<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Client;
use App\Models\JobCategory;
use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;

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
            'categoriesCount' => JobCategory::count(),
        ]);
    }
}
