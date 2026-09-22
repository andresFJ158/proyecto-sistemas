<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\EvidenceController;
use App\Http\Controllers\Api\RequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/request-types', [CatalogController::class, 'requestTypes']);
        Route::get('/resources', [CatalogController::class, 'resources']);

        Route::apiResource('requests', RequestController::class)->parameters(['requests' => 'serviceRequest']);
        Route::patch('/requests/{serviceRequest}/assign', [RequestController::class, 'assign']);
        Route::patch('/requests/{serviceRequest}/status', [RequestController::class, 'changeStatus']);
        Route::get('/requests/{serviceRequest}/evidences', [EvidenceController::class, 'index']);
        Route::post('/requests/{serviceRequest}/evidences', [EvidenceController::class, 'store']);
        Route::get('/requests/{serviceRequest}/comments', [CommentController::class, 'index']);
        Route::post('/requests/{serviceRequest}/comments', [CommentController::class, 'store']);
    });
});
