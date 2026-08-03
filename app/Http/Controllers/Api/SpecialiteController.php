<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Specialite;
use Illuminate\Http\Request;

class SpecialiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialites = Specialite::all();

        return response()->json([
            'success' => true,
            'data' => $specialites
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_specialite' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $specialite = Specialite::create([
            'nom_specialite' => $request->nom_specialite,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Spécialité créée avec succès',
            'data' => $specialite
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $specialite = Specialite::find($id);

        if (!$specialite) {
            return response()->json([
                'message' => 'Spécialité introuvable'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $specialite
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $specialite = Specialite::find($id);

        if (!$specialite) {
            return response()->json([
                'message' => 'Spécialité introuvable'
            ], 404);
        }

        $request->validate([
            'nom_specialite' => 'sometimes|string|max:100',
            'description' => 'nullable|string|max:255',
        ]);

        $specialite->update($request->only([
            'nom_specialite',
            'description'
        ]));

        return response()->json([
            'message' => 'Spécialité modifiée avec succès',
            'data' => $specialite
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specialite = Specialite::find($id);

        if (!$specialite) {
            return response()->json([
                'message' => 'Spécialité introuvable'
            ], 404);
        }

        $specialite->delete();

        return response()->json([
            'message' => 'Spécialité supprimée avec succès'
        ]);
    }
}