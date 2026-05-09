<?php
// /server/api/listing-types.php

declare(strict_types=1);

use App\Models\ListingType;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch standard transaction types (Swap, Sale, Gift)
        $types = ListingType::orderBy('type_id', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $types
        ]);
    }

    json_response(['success' => false, 'messages' => ['Method not allowed']], 405);
} catch (Throwable $e) {
    json_response([
        'success' => false,
        'messages' => ['Server error: ' . $e->getMessage()]
    ], 500);
}
