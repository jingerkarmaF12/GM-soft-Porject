<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_chats', function (Blueprint $table) {

            $table->increments('id_message');

            $table->longText('contenu');

            $table->enum('type_message', [
                'user',
                'assistant',
                'system'
            ]);

            $table->timestamp('date_envoi')->useCurrent();

            $table->longText('sources')->nullable();

            $table->unsignedInteger('id_conversation');

            $table->foreign('id_conversation')
                ->references('id_conversation')
                ->on('conversation_rags')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_chats');
    }
};