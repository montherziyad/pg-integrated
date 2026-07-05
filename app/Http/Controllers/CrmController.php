<?php

namespace App\Http\Controllers;

use App\Models\CrmActivity;
use App\Models\CrmCompany;
use App\Models\CrmTask;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CrmController extends Controller
{
    public function index(Request $request): View
    {
        $companies = CrmCompany::query()
            ->with(['owner', 'contacts'])
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        return view('crm.index', compact('companies'));
    }

    public function create(): View
    {
        return view('crm.form', [
            'company' => new CrmCompany,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $companyData = $this->validateCompany($request);
        $contactData = $request->validate([
            'contact_name' => ['nullable', 'required_with:contact_email,contact_phone,contact_whatsapp', 'string', 'max:180'],
            'contact_position' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
        ]);

        $company = DB::transaction(function () use ($companyData, $contactData) {
            $company = CrmCompany::create($companyData);

            if (filled($contactData['contact_name'] ?? null)) {
                $company->contacts()->create([
                    'name' => $contactData['contact_name'],
                    'position' => $contactData['contact_position'] ?? null,
                    'email' => $contactData['contact_email'] ?? null,
                    'phone' => $contactData['contact_phone'] ?? null,
                    'whatsapp' => $contactData['contact_whatsapp'] ?? null,
                    'is_primary' => true,
                ]);
            }

            return $company;
        });

        return redirect()->route('crm.show', $company)->with('status', 'Company created.');
    }

    public function show(CrmCompany $company): View
    {
        $company->load(['owner', 'contacts', 'activities.user', 'tasks.assignee']);

        return view('crm.show', compact('company'));
    }

    public function edit(CrmCompany $company): View
    {
        return view('crm.form', [
            'company' => $company,
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, CrmCompany $company): RedirectResponse
    {
        $company->update($this->validateCompany($request));

        return redirect()->route('crm.show', $company)->with('status', 'Company updated.');
    }

    public function activity(Request $request, CrmCompany $company): RedirectResponse
    {
        CrmActivity::create($request->validate([
            'type' => ['required', 'string', 'max:50'],
            'channel' => ['nullable', 'string', 'max:50'],
            'summary' => ['required', 'string', 'max:500'],
            'body' => ['nullable', 'string'],
            'activity_at' => ['nullable', 'date'],
        ]) + [
            'company_id' => $company->id,
            'user_id' => $request->user()->id,
            'activity_at' => now(),
        ]);

        $company->update(['last_contacted_at' => now()]);

        return back()->with('status', 'Activity logged.');
    }

    public function task(Request $request, CrmCompany $company): RedirectResponse
    {
        CrmTask::create($request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_at' => ['nullable', 'date'],
        ]) + [
            'company_id' => $company->id,
        ]);

        return back()->with('status', 'Task created.');
    }

    private function validateCompany(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'industry' => ['nullable', 'string', 'max:120'],
            'country' => ['nullable', 'string', 'max:120'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'linkedin_url' => ['nullable', 'url:http,https', 'max:255'],
            'source' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['new', 'contacted', 'interested', 'proposal', 'negotiation', 'won', 'lost'])],
            'lead_score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_value' => ['nullable', 'numeric', 'min:0'],
            'owner_id' => ['nullable', 'exists:users,id'],
            'next_follow_up_at' => ['nullable', 'date'],
        ]);
    }
}
