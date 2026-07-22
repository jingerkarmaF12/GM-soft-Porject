<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageChat extends Model
{
    protected $table = 'message_chats';

    protected $primaryKey = 'id_message';

    public $timestamps = false;

    protected $fillable = [
        'contenu',
        'type_message',
        'date_envoi',
        'sources',
        'id_conversation',
    ];

    public function conversationRAG()
    {
        return $this->belongsTo(ConversationRAG::class, 'id_conversation', 'id_conversation');
    }
}