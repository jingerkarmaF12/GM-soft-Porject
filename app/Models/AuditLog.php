<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $primaryKey = 'id_audit';

    public $timestamps = false;

    protected $fillable = [
        'action',
        'nom_table',
        'id_enregistrement',
        'ancienne_valeur',
        'nouvelle_valeur',
        'adresse_ip',
        'navigateur',
        'date_action',
        'id_utilisateur',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur', 'id_utilisateur');
    }
}