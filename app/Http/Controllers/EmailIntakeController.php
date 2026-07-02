<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EmailIntake;
use App\Models\JobCategory;
use App\Models\Project;
use App\Modules\EmailIntakes\Requests\AcceptEmailIntakeRequest;
use App\Modules\EmailIntakes\Requests\RejectEmailIntakeRequest;
use App\Modules\EmailIntakes\Services\EmailIntakeService;

class EmailIntakeController extends Controller
{
    public function __construct(protected EmailIntakeService $service) {}

    public function index()
    {
        return view('email-intakes.index', [
            'intakes' => $this->service->all(),
            'counts' => $this->service->counts(),
        ]);
    }

    public function show(EmailIntake $emailIntake)
    {
        return view('email-intakes.show', [
            'intake' => $this->service->find($emailIntake->id),
            'clients' => Client::orderBy('name')->get(),
            'projects' => Project::orderBy('name')->get(),
            'categories' => JobCategory::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function accept(AcceptEmailIntakeRequest $request, EmailIntake $emailIntake)
    {
        $job = $this->service->accept($emailIntake, $request->validated());

        return redirect()->route('jobs.show', $job)->with('success', 'Email accepted and converted to a Job.');
    }

    public function reject(RejectEmailIntakeRequest $request, EmailIntake $emailIntake)
    {
        $this->service->reject($emailIntake, $request->validated('rejection_reason'));

        return redirect()->route('email-intakes.index')->with('success', 'Email intake rejected.');
    }
}
