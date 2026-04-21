<?php
// /scripts/reset/conversation-messages.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ConversationMessage;

/**
 * Resets the conversation_messages table for the "WhatsApp" flow 💎
 */
function resetConversationMessagesTable(): array
{
    $messages = [];
    $tableName = (new ConversationMessage())->getTable();

    try {
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // Link to the thread
            $table->unsignedBigInteger('conversation_id')->index();

            // The sender
            $table->unsignedBigInteger('sender_id')->index();

            // Content handling
            $table->text('message_text')->nullable();
            $table->string('message_type', 20)->default('text'); // text, image, video, youtube
            $table->string('attachment_url')->nullable();

            // WhatsApp "Read" status logic
            $table->boolean('is_read')->default(false)->index();

            $table->timestamps();

            // Foreign Keys
            $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
