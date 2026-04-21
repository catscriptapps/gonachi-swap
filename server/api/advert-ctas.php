<?php
// /server/api/advert-ctas.php

declare(strict_types=1);

use App\Models\AdvertCallToAction;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all CTAs from the new collection
        $ctas = AdvertCallToAction::orderBy('call_to_action', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $ctas
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
