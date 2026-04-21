<?php
// /scripts/reset/skilled-trades.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\SkilledTrade;

/**
 * Resets the skilled_trades table and seeds the default list.
 */
function resetSkilledTradesTable(): array
{
    $messages = [];

    try {
        $model = new SkilledTrade();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('skilled_trade_id');
            $table->string('skilled_trade', 150);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data
        $trades = [
            [1, 'Appliance Repair & Installation'],
            [2, 'Architectural Services'],
            [3, 'Basement Renovation & Finishing'],
            [4, 'Brick, Masonry & Concrete'],
            [5, 'Carpentry, Crown Moulding & Trimwork'],
            [6, 'Cleaning Services'],
            [7, 'Drywall & Stucco Removal'],
            [8, 'Electrician'],
            [9, 'Excavation, Demolition & Waterproofing'],
            [10, 'Fence, Deck, Railing & Siding'],
            [11, 'Flooring'],
            [12, 'Garage Door'],
            [13, 'General Labour'],
            [14, 'Heating, Ventilation & Air Conditioning'],
            [15, 'Home Building & Construction'],
            [16, 'Hot Tub installation & Services'],
            [17, 'House Renovation'],
            [18, 'Insulation'],
            [19, 'Interlock, Paving & Driveways'],
            [20, 'Junk Removal'],
            [21, 'Kitchen Renovation & Installation'],
            [22, 'Lawn, Tree Maintenance & Eavestrough'],
            [23, 'Moving Assistant'],
            [24, 'Painters & Painting'],
            [25, 'Permit Services'],
            [26, 'Phone, Network, Cable & Home-wiring'],
            [27, 'Plumbing'],
            [28, 'Pool Installation & Services'],
            [29, 'Renovations & General Contracting'],
            [30, 'Roofing'],
            [31, 'Snow Removal & Property Maintenance'],
            [32, 'Washroom Renovation & Installation'],
            [33, 'Welding'],
            [34, 'Windows & Doors'],
            [35, 'Other']
        ];

        foreach ($trades as $trade) {
            SkilledTrade::create([
                'skilled_trade_id' => $trade[0],
                'skilled_trade'    => $trade[1]
            ]);
        }

        $messages[] = "successfully seeded " . count($trades) . " skilled trades.";
    } catch (\Throwable $e) {
        $messages[] = 'skilled trades table error: ' . $e->getMessage();
    }

    return $messages;
}
