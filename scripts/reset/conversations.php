<?php
// /scripts/reset/conversations.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Conversation;

/**
 * Resets the conversations table to match the high-performance chat model 💎
 */
function resetConversationsTable(): array
{
    $messages = [];
    $tableName = (new Conversation())->getTable();

    try {
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // User pair (Ordered: user_one_id always < user_two_id for uniqueness)
            $table->unsignedBigInteger('user_one_id')->index();
            $table->unsignedBigInteger('user_two_id')->index();

            // Crucial for ordering the "Inbox" view without massive JOINs
            $table->timestamp('last_message_at')->nullable()->index();

            $table->timestamps();

            // Ensure we never have two separate threads for the same two people
            $table->unique(['user_one_id', 'user_two_id']);

            $table->foreign('user_one_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two_id')->references('id')->on('users')->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
