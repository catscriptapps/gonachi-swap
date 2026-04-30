<?php
// /scripts/reset/listing-pics.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingPic;

function resetListingPicsTable(): array
{
    $messages = [];
    $tableName = (new ListingPic())->getTable();

    try {
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('entry_id');
            $table->unsignedInteger('listing_id')->index();

            $table->string('pic_name', 255);
            $table->string('pic_caption', 255)->nullable();
            $table->integer('pos_index')->default(0);

            $table->timestamps();

            // Foreign Key to Listings
            $table->foreign('listing_id')
                ->references('listing_id')
                ->on('listings')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
