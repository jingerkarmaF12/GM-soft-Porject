<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    protected $table = 'affectations';

    protected $primaryKey = 'id_affectation';

    public $timestamps = false;

    protected $fillable = [
        'date_affectation',
        'role_intervention',
        'statut',
        'commentaire',
        'id_utilisateur',
        'id_ot',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function ordreTravail()
    {
        return $this->belongsTo(OrdreTravail::class, 'id_ot', 'id_ot');
    }
}