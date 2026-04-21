<?php
// /scripts/reset/listings.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Listing;

/**
 * Resets the listings table structure.
 */
function resetListingsTable(): array
{
    $messages = [];

    try {
        $model = new Listing();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('listing_id');
            $table->unsignedBigInteger('orig_user_id')->nullable()->index();
            $table->string('listing_title', 300)->nullable();
            $table->string('city', 300)->nullable();

            // Foreign Key Lookups
            $table->integer('category_id')->nullable();
            $table->integer('category_type_id')->nullable();
            $table->integer('unit_type_id')->nullable();
            $table->integer('house_type_id')->nullable();
            $table->integer('bedroom_id')->nullable();
            $table->integer('bathroom_id')->nullable();

            $table->text('listing_description')->nullable();
            $table->string('address', 300)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('agreement_type_id')->nullable();

            $table->string('price', 100)->nullable();
            $table->string('property_size', 11)->nullable();
            $table->string('move_in_date', 11)->nullable();

            // Feature strings
            $table->string('is_ac', 30)->nullable();
            $table->string('is_furnished', 30)->nullable();
            $table->string('parking', 30)->nullable();
            $table->string('pets_allowed', 30)->nullable();

            // The New Amenities JSON Column
            $table->json('amenities')->nullable();

            $table->text('youtube_url')->nullable();
            $table->string('contact_phone', 30)->nullable();

            // Lifecycle: 1=Draft, 2=Posted, 3=Archived
            $table->integer('status_id')->default(1);
            $table->integer('views')->default(0);
            $table->text('comments')->nullable();

            /**
             * Standard Eloquent Timestamps
             * Creates 'created_at' and 'updated_at' automatically
             */
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure with JSON amenities and standard timestamps.";
    } catch (\Throwable $e) {
        $messages[] = 'listings table error: ' . $e->getMessage();
    }

    return $messages;
}
