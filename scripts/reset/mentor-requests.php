<?php
// /scripts/reset/mentors-requests.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\MentorRequest;

/**
 * Resets the mentors_requests table to match the simplified model 💎
 */
function resetMentorsRequestsTable(): array
{
    $messages = [];
    $tableName = (new MentorRequest())->getTable();

    try {
        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // The user requesting help
            $table->unsignedBigInteger('sender_id')->index();

            // The Mentor Card ID
            $table->unsignedBigInteger('mentor_id')->index();

            // Handshake State
            $table->string('status', 20)->default('pending'); // pending, accepted, declined

            // Initial pitch message (renamed to 'message' to match the model)
            $table->text('message')->nullable();

            // Standard Eloquent Timestamps
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('sender_id')
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
