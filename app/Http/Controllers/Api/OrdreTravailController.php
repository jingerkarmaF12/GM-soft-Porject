<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrdreTravail;
use App\Models\DemandeIntervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OrdreTravailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordres = OrdreTravail::with(['demandeIntervention', 'equipement'])->get();

        return response()->json([
            'success' => true,
            'data' => $ordres
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
        'date_planifiee' => 'nullable|date',
        'id_demande' => 'required|integer|exists:DemandeInterventions,id_demande',
    ]);

    return DB::transaction(function () use ($request) {

        // Vérifier que la demande existe
        $demande = DemandeIntervention::find($request->id_demande);

        if (!$demande) {
            return response()->json([
                'message' => 'Demande introuvable.'
            ], 404);
        }

        // Vérifier que la demande est acceptée
        if ($demande->statut !== 'acceptee') {
            return response()->json([
                'message' => 'La demande doit être acceptée avant de créer un OT.'
            ], 422);
        }

        // Vérifier qu'un OT n'existe pas déjà
        $otExiste = OrdreTravail::where('id_demande', $demande->id_demande)->exists();

        if ($otExiste) {
            return response()->json([
                'message' => 'Cette demande possède déjà un ordre de travail.'
            ], 409);
        }

        $ordre = OrdreTravail::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'priorite' => $request->priorite,
            'statut' => 'planifie',

            'date_planifiee' => $request->date_planifiee,
            'date_debut' => null,
            'date_fin' => null,
            'temps_reel' => 0,
            'commentaire_cloture' => null,

            'id_demande' => $demande->id_demande,

            // automatiquement depuis la demande
            'id_equipement' => $demande->id_equipement,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ordre de travail créé avec succès.',
            'data' => $ordre->load(['demandeIntervention', 'equipement'])
        ], 201);
    });
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ordre = OrdreTravail::with(['demandeIntervention', 'equipement'])
            ->find($id);

        if (!$ordre) {
            return response()->json([
                'message' => 'Ordre de travail introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ordre
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
{
    $ordre = OrdreTravail::find($id);

    if (!$ordre) {
        return response()->json([
            'message' => 'Ordre de travail introuvable.'
        ], 404);
    }

    $request->validate([
        'titre' => 'sometimes|string|max:255',
        'description' => 'sometimes|string',
        'priorite' => 'sometimes|in:faible,moyenne,elevee,critique',
        'statut' => 'sometimes|in:planifie,en_cours,suspendu,termine',

        'date_planifiee' => 'nullable|date',
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',

        'temps_reel' => 'nullable|integer|min:0',

        'commentaire_cloture' => 'nullable|string',
    ]);

    $ordre->update($request->only([
        'titre',
        'description',
        'priorite',
        'statut',
        'date_planifiee',
        'date_debut',
        'date_fin',
        'temps_reel',
        'commentaire_cloture',
    ]));

    return response()->json([
        'success' => true,
        'message' => 'Ordre de travail modifié avec succès.',
        'data' => $ordre->fresh()->load(['demandeIntervention', 'equipement'])
    ]);
}

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $ordre = OrdreTravail::find($id);

        if (!$ordre) {
            return response()->json([
                'message' => 'Ordre de travail introuvable.'
            ], 404);
        }

        $ordre->delete();

        return response()->json([
            'message' => 'Ordre de travail supprimé avec succès.'
        ]);
    }
    public function AssociatedOT()
    {
        $user = auth('api')->user();

        // Vérifier si l'utilisateur est authentifié
        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        // Récupérer les ordres de travail associés à l'utilisateur
        $ordres = OrdreTravail::whereHas('demandeIntervention', function ($query) use ($user) {
            $query->where('id_utilisateur', $user->id_utilisateur);
        })->with(['demandeIntervention', 'equipement'])->get();

        return response()->json([
            'success' => true,
            'data' => $ordres
        ]);

    }
}