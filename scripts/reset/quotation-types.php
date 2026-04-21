<?php
// /scripts/reset/quotation-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\QuotationType;

/**
 * Resets the quotations_types table and seeds default labor options.
 */
function resetQuotationTypesTable(): array
{
    $messages = [];

    try {
        $model = new QuotationType();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('quotation_type_id');
            $table->string('quotation_type', 150);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $types = [
            ['quotation_type_id' => 1, 'quotation_type' => 'Labour Only'],
            ['quotation_type_id' => 2, 'quotation_type' => 'Labour And Materials'],
        ];

        foreach ($types as $type) {
            QuotationType::create($type);
        }

        $messages[] = "successfully seeded " . count($types) . " quotation types.";
    } catch (\Throwable $e) {
        $messages[] = 'quotation types table error: ' . $e->getMessage();
    }

    return $messages;
}
