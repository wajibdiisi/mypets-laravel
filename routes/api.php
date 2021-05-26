<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\MomentController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\LocationController;

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
Route::get("adoption/{count}",[AdoptionController::class,'latestAdoption']);
Route::get("adoption/detail/{adoption_id}",[AdoptionController::class,'getSpecificAdoption']);
Route::get("adoption/detail/{adoption_id}/images",[AdoptionController::class,'getAdoptionImage']);
Route::get("profile/adoption/{id_user}",[AdoptionController::class,'userAdoption']);
Route::get("profile/moment/{id_user}",[MomentController::class,'getMoment']);
Route::get("profile/vaccine/{id_user}",[VaccineController::class,'getVaccine']);
Route::get("moment/{breeds_type}",[MomentController::class,'getMomentByBreeds']);
Route::get("adoption/{adoption_id}/{count}",[AdoptionController::class,'adoptionImage']);
Route::get("adoption/{type}",[AdoptionController::class,'show']);
Route::get("location/cities",[LocationController::class,'getAllCities']);

Route::get("adoption/{type}/{slug}",[AdoptionController::class,'show']);
Route::get("image/{url}",[ImageController::class,'getImage']);
Route::middleware('auth:api')->group(function(){
    Route::post('/upload/adoption', [AdoptionController::class, 'uploadAdoption'])->name('upload');
    Route::post('/profile/{id_user}/update', [UserController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/profile/{id_user}/moment/{id_moment}', [MomentController::class, 'patchMoment'])->name('patchMoment');
    Route::delete('/profile/{id_user}/moment/{id_moment}', [MomentController::class, 'deleteMoment'])->name('deleteMoment');
    Route::delete('/profile/{id_user}/vaccine/{id_vaccine}', [VaccineController::class, 'deleteVaccine'])->name('deleteVaccine');
    Route::delete('/profile/{id_user}/adoption/{id_adoption}', [AdoptionController::class, 'deleteAdoption'])->name('deleteAdoption');
    Route::post('/upload/moment', [MomentController::class, 'uploadMoment'])->name('uploadMoment');
    Route::post('/upload/vaccine', [VaccineController::class, 'uploadVaccine'])->name('uploadVaccine');
    Route::post('/animal/like/{slug}/{user_id}', [AnimalController::class, 'addLike'])->name('addLike');
    Route::get('/animal/like/{slug}/{user_id}', [AnimalController::class, 'getAnimalLikeUser'])->name('getAnimalLikeUser');
    Route::get('/edit/moment/{id}', [MomentController::class, 'getMomentByID'])->name('editMoment');
    Route::get('/profile/{id}/information', [UserController::class, 'getUserData'])->name('getUserData');
    Route::post("adoption",[AdoptionController::class,'create']);
    Route::patch("user/{id}",[UserController::class,'updateProfile']);
});

