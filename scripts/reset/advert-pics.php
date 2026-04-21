<?php
// /scripts/reset/advert-pics.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\AdvertPic;

/**
 * Resets the adverts_pics table.
 */
function resetAdvertPicsTable(): array
{
    $messages = [];

    try {
        $model = new AdvertPic();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('entry_id');

            // Link to the advert_id in the adverts table
            $table->unsignedBigInteger('advert_id')->nullable()->index();

            $table->text('pic_name')->nullable();
            $table->text('pic_caption')->nullable();
            $table->integer('pos_index')->default(0);

            $table->timestamps();

            // Foreign key constraint to ensure pics don't orphan
            $table->foreign('advert_id')
                ->references('advert_id')
                ->on('adverts')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure (empty).";
    } catch (\Throwable $e) {
        $messages[] = 'advert pics table error: ' . $e->getMessage();
    }

    return $messages;
}
