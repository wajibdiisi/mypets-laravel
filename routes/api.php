<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnimalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdoptionController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("animal",[AnimalController::class,'showAll']);
Route::get("animal/{type}",[AnimalController::class,'showAnimal']);
Route::get("animal/{type}/{slug}",[animalController::class,'showAnimalSpecies']);
Route::post("signup", [UserController::class,'userSignUp']);
Route::post("login", [UserController::class,'userLogin']);
Route::get("adoption/{type}",[AdoptionController::class,'show']);
Route::get("adoption/{type}/{slug}",[AdoptionController::class,'show']);
Route::middleware('auth:api')->group(function(){
    Route::post("adoption",[AdoptionController::class,'create']);
    Route::patch("user/{id}",[UserController::class,'updateProfile']);
});

