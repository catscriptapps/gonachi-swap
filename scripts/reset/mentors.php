<?php
// /scripts/reset/mentors.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Mentor;

/**
 * Resets the mentors table with professional directory fields.
 */
function resetMentorsTable(): array
{
    $messages = [];
    $tableName = (new Mentor())->getTable();

    try {
        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');

            // Foreign Key: User who is the mentor
            $table->unsignedBigInteger('orig_user_id')->nullable()->index();

            // Geographic Lookups
            $table->unsignedInteger('country_id')->nullable()->index();
            $table->unsignedInteger('region_id')->nullable()->index();
            $table->string('city', 150)->nullable(); // Added 💎

            // The target audience (e.g., this mentor wants to help 'Tenants')
            $table->unsignedInteger('target_user_type_id')->nullable()->index();

            // Professional Content
            $table->string('headline', 300)->nullable();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable(); // JSON for tags/expertise
            $table->unsignedInteger('years_experience')->default(0); // Added 💎

            // Social & Proof
            $table->string('youtube_url', 500)->nullable(); // Added 💎
            $table->string('website_url', 500)->nullable(); // Added 💎

            // Settings (Hourly Rate Removed per request 🗑️)
            $table->boolean('is_active')->default(true)->index();
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
