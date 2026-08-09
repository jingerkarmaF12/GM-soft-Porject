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



Route::post('/login',[AuthController::class,'login'])->middleware('throttle:login');;

Route::middleware('auth:api')->group(function(){

    Route::get('/me',[AuthController::class,'me']);

    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/refresh',[AuthController::class,'refresh']);

});



Route::middleware(['auth:api', 'IsAdminUser'])->group(function () {
    Route::apiResource('utilisateurs', UtilisateurController::class);
});

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

use App\Http\Controllers\Api\AffectationController;

/*
|--------------------------------------------------------------------------
| Routes du Responsable Maintenance
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'IsResponsableUser'])->group(function () {

    Route::get('/affectations', [AffectationController::class, 'index']);

    Route::post('/affectations', [AffectationController::class, 'store']);

    Route::put('/affectations/{id}', [AffectationController::class, 'update']);

    Route::delete('/affectations/{id}', [AffectationController::class, 'destroy']);

});


/*
|--------------------------------------------------------------------------
| Routes du Technicien
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:api', 'IsAdminUser'])->group(function () {

    Route::get('/affectations/{id}', [AffectationController::class, 'show']);

});


Route::middleware(['auth:api', 'IsTechnicienUser'])->group(function () {

    Route::get('/mes-affectations', [AffectationController::class, 'mesAffectations']);

});


use App\Http\Controllers\Api\PanneController;

// Historique des pannes
Route::middleware(['auth:api'])->group(function () {

    Route::get('/pannes', [PanneController::class, 'index']);

    Route::get('/pannes/{id}', [PanneController::class, 'show']);

});

// Responsable maintenance uniquement
Route::middleware(['auth:api', 'IsResponsableUser'])->group(function () {

    Route::post('/pannes', [PanneController::class, 'store']);

});



use App\Http\Controllers\Api\DocumentController;

Route::middleware('auth:api')->group(function () {

    Route::get('/documents', [DocumentController::class, 'index']);

    Route::get('/documents/{id}', [DocumentController::class, 'show']);

});


Route::middleware(['auth:api', 'IsAdminUser'])->group(function () {

    Route::post('/documents', [DocumentController::class, 'store']);

    Route::post('/documents/{id}', [DocumentController::class, 'update']);

    Route::delete('/documents/{id}', [DocumentController::class, 'destroy']);

});


use App\Http\Controllers\Api\DocumentChunkController;

Route::middleware('auth:api')->group(function () {

    Route::get('/document-chunks', [
        DocumentChunkController::class,
        'index'
    ]);

    Route::get('/document-chunks/{id}', [
        DocumentChunkController::class,
        'show'
    ]);

    Route::get('/documents/{documentId}/chunks', [
        DocumentChunkController::class,
        'byDocument'
    ]);

    Route::post('/document-chunks', [
        DocumentChunkController::class,
        'store'
    ]);

    Route::put('/document-chunks/{id}', [
        DocumentChunkController::class,
        'update'
    ]);

    Route::delete('/document-chunks/{id}', [
        DocumentChunkController::class,
        'destroy'
    ]);

});



use App\Http\Controllers\Api\MessageChatController;

Route::middleware('auth:api')->group(function () {

    Route::get('/messages', [
        MessageChatController::class,
        'index'
    ]);

    Route::get('/messages/{id}', [
        MessageChatController::class,
        'show'
    ]);

    Route::get('/conversations/{conversationId}/messages', [
        MessageChatController::class,
        'byConversation'
    ]);

    Route::post('/messages', [
        MessageChatController::class,
        'store'
    ]);

    Route::put('/messages/{id}', [
        MessageChatController::class,
        'update'
    ]);

    Route::delete('/messages/{id}', [
        MessageChatController::class,
        'destroy'
    ]);

});


use App\Http\Controllers\Api\ConversationRAGController;

Route::middleware('auth:api')->group(function () {

    // Liste des conversations de l'utilisateur connecté
    Route::get('/conversations', [
        ConversationRAGController::class,
        'index'
    ]);

    // Créer une conversation
    Route::post('/conversations', [
        ConversationRAGController::class,
        'store'
    ]);

    // Afficher une conversation + ses messages
    Route::get('/conversations/{id}', [
        ConversationRAGController::class,
        'show'
    ]);

    // Modifier le titre
    Route::put('/conversations/{id}', [
        ConversationRAGController::class,
        'update'
    ]);

    // Clôturer une conversation
    Route::put('/conversations/{id}/close', [
        ConversationRAGController::class,
        'close'
    ]);

    // Supprimer une conversation
    Route::delete('/conversations/{id}', [
        ConversationRAGController::class,
        'destroy'
    ]);

});