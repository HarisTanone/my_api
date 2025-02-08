<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::any('login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore']);

    Route::apiResource('books', BookController::class);
    Route::post('books/{id}/restore', [BookController::class, 'restore']);
});