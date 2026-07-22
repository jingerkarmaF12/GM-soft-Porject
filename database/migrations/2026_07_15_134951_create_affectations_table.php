<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {

            $table->increments('id_affectation');

            $table->dateTime('date_affectation')->useCurrent();

            $table->string('role_intervention');

            $table->enum('statut', [
                'assignee',
                'en_cours',
                'terminee',
                'annulee'
            ])->default('assignee');

            $table->text('commentaire')->nullable();

            $table->unsignedInteger('id_utilisateur');
            $table->unsignedInteger('id_ot');

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')
                ->on('utilisateurs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('id_ot')
                ->references('id_ot')
                ->on('ordre_travails')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};