<?php
// /server/api/unit-types.php

declare(strict_types=1);

use App\Models\UnitType;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all unit types, alphabetically
        $types = UnitType::orderBy('unit_type', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $types
        ]);
    }

    json_response([
        'success' => false,
        'messages' => ['Method not allowed']
    ], 405);
} catch (Throwable $e) {
    json_response([
        'success' => false,
        'messages' => ['Server error: ' . $e->getMessage()]
    ], 500);
}
