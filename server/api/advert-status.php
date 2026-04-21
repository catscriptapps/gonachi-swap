<?php
// /server/api/advert-status.php

declare(strict_types=1);

use Src\Controller\AdvertsAdminController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check - Ensure the user is logged in
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

/** * NOTE: If your AuthService has an isAdmin check, 
 * you should strictly enforce it here.
 */
// if (!AuthService::isAdmin()) {
//     json_response(['success' => false, 'messages' => ['Admin privileges required']], 403);
//     exit;
// }

$input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new AdvertsAdminController();

    // We only support POST for status updates
    if ($method === 'POST') {
        // We pass the raw input to updateStatus (which handles ID decoding)
        $result = $controller->updateStatus($input);

        // Ensure the returned rowHtml is clean for JSON
        if (!empty($result['rowHtml'])) {
            $result['rowHtml'] = mb_convert_encoding($result['rowHtml'], 'UTF-8', 'UTF-8');
        }

        json_response($result);
    } else {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
    }
} catch (\Throwable $e) {
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
