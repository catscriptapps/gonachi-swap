<?php
// /scripts/reset/listing-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingType;

function resetListingTypesTable(): array
{
    $messages = [];
    $tableName = (new ListingType())->getTable();

    try {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists($tableName);
        Capsule::schema()->enableForeignKeyConstraints();

        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('type_id');
            $table->string('type_name', 50);
        });

        $messages[] = "created {$tableName} table structure.";

        $types = [
            ['type_id' => 1, 'type_name' => 'Swap'],
            ['type_id' => 2, 'type_name' => 'Sale'],
            ['type_id' => 3, 'type_name' => 'Gift'],
        ];

        Capsule::table($tableName)->insert($types);
        $messages[] = "seeded " . count($types) . " listing types.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
