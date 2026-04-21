<?php
// /server/api/social-relations.php

declare(strict_types=1);

use Src\Controller\SocialRelationsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// Initialize Global asset base if needed
$GLOBALS['assetBase'] = getAssetBase();

// 1. Auth Check
$userId = AuthService::userId();
if (!$userId) {
    http_response_code(401);
    json_response(['success' => false, 'messages' => ['Authentication required']]);
    exit;
}

/**
 * Capture input from either JSON body or standard POST
 */
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true) ?: [];
$input = array_merge($_POST, $jsonData);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new SocialRelationsController();

    // --- HANDLE GET REQUESTS ---
    if ($method === 'GET') {
        $action = $_GET['action'] ?? '';

        if ($action === 'get-stats') {
            $stats = $controller->getStats((int)$userId);
            json_response(array_merge(['success' => true], $stats));
        }
        // 🍊 ADD THIS BLOCK: Handle the list requests
        elseif ($action === 'get-list') {
            $type = $_GET['type'] ?? 'following';
            json_response($controller->getList($type));
        } else {
            json_response($controller->getSuggestions());
        }
        exit;
    }

    // --- HANDLE POST REQUESTS (Follow/Unfollow) ---
    if ($method === 'POST') {
        $result = $controller->toggleFollow($input);
        json_response($result);
        exit;
    }

    http_response_code(405);
    json_response(['success' => false, 'messages' => ['Method not supported']]);
} catch (\Throwable $e) {
    http_response_code(500);
    json_response(['success' => false, 'messages' => [$e->getMessage()]]);
}
