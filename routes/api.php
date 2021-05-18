<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\MomentController;

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
Route::get("profile/{username}",[UserController::class,'userDetail']);
Route::get("user/{user_id}",[UserController::class,'userDetail']);
Route::get("animal",[AnimalController::class,'showAll']);
Route::get("animal/{type}",[AnimalController::class,'showAnimal']);
Route::get("animal/{type}/{slug}",[animalController::class,'showAnimalSpecies']);
Route::get("animal/{type}/details/{slug}",[animalController::class,'showAnimalDetail']);
Route::post("signup", [UserController::class,'userSignUp']);
Route::post("login", [UserController::class,'userLogin']);
Route::get("adoption/",[AdoptionController::class,'showAll']);
Route::get("adoption/detail/{adoption_id}",[AdoptionController::class,'getSpecificAdoption']);
Route::get("profile/adoption/{id_user}",[AdoptionController::class,'userAdoption']);
Route::get("profile/moment/{id_user}",[MomentController::class,'getMoment']);
Route::get("adoption/{adoption_id}/{count}",[AdoptionController::class,'adoptionImage']);
Route::get("adoption/{type}",[AdoptionController::class,'show']);
Route::get("adoption/{type}/{slug}",[AdoptionController::class,'show']);
Route::get("image/{url}",[ImageController::class,'getImage']);
Route::middleware('auth:api')->group(function(){
    Route::post('/upload/adoption', [AdoptionController::class, 'uploadAdoption'])->name('upload');
    Route::post('/upload/moment', [MomentController::class, 'uploadMoment'])->name('uploadMoment');
    Route::post("adoption",[AdoptionController::class,'create']);
    Route::patch("user/{id}",[UserController::class,'updateProfile']);
});

