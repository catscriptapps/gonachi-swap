<?php
// /scripts/reset/quotations.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Quotation;

/**
 * Resets the quotations table.
 */
function resetQuotationsTable(): array
{
    $messages = [];
    $tableName = (new Quotation())->getTable();

    try {
        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('quotation_id');

            // Foreign Key: User who created the quote
            $table->unsignedBigInteger('orig_user_id')->nullable()->index();

            // Location & Category Lookups
            $table->unsignedInteger('country_id')->nullable()->index();
            $table->unsignedInteger('region_id')->nullable()->index();
            $table->unsignedInteger('contractor_type_id')->nullable()->index();
            $table->unsignedInteger('skilled_trade_id')->nullable()->index();

            // Core Content
            $table->string('quotation_title', 300);
            $table->string('city', 300)->nullable();
            $table->text('description_of_work_to_be_done')->nullable();

            // Property/Unit Details
            $table->unsignedInteger('unit_type_id')->nullable();
            $table->unsignedInteger('house_type_id')->nullable();

            // Schedule & Timing (kept as strings per your schema example)
            $table->string('start_date', 12)->nullable();
            $table->string('finish_date', 12)->nullable();
            $table->string('start_time', 10)->nullable();
            $table->string('finish_time', 10)->nullable();

            // Financials & Type
            $table->string('quotation_budget', 100)->nullable();
            $table->unsignedInteger('quotation_type_id')->nullable();
            $table->unsignedInteger('quotation_dest_id')->nullable();

            // External & Contact
            $table->text('youtube_url')->nullable();
            $table->string('contact_phone', 30)->nullable();
            $table->string('notify', 3)->default('no'); // e.g., 'yes'/'no'
            $table->integer('views')->default(0);

            // Status Logic
            $table->unsignedInteger('status_id')->default(1)->index();

            // Eloquent Timestamps
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('orig_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table structure.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
