<?php
// /server/api/skilled-trades.php

declare(strict_types=1);

use App\Models\SkilledTrade;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all trades, alphabetically
        $trades = SkilledTrade::orderBy('skilled_trade', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $trades
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
