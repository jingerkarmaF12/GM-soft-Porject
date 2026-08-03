<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UtilisateurController;
use App\Http\Controllers\Api\SpecialiteController;
use App\Http\Controllers\Api\EquipementController;
use App\Http\Controllers\Api\OrdreTravailController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function(){

    Route::get('/me',[AuthController::class,'me']);

    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/refresh',[AuthController::class,'refresh']);

});



Route::apiResource('utilisateurs', UtilisateurController::class)
    ->except(['store','destroy']);

Route::post('/utilisateurs', [UtilisateurController::class, 'store'])
    ->middleware('auth:api','IsAdminUser');



Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy'])
    ->middleware('auth:api','IsAdminUser');


Route::apiResource('specialites', SpecialiteController::class)
    ->middleware('IsAdminUser');


/*
|--------------------------------------------------------------------------
| Routes accessibles à tous les utilisateurs authentifiés
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    Route::get('/equipements', [EquipementController::class, 'index']);

    Route::get('/equipements/{id}', [EquipementController::class, 'show']);

});


/*
|--------------------------------------------------------------------------
| Routes réservées aux administrateurs
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'IsAdminUser'])->group(function () {

    Route::post('/equipements', [EquipementController::class, 'store']);

    Route::put('/equipements/{id}', [EquipementController::class, 'update']);

    Route::delete('/equipements/{id}', [EquipementController::class, 'destroy']);

});

Route::middleware('auth:api')->get(
    '/equipements/{id}/qr',
    [EquipementController::class, 'qr']
);



use App\Http\Controllers\Api\DemandeInterventionController;

/*
|--------------------------------------------------------------------------
| Routes accessibles aux utilisateurs authentifiés
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api','IsResponsableUser')->group(function () {

    // Afficher toutes les demandes
    Route::get('/demandes', [DemandeInterventionController::class, 'index']);

    // Afficher une demande
    Route::get('/demandes/{id}', [DemandeInterventionController::class, 'show']);

    // Créer une demande
    Route::post('/demandes', [DemandeInterventionController::class, 'store']);
});


/*
|--------------------------------------------------------------------------
| Routes réservées aux administrateurs
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'IsWorkerUser'])->group(function () {

    // Modifier une demande
    Route::put('/demandes/{id}', [DemandeInterventionController::class, 'update']);

    // Supprimer une demande
    Route::delete('/demandes/{id}', [DemandeInterventionController::class, 'destroy']);

});



/*
|--------------------------------------------------------------------------
| Routes accessibles aux utilisateurs authentifiés
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    Route::get('/ordres-travail', [OrdreTravailController::class, 'index']);

    Route::get('/ordres-travail/{id}', [OrdreTravailController::class, 'show']);

});


/*
|--------------------------------------------------------------------------
| Routes réservées aux administrateurs
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'IsAdminUser'])->group(function () {

    Route::post('/ordres-travail', [OrdreTravailController::class, 'store']);

    Route::put('/ordres-travail/{id}', [OrdreTravailController::class, 'update']);

    Route::delete('/ordres-travail/{id}', [OrdreTravailController::class, 'destroy']);

});