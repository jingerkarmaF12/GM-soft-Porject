<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    protected $table = 'equipements';

    protected $primaryKey = 'id_equipement';

    protected $fillable = [
        'nom_equipement',
        'marque',
        'modele',
        'numero_serie',
        'date_acquisition',
        'date_mise_service',
        'etat',
        'criticite',
        'description',
        'localisation',
        'code_qr',
        'signature_cryptographique',
    ];

    public function demandeInterventions()
    {
        return $this->hasMany(DemandeIntervention::class, 'id_equipement', 'id_equipement');
    }

    public function ordreTravails()
    {
        return $this->hasMany(OrdreTravail::class, 'id_equipement', 'id_equipement');
    }

    public function pannes()
    {
        return $this->hasMany(Panne::class, 'id_equipement', 'id_equipement');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'id_equipement', 'id_equipement');
    }
}