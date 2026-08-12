<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditObserver
{
    /**
     * Enregistrer une création.
     */
    public function created(Model $model): void
    {
        if ($model instanceof AuditLog) {
            return;
        }

        AuditLog::create([
            'action' => 'CREATE',
            'nom_table' => $model->getTable(),
            'id_enregistrement' => $model->getKey(),
            'ancienne_valeur' => null,
            'nouvelle_valeur' => json_encode($model->toArray(), JSON_UNESCAPED_UNICODE),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_action' => now(),
            'id_utilisateur' => Auth::id() ?? $model->getKey(),
        ]);
    }

    /**
     * Enregistrer une modification.
     */
    public function updated(Model $model): void
    {
        if ($model instanceof AuditLog) {
            return;
        }

        AuditLog::create([
            'action' => 'UPDATE',
            'nom_table' => $model->getTable(),
            'id_enregistrement' => $model->getKey(),
            'ancienne_valeur' => json_encode($model->getOriginal(), JSON_UNESCAPED_UNICODE),
            'nouvelle_valeur' => json_encode($model->fresh()->toArray(), JSON_UNESCAPED_UNICODE),
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_action' => now(),
            'id_utilisateur' => Auth::id(),
        ]);
    }

    /**
     * Enregistrer une suppression.
     */
    public function deleting(Model $model): void
    {
        if ($model instanceof AuditLog) {
            return;
        }

        AuditLog::create([
            'action' => 'DELETE',
            'nom_table' => $model->getTable(),
            'id_enregistrement' => $model->getKey(),
            'ancienne_valeur' => json_encode($model->toArray(), JSON_UNESCAPED_UNICODE),
            'nouvelle_valeur' => null,
            'adresse_ip' => request()->ip(),
            'navigateur' => request()->userAgent(),
            'date_action' => now(),
            'id_utilisateur' => Auth::id(),
        ]);
    }
}