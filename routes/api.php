<?php

use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\TravelController;
use App\Http\Controllers\Api\UserController;
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
//ADMIN ONLY
Route::middleware(['auth:sanctum','ability:admin'])
    ->group(function(){
        Route::post('/tours', [TourController::class,'store']);
        Route::post('/travels', [TravelController::class,'store']);
    });

//EDITOR ONLY
Route::middleware(['auth:sanctum','ability:editor,admin'])
    ->group(function(){
        Route::put('/travel/{travel:slug}',[TravelController::class,'update']);
    });

//LOGIN (create token)
Route::post('/login',[UserController::class,'store']);
//LOGOUT (destroy tokens)
Route::post('/logout',[UserController::class, 'destroy']);

//PUBLIC
Route::get('/tours',[TourController::class,'index']);