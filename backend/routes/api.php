<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\MoodEntryController;
use App\Http\Controllers\NativeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public mood routes (no authentication required)
Route::get('moods/tree', [MoodEntryController::class, 'moodTree']);

Route::get('moods', [MoodEntryController::class, 'moods']);
Route::post('mood-entries', [MoodEntryController::class, 'store']);
Route::get('mood-entries/today', [MoodEntryController::class, 'today']);
Route::get('mood-entries/summary', [MoodEntryController::class, 'summary']);

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
