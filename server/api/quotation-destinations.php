<?php
// /server/api/quotation-destinations.php

declare(strict_types=1);

use App\Models\QuotationDestination;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($method === 'GET') {
        // Fetch all quotation destinations, alphabetically
        $destinations = QuotationDestination::orderBy('quotation_dest', 'asc')->get();

        json_response([
            'success' => true,
            'data' => $destinations
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
