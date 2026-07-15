<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\App\TenantController;
use App\Http\Controllers\App\SubjectController;
use App\Http\Controllers\App\StudentController;
use App\Http\Controllers\App\ActivityController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Welcome page with subscription form
Route::get('/', [SubscriptionController::class, 'showApplicationForm'])->name('welcome');

// Subscription application route
Route::post('/subscription/apply', [SubscriptionController::class, 'apply'])->name('subscription.apply');

Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tenant management routes
    Route::resource('tenants', TenantController::class);
    Route::post('/tenants/{tenant}/disable', [TenantController::class, 'disable'])->name('tenants.disable');
    Route::post('/tenants/{tenant}/enable', [TenantController::class, 'enable'])->name('tenants.enable');
    Route::post('/tenants/{tenant}/migrate', [TenantController::class, 'runMigrations'])->name('tenants.migrate');
    Route::post('/tenants/{tenant}/backup', [TenantController::class, 'downloadBackup'])->name('tenants.backup');
    Route::post('/tenants/{tenant}/check-domain', [TenantController::class, 'checkDomain'])->name('tenants.check-domain');

    // Tenant application routes
    Route::get('/applications', [SubscriptionController::class, 'listApplications'])->name('applications.index');
    Route::post('/applications/{application}/approve', [SubscriptionController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{application}/reject', [SubscriptionController::class, 'reject'])->name('applications.reject');

    // System Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // Audit Log routes
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
});


Route::name('central.')->group(function () {
    require __DIR__.'/auth.php';
});
