<?php
// /scripts/reset/agreement-types.php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use App\Models\AgreementType;

function resetAgreementTypesTable(): array
{
    $messages = [];
    try {
        $tableName = (new AgreementType())->getTable();
        Capsule::schema()->dropIfExists($tableName);
        $messages[] = "dropped existing {$tableName} table.";

        Capsule::schema()->create($tableName, function (Blueprint $table) {
            $table->id('agreement_type_id');
            $table->string('agreement_type', 300)->nullable();
            $table->timestamps();
        });

        $seeds = [
            ['agreement_type_id' => 1, 'agreement_type' => 'Month-to-Month'],
            ['agreement_type_id' => 2, 'agreement_type' => '1 Year'],
            ['agreement_type_id' => 3, 'agreement_type' => 'Not Applicable'],
        ];

        foreach ($seeds as $seed) AgreementType::create($seed);
        $messages[] = "successfully seeded " . count($seeds) . " agreement types.";
    } catch (\Throwable $e) {
        $messages[] = "agreement types error: " . $e->getMessage();
    }
    return $messages;
}
