<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\MoodEntryController;
use App\Http\Controllers\NativeController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\SpintlyTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public mood routes (no authentication required)
Route::get('moods/tree', [MoodEntryController::class, 'moodTree']);

Route::get('moods', [MoodEntryController::class, 'moods']);
Route::post('mood-entries', [MoodEntryController::class, 'store']);
Route::get('/mood-entries/range', [MoodEntryController::class, 'entriesByRange']);
Route::get('/mood-entries/range-primary', [MoodEntryController::class, 'entriesPrimaryByRange']);
Route::get('/mood-entries/summary', [MoodEntryController::class, 'summary']);

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

// spintly integration test

Route::prefix('spintly')->group(function () {
    Route::get('/sites', [SpintlyTestController::class, 'fetchSites']);
    Route::get('/access-points', [SpintlyTestController::class, 'fetchAccessPoints']);
    Route::get('/roles', [SpintlyTestController::class, 'fetchRoles']);
    Route::get('/users', [SpintlyTestController::class, 'fetchAllUsers']);

    Route::patch('/users', [SpintlyTestController::class, 'updateUser']);
    Route::post('/users', [SpintlyTestController::class, 'createUser']);

    Route::get('/users/permissions/{userId}', [SpintlyTestController::class, 'fetchUserPermissions']);

    Route::patch('/users/deactivate/{userId}', [SpintlyTestController::class, 'deactivateUser']);
    Route::patch('/users/activate/{userId}', [SpintlyTestController::class, 'activateUser']);

    Route::delete('/users/{userId}', [SpintlyTestController::class, 'deleteUser']);

});
