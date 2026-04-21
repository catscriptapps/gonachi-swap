<?php
// /server/api/bathrooms.php

declare(strict_types=1);

use App\Models\Bathroom;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all bathroom options, ordered by the display value
        $bathrooms = Bathroom::orderBy('bathroom', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $bathrooms
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
