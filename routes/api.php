<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;



Route::any('login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
});

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
        Route::post('categories/{id}/restore', [CategoryController::class, 'restore']);
    });

    Route::get('books', [BookController::class, 'index']);
    Route::get('books/{id}', [BookController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('books', [BookController::class, 'store']);
        Route::put('books/{id}', [BookController::class, 'update']);
        Route::delete('books/{id}', [BookController::class, 'destroy']);
        Route::post('books/{id}/restore', [BookController::class, 'restore']);
    });

    Route::get('/loans', [LoanController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/loans', [LoanController::class, 'store']);
        Route::post('/loans/{id}/returnbook', [LoanController::class, 'rBook']);
    });
});
