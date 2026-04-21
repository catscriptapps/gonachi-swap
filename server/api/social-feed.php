<?php
// /server/api/social-feed.php

declare(strict_types=1);

use Src\Controller\SocialFeedController;
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
 * Capture input from either JSON body or standard POST (FormData)
 */
$rawInput = file_get_contents('php://input');
$jsonData = json_decode($rawInput, true) ?: [];
$input = array_merge($_POST, $jsonData);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new SocialFeedController();

    // --- HANDLE GET REQUESTS ---
    if ($method === 'GET') {
        $action = $_GET['action'] ?? '';

        if ($action === 'get-details') {
            $result = $controller->getDetails($_GET['post_id'] ?? 0);
        } else {
            $controller->index();
            json_response(['success' => true, 'html' => $GLOBALS['feedHtml']]);
            exit;
        }

        json_response($result);
        exit;
    }

    // --- HANDLE POST REQUESTS ---
    if ($method === 'POST') {
        $action = $input['action'] ?? '';

        switch ($action) {
            case 'toggle-like':
                $result = $controller->toggleLike($input['post_id'] ?? 0);
                break;

            case 'add-comment':
                $result = $controller->addComment($input);
                break;

            case 'delete-comment':
                $result = $controller->deleteComment($input);
                break;

            case 'delete-post': // 🍊 FIXED: Linked to Controller
                $result = $controller->deletePost($input);
                break;

            default:
                $result = $controller->save($input);
                break;
        }

        // Final UTF-8 Clean for JSON safety
        if (!empty($result['html'])) {
            $result['html'] = mb_convert_encoding($result['html'], 'UTF-8', 'UTF-8');
        }
        if (!empty($result['commentHtml'])) {
            $result['commentHtml'] = mb_convert_encoding($result['commentHtml'], 'UTF-8', 'UTF-8');
        }

        json_response($result);
        exit;
    }

    http_response_code(405);
    json_response(['success' => false, 'messages' => ['Method not supported']]);
} catch (\Throwable $e) {
    http_response_code(500);
    json_response(['success' => false, 'messages' => [$e->getMessage()]]);
}
