<?php
// /server/api/advert-upload-pics.php

declare(strict_types=1);

use Src\Controller\AdvertsPicturesController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

$userId = AuthService::userId();
if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not supported']);
    exit;
}

try {
    $controller = new AdvertsPicturesController();
    // Use 'id' from query string as per our previous sync
    $adId = $_GET['id'] ?? null;
    $controller->store($adId);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
