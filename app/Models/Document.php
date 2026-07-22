<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $primaryKey = 'id_document';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'nom_fichier',
        'type_fichier',
        'chemin_fichier',
        'taille',
        'version',
        'date_importation',
        'description',
        'id_equipement',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'id_equipement', 'id_equipement');
    }

    public function documentChunks()
    {
        return $this->hasMany(DocumentChunk::class, 'id_document', 'id_document');
    }
}