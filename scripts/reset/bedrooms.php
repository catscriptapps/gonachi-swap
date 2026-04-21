<?php
// /scripts/reset/bedrooms.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Bedroom;

function resetBedroomsTable(): array
{
    $messages = [];
    try {
        $tableName = (new Bedroom())->getTable();
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('bedroom_id');
            $table->string('bedroom', 300)->nullable();
            $table->timestamps();
        });

        $seeds = [
            ['bedroom_id' => 1, 'bedroom' => '1'],
            ['bedroom_id' => 2, 'bedroom' => '1 +Den'],
            ['bedroom_id' => 3, 'bedroom' => '2'],
            ['bedroom_id' => 4, 'bedroom' => '2 +Den'],
            ['bedroom_id' => 5, 'bedroom' => '3'],
            ['bedroom_id' => 6, 'bedroom' => '3 +Den'],
            ['bedroom_id' => 7, 'bedroom' => '4'],
            ['bedroom_id' => 8, 'bedroom' => '4 +Den'],
            ['bedroom_id' => 9, 'bedroom' => '5'],
            ['bedroom_id' => 10, 'bedroom' => '5 +Den'],
            ['bedroom_id' => 11, 'bedroom' => 'Whole House (with Unfinished Basement)'],
            ['bedroom_id' => 12, 'bedroom' => 'Whole House (with Finished Basement)'],
            ['bedroom_id' => 13, 'bedroom' => 'Whole House (with Apartment Basement)'],
        ];

        foreach ($seeds as $seed) Bedroom::create($seed);
        $messages[] = "successfully seeded " . count($seeds) . " bedroom options.";
    } catch (\Throwable $e) {
        $messages[] = "bedrooms error: " . $e->getMessage();
    }
    return $messages;
}
