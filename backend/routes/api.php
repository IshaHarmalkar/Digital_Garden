<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\NativeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('natives', NativeController::class);

    Route::apiResource('comments', CommentController::class)
        ->only(['store', 'update', 'destroy']);
}

);
