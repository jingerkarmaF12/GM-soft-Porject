<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationRAG extends Model
{
    protected $table = 'conversation_rags';

    protected $primaryKey = 'id_conversation';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'date_debut',
        'date_fin',
        'id_utilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function messageChats()
    {
        return $this->hasMany(MessageChat::class, 'id_conversation', 'id_conversation');
    }
}