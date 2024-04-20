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
        Route::apiResource('tours', TourController::class);
    });

//EDITOR OR ADMIN ONLY
Route::middleware(['auth:sanctum', 'ability:editor,admin'])
    ->group(function () {
        Route::apiResource('travels', TravelController::class)
            ->only(['index', 'update', 'show'])
            ->parameters(['travels'=>'travel:slug']);
        Route::apiResource('tours', TourController::class)
            ->except(['update', 'destroy'])
            ->parameters(['tours'=>'tour:name']);;
    });

//TOKEN
Route::name('token')
    ->prefix('token')
    ->group(function () {
        Route::get('/', [TokenController::class, 'store']);
        Route::delete('/', [TokenController::class, 'destroy']);
    });



//PUBLIC
Route::name('public')
    ->group(function () {
        Route::apiResource('tours', TourController::class)
            ->only(['index', 'show']);
        Route::get('/travels/{slug}/tours', [TourController::class, 'index']);
    });
