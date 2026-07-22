<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demande_interventions', function (Blueprint $table) {

            $table->increments('id_demande');

            $table->string('titre');
            $table->text('description');

            $table->enum('priorite', [
                'faible',
                'moyenne',
                'elevee',
                'critique'
            ])->default('faible');

            $table->enum('statut', [
                'en_attente',
                'validee',
                'refusee',
                'en_cours',
                'terminee'
            ])->default('en_attente');

            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('date_validation')->nullable();

            $table->unsignedInteger('id_equipement');
            $table->unsignedInteger('id_utilisateur');

            $table->foreign('id_equipement')
                ->references('id_equipement')
                ->on('equipements')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')
                ->on('utilisateurs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demande_interventions');
    }
};