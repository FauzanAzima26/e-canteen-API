<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\Api\CategoriesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoriesController::class);
    
    Route::apiResource('suppliers', SuppliersController::class);

    Route::get('logout', [AuthController::class, 'logout']);
});

Route::get('user-list ', [UserController::class, 'index']);

Route::post('registration', [AuthController::class, 'registration']);
Route::post('login', [AuthController::class, 'login']);