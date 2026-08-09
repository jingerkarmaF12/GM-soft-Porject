<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageChat;
use Illuminate\Http\Request;

class MessageChatController extends Controller
{
    /**
     * Display a listing of all messages.
     */
    public function index()
    {
        $messages = MessageChat::with('conversationRAG')
            ->orderBy('date_envoi', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Display messages of a specific conversation.
     */
    public function byConversation(string $conversationId)
    {
        $messages = MessageChat::where(
            'id_conversation',
            $conversationId
        )
            ->orderBy('date_envoi', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contenu' => 'required|string',

            'type_message' => [
                'required',
                'in:user,assistant,system'
            ],

            'sources' => 'nullable',

            'id_conversation' => [
                'required',
                'exists:conversation_rags,id_conversation'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Préparation des sources
        |--------------------------------------------------------------------------
        */

        $sources = null;

        if ($request->has('sources')) {

            if (is_array($request->sources)) {
                $sources = json_encode(
                    $request->sources,
                    JSON_UNESCAPED_UNICODE
                );
            } else {
                $sources = $request->sources;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Création du message
        |--------------------------------------------------------------------------
        */

        $message = MessageChat::create([
            'contenu' => $request->contenu,
            'type_message' => $request->type_message,
            'date_envoi' => now(),
            'sources' => $sources,
            'id_conversation' => $request->id_conversation,
        ]);

        return response()->json([
            'message' => 'Message créé avec succès.',
            'data' => $message
        ], 201);
    }

    /**
     * Display the specified message.
     */
    public function show(string $id)
    {
        $message = MessageChat::with('conversationRAG')
            ->find($id);

        if (!$message) {
            return response()->json([
                'message' => 'Message introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Update the specified message.
     */
    public function update(Request $request, string $id)
    {
        $message = MessageChat::find($id);

        if (!$message) {
            return response()->json([
                'message' => 'Message introuvable.'
            ], 404);
        }

        $request->validate([
            'contenu' => 'sometimes|string',

            'type_message' => [
                'sometimes',
                'in:user,assistant,system'
            ],

            'sources' => 'nullable',

            'id_conversation' => [
                'sometimes',
                'exists:conversation_rags,id_conversation'
            ],
        ]);

        $data = [];

        if ($request->has('contenu')) {
            $data['contenu'] = $request->contenu;
        }

        if ($request->has('type_message')) {
            $data['type_message'] = $request->type_message;
        }

        if ($request->has('sources')) {

            if (is_array($request->sources)) {
                $data['sources'] = json_encode(
                    $request->sources,
                    JSON_UNESCAPED_UNICODE
                );
            } else {
                $data['sources'] = $request->sources;
            }
        }

        if ($request->has('id_conversation')) {
            $data['id_conversation'] = $request->id_conversation;
        }

        $message->update($data);

        return response()->json([
            'message' => 'Message modifié avec succès.',
            'data' => $message->fresh()
        ]);
    }

    /**
     * Remove the specified message.
     */
    public function destroy(string $id)
    {
        $message = MessageChat::find($id);

        if (!$message) {
            return response()->json([
                'message' => 'Message introuvable.'
            ], 404);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message supprimé avec succès.'
        ]);
    }
}