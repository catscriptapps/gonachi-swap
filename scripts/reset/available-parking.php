<?php
// /scripts/reset/available-parking.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\AvailableParking;

function resetAvailableParkingTable(): array
{
    $messages = [];
    try {
        $tableName = (new AvailableParking())->getTable();
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('parking_id');
            $table->string('parking', 10)->nullable();
            $table->timestamps();
        });

        $seeds = [
            ['parking_id' => 1, 'parking' => '0'],
            ['parking_id' => 2, 'parking' => '1'],
            ['parking_id' => 3, 'parking' => '2'],
            ['parking_id' => 4, 'parking' => '3'],
            ['parking_id' => 5, 'parking' => '3+'],
        ];

        foreach ($seeds as $seed) AvailableParking::create($seed);
        $messages[] = "successfully seeded " . count($seeds) . " parking options.";
    } catch (\Throwable $e) {
        $messages[] = "available parking error: " . $e->getMessage();
    }
    return $messages;
}
