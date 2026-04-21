<?php
// /scripts/reset/contractor-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\ContractorType;

/**
 * Resets the contractor_types table and seeds default options.
 */
function resetContractorTypesTable(): array
{
    $messages = [];

    try {
        $model = new ContractorType();
        $tableName = $model->getTable();

        // 1. Drop existing table
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        // 2. Create table structure
        Capsule::schema()->create($tableName, function (Blueprint $table) {
            // id() creates an auto-incrementing bigInteger primary key named contractor_type_id
            $table->id('contractor_type_id');
            $table->string('contractor_type', 100);
            $table->timestamps();
        });

        $messages[] = "created {$tableName} table structure.";

        // 3. Seed data from your provided options
        $defaultTypes = [
            [
                'contractor_type_id' => 1,
                'contractor_type'    => 'Licensed Contractor'
            ],
            [
                'contractor_type_id' => 2,
                'contractor_type'    => 'Unlicensed Contractor'
            ],
            [
                'contractor_type_id' => 3,
                'contractor_type'    => 'Not Applicable'
            ]
        ];

        foreach ($defaultTypes as $type) {
            ContractorType::create($type);
        }

        $messages[] = "successfully seeded " . count($defaultTypes) . " contractor types.";
    } catch (\Throwable $e) {
        $messages[] = 'contractor types table error: ' . $e->getMessage();
    }

    return $messages;
}
