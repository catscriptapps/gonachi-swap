<?php
// /scripts/reset/notifications.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Notification;

function resetNotificationsTable(): array
{
    $messages = [];
    $tableName = (new Notification())->getTable();

    try {
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // Core IDs
            $table->unsignedBigInteger('receiver_id')->index();
            $table->unsignedBigInteger('sender_id')->nullable()->index();

            // Metadata
            $table->string('type', 50)->index();
            $table->unsignedBigInteger('target_id')->nullable()->index();
            $table->string('target_status', 50)->default('pending')->index();

            // Content
            $table->string('subject', 255);
            $table->text('notification_message');

            // State
            $table->boolean('is_read')->default(false)->index();

            $table->timestamps();

            // Foreign Key to Users (Receiver)
            $table->foreign('receiver_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
