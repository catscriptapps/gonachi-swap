<?php
// /scripts/reset/listings-pics.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingPic;

/**
 * Resets the listings_pics table structure.
 */
function resetListingPicsTable(): array
{
    $messages = [];

    try {
        $model = new ListingPic();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('entry_id');
            $table->unsignedBigInteger('listing_id')->index();
            $table->string('pic_name', 300);
            $table->string('pic_caption', 300)->nullable();
            $table->integer('pos_index')->default(0);
            $table->timestamps();

            // Foreign key to listings table
            $table->foreign('listing_id')
                ->references('listing_id')
                ->on('listings')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure (no seeding).";
    } catch (\Throwable $e) {
        $messages[] = 'listings pics table error: ' . $e->getMessage();
    }

    return $messages;
}
