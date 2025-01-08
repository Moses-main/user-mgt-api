<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Login route
Route::post('/login', [UserController::class, 'login']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/register', [UserController::class, 'register']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Logout route (protected by Sanctum)
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Route::middleware('auth:sanctum')->get('/user/profile', [UserController::class, 'profile']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/user/profile', [UserController::class, 'profile']);
});

