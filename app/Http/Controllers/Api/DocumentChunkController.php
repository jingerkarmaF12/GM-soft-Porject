<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentChunk;
use Illuminate\Http\Request;

class DocumentChunkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chunks = DocumentChunk::with('document')
            ->orderBy('id_document')
            ->orderBy('ordre_chunk')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $chunks
        ]);
    }

    /**
     * Display chunks of a specific document.
     */
    public function byDocument(string $documentId)
    {
        $chunks = DocumentChunk::where('id_document', $documentId)
            ->orderBy('ordre_chunk')
            ->get();

        if ($chunks->isEmpty()) {
            return response()->json([
                'message' => 'Aucun chunk trouvé pour ce document.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $chunks
        ]);
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',

            'ordre_chunk' => 'required|integer|min:0',

            'id_document' => 'required|exists:documents,id_document',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Calcul approximatif du nombre de tokens
        |--------------------------------------------------------------------------
        |
        | Ici on utilise une approximation simple.
        | Le nombre réel dépendra du tokenizer du modèle IA utilisé.
        |
        */

        $nombreTokens = $this->estimerTokens($request->contenu);

        $chunk = DocumentChunk::create([
            'contenu' => $request->contenu,
            'ordre_chunk' => $request->ordre_chunk,
            'nombre_tokens' => $nombreTokens,
            'id_document' => $request->id_document,
        ]);

        return response()->json([
            'message' => 'Chunk créé avec succès.',
            'data' => $chunk
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $chunk = DocumentChunk::with('document')
            ->find($id);

        if (!$chunk) {
            return response()->json([
                'message' => 'Chunk introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $chunk
        ]);
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $chunk = DocumentChunk::find($id);

        if (!$chunk) {
            return response()->json([
                'message' => 'Chunk introuvable.'
            ], 404);
        }

        $request->validate([
            'contenu' => 'sometimes|string',

            'ordre_chunk' => 'sometimes|integer|min:0',

            'id_document' => 'sometimes|exists:documents,id_document',
        ]);

        $data = [];

        if ($request->has('contenu')) {
            $data['contenu'] = $request->contenu;

            // Recalcul du nombre de tokens
            $data['nombre_tokens'] =
                $this->estimerTokens($request->contenu);
        }

        if ($request->has('ordre_chunk')) {
            $data['ordre_chunk'] = $request->ordre_chunk;
        }

        if ($request->has('id_document')) {
            $data['id_document'] = $request->id_document;
        }

        $chunk->update($data);

        return response()->json([
            'message' => 'Chunk modifié avec succès.',
            'data' => $chunk->fresh()
        ]);
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $chunk = DocumentChunk::find($id);

        if (!$chunk) {
            return response()->json([
                'message' => 'Chunk introuvable.'
            ], 404);
        }

        $chunk->delete();

        return response()->json([
            'message' => 'Chunk supprimé avec succès.'
        ]);
    }

    /**
     * Estimate number of tokens.
     *
     * This is only an approximation.
     */
    private function estimerTokens(string $contenu): int
    {
        $nombreCaracteres = mb_strlen($contenu);

        return max(1, (int) ceil($nombreCaracteres / 4));
    }
}