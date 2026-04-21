<?php
// /server/api/amenities.php

declare(strict_types=1);

use App\Models\AmenityCategory;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Eager load amenities for efficient database access
        $categories = AmenityCategory::with(['amenities' => function ($query) {
            $query->orderBy('name', 'asc');
        }])->orderBy('name', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $categories
        ]);
    }

    json_response(['success' => false, 'messages' => ['Method not allowed']], 405);
} catch (Throwable $e) {
    json_response(['success' => false, 'messages' => ['Server error: ' . $e->getMessage()]], 500);
}
