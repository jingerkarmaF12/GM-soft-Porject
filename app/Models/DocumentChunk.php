<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentChunk extends Model
{
    protected $table = 'document_chunks';

    protected $primaryKey = 'id_chunk';

    public $timestamps = false;

    protected $fillable = [
        'contenu',
        'ordre_chunk',
        'nombre_tokens',
        'id_document',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'id_document', 'id_document');
    }
}