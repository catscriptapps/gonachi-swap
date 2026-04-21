<?php
// /scripts/reset/house-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\HouseType;

/**
 * Resets the house_types table and seeds architectural categories.
 */
function resetHouseTypesTable(): array
{
    $messages = [];

    try {
        $model = new HouseType();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('house_type_id');
            $table->string('house_type', 150);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $houses = [
            ['house_type_id' => 1, 'house_type' => 'Detached House'],
            ['house_type_id' => 2, 'house_type' => 'Semi-Detached House'],
            ['house_type_id' => 3, 'house_type' => 'Town House'],
            ['house_type_id' => 4, 'house_type' => 'Bungalow'],
            ['house_type_id' => 5, 'house_type' => 'Cottage'],
        ];

        foreach ($houses as $house) {
            HouseType::create($house);
        }

        $messages[] = "successfully seeded " . count($houses) . " house types.";
    } catch (\Throwable $e) {
        $messages[] = 'house types table error: ' . $e->getMessage();
    }

    return $messages;
}
