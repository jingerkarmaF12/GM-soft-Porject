<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeIntervention extends Model
{
    protected $table = 'demande_interventions';

    protected $primaryKey = 'id_demande';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'description',
        'priorite',
        'statut',
        'date_creation',
        'date_validation',
        'id_equipement',
        'id_utilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'id_equipement', 'id_equipement');
    }

    public function ordreTravails()
    {
        return $this->hasMany(OrdreTravail::class, 'id_demande', 'id_demande');
    }
}