<?php
// /server/api/social-block.php

declare(strict_types=1);

use Src\Controller\SocialRelationsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

$GLOBALS['assetBase'] = getAssetBase();

// 1. Auth Check
$userId = AuthService::userId();
if (!$userId) {
    http_response_code(401);
    json_response(['success' => false, 'messages' => ['Authentication required']]);
    exit;
}

/**
 * Capture input (target_id is expected here)
 */
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true) ?: [];
$input = array_merge($_POST, $jsonData);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new SocialRelationsController();

    if ($method === 'POST') {
        // Specifically calls the block logic
        $result = $controller->blockUser($input);
        json_response($result);
        exit;
    }

    http_response_code(405);
    json_response(['success' => false, 'messages' => ['Method not supported']]);
} catch (\Throwable $e) {
    http_response_code(500);
    json_response(['success' => false, 'messages' => [$e->getMessage()]]);
}
