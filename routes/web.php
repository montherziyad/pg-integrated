<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailIntakeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\OutlookConnectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudioController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TrafficController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
});

require __DIR__.'/auth.php';
