<?php
// /server/api/quotation-respond.php

declare(strict_types=1);

use Src\Controller\QuotationResponsesController;

header('Content-Type: application/json; charset=utf-8');

// Merge both standard POST and JSON payload from JS fetch
$input = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    $controller = new QuotationResponsesController();

    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'accept':
                $result = $controller->acceptResponse($input);
                break;
            case 'decline':
                $result = $controller->declineResponse($input);
                break;
            default:
                $result = ['success' => false, 'message' => 'Invalid action provided.', 'status' => 400];
                break;
        }
    } else {
        // This handles the initial "Send Response" (the bid/reply)
        $result = $controller->sendResponse($input);
    }

    $status = $result['status'] ?? ($result['success'] ? 200 : 400);
    json_response($result, (int)$status);
} catch (\Throwable $e) {
    json_response([
        'success' => false,
        'message' => 'Quotation API Error: ' . $e->getMessage()
    ], 500);
}
