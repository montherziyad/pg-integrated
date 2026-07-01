<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
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