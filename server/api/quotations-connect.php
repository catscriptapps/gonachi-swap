<?php
// /server/api/quotations-connect.php

declare(strict_types=1);

use Src\Controller\QuotationResponsesController;

header('Content-Type: application/json; charset=utf-8');

/**
 * Merging inputs to support both traditional POST and modern JSON fetch requests 💎
 */
$input = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    $controller = new QuotationResponsesController();

    /**
     * Routing based on the 'action' key from our JS payload.
     * Actions: 'accept', 'decline', or null (defaults to 'send')
     */
    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'accept':
                $result = $controller->acceptResponse($input);
                break;
            case 'decline':
                $result = $controller->declineResponse($input);
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
        // Fallback: If no action is specified, treat it as a new project bid 💎
        $result = $controller->sendResponse($input);
    }

    // Determine the status code based on logic results
    $status = (isset($result['success']) && $result['success'])
        ? 200
        : ($result['status'] ?? 400);

    json_response($result, (int)$status);
} catch (\Throwable $e) {
    // 💎 Keep the UI responsive even if the backend fails
    json_response([
        'success' => false,
        'message' => 'API Error: ' . $e->getMessage()
    ], 500);
}
