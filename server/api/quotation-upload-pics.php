<?php
// /server/api/quotation-upload-pics.php

declare(strict_types=1);

use Src\Controller\QuotationPicturesController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check
$userId = AuthService::userId();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

// 2. Method Check (Must be POST for uploads)
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not supported']);
    exit;
}

try {
    // 3. Hand off to the Quotation-specific controller
    $controller = new QuotationPicturesController();

    // Get the ID from the query string (?id=...)
    $quoteId = $_GET['id'] ?? null;

    $controller->store($quoteId);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
