<?php
// /server/api/chats.php

declare(strict_types=1);

use Src\Controller\ChatsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

/**
 * Chats API - Secure Entry Point 💎
 */

// 1. Auth Check - Privacy is paramount for private messaging
$currentUserId = AuthService::userId();
if (!$currentUserId) {
    json_response(['success' => false, 'messages' => ['Authentication required to access messages']], 401);
    exit;
}

/**
 * 🍊 DATA MERGE 🍊
 * We merge $_POST with the JSON input stream. 
 * This ensures that if JS sends FormData OR JSON, the $input variable is fully populated.
 */
$jsonData = json_decode(file_get_contents('php://input'), true) ?: [];
$input = array_merge($_POST, $jsonData);

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new ChatsController();

    /**
     * GET: Fetching data
     * /api/chats -> Get Inbox List
     * /api/chats?with={encoded_id} -> Get specific message thread
     */
    if ($method === 'GET') {
        $withUser = $_GET['with'] ?? null;

        if ($withUser) {
            // Fetch the specific conversation thread
            $controller->show($withUser);
        } else {
            // Fetch the overall Inbox/Conversation list
            $controller->index();
        }
        exit;
    }

    /**
     * POST: Sending or Modifying
     * /api/chats (POST) -> Send a message
     */
    if ($method === 'POST') {
        $override = strtoupper($input['_method'] ?? '');

        if ($override === 'DELETE') {
            // Optional: Delete an entire conversation or specific message
            $result = $controller->delete($input['id'] ?? 0);
        } else {
            // Send new message or reply
            // $input now contains all the data regardless of the transmission method
            $result = $controller->sendMessage($input);
        }

        // Ensure HTML snippets for new messages/bubbles are UTF-8 clean
        if (!empty($result['html'])) {
            $result['html'] = mb_convert_encoding($result['html'], 'UTF-8', 'UTF-8');
        }

        json_response($result);
    } else {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
    }
} catch (\Throwable $e) {
    // Log the error internally and return a clean message to the UI
    error_log("Chat API Error: " . $e->getMessage());
    json_response(['success' => false, 'messages' => ['Messaging service error. Please try again later.']], 500);
}
