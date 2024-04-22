<?php

use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TravelController;
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
//ADMIN ONLY
Route::middleware(['auth:sanctum', 'ability:admin'])
    ->group(function () {
        Route::apiResource('travels', TravelController::class)
            ->only(['store', 'destroy']);
        Route::apiResource('tours', TourController::class)
            ->only(['store']);
        Route::apiResource('/travels/{slug}/tours', TourController::class)
            ->only(['store']);
    });

//EDITOR OR ADMIN ONLY
Route::middleware(['auth:sanctum', 'ability:editor,admin'])
    ->group(function () {
        Route::apiResource('travels', TravelController::class)
            ->only(['index', 'update', 'show'])
            ->parameters(['travels' => 'travel:slug']);
        Route::apiResource('tours', TourController::class)
            ->only(['show'])
            ->parameters(['tours' => 'tour:name']);
        Route::apiResource('/travels/{slug}/tours', TourController::class)
            ->only(['show'])
            ->parameters(['tours' => 'tour:name']);
    });

//TOKEN
Route::apiResource('token', TokenController::class)
    ->only(['store', 'destroy']);



//PUBLIC
Route::apiResource('tours', TourController::class)
    ->only(['index']);
Route::apiResource('/travels/{slug}/tours', TourController::class)
    ->only(['index']);
