<?php
// /scripts/reset/listings.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Listing;

function resetListingsTable(): array
{
    $messages = [];
    $tableName = (new Listing())->getTable();

    try {
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists($tableName);
        Capsule::schema()->enableForeignKeyConstraints();

        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('listing_id');

            // Core IDs
            $table->unsignedBigInteger('orig_user_id')->index();
            $table->unsignedInteger('category_id')->index();

            // Attributes
            $table->string('listing_title', 255);
            $table->text('listing_description')->nullable();
            $table->tinyInteger('type_id')->comment('1: Swap, 2: Sale, 3: Gift')->index();
            $table->tinyInteger('condition_id')->comment('1: New, 2: Like New, 3: Used, 4: Parts')->index();
            $table->tinyInteger('status_id')->default(0)->comment('0: Draft, 1: Posted, 2: Completed, 3: Archived')->index();

            // Details
            $table->decimal('price', 10, 2)->nullable();
            $table->text('trade_pref')->nullable();

            // Location
            $table->string('city', 100)->nullable();
            $table->integer('region_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Extras
            $table->string('youtube_url', 255)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->unsignedInteger('views')->default(0);

            $table->timestamps();

            // Foreign Keys
            $table->foreign('orig_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('category_id')->on('listing_categories');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
