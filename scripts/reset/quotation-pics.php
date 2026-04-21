<?php
// /scripts/reset/quotation-pics.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\QuotationPic;

/**
 * Resets the quotations_pics table.
 */
function resetQuotationPicsTable(): array
{
    $messages = [];

    try {
        $model = new QuotationPic();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('entry_id');

            // Link to the quotation_id in the quotations table
            $table->unsignedBigInteger('quotation_id')->nullable()->index();

            $table->text('pic_name')->nullable();
            $table->text('pic_caption')->nullable();
            $table->integer('pos_index')->default(0);

            $table->timestamps();

            // Optional: Foreign key constraint to ensure pics don't orphan
            $table->foreign('quotation_id')
                ->references('quotation_id')
                ->on('quotations')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure (empty).";
    } catch (\Throwable $e) {
        $messages[] = 'quotation pics table error: ' . $e->getMessage();
    }

    return $messages;
}
