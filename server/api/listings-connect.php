<?php
// /server/api/listings-connect.php

declare(strict_types=1);

use Src\Controller\ListingResponsesController;

header('Content-Type: application/json; charset=utf-8');

// Merging both POST and JSON body inputs to handle various JS fetch styles 💎
$input = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    $controller = new ListingResponsesController();

    /**
     * Routing based on the 'action' key from our JS payload.
     * Actions: 'accept', 'decline', or null (defaults to 'send')
     */
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'accept':
                $result = $controller->acceptInquiry($input);
                break;
            case 'decline':
                $result = $controller->declineInquiry($input);
                break;
            default:
                $result = [
                    'success' => false,
                    'message' => 'Invalid action provided.',
                    'status' => 400
                ];
                break;
        }
    } else {
        // Fallback: If no action is specified, treat it as a fresh inquiry 💎
        $result = $controller->sendInquiry($input);
    }

    // Determine the appropriate HTTP status code based on result
    $status = (isset($result['success']) && $result['success'])
        ? 200
        : ($result['status'] ?? 400);

    json_response($result, (int)$status);
} catch (\Throwable $e) {
    // Catch-all for unexpected failures to keep the UI from hanging 💎
    json_response([
        'success' => false,
        'message' => 'API Error: ' . $e->getMessage()
    ], 500);
}
