<?php
// /server/api/quotation-pictures.php

declare(strict_types=1);

use Src\Controller\QuotationPicturesController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Guard
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    // 2. Initialize the Quotation-specific Controller
    $controller = new QuotationPicturesController();

    if ($method === 'GET') {
        // Fetch pictures for a specific Quotation (Project)
        $quoteId = $_GET['id'] ?? null;

        if (!$quoteId) {
            json_response(['success' => false, 'messages' => ['Quotation ID is required']], 400);
            exit;
        }

        $controller->index($quoteId);
        exit;
    }

    // Handle other methods (POST/DELETE) as they are implemented
    json_response(['success' => false, 'messages' => ['Method not supported']], 405);
} catch (\Throwable $e) {
    // Log the error for internal debugging if your system has a logger
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
