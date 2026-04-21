<?php
// /scripts/reset/amenities.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\AmenityCategory;
use App\Models\Amenity;

function resetAmenitiesTables(): array
{
    $messages = [];
    try {
        // Drop children first
        Capsule::schema()->dropIfExists('amenities');
        Capsule::schema()->dropIfExists('amenity_categories');
        $messages[] = "dropped existing amenities tables.";

        // Create Categories
        Capsule::schema()->create('amenity_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('name', 100);
            $table->timestamps();
        });

        // Create Amenities
        Capsule::schema()->create('amenities', function (Blueprint $table) {
            $table->id('amenity_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name', 150);
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('amenity_categories')->onDelete('cascade');
        });

        $messages[] = "created amenity table structures.";

        // Seed Categories
        $categories = [
            ['category_id' => 1, 'name' => 'Appliances'],
            ['category_id' => 2, 'name' => 'Utilities'],
            ['category_id' => 3, 'name' => 'Wi-Fi & Entertainment'],
            ['category_id' => 4, 'name' => 'Outdoor Space'],
        ];
        foreach ($categories as $c) AmenityCategory::create($c);

        // Seed Amenities
        $amenities = [
            ['category_id' => 1, 'name' => 'Laundry (In Unit)'],
            ['category_id' => 1, 'name' => 'Laundry (In Building)'],
            ['category_id' => 1, 'name' => 'Dishwasher'],
            ['category_id' => 1, 'name' => 'Fridge / Freezer'],
            ['category_id' => 2, 'name' => 'Water'],
            ['category_id' => 2, 'name' => 'Hydro'],
            ['category_id' => 2, 'name' => 'Heat'],
            ['category_id' => 3, 'name' => 'Internet'],
            ['category_id' => 3, 'name' => 'Cable / TV'],
            ['category_id' => 4, 'name' => 'Yard'],
            ['category_id' => 4, 'name' => 'Balcony'],
        ];
        foreach ($amenities as $a) Amenity::create($a);

        $messages[] = "successfully seeded " . count($amenities) . " amenities.";
    } catch (\Throwable $e) {
        $messages[] = "amenities error: " . $e->getMessage();
    }
    return $messages;
}
