<?php
// /scripts/reset/adverts.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Advert;

function resetAdvertsTable(): array
{
    $messages = [];

    try {
        $tableName = (new Advert())->getTable();

        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            // Primary Key: matches bigInt standard
            $table->bigIncrements('advert_id');

            // Content fields
            $table->string('title');
            $table->text('description');
            $table->string('call_to_action_id')->nullable();
            $table->text('keywords')->nullable();
            $table->string('landing_page_url')->nullable();

            // Targeting & Audience (JSON blobs)
            $table->text('selected_countries')->nullable();
            $table->text('selected_user_types')->nullable();

            // Logic & Payment
            $table->unsignedInteger('advert_package')->default(Advert::PACKAGE_FREE)->index();
            $table->string('status')->default('pending')->index();
            $table->integer('views')->default(0);

            // Foreign Key: Must be unsignedBigInteger to match Users ID
            $table->unsignedBigInteger('orig_user_id')->nullable()->index();

            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Foreign key to users table
            $table->foreign('orig_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        $messages[] = "created {$tableName} table";

        // ---------------------------------------------------------
        // Default Seed Entry
        // ---------------------------------------------------------
        Advert::create([
            'orig_user_id'          => 1, // Assuming Admin or first user
            'title'                 => 'Welcome to Gonachi Adverts',
            'description'           => 'Start promoting your real estate business today to thousands of targeted users.',
            'call_to_action'        => 'Get Started',
            'keywords'              => 'real estate, advertising, property',
            'landing_page_url'      => 'https://gonachi.com',
            'selected_countries'    => ['all'],
            'selected_user_types'   => ['all'],
            'advert_package'        => Advert::PACKAGE_FREE,
            'status'                => 'active',
            'expires_at'            => null // Free ads don't expire by default
        ]);

        $messages[] = "seeded initial gonachi advert";
    } catch (\Throwable $e) {
        $messages[] = "error resetting " . ($tableName ?? 'adverts') . " table: " . $e->getMessage();
    }

    return $messages;
}
