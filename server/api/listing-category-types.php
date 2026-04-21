<?php
// /server/api/listing-category-types.php

declare(strict_types=1);

use App\Models\ListingCategoryType;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        $query = ListingCategoryType::orderBy('category_type', 'asc');

        // Optional filter: if category_id is provided, filter the types
        $categoryId = $_GET['category_id'] ?? null;
        if ($categoryId) {
            $query->where('category_id', (int)$categoryId);
        }

        $types = $query->get();

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
