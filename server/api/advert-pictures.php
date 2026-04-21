<?php
// /server/api/advert-pictures.php

declare(strict_types=1);

use Src\Controller\AdvertsPicturesController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new AdvertsPicturesController();

    if ($method === 'GET') {
        // Fetch pictures for a specific Ad
        $adId = $_GET['id'] ?? null;
        $controller->index($adId);
        exit;
    }

    // POST/DELETE will go here once we build the upload/removal logic
    json_response(['success' => false, 'messages' => ['Method not supported']], 405);
} catch (\Throwable $e) {
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
