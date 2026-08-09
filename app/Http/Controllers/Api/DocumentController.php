<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index()
    {
        $documents = Document::with('equipement')->get();

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',

            'fichier' => [
                'required',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png'
            ],

            'version' => 'nullable|string|max:50',

            'description' => 'nullable|string',

            'id_equipement' => 'nullable|exists:equipements,id_equipement',
        ]);

        $fichier = $request->file('fichier');

        // Nom original du fichier
        $nomFichier = $fichier->getClientOriginalName();

        // Type MIME
        $typeFichier = $fichier->getClientMimeType();

        // Taille en bytes
        $taille = $fichier->getSize();

        // Stockage du fichier
        $cheminFichier = $fichier->store('documents', 'public');

        // Création du document
        $document = Document::create([
            'titre' => $request->titre,
            'nom_fichier' => $nomFichier,
            'type_fichier' => $typeFichier,
            'chemin_fichier' => $cheminFichier,
            'taille' => $taille,
            'version' => $request->version ?? '1.0',
            'date_importation' => now(),
            'description' => $request->description,
            'id_equipement' => $request->id_equipement,
        ]);

        return response()->json([
            'message' => 'Document importé avec succès.',
            'data' => $document,
            'url' => asset('storage/' . $cheminFichier)
        ], 201);
    }

    /**
     * Display the specified document.
     */
    public function show(string $id)
    {
        $document = Document::with('equipement')->find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $document,
            'url' => asset('storage/' . $document->chemin_fichier)
        ]);
    }

    /**
     * Update the specified document.
     */
    public function update(Request $request, string $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable.'
            ], 404);
        }

        $request->validate([
            'titre' => 'sometimes|string|max:255',

            'fichier' => [
                'nullable',
                'file',
                'max:20480',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png'
            ],

            'version' => 'sometimes|string|max:50',

            'description' => 'nullable|string',

            'id_equipement' => 'nullable|exists:equipements,id_equipement',
        ]);

        $data = [
            'titre' => $request->titre ?? $document->titre,
            'version' => $request->version ?? $document->version,
            'description' => $request->description,
            'id_equipement' => $request->id_equipement,
        ];

        /*
        |--------------------------------------------------------------------------
        | Si un nouveau fichier est envoyé
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('fichier')) {

            // Supprimer l'ancien fichier
            if (
                $document->chemin_fichier &&
                Storage::disk('public')->exists($document->chemin_fichier)
            ) {
                Storage::disk('public')->delete(
                    $document->chemin_fichier
                );
            }

            $fichier = $request->file('fichier');

            $cheminFichier = $fichier->store(
                'documents',
                'public'
            );

            $data['nom_fichier'] = $fichier->getClientOriginalName();
            $data['type_fichier'] = $fichier->getClientMimeType();
            $data['taille'] = $fichier->getSize();
            $data['chemin_fichier'] = $cheminFichier;
            $data['date_importation'] = now();
        }

        $document->update($data);

        return response()->json([
            'message' => 'Document modifié avec succès.',
            'data' => $document->fresh(),
            'url' => asset('storage/' . $document->chemin_fichier)
        ]);
    }

    /**
     * Remove the specified document.
     */
    public function destroy(string $id)
    {
        $document = Document::find($id);

        if (!$document) {
            return response()->json([
                'message' => 'Document introuvable.'
            ], 404);
        }

        // Supprimer le fichier physique
        if (
            $document->chemin_fichier &&
            Storage::disk('public')->exists($document->chemin_fichier)
        ) {
            Storage::disk('public')->delete(
                $document->chemin_fichier
            );
        }

        // Supprimer le record
        $document->delete();

        return response()->json([
            'message' => 'Document supprimé avec succès.'
        ]);
    }
}