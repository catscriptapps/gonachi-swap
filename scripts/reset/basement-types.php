<?php
// /scripts/reset/basement-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\BasementType;

function resetBasementTypesTable(): array
{
    $messages = [];
    try {
        $tableName = (new BasementType())->getTable();
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('basement_type_id');
            $table->string('basement_type', 300)->nullable();
            $table->timestamps();
        });

        $seeds = [
            ['basement_type_id' => 1, 'basement_type' => 'Finished Basement'],
            ['basement_type_id' => 2, 'basement_type' => 'Unfinished Basement'],
            ['basement_type_id' => 3, 'basement_type' => 'Apartment Basement'],
            ['basement_type_id' => 4, 'basement_type' => 'Not Applicable'],
        ];

        foreach ($seeds as $seed) BasementType::create($seed);
        $messages[] = "successfully seeded " . count($seeds) . " basement types.";
    } catch (\Throwable $e) {
        $messages[] = "basement types error: " . $e->getMessage();
    }
    return $messages;
}
