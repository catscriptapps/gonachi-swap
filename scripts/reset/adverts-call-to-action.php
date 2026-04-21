<?php
// /scripts/reset/adverts-call-to-action.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\AdvertCallToAction;

/**
 * Resets the adverts_call_to_action table and seeds default CTA options.
 */
function resetAdvertCtasTable(): array
{
    $messages = [];

    try {
        $model = new AdvertCallToAction();
        $tableName = $model->getTable();

        // Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('call_to_action_id');
            $table->string('call_to_action', 100);
            $table->timestamps(); // Eloquent timestamps for record keeping
        });

        $messages[] = "created {$tableName} table structure.";

        // Seed data from legacy select options
        $defaultCtas = [
            ['call_to_action' => 'Request Quotation'],
            ['call_to_action' => 'Hire Now'],
            ['call_to_action' => 'Book Now'],
            ['call_to_action' => 'Contact Us'],
            ['call_to_action' => 'Get Offer'],
            ['call_to_action' => 'Get Quote'],
            ['call_to_action' => 'Learn More'],
            ['call_to_action' => 'Ask for Price'],
            ['call_to_action' => 'Message Us'],
            ['call_to_action' => 'Contact for Price'],
            ['call_to_action' => 'Call Us'],
            ['call_to_action' => 'Schedule a Viewing'],
            ['call_to_action' => 'Inquire Now'],
            ['call_to_action' => 'Call Us Today'],
            ['call_to_action' => 'Request a Demo'],
            ['call_to_action' => 'Sign Up Now'],
            ['call_to_action' => 'Explore Services'],
            ['call_to_action' => 'Discover More'],
            ['call_to_action' => 'Start Free Trial'],
            ['call_to_action' => 'Explore Options'],
            ['call_to_action' => 'Get Started'],
            ['call_to_action' => 'Shop Now'],
            ['call_to_action' => 'Join Now'],
        ];

        foreach ($defaultCtas as $cta) {
            AdvertCallToAction::create($cta);
        }

        $messages[] = "successfully seeded " . count($defaultCtas) . " Call to Action options.";
    } catch (\Throwable $e) {
        $messages[] = 'Advert CTAs table error: ' . $e->getMessage();
    }

    return $messages;
}
