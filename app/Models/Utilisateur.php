<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Utilisateur extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'telephone',
        'photo_profil',
        'statut',
        'id_role',
        'id_specialite',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    /**
     * Laravel Authentication
     * Bach y3ref belli password hiya mot_de_passe
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * JWT
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id_role');
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class, 'id_specialite', 'id_specialite');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function demandeInterventions()
    {
        return $this->hasMany(DemandeIntervention::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_utilisateur', 'id_utilisateur');
    }

    public function conversationRags()
    {
        return $this->hasMany(ConversationRAG::class, 'id_utilisateur', 'id_utilisateur');
    }
}