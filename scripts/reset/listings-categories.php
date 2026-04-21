<?php
// /scripts/reset/listings-categories.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingCategory;

/**
 * Resets the listings_categories table and seeds default categories.
 */
function resetListingsCategoriesTable(): array
{
    $messages = [];

    try {
        $model = new ListingCategory();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category', 300)->nullable();
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $categories = [
            ['category_id' => 1, 'category' => 'Real Estate'],
            ['category_id' => 2, 'category' => 'Real Estate Services'],
            ['category_id' => 3, 'category' => 'Other'],
        ];

        foreach ($categories as $cat) {
            ListingCategory::create($cat);
        }

        $messages[] = "successfully seeded " . count($categories) . " listing categories.";
    } catch (\Throwable $e) {
        $messages[] = 'listings categories table error: ' . $e->getMessage();
    }

    return $messages;
}
