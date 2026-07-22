<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipements', function (Blueprint $table) {

            $table->increments('id_equipement');

            $table->string('nom_equipement');
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();

            $table->string('numero_serie')->unique();

            $table->date('date_acquisition')->nullable();
            $table->date('date_mise_service')->nullable();

            $table->enum('etat',[
                'fonctionnel',
                'en_panne',
                'maintenance',
                'hors_service'
            ])->default('fonctionnel');

            $table->enum('criticite',[
                'faible',
                'moyenne',
                'elevee',
                'critique'
            ])->default('faible');

            $table->text('description')->nullable();

            $table->string('localisation')->nullable();

            $table->string('code_qr')->unique();

            $table->text('signature_cryptographique');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};