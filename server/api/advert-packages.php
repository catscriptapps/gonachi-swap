<?php
// /server/api/advert-packages.php

declare(strict_types=1);

use App\Models\AdvertPackage;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all Packages ordered by their display order
        $packages = AdvertPackage::orderBy('package_order', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $packages
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
