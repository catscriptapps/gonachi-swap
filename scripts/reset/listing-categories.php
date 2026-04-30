<?php
// /scripts/reset/listing-categories.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingCategory;

function resetListingCategoriesTable(): array
{
    $messages = [];
    $tableName = (new ListingCategory())->getTable();

    try {
        // Disable foreign keys to drop safely if linked
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists($tableName);
        Capsule::schema()->enableForeignKeyConstraints();

        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('category_name', 100);
            $table->string('category_slug', 100)->unique();
            $table->string('category_icon', 50)->nullable();
            $table->tinyInteger('status_id')->default(1);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
