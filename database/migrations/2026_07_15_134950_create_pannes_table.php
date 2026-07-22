<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pannes', function (Blueprint $table) {

            $table->increments('id_panne');

            $table->string('titre');

            $table->text('description');

            $table->enum('gravite', [
                'faible',
                'moyenne',
                'grave',
                'critique'
            ]);

            $table->dateTime('date_detection');

            $table->text('cause')->nullable();

            $table->text('solution')->nullable();

            $table->text('symptomes')->nullable();

            $table->unsignedInteger('id_equipement');

            $table->unsignedInteger('id_ot');

            $table->foreign('id_equipement')
                ->references('id_equipement')
                ->on('equipements')
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
        Schema::dropIfExists('pannes');
    }
};