<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController as Project;
use API\ProjectController;
use App\Http\Controllers\API\ReviewController as Review;
use API\ReviewController;
use API\NotificationsController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//LOgin
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
//Profile
Route::post('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/profile/type', [AuthController::class, 'type'])->middleware('auth:sanctum');
Route::post('/profile/status', [AuthController::class, 'cekStatus'])->middleware('auth:sanctum');


//Projects
Route::resource('projects', ProjectController::class)->middleware('auth:sanctum');
Route::get('projects/user/{id}', [Project::class, 'myProject'])->middleware('auth:sanctum');
Route::get('get', [Project::class, 'getData'])->middleware('auth:sanctum');
//Upload File Swb
Route::post('/upload', [Project::class, 'upload'])->middleware('auth:sanctum');
//Request Download
Route::post('/request/{id}', [Project::class, 'request'])->middleware('auth:sanctum');
//Download file
Route::get('/download/{path}', [Project::class, 'download'])->middleware('auth:sanctum'); 

//Reviews
Route::resource('reviews', ReviewController::class)->middleware('auth:sanctum');
Route::get('reviews/project/{id}', [Review::class, 'getReviews'])->middleware('auth:sanctum');

//Notification
Route::resource('notifications', NotificationsController::class)->middleware('auth:sanctum');

Route::get('category', function (){
    $category = Category::all();
    return response()->json([
        'status' => true,
        'data' => $category
    ]);
});

