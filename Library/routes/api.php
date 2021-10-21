<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;

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
