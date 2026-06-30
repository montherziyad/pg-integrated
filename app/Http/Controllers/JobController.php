<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\JobCategory;
use App\Models\JobStatus;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return view('jobs.index');
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $categories = JobCategory::orderBy('name')->get();
        $statuses = JobStatus::orderBy('sort_order')->get();

        return view('jobs.create', compact(
            'clients',
            'projects',
            'categories',
            'statuses'
        ));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}