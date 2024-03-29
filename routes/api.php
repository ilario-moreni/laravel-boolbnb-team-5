<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentController as ApartmentController;
use App\Http\Controllers\Api\ServiceController as ServiceController;
use App\Http\Controllers\Api\MessageController as MessageController;
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

Route::post('/apartments', [ApartmentController::class, 'index']);
Route::get('/apartments/{slug}', [ApartmentController::class, 'show']);
Route::post('/apartments/filter/', [ApartmentController::class, 'filter']);
Route::post('/apartments/sponsor/', [ApartmentController::class, 'sponsor']);

Route::get('/services', [ServiceController::class, 'index']);
Route::post('/messages', [MessageController::class, 'index']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
