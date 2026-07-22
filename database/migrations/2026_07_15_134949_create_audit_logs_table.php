<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {

            $table->increments('id_audit');

            $table->string('action');
            $table->string('nom_table');
            $table->unsignedBigInteger('id_enregistrement');

            $table->longText('ancienne_valeur')->nullable();
            $table->longText('nouvelle_valeur')->nullable();

            $table->string('adresse_ip',45)->nullable();
            $table->string('navigateur')->nullable();

            $table->timestamp('date_action')->useCurrent();

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
        Schema::dropIfExists('audit_logs');
    }
};