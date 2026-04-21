<?php
// /server/api/listing-categories.php

declare(strict_types=1);

use App\Models\ListingCategory;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all listing categories, alphabetically
        $categories = ListingCategory::orderBy('category', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $categories
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
