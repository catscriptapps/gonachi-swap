<?php
// /scripts/reset/listing-categories.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ListingCategory;
use Illuminate\Support\Str;

function resetListingCategoriesTable(): array
{
    $messages = [];
    $tableName = (new ListingCategory())->getTable();

    try {
        // Disable foreign keys to drop safely if linked
        Capsule::schema()->disableForeignKeyConstraints();
        Capsule::schema()->dropIfExists($tableName);
        Capsule::schema()->enableForeignKeyConstraints();

        $messages[] = "dropped existing {$tableName} table.";

        // 1. Create Table Structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->increments('category_id');
            $table->string('category_name', 100);
            $table->string('category_slug', 100)->unique();
            $table->string('category_icon', 100)->nullable(); // Increased length for Heroicon names
            $table->tinyInteger('status_id')->default(1);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 2. Seed Default Categories (Heroicons Outline names)
        $categories = [
            'Vehicles'               => 'truck',
            'Fashion & Clothing'     => 'shopping-bag',
            'Electronics & Gadgets'  => 'device-tablet',
            'Entertainment'          => 'ticket',
            'Pet Supplies'           => 'heart',
            'Musical Instruments'    => 'musical-note',
            'Toys & Games'           => 'puzzle-piece',
            'Properties'             => 'home-modern',
            'Beauty & Personal Care' => 'sparkles',
            'Home & Living'          => 'home',
            'Health & Wellness'      => 'beaker',
            'Sports & Outdoors'      => 'trophy'
        ];

        $seedData = [];
        $now = date('Y-m-d H:i:s');

        foreach ($categories as $name => $icon) {
            $seedData[] = [
                'category_name' => $name,
                'category_slug' => Str::slug($name),
                'category_icon' => $icon,
                'status_id'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        Capsule::table($tableName)->insert($seedData);
        $messages[] = "seeded " . count($seedData) . " default categories into {$tableName}.";
    } catch (\Throwable $e) {
        $messages[] = "error resetting {$tableName} table: " . $e->getMessage();
    }

    return $messages;
}
