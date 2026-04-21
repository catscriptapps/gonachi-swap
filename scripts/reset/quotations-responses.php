<?php
// /scripts/reset/quotations-responses.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\QuotationResponse;

/**
 * Resets the quotations-responses table 💎
 */
function resetQuotationResponsesTable(): array
{
    $messages = [];
    $tableName = (new QuotationResponse())->getTable();

    try {
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // The user sending the response/bid
            $table->unsignedBigInteger('sender_id')->index();

            // The target Quotation ID
            $table->unsignedBigInteger('quotation_id')->index();

            // Handshake State
            $table->string('status', 20)->default('pending');

            // The message/pitch
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
