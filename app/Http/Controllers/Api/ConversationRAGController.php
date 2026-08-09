<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConversationRAG;
use Illuminate\Http\Request;

class ConversationRAGController extends Controller
{
    /**
     * Display a listing of the conversations
     * of the authenticated user.
     */
    public function index()
    {
        $idUtilisateur = auth('api')->id();

        $conversations = ConversationRAG::where(
            'id_utilisateur',
            $idUtilisateur
        )
            ->withCount('messageChats')
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $conversations
        ]);
    }

    /**
     * Store a newly created conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
        ]);

        $conversation = ConversationRAG::create([
            'titre' => $request->titre,
            'date_debut' => now(),
            'date_fin' => null,
            'id_utilisateur' => auth('api')->id(),
        ]);

        return response()->json([
            'message' => 'Conversation créée avec succès.',
            'data' => $conversation
        ], 201);
    }

    /**
     * Display the specified conversation.
     */
    public function show(string $id)
    {
        $conversation = ConversationRAG::with([
            'utilisateur',
            'messageChats'
        ])
            ->where('id_conversation', $id)
            ->where('id_utilisateur', auth('api')->id())
            ->first();

        if (!$conversation) {
            return response()->json([
                'message' => 'Conversation introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $conversation
        ]);
    }

    /**
     * Update the specified conversation.
     */
    public function update(Request $request, string $id)
    {
        $conversation = ConversationRAG::where(
            'id_conversation',
            $id
        )
            ->where(
                'id_utilisateur',
                auth('api')->id()
            )
            ->first();

        if (!$conversation) {
            return response()->json([
                'message' => 'Conversation introuvable.'
            ], 404);
        }

        $request->validate([
            'titre' => 'sometimes|string|max:255',

            'date_fin' => 'nullable|date',
        ]);

        $conversation->update(
            $request->only([
                'titre',
                'date_fin'
            ])
        );

        return response()->json([
            'message' => 'Conversation modifiée avec succès.',
            'data' => $conversation
        ]);
    }

    /**
     * Close the conversation.
     */
    public function close(string $id)
    {
        $conversation = ConversationRAG::where(
            'id_conversation',
            $id
        )
            ->where(
                'id_utilisateur',
                auth('api')->id()
            )
            ->first();

        if (!$conversation) {
            return response()->json([
                'message' => 'Conversation introuvable.'
            ], 404);
        }

        $conversation->update([
            'date_fin' => now()
        ]);

        return response()->json([
            'message' => 'Conversation clôturée avec succès.',
            'data' => $conversation
        ]);
    }

    /**
     * Remove the specified conversation.
     */
    public function destroy(string $id)
    {
        $conversation = ConversationRAG::where(
            'id_conversation',
            $id
        )
            ->where(
                'id_utilisateur',
                auth('api')->id()
            )
            ->first();

        if (!$conversation) {
            return response()->json([
                'message' => 'Conversation introuvable.'
            ], 404);
        }

        $conversation->delete();

        return response()->json([
            'message' => 'Conversation supprimée avec succès.'
        ]);
    }
}