<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AiEmployeeController;
use App\Http\Controllers\AiWorkspaceController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPortalController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailIntakeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MarketingCampaignController;
use App\Http\Controllers\OutlookConnectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicWebsiteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicWebsiteController::class, 'home'])->name('website.home');
Route::get('/about', [PublicWebsiteController::class, 'about'])->name('website.about');
Route::get('/services', [PublicWebsiteController::class, 'services'])->name('website.services');
Route::get('/work', [PublicWebsiteController::class, 'work'])->name('website.work');
Route::get('/team', [PublicWebsiteController::class, 'team'])->name('website.team');
Route::get('/clients', [PublicWebsiteController::class, 'clients'])->name('website.clients');
Route::get('/contact', [PublicWebsiteController::class, 'contact'])->name('website.contact');
Route::get('/join-us', [CareerController::class, 'index'])->name('careers.index');
Route::post('/join-us/apply', [CareerController::class, 'apply'])->name('careers.apply');
Route::get('/pages/{page:slug}', [PublicWebsiteController::class, 'show'])->name('website.page');

Route::middleware('guest:client')->group(function () {
    Route::get('/client/login', [ClientAuthController::class, 'create'])->name('client.login');
    Route::post('/client/login', [ClientAuthController::class, 'store'])->name('client.login.store');
});

