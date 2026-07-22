<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdreTravail extends Model
{
    protected $table = 'ordre_travails';

    protected $primaryKey = 'id_ot';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'description',
        'priorite',
        'statut',
        'date_planifiee',
        'date_debut',
        'date_fin',
        'temps_reel',
        'commentaire_cloture',
        'id_demande',
        'id_equipement',
    ];

    public function demandeIntervention()
    {
        return $this->belongsTo(DemandeIntervention::class, 'id_demande', 'id_demande');
    }

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'id_equipement', 'id_equipement');
    }

    public function pannes()
    {
        return $this->hasMany(Panne::class, 'id_ot', 'id_ot');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_ot', 'id_ot');
    }
}