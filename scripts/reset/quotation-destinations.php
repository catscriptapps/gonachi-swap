<?php
// /scripts/reset/quotation-destinations.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\QuotationDestination;

/**
 * Resets the quotations_destinations table and seeds visibility scopes.
 */
function resetQuotationDestinationsTable(): array
{
    $messages = [];

    try {
        $model = new QuotationDestination();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('quotation_dest_id');
            $table->string('quotation_dest', 150);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $destinations = [
            ['quotation_dest_id' => 1, 'quotation_dest' => 'Contractors Within Region'],
            ['quotation_dest_id' => 2, 'quotation_dest' => 'Contractors Within Country'],
        ];

        foreach ($destinations as $dest) {
            QuotationDestination::create($dest);
        }

        $messages[] = "successfully seeded " . count($destinations) . " quotation destinations.";
    } catch (\Throwable $e) {
        $messages[] = 'quotation destinations table error: ' . $e->getMessage();
    }

    return $messages;
}
