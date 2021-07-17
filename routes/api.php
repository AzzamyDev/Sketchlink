<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController as Project;
use API\ProjectController;
use App\Http\Controllers\API\ReviewController as Review;
use API\ReviewController;
use API\NotificationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//LOgin
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
//Profile
Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/profile/type', [AuthController::class, 'type'])->middleware('auth:sanctum');
Route::get('/profile/status', [AuthController::class, 'cekStatus'])->middleware('auth:sanctum');
});

//Projects
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/user/{id}', [Project::class, 'myProject']);
    Route::get('get', [Project::class, 'getData']);
    //Upload File Swb
    Route::post('/upload', [Project::class, 'upload']);
    //Request Download
    Route::post('/request/{id}', [Project::class, 'request']);
    //Download file
    Route::get('/download/{path}', [Project::class, 'download']);    
});

//Reviews
Route::apiResource('reviews', ReviewController::class)->middleware('auth:sanctum');
Route::get('reviews/project/{id}', [Review::class, 'getReviews'])->middleware('auth:sanctum');

//Notification
Route::apiResource('notifications', NotificationsController::class)->middleware('auth:sanctum');

