<?php
// /scripts/reset/listing-conditions.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingCondition;

function resetListingConditionsTable(): array
{
    $messages = [];
    $tableName = (new ListingCondition())->getTable();

    try {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists($tableName);
        Capsule::schema()->enableForeignKeyConstraints();

        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('condition_id');
            $table->string('condition_name', 50);
        });

        $messages[] = "created {$tableName} table structure.";

        $conditions = [
            ['condition_id' => 1, 'condition_name' => 'New'],
            ['condition_id' => 2, 'condition_name' => 'Like New'],
            ['condition_id' => 3, 'condition_name' => 'Used'],
            ['condition_id' => 4, 'condition_name' => 'Parts'],
        ];

        Capsule::table($tableName)->insert($conditions);
        $messages[] = "seeded " . count($conditions) . " listing conditions.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
