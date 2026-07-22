<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panne extends Model
{
    protected $table = 'pannes';

    protected $primaryKey = 'id_panne';

    public $timestamps = false;

    protected $fillable = [
        'titre',
        'description',
        'gravite',
        'date_detection',
        'cause',
        'solution',
        'symptomes',
        'id_equipement',
        'id_ot',
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipement::class, 'id_equipement', 'id_equipement');
    }

    public function ordreTravail()
    {
        return $this->belongsTo(OrdreTravail::class, 'id_ot', 'id_ot');
    }
}