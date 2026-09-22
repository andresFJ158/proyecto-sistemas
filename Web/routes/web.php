<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RequestController;
use App\Http\Controllers\Web\ResourceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store')->middleware('throttle:login');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('requests', RequestController::class)->parameters(['requests' => 'serviceRequest']);
    Route::patch('/requests/{serviceRequest}/assign', [RequestController::class, 'assign'])->name('requests.assign');
    Route::patch('/requests/{serviceRequest}/status', [RequestController::class, 'changeStatus'])->name('requests.status');
    Route::post('/requests/{serviceRequest}/comments', [RequestController::class, 'comment'])->name('requests.comments.store');
    Route::post('/requests/{serviceRequest}/evidences', [RequestController::class, 'evidence'])->name('requests.evidences.store');
    Route::resource('resources', ResourceController::class)->except('show');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
});
