<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {

            $table->increments('id_utilisateur');

            $table->string('nom',100);
            $table->string('prenom',100);

            $table->string('email')->unique();

            $table->string('mot_de_passe');

            $table->string('telephone',20)->nullable();

            $table->string('photo_profil')->nullable();

            $table->enum('statut',[
                'actif',
                'inactif',
                'suspendu'
            ])->default('actif');

            $table->unsignedInteger('id_role');

            $table->unsignedInteger('id_specialite')->nullable();

            $table->timestamps();

            $table->foreign('id_role')
                ->references('id_role')
                ->on('roles')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('id_specialite')
                ->references('id_specialite')
                ->on('specialites')
                ->cascadeOnUpdate()
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};