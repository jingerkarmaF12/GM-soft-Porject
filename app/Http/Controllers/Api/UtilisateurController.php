<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserAccountCreated;
use Illuminate\Support\Str;
class UtilisateurController extends Controller
{

    // Get all utilisateurs
    public function index()
    {
        $utilisateurs = Utilisateur::all();

        return response()->json([
            'success' => true,
            'data' => $utilisateurs
        ]);
    }


    // Create utilisateur
    public function store(Request $request)
    {

        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs,email',
            'telephone' => 'nullable|string|max:20',
            'photo_profil' => 'nullable|string',
            'id_role' => 'required|integer',
            'id_specialite' => 'nullable|integer'
        ]);
        $temporaryPassword = Str::random(12);


        $utilisateur = Utilisateur::create([

            'nom' => $request->nom,

            'prenom' => $request->prenom,

            'email' => $request->email,

            'mot_de_passe' => Hash::make($temporaryPassword),

            'telephone' => $request->telephone,

            'photo_profil' => $request->photo_profil,

            'id_role' => $request->id_role,

            'id_specialite' => $request->id_specialite

        ]);

        $utilisateur->notify(
        new UserAccountCreated($temporaryPassword)
    );


        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'data' => $utilisateur
        ],201);

    }



    // Get one utilisateur
    public function show($id)
    {

        $utilisateur = Utilisateur::find($id);


        if(!$utilisateur){

            return response()->json([
                'message'=>'Utilisateur introuvable'
            ],404);

        }


        return response()->json($utilisateur);

    }




    // Update utilisateur
    public function update(Request $request,$id)
    {

        $utilisateur = Utilisateur::find($id);


        if(!$utilisateur){

            return response()->json([
                'message'=>'Utilisateur introuvable'
            ],404);

        }



        $request->validate([
            'nom'=>'sometimes|string|max:100',
            'prenom'=>'sometimes|string|max:100',
            'email'=>'sometimes|email',
            'telephone'=>'nullable|string|max:20',
            'photo_profil'=>'nullable|string',
            'statut'=>'sometimes|in:actif,inactif,suspendu',
            'id_role'=>'sometimes|integer',
            'id_specialite'=>'nullable|integer'
        ]);



        $utilisateur->update($request->only([
    'nom',
    'prenom',
    'email',
    'telephone',
    'photo_profil',
    'statut',
    'id_role',
    'id_specialite',
]));


        return response()->json([
            'message'=>'Modification réussie',
            'data'=>$utilisateur
        ]);

    }





    // Delete utilisateur
    public function destroy($id)
    {

        $utilisateur = Utilisateur::find($id);


        if(!$utilisateur){

            return response()->json([
                'message'=>'Utilisateur introuvable'
            ],404);

        }


        $utilisateur->delete();


        return response()->json([
            'message'=>'Utilisateur supprimé'
        ]);

    }

}