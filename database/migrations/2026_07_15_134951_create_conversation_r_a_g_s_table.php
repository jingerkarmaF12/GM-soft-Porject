<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_rags', function (Blueprint $table) {

            $table->increments('id_conversation');

            $table->string('titre');

            $table->timestamp('date_debut')->useCurrent();

            $table->timestamp('date_fin')->nullable();

            $table->unsignedInteger('id_utilisateur');

            $table->foreign('id_utilisateur')
                ->references('id_utilisateur')
                ->on('utilisateurs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_rags');
    }
};