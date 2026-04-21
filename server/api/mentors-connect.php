<?php
// /server/api/mentors-connect.php

declare(strict_types=1);

use Src\Controller\MentorRequestsController;

header('Content-Type: application/json; charset=utf-8');

$input = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    $controller = new MentorRequestsController();

    // Routing based on the 'action' key from our JS payload
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'accept':
                $result = $controller->acceptRequest($input);
                break;
            case 'decline':
                $result = $controller->declineRequest($input);
                break;
            default:
                $result = ['success' => false, 'message' => 'Invalid action provided.'];
                break;
        }
    } else {
        // Fallback for new requests
        $result = $controller->sendRequest($input);
    }

    $status = (isset($result['success']) && $result['success']) ? 200 : ($result['status'] ?? 400);
    json_response($result, (int)$status);
} catch (\Throwable $e) {
    json_response([
        'success' => false,
        'message' => 'API Error: ' . $e->getMessage()
    ], 500);
}
