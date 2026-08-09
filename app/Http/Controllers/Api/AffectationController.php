<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $affectations = Affectation::with(['utilisateur', 'ordreTravail'])->get();

        return response()->json([
            'success' => true,
            'data' => $affectations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_intervention' => 'required|string|max:255',

            'statut' => 'nullable|in:assignee,en_cours,terminee,annulee',

            'commentaire' => 'nullable|string',

            'id_utilisateur' => 'required|exists:utilisateurs,id_utilisateur',

            'id_ot' => 'required|exists:ordre_travails,id_ot',
        ]);

        $affectation = Affectation::create([
            'date_affectation' => now(),
            'role_intervention' => $request->role_intervention,
            'statut' => $request->statut ?? 'assignee',
            'commentaire' => $request->commentaire,
            'id_utilisateur' => $request->id_utilisateur,
            'id_ot' => $request->id_ot,
        ]);

        return response()->json([
            'message' => 'Affectation créée avec succès.',
            'data' => $affectation
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $affectation = Affectation::with(['utilisateur', 'ordreTravail'])
            ->find($id);

        if (!$affectation) {
            return response()->json([
                'message' => 'Affectation introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $affectation
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $affectation = Affectation::find($id);

        if (!$affectation) {
            return response()->json([
                'message' => 'Affectation introuvable.'
            ], 404);
        }

        $request->validate([
            'role_intervention' => 'sometimes|string|max:255',

            'statut' => 'sometimes|in:assignee,en_cours,terminee,annulee',

            'commentaire' => 'nullable|string',

            'id_utilisateur' => 'sometimes|exists:utilisateurs,id_utilisateur',

            'id_ot' => 'sometimes|exists:ordre_travails,id_ot',
        ]);

        $affectation->update($request->only([
            'role_intervention',
            'statut',
            'commentaire',
            'id_utilisateur',
            'id_ot',
        ]));

        return response()->json([
            'message' => 'Affectation modifiée avec succès.',
            'data' => $affectation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $affectation = Affectation::find($id);

        if (!$affectation) {
            return response()->json([
                'message' => 'Affectation introuvable.'
            ], 404);
        }

        $affectation->delete();

        return response()->json([
            'message' => 'Affectation supprimée avec succès.'
        ]);
    }
    public function mesAffectations()
{
    $affectations = Affectation::with(['ordreTravail'])
        ->where('id_utilisateur', auth('api')->id())
        ->get();

    return response()->json([
        'success' => true,
        'data' => $affectations
    ]);
}
}