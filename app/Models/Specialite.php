<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    protected $table = 'specialites';

    protected $primaryKey = 'id_specialite';

    protected $fillable = [
        'nom_specialite',
        'description',
    ];

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'id_specialite', 'id_specialite');
    }
}