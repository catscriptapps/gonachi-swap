<?php
// /scripts/reset/unit-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\UnitType;

/**
 * Resets the unit_types table and seeds default property options.
 */
function resetUnitTypesTable(): array
{
    $messages = [];

    try {
        $model = new UnitType();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('unit_type_id');
            $table->string('unit_type', 150);
            $table->boolean('for_sale')->default(false);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $units = [
            ['unit_type_id' => 1, 'unit_type' => 'Apartment', 'for_sale' => 0],
            ['unit_type_id' => 2, 'unit_type' => 'Basement', 'for_sale' => 0],
            ['unit_type_id' => 3, 'unit_type' => 'Condo', 'for_sale' => 1],
            ['unit_type_id' => 4, 'unit_type' => 'Duplex / Triplex', 'for_sale' => 1],
            ['unit_type_id' => 5, 'unit_type' => 'House', 'for_sale' => 1],
            ['unit_type_id' => 6, 'unit_type' => 'Shared Accomodation Bedrooms', 'for_sale' => 0],
            ['unit_type_id' => 7, 'unit_type' => 'Shared Accomodation House', 'for_sale' => 0],
            ['unit_type_id' => 8, 'unit_type' => 'Cottage Rentals', 'for_sale' => 0],
        ];

        foreach ($units as $unit) {
            UnitType::create($unit);
        }

        $messages[] = "successfully seeded " . count($units) . " Unit Types.";
    } catch (\Throwable $e) {
        $messages[] = 'unit types table error: ' . $e->getMessage();
    }

    return $messages;
}
