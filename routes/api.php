<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AutherController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');

});
Route::apiResource('book', BookController::class);
Route::apiResource('auther', AutherController::class);

Route::apiResource('review', ReviewController::class);


Route::middleware(['app/Http/Middleware/LogRequests', 'auth', 'app/Http/Middleware/CheckPermissions', 'app/Http/Middleware/ManageTransaction'])->group(function () {
    Route::apiResource('book', BookController::class);
});

Route::apiResource('notifications', NotificationController::class);
Route::put('/notifications/{id}/read', 'NotificationController@markAsRead');


