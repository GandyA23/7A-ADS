<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EditorialController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('category')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/{id?}', [CategoryController::class, 'store']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::prefix('editorial')->group(function () {
    Route::get('', [EditorialController::class, 'index']);
    Route::get('/{id}', [EditorialController::class, 'show']);
    Route::post('/{id?}', [EditorialController::class, 'store']);
    Route::delete('/{id}', [EditorialController::class, 'destroy']);
});

Route::prefix('book')->group(function () {
    Route::get('', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::post('/{id?}', [BookController::class, 'store']);
    Route::delete('/{id}', [BookController::class, 'destroy']);
});

Route::prefix('author')->group(function () {
    Route::get('', [AuthorController::class, 'index']);
    Route::get('/{id}', [AuthorController::class, 'show']);
    Route::post('/{id?}', [AuthorController::class, 'store']);
    Route::delete('/{id}', [AuthorController::class, 'destroy']);
});