Route::middleware('auth:client')->group(function () {
    Route::get('/client', [ClientPortalController::class, 'index'])->name('client.portal');
    Route::get('/client/profile', [ClientPortalController::class, 'profile'])->name('client.profile');
    Route::patch('/client/profile', [ClientPortalController::class, 'updateProfile'])->name('client.profile.update');
    Route::get('/client/projects', [ClientPortalController::class, 'projects'])->name('client.projects');
    Route::get('/client/requests/new', [ClientPortalController::class, 'createRequest'])->name('client.requests.create');
    Route::post('/client/requests', [ClientPortalController::class, 'storeRequest'])->name('client.requests.store');
    Route::get('/client/calendar', [ClientPortalController::class, 'calendar'])->name('client.calendar');
    Route::post('/client/logout', [ClientAuthController::class, 'destroy'])->name('client.logout');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Administration
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/teams', [TeamController::class, 'index'])
        ->name('admin.teams.index');

    Route::get('/admin/teams/create', [TeamController::class, 'create'])
        ->name('admin.teams.create');

    Route::post('/admin/teams', [TeamController::class, 'store'])
        ->name('admin.teams.store');

    Route::get('/admin/teams/{team}', [TeamController::class, 'show'])
        ->name('admin.teams.show');

    Route::get('/admin/teams/{team}/edit', [TeamController::class, 'edit'])
        ->name('admin.teams.edit');

    Route::put('/admin/teams/{team}', [TeamController::class, 'update'])
        ->name('admin.teams.update');

    Route::delete('/admin/teams/{team}', [TeamController::class, 'destroy'])
        ->name('admin.teams.destroy');

    Route::get('/admin/roles', [RoleController::class, 'index'])
        ->name('admin.roles.index');

    Route::get('/admin/roles/create', [RoleController::class, 'create'])
        ->name('admin.roles.create');

    Route::post('/admin/roles', [RoleController::class, 'store'])
        ->name('admin.roles.store');

    Route::get('/admin/roles/{role}', [RoleController::class, 'show'])
        ->name('admin.roles.show');

    Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])
        ->name('admin.roles.edit');

    Route::put('/admin/roles/{role}', [RoleController::class, 'update'])
        ->name('admin.roles.update');

    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])
        ->name('admin.roles.destroy');

    Route::get('/admin/branches', [BranchController::class, 'index'])
        ->name('admin.branches.index');

    Route::get('/admin/branches/create', [BranchController::class, 'create'])
        ->name('admin.branches.create');

    Route::post('/admin/branches', [BranchController::class, 'store'])
        ->name('admin.branches.store');

    Route::get('/admin/branches/{branch}', [BranchController::class, 'show'])
        ->name('admin.branches.show');

    Route::get('/admin/branches/{branch}/edit', [BranchController::class, 'edit'])
        ->name('admin.branches.edit');

    Route::put('/admin/branches/{branch}', [BranchController::class, 'update'])
        ->name('admin.branches.update');

    Route::delete('/admin/branches/{branch}', [BranchController::class, 'destroy'])
        ->name('admin.branches.destroy');

    Route::get('/admin/clients', [ClientController::class, 'index'])
        ->name('admin.clients.index');

    Route::get('/admin/clients/create', [ClientController::class, 'create'])
        ->name('admin.clients.create');

    Route::post('/admin/clients', [ClientController::class, 'store'])
        ->name('admin.clients.store');

    Route::get('/admin/clients/{client}', [ClientController::class, 'show'])
        ->name('admin.clients.show');

    Route::get('/admin/clients/{client}/edit', [ClientController::class, 'edit'])
        ->name('admin.clients.edit');

    Route::put('/admin/clients/{client}', [ClientController::class, 'update'])
        ->name('admin.clients.update');

    Route::delete('/admin/clients/{client}', [ClientController::class, 'destroy'])
        ->name('admin.clients.destroy');

    Route::resource('/admin/projects', ProjectController::class)
        ->names('admin.projects');

    Route::resource('/admin/categories', CategoryController::class)
        ->names('admin.categories');

    Route::get('/admin/settings', [SettingController::class, 'index'])
        ->name('admin.settings.index');

    Route::put('/admin/settings', [SettingController::class, 'update'])
        ->name('admin.settings.update');

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.index');

    Route::get('/admin/careers/applications', [CareerController::class, 'applications'])
        ->name('admin.career-applications.index');

    Route::get('/admin/careers/applications/{application}', [CareerController::class, 'showApplication'])
        ->name('admin.career-applications.show');

    Route::patch('/admin/careers/applications/{application}', [CareerController::class, 'updateApplication'])
        ->name('admin.career-applications.update');

    Route::get('/admin/careers/applications/{application}/cv', [CareerController::class, 'downloadCv'])
        ->name('admin.career-applications.cv');

    Route::get('/admin/careers', [CareerController::class, 'adminIndex'])
        ->name('admin.careers.index');

    Route::get('/admin/careers/create', [CareerController::class, 'create'])
        ->name('admin.careers.create');

    Route::post('/admin/careers', [CareerController::class, 'store'])
        ->name('admin.careers.store');

    Route::get('/admin/careers/{career}/edit', [CareerController::class, 'edit'])
        ->name('admin.careers.edit');

    Route::put('/admin/careers/{career}', [CareerController::class, 'update'])
        ->name('admin.careers.update');

    Route::delete('/admin/careers/{career}', [CareerController::class, 'destroy'])
        ->name('admin.careers.destroy');

    Route::get('/admin/users', [UserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/admin/users/create', [UserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/users', [UserController::class, 'store'])
        ->name('admin.users.store');

    Route::get('/admin/users/{user}', [UserController::class, 'show'])
        ->name('admin.users.show');

    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])
        ->name('admin.users.edit');

    Route::put('/admin/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');

    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])
        ->name('admin.users.destroy');

    /*
    |--------------------------------------------------------------------------
    | Jobs
    |--------------------------------------------------------------------------
    */

    Route::resource('jobs', JobController::class);

    Route::get('/email-intakes', [EmailIntakeController::class, 'index'])
        ->name('email-intakes.index');

    Route::get('/email-intakes/{emailIntake}', [EmailIntakeController::class, 'show'])
        ->name('email-intakes.show');

    Route::post('/email-intakes/{emailIntake}/accept', [EmailIntakeController::class, 'accept'])
        ->name('email-intakes.accept');

    Route::post('/email-intakes/{emailIntake}/reject', [EmailIntakeController::class, 'reject'])
        ->name('email-intakes.reject');

    Route::post('/admin/settings/outlook/test', [OutlookConnectionController::class, 'test'])
        ->name('admin.settings.outlook.test');

    Route::post('/admin/settings/outlook/subscribe', [OutlookConnectionController::class, 'subscribe'])
        ->name('admin.settings.outlook.subscribe');

    Route::get('/traffic-board', [TrafficController::class, 'index'])
        ->name('traffic.index');

    Route::get('/team-workload', [StudioController::class, 'index'])
        ->name('workload.index');

    Route::get('/archive', [ArchiveController::class, 'index'])
        ->name('archive.index');

    Route::get('/archive/assets', [ArchiveController::class, 'assets'])
        ->name('archive.assets');

    Route::post('/archive/{job}', [ArchiveController::class, 'archive'])
        ->name('archive.store');

    Route::delete('/archive/{job}', [ArchiveController::class, 'restore'])
        ->name('archive.restore');

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/ai-employee', [AiEmployeeController::class, 'index'])
        ->name('ai-employee.index');

    Route::post('/ai-employee/client-requests/{clientRequest}/suggest', [AiEmployeeController::class, 'suggestClientRequest'])
        ->name('ai-employee.client-requests.suggest');

    Route::post('/ai-employee/support/{ticket}/suggest-reply', [AiEmployeeController::class, 'suggestSupportReply'])
        ->name('ai-employee.support.suggest-reply');

    Route::post('/ai-employee/crm/suggest-lead', [AiEmployeeController::class, 'suggestCrmLead'])
        ->name('ai-employee.crm.suggest-lead');

    Route::post('/ai-employee/suggestions/{suggestion}/approve', [AiEmployeeController::class, 'approve'])
        ->name('ai-employee.suggestions.approve');

    Route::post('/ai-employee/suggestions/{suggestion}/reject', [AiEmployeeController::class, 'reject'])
        ->name('ai-employee.suggestions.reject');

    Route::get('/admin/client-requests', [ClientPortalController::class, 'adminRequests'])
        ->name('admin.client-requests.index');

    Route::get('/admin/client-requests/{clientRequest}', [ClientPortalController::class, 'adminRequestShow'])
        ->name('admin.client-requests.show');

    Route::post('/jobs/{job}/assign', [JobController::class, 'assign'])
        ->name('jobs.assign');

    Route::post('/jobs/{job}/attachments', [JobController::class, 'uploadAttachments'])
        ->name('jobs.attachments.upload');

    Route::get('/assets/{asset}/download', [JobController::class, 'downloadAttachment'])
        ->name('assets.download');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    /*
    |--------------------------------------------------------------------------
    | PG Integrated Growth Platform MVP
    |--------------------------------------------------------------------------
    */
    Route::resource('/admin/cms', CmsPageController::class)
        ->parameters(['cms' => 'cms'])
        ->except(['show'])
        ->names('admin.cms');

    Route::get('/crm', [CrmController::class, 'index'])
        ->name('crm.index');
    Route::get('/crm/create', [CrmController::class, 'create'])
        ->name('crm.create');
    Route::post('/crm', [CrmController::class, 'store'])
        ->name('crm.store');
    Route::get('/crm/{company}', [CrmController::class, 'show'])
        ->name('crm.show');
    Route::get('/crm/{company}/edit', [CrmController::class, 'edit'])
        ->name('crm.edit');
    Route::put('/crm/{company}', [CrmController::class, 'update'])
        ->name('crm.update');
    Route::post('/crm/{company}/activities', [CrmController::class, 'activity'])
        ->name('crm.activities.store');
    Route::post('/crm/{company}/tasks', [CrmController::class, 'task'])
        ->name('crm.tasks.store');

    Route::get('/support', [SupportTicketController::class, 'index'])
        ->name('support.index');
    Route::get('/support/create', [SupportTicketController::class, 'create'])
        ->name('support.create');
    Route::post('/support', [SupportTicketController::class, 'store'])
        ->name('support.store');
    Route::get('/support/{ticket}', [SupportTicketController::class, 'show'])
        ->name('support.show');
    Route::post('/support/{ticket}/reply', [SupportTicketController::class, 'reply'])
        ->name('support.reply');

    Route::get('/ai-workspace', [AiWorkspaceController::class, 'index'])
        ->name('ai.workspace');
    Route::post('/ai-workspace/draft', [AiWorkspaceController::class, 'draft'])
        ->name('ai.draft');

    Route::get('/marketing', [MarketingCampaignController::class, 'index'])
        ->name('marketing.index');
    Route::get('/marketing/create', [MarketingCampaignController::class, 'create'])
        ->name('marketing.create');
    Route::post('/marketing', [MarketingCampaignController::class, 'store'])
        ->name('marketing.store');

});

require __DIR__.'/auth.php';
