<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Panne;
use App\Models\OrdreTravail;
use Illuminate\Http\Request;

class PanneController extends Controller
{
    /**
     * Liste de toutes les pannes.
     */
    public function index()
    {
        $pannes = Panne::with(['equipement', 'ordreTravail'])->get();

        return response()->json([
            'success' => true,
            'data' => $pannes
        ]);
    }

    /**
     * Créer une panne (historique).
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',

            'gravite' => 'required|in:faible,moyenne,grave,critique',

            'cause' => 'nullable|string',

            'solution' => 'nullable|string',

            'symptomes' => 'nullable|string',

            'id_ot' => 'required|exists:ordre_travails,id_ot',
        ]);

        $ordreTravail = OrdreTravail::findOrFail($request->id_ot);

        
if (Panne::where('id_ot', $ordreTravail->id_ot)->exists()) {
    return response()->json([
        'message' => 'Une panne est déjà enregistrée pour cet ordre de travail.'
    ], 409);
}

$panne = Panne::create([
    'titre' => $request->titre,
    'description' => $request->description,
    'gravite' => $request->gravite,
    'date_detection' => now(),
    'cause' => $request->cause,
    'solution' => $request->solution,
    'symptomes' => $request->symptomes,

    // كنجيب id_equipement من OT
    'id_equipement' => $ordreTravail->id_equipement,

    'id_ot' => $ordreTravail->id_ot,
]);

        return response()->json([
            'message' => 'Panne enregistrée avec succès.',
            'data' => $panne
        ], 201);
    }

    /**
     * Afficher une panne.
     */
    public function show(string $id)
    {
        $panne = Panne::with(['equipement', 'ordreTravail'])->find($id);

        if (!$panne) {
            return response()->json([
                'message' => 'Panne introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $panne
        ]);
    }
}