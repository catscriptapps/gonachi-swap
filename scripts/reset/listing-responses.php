<?php
// /scripts/reset/listing-responses.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingResponse;

/**
 * Resets the listings-responses table 💎
 */
function resetListingResponsesTable(): array
{
    $messages = [];
    $tableName = (new ListingResponse())->getTable();

    try {
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // The user inquiring/responding to the listing
            $table->unsignedBigInteger('sender_id')->index();

            // The target Listing ID
            $table->unsignedBigInteger('listing_id')->index();

            // Handshake State
            $table->string('status', 20)->default('pending');

            // The inquiry message
            $table->text('message')->nullable();

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
