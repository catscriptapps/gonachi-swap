<?php
// /server/api/listing-conditions.php

declare(strict_types=1);

use App\Models\ListingCondition;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch item conditions (New, Used, etc)
        $conditions = ListingCondition::orderBy('condition_id', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $conditions
        ]);
    }

    json_response(['success' => false, 'messages' => ['Method not allowed']], 405);
} catch (Throwable $e) {
    json_response([
        'success' => false,
        'messages' => ['Server error: ' . $e->getMessage()]
    ], 500);
}
