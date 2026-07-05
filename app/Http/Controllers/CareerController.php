<?php

namespace App\Http\Controllers;

use App\Models\CareerApplication;
use App\Models\CareerJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        $jobs = collect();

        if (Schema::hasTable('career_jobs')) {
            $jobs = CareerJob::query()
                ->published()
                ->latest('published_at')
                ->latest()
                ->get();
        }

        return view('website.careers', [
            'jobs' => $jobs,
            'navigationPages' => app(PublicWebsiteController::class)->publicNavigationPages(),
        ]);
    }

    public function apply(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('career_applications')) {
            return back()
                ->withInput()
                ->with('error', 'Careers module is not installed yet. Please run php artisan migrate.');
        }

        $data = $request->validate([
            'career_job_id' => [
                'nullable',
                Schema::hasTable('career_jobs') ? 'exists:career_jobs,id' : 'integer',
            ],
            'full_name' => ['required', 'string', 'max:180'],
            'email' => ['required', 'email', 'max:180'],
            'phone' => ['nullable', 'string', 'max:80'],
            'current_title' => ['nullable', 'string', 'max:180'],
            'portfolio_url' => ['nullable', 'url', 'max:500'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'message' => ['nullable', 'string', 'max:4000'],
        ]);

        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('career-applications/cv', 'public');
        }

        unset($data['cv']);

        CareerApplication::create($data);

        return redirect()
            ->route('careers.index')
            ->with('status', 'Your application has been received. Our team will review it soon.');
    }

    public function adminIndex(): View
    {
        $jobs = collect();
        $applications = collect();
        $newApplicationsCount = 0;

        if (Schema::hasTable('career_jobs')) {
            $jobs = CareerJob::query()->withCount('applications')->latest()->paginate(15);
        }

        if (Schema::hasTable('career_applications')) {
            $applications = CareerApplication::query()->with('job')->latest()->limit(8)->get();
            $newApplicationsCount = CareerApplication::query()->where('status', 'new')->count();
        }

        return view('careers.admin.index', [
            'jobs' => $jobs,
            'applications' => $applications,
            'newApplicationsCount' => $newApplicationsCount,
            'moduleInstalled' => Schema::hasTable('career_jobs') && Schema::hasTable('career_applications'),
        ]);
    }

    public function create(): View
    {
        return view('careers.admin.form', ['job' => new CareerJob]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('career_jobs')) {
            return back()->withInput()->with('error', 'Careers tables are missing. Please run php artisan migrate.');
        }

        CareerJob::create($this->jobData($request) + ['created_by' => $request->user()->id]);

        return redirect()->route('admin.careers.index')->with('success', 'Career job published.');
    }

    public function edit(CareerJob $career): View
    {
        return view('careers.admin.form', ['job' => $career]);
    }

    public function update(Request $request, CareerJob $career): RedirectResponse
    {
        $career->update($this->jobData($request));

        return redirect()->route('admin.careers.index')->with('success', 'Career job updated.');
    }

    public function destroy(CareerJob $career): RedirectResponse
    {
        $career->delete();

        return redirect()->route('admin.careers.index')->with('success', 'Career job removed from the website.');
    }

    public function applications(): View
    {
        $applications = collect();

        if (Schema::hasTable('career_applications')) {
            $applications = CareerApplication::query()->with('job')->latest()->paginate(20);
        }

        return view('careers.admin.applications', [
            'applications' => $applications,
            'moduleInstalled' => Schema::hasTable('career_applications'),
        ]);
    }

    public function showApplication(CareerApplication $application): View
    {
        return view('careers.admin.application-show', ['application' => $application->load('job', 'reviewer')]);
    }

    public function updateApplication(Request $request, CareerApplication $application): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['new', 'reviewing', 'shortlisted', 'rejected', 'hired'])],
        ]);

        $application->update($data + [
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        return redirect()->route('admin.career-applications.show', $application)->with('success', 'Application status updated.');
    }

    public function downloadCv(CareerApplication $application)
    {
        abort_unless($application->cv_path && Storage::disk('public')->exists($application->cv_path), 404);

        return Storage::disk('public')->download($application->cv_path);
    }

    private function jobData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'department' => ['nullable', 'string', 'max:180'],
            'location' => ['nullable', 'string', 'max:180'],
            'employment_type' => ['required', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'requirements_text' => ['nullable', 'string'],
            'responsibilities_text' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'closes_at' => ['nullable', 'date'],
        ]);

        $data['requirements'] = $this->lines($data['requirements_text'] ?? null);
        $data['responsibilities'] = $this->lines($data['responsibilities_text'] ?? null);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['published_at'] ?? now();

        unset($data['requirements_text'], $data['responsibilities_text']);

        return $data;
    }

    private function lines(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
