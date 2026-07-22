<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_chunks', function (Blueprint $table) {

            $table->increments('id_chunk');

            $table->longText('contenu');

            $table->integer('ordre_chunk');

            $table->integer('nombre_tokens');

            $table->unsignedInteger('id_document');

            $table->foreign('id_document')
                ->references('id_document')
                ->on('documents')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_chunks');
    }
};