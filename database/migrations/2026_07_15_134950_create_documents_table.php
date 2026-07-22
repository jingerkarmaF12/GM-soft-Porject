<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {

            $table->increments('id_document');

            $table->string('titre');

            $table->string('nom_fichier');

            $table->string('type_fichier');

            $table->string('chemin_fichier');

            $table->unsignedBigInteger('taille');

            $table->string('version')->default('1.0');

            $table->timestamp('date_importation')->useCurrent();

            $table->text('description')->nullable();

            $table->unsignedInteger('id_equipement')->nullable();

            $table->foreign('id_equipement')
                ->references('id_equipement')
                ->on('equipements')
                ->cascadeOnUpdate()
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};