<?php
// /server/api/listing-inquiry.php

declare(strict_types=1);

use Src\Controller\ListingResponsesController;

header('Content-Type: application/json; charset=utf-8');

// Merging JS fetch (JSON) and standard POST
$input = array_merge($_POST, json_decode(file_get_contents('php://input'), true) ?: []);

try {
    $controller = new ListingResponsesController();

    if (isset($input['action'])) {
        switch ($input['action']) {
            case 'accept':
                // Targets ListingResponsesController::acceptInquiry()
                $result = $controller->acceptInquiry($input);
                break;
            case 'decline':
                // Targets ListingResponsesController::declineInquiry()
                $result = $controller->declineInquiry($input);
                break;
            default:
                $result = ['success' => false, 'message' => 'Invalid listing action.', 'status' => 400];
                break;
        }
    } else {
        // Targets ListingResponsesController::sendInquiry() for the initial inquiry
        $result = $controller->sendInquiry($input);
    }

    $status = $result['status'] ?? ($result['success'] ? 200 : 400);
    json_response($result, (int)$status);
} catch (\Throwable $e) {
    json_response([
        'success' => false,
        'message' => 'Listing Response API Error: ' . $e->getMessage()
    ], 500);
}
