<?php
// /server/api/listing-pictures.php

declare(strict_types=1);

use Src\Controller\ListingPicturesController;
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
    // 2. Initialize the Listing-specific Controller
    $controller = new ListingPicturesController();

    // 3. Resolve Listing ID from Request
    $listingId = $_GET['id'] ?? $_POST['id'] ?? null;

    if (!$listingId) {
        json_response(['success' => false, 'messages' => ['Listing ID is required']], 400);
        exit;
    }

    if ($method === 'GET') {
        // Fetch pictures for a specific Listing
        $controller->index($listingId);
        exit;
    }

    if ($method === 'POST') {
        // Store new pictures for a specific Listing
        $controller->store($listingId);
        exit;
    }

    // Handle unsupported methods
    json_response(['success' => false, 'messages' => ['Method not supported']], 405);
} catch (\Throwable $e) {
    // Log the error for internal debugging
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
