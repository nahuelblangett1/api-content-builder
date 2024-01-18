<?php

use App\Http\Controllers\ApiPlaraphyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContentGeneratorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    
    Route::middleware('auth:sanctum')->group(function () {

        Route::get('description-builder', [ContentGeneratorController::class, 'getDescription']);
        Route::get('name-builder', [ContentGeneratorController::class, 'getNames']);

        Route::prefix('plaraphy')->group(function () {
            
            Route::post('short-rewriter', [ApiPlaraphyController::class, 'getShortRewriter']);
            Route::post('sentiment', [ApiPlaraphyController::class, 'getSentiment']);
            Route::post('summarizer', [ApiPlaraphyController::class, 'getSummarizer']);
            Route::post('long-rewriter', [ApiPlaraphyController::class, 'getLongRewriter']);
            Route::post('retrieve-long-rewriter', [ApiPlaraphyController::class, 'getRetrieveLongRewriter']);
        });
        
        Route::prefix('ai-promps')->group(function () {
            Route::get('completions', [ContentGeneratorController::class, 'completions']);
        });
    });
});
