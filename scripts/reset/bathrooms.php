<?php
// /scripts/reset/bathrooms.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Bathroom;

function resetBathroomsTable(): array
{
    $messages = [];
    try {
        $tableName = (new Bathroom())->getTable();
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('bathroom_id');
            $table->string('bathroom', 10)->nullable();
            $table->timestamps();
        });

        $seeds = [
            ['bathroom_id' => 1, 'bathroom' => '1'],
            ['bathroom_id' => 2, 'bathroom' => '1.5'],
            ['bathroom_id' => 3, 'bathroom' => '2'],
            ['bathroom_id' => 4, 'bathroom' => '2.5'],
            ['bathroom_id' => 5, 'bathroom' => '3'],
            ['bathroom_id' => 6, 'bathroom' => '3.5'],
            ['bathroom_id' => 7, 'bathroom' => '4'],
            ['bathroom_id' => 8, 'bathroom' => '4.5'],
            ['bathroom_id' => 9, 'bathroom' => '5'],
            ['bathroom_id' => 10, 'bathroom' => '5.5'],
            ['bathroom_id' => 11, 'bathroom' => '6 +'],
        ];

        foreach ($seeds as $seed) Bathroom::create($seed);
        $messages[] = "successfully seeded " . count($seeds) . " bathroom options.";
    } catch (\Throwable $e) {
        $messages[] = "bathrooms error: " . $e->getMessage();
    }
    return $messages;
}
