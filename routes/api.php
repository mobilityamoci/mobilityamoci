<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::middleware('api')->post('authenticate', [ApiController::class, 'authenticate']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/parent', [ApiController::class, 'getParent']);
    Route::post('/absence-days', [ApiController::class, 'postAbsenceDays']);
    Route::get('/get-percorso/{pedibusLine:uuid}', [ApiController::class, 'getPedibusLine']);
    Route::get('/get-fermate/{pedibusLine:uuid}', [ApiController::class, 'getPedibusStops']);
});

