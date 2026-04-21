<?php
// /server/api/bedrooms.php

declare(strict_types=1);

use App\Models\Bedroom;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all bedroom options, ordered by the display value
        $bedrooms = Bedroom::orderBy('bedroom', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $bedrooms
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
