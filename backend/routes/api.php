<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\MoodEntryController;
use App\Http\Controllers\NativeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ReflectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public mood routes (no authentication required)
Route::get('moods/tree', [MoodEntryController::class, 'moodTree']);

Route::get('moods', [MoodEntryController::class, 'moods']);
Route::post('mood-entries', [MoodEntryController::class, 'store']);
Route::get('/mood-entries/range', [MoodEntryController::class, 'entriesByRange']);
Route::get('/mood-entries/range-primary', [MoodEntryController::class, 'entriesPrimaryByRange']);

Route::get('/mood-entries/all', [MoodEntryController::class, 'index']);

// Protected routes group
Route::middleware(['auth:sanctum'])->group(function () {
    // User info route
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    // Native resource routes
    Route::apiResource('natives', NativeController::class);

    // Comment routes (limited actions)
    Route::apiResource('comments', CommentController::class)
        ->only(['store', 'update', 'destroy']);
});

Route::post('reflections/', [ReflectionController::class, 'store']);          // Save or update reflection
Route::get('reflections/today', [ReflectionController::class, 'today']);      // Get today’s reflection
Route::get('reflections/summary', [ReflectionController::class, 'summary']);  // Get summary for week/month/quarter
Route::get('reflections/gratitudes', [ReflectionController::class, 'allGratitudes']);

Route::get('reflections/journals', [ReflectionController::class, 'allJournals']);

Route::get('reflections/{date}', [ReflectionController::class, 'show']);      // Get reflection by specific date

// newsletter
// Route::prefix('newsletter')->group(function () {
//     Route::get('/{newsletter}/feedback', [NewsletterController::class, 'showFeedback'])
//         ->name('api.newsletter.feedback.show');

//     Route::post('/feedback', [NewsletterController::class, 'submitFeedback'])
//         ->name('api.newsletter.feedback.submit');
// });
