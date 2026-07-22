<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordre_travails', function (Blueprint $table) {

            $table->increments('id_ot');

            $table->string('titre');
            $table->text('description');

            $table->enum('priorite', [
                'faible',
                'moyenne',
                'elevee',
                'critique'
            ]);

            $table->enum('statut', [
                'planifie',
                'en_cours',
                'suspendu',
                'termine'
            ])->default('planifie');

            $table->dateTime('date_planifiee')->nullable();
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();

            $table->integer('temps_reel')->nullable();

            $table->text('commentaire_cloture')->nullable();

            $table->unsignedInteger('id_demande');
            $table->unsignedInteger('id_equipement');

            $table->foreign('id_demande')
                ->references('id_demande')
                ->on('demande_interventions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('id_equipement')
                ->references('id_equipement')
                ->on('equipements')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordre_travails');
    }
};