<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EquipementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $equipements = Equipement::all();

        return response()->json([
            'success' => true,
            'data' => $equipements
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
{
    $request->validate([
        'nom_equipement' => 'required|string|max:255',
        'marque' => 'nullable|string|max:255',
        'modele' => 'nullable|string|max:255',
        'numero_serie' => 'required|string|unique:equipements,numero_serie',
        'date_acquisition' => 'nullable|date',
        'date_mise_service' => 'nullable|date',
        'etat' => 'required|in:fonctionnel,en_panne,maintenance,hors_service',
        'criticite' => 'required|in:faible,moyenne,elevee,critique',
        'description' => 'nullable|string',
        'localisation' => 'nullable|string|max:255',
    ]);

    $signature = hash_hmac(
        'sha256',
        $request->nom_equipement .
        $request->numero_serie .
        now()->timestamp,
        config('app.key')
    );

    $equipement = Equipement::create([
        'nom_equipement' => $request->nom_equipement,
        'marque' => $request->marque,
        'modele' => $request->modele,
        'numero_serie' => $request->numero_serie,
        'date_acquisition' => $request->date_acquisition,
        'date_mise_service' => $request->date_mise_service,
        'etat' => $request->etat,
        'criticite' => $request->criticite,
        'description' => $request->description,
        'localisation' => $request->localisation,
        'code_qr' => '',
        'signature_cryptographique' => $signature,
    ]);

    
    $equipement->code_qr = url('/api/equipements/' . $equipement->id_equipement);

    $equipement->save();

    return response()->json([
        'message' => 'Equipement créé avec succès.',
        'data' => $equipement
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipement = Equipement::find($id);

        if (!$equipement) {
            return response()->json([
                'message' => 'Equipement introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $equipement
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $equipement = Equipement::find($id);

        if (!$equipement) {
            return response()->json([
                'message' => 'Equipement introuvable.'
            ], 404);
        }

        $request->validate([
            'nom_equipement' => 'sometimes|string|max:255',
            'marque' => 'nullable|string|max:255',
            'modele' => 'nullable|string|max:255',

            'numero_serie' => [
                'sometimes',
                'string',
                Rule::unique('equipements', 'numero_serie')
                    ->ignore($equipement->id_equipement, 'id_equipement'),
            ],

            'date_acquisition' => 'nullable|date',
            'date_mise_service' => 'nullable|date',

            'etat' => 'sometimes|in:fonctionnel,en_panne,maintenance,hors_service',

            'criticite' => 'sometimes|in:faible,moyenne,elevee,critique',

            'description' => 'nullable|string',

            'localisation' => 'nullable|string|max:255',

        ]);

        $equipement->update($request->only([
    'nom_equipement',
    'marque',
    'modele',
    'numero_serie',
    'date_acquisition',
    'date_mise_service',
    'etat',
    'criticite',
    'description',
    'localisation',
]));

        return response()->json([
            'message' => 'Equipement modifié avec succès.',
            'data' => $equipement
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $equipement = Equipement::find($id);

        if (!$equipement) {
            return response()->json([
                'message' => 'Equipement introuvable.'
            ], 404);
        }

        $equipement->delete();

        return response()->json([
            'message' => 'Equipement supprimé avec succès.'
        ]);
    }
    
public function qr($id)
{
    $equipement = Equipement::findOrFail($id);

    return QrCode::size(300)
        ->generate($equipement->code_qr);
}
}