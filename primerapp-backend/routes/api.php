<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\PetController;

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

// Ruta de prueba
Route::get('/test', [PetController::class, 'test']);

/*
    Es posible usar Route::resource('/pet', UserController::class);
    Aunque no se usará en este caso porque muchas rutas no se van a utilizar.
*/
Route::prefix('pet')->group(function () {
    Route::post('', [PetController::class, 'store']);
    Route::get('', [PetController::class, 'getAll']);
    Route::get('/{id}', [PetController::class, 'get']);
    Route::post('/{id}', [PetController::class, 'update']);
    Route::delete('/{id}', [PetController::class, 'destroy']);
});
