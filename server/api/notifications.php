<?php
// /server/api/notifications.php

declare(strict_types=1);

use Src\Controller\NotificationsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check 🛡️
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

$input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new NotificationsController();

    // HANDLE FETCH (GET)
    if ($method === 'GET') {
        // Typically used for refreshing the list or getting unread count
        $controller->getLatest();
        exit;
    }

    // HANDLE ACTIONS (POST / DELETE OVERRIDE)
    if ($method === 'POST') {
        $override = strtoupper($input['_method'] ?? '');

        if ($override === 'DELETE') {
            // Delete a specific notification 🗑️
            $result = $controller->delete((int)($input['id'] ?? 0));
            json_response($result);
        } else {
            // Future-proofing: could be used for 'mark as read' bulk actions
            json_response(['success' => false, 'messages' => ['Action not defined']], 400);
            exit;
        }
    } else {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
    }
} catch (\Throwable $e) {
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
