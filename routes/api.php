<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function() {

    Route::post('logout', [AuthController::class,'logout']);
    Route::get('me', [AuthController::class,'me']);

    // Admin only
    Route::middleware('role:admin')->group(function() {
        Route::get('projects', [ProjectController::class, 'index']);
        Route::get('projects/{id}', [ProjectController::class, 'show']);
        Route::post('projects', [ProjectController::class, 'store']);
        Route::patch('projects/{id}', [ProjectController::class, 'update']);
        Route::delete('projects/{id}', [ProjectController::class, 'destroy']);
    });

    // Task routes
    Route::get('projects/{project}/tasks', [TaskController::class,'index']);
    Route::get('tasks/{task}', [TaskController::class,'show']);


    // Manager only
    Route::middleware('role:manager')->group(function() {
        Route::post('projects/{project}/tasks', [TaskController::class,'store']);
        Route::put('tasks/{task}', [TaskController::class,'update']);
        Route::delete('tasks/{task}', [TaskController::class,'destroy']);
    });

    // Comment routes
    Route::post('tasks/{task}/comments', [CommentController::class,'store']);
    Route::get('tasks/{task}/comments', [CommentController::class,'index']);
});
