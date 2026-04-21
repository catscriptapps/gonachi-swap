<?php
// /server/api/resend-verification.php

declare(strict_types=1);

use Src\Controller\AuthController;

header('Content-Type: application/json; charset=UTF-8');

// Handle both JSON and traditional POST data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'POST';

try {
    // 1. Only allow POST requests
    if ($method !== 'POST') {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
        exit;
    }

    /**
     * 2. AUTHENTICATION LOGIC GATE
     * This is a "Guest-Allowed" endpoint. 
     * We do NOT check for AuthService::userId() here because 
     * the user is trying to verify an account they can't log into yet.
     */

    // Call the static method we drafted for the AuthController
    $result = AuthController::resendVerification($input);

    json_response($result);
} catch (\Throwable $e) {
    // Standard error handling to match your users.php pattern
    json_response([
        'success' => false,
        'messages' => ['Server Error: ' . $e->getMessage()]
    ], 500);
}
