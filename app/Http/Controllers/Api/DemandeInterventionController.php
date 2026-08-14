<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DemandeIntervention;
use Illuminate\Http\Request;

class DemandeInterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $demandes = DemandeIntervention::with(['utilisateur', 'equipement'])->get();

        return response()->json([
            'success' => true,
            'data' => $demandes
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',

            'priorite' => 'required|in:faible,moyenne,elevee,critique',

            'id_equipement' => 'required|exists:equipements,id_equipement',

        ]);

        $demande = DemandeIntervention::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'statut' => 'en_attente',
            'date_creation' => now(),
            'id_equipement' => $request->id_equipement,
            'id_utilisateur' => auth('api')->id(),
        ]);

        return response()->json([
            'message' => 'Demande d\'intervention créée avec succès.',
            'data' => $demande
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $demande = DemandeIntervention::with(['utilisateur', 'equipement'])
            ->find($id);

        if (!$demande) {
            return response()->json([
                'message' => 'Demande introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $demande
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $demande = DemandeIntervention::find($id);

        if (!$demande) {
            return response()->json([
                'message' => 'Demande introuvable.'
            ], 404);
        }

        $request->validate([
            'titre' => 'sometimes|string|max:255',

            'description' => 'sometimes|string',

            'priorite' => 'sometimes|in:faible,moyenne,elevee,critique',

            'date_validation' => 'nullable|date',

            'id_equipement' => 'sometimes|exists:equipements,id_equipement',

        ]);

        $demande->update($request->only([
            'titre',
            'description',
            'priorite',
            'date_validation',
            'id_equipement',
            
        ]));

        return response()->json([
            'message' => 'Demande modifiée avec succès.',
            'data' => $demande
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $demande = DemandeIntervention::find($id);

        if (!$demande) {
            return response()->json([
                'message' => 'Demande introuvable.'
            ], 404);
        }

        $demande->delete();

        return response()->json([
            'message' => 'Demande supprimée avec succès.'
        ]);
    }

    
}