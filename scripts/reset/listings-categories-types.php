<?php
// /scripts/reset/listings-categories-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingCategoryType;

/**
 * Resets the listings_categories_types table and seeds default sub-categories.
 */
function resetListingsCategoriesTypesTable(): array
{
    $messages = [];

    try {
        $model = new ListingCategoryType();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('category_type_id');

            // Foreign Key to the Parent Category
            $table->unsignedBigInteger('category_id')->index();

            $table->string('category_type', 300);
            $table->timestamps();

            // Constraints
            $table->foreign('category_id')
                ->references('category_id')
                ->on('listings_categories')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $types = [
            ['category_type_id' => 1, 'category_id' => 1, 'category_type' => 'Accomodation for Rent'],
            ['category_type_id' => 2, 'category_id' => 1, 'category_type' => 'Accomodation for Sale'],
            ['category_type_id' => 3, 'category_id' => 1, 'category_type' => 'Commercial Renting'],
            ['category_type_id' => 4, 'category_id' => 2, 'category_type' => 'Real Estate Agent Services'],
            ['category_type_id' => 5, 'category_id' => 2, 'category_type' => 'Broker Services'],
            ['category_type_id' => 6, 'category_id' => 2, 'category_type' => 'Contractor Services'],
            ['category_type_id' => 7, 'category_id' => 1, 'category_type' => 'Short Rentals'],
        ];

        foreach ($types as $type) {
            ListingCategoryType::create($type);
        }

        $messages[] = "successfully seeded " . count($types) . " listing category types.";
    } catch (\Throwable $e) {
        $messages[] = 'listings categories types error: ' . $e->getMessage();
    }

    return $messages;
}
