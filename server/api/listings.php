<?php
// /server/api/listings.php

declare(strict_types=1);

use Src\Controller\ListingsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

/**
 * 1. Auth Shield
 * Listings management (CRUD) is restricted to authenticated users.
 */
$userId = AuthService::userId();
if (!$userId) {
    echo json_encode(['success' => false, 'messages' => ['Authentication required']]);
    http_response_code(401);
    exit;
}

// Support both JSON payloads and standard FormData
$input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new ListingsController();

    /**
     * HANDLE FETCH (GET)
     * Supports:
     * - ?page=x (Pagination)
     * - ?q=term (Search)
     * - ?all=true (Browse mode vs. My Listings mode)
     */
    if ($method === 'GET') {
        $showAll = filter_var($_GET['all'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $controller->index($showAll);
        exit;
    }

    /**
     * HANDLE ACTIONS (POST)
     * Uses 'intent' to differentiate between Saving, Deleting, and Status Toggles 💎
     */
    if ($method === 'POST') {
        $override = strtoupper($input['_method'] ?? '');
        $intent   = $input['intent'] ?? ''; 
        $id       = $input['id'] ?? $input['encoded_id'] ?? 0;

        if ($override === 'DELETE' || $intent === 'delete') {
            $result = $controller->delete($id);
        } 
        elseif ($intent === 'deactivate') {
            $result = $controller->deactivate($id);
        } 
        elseif ($intent === 'reactivate') {
            $result = $controller->reactivate($id);
        } 
        else {
            // Default: Create or Update logic
            $result = $controller->save($input);
        }

        // Final UTF-8 Clean for JSON safety before output
        if (!empty($result['cardHtml'])) {
            $result['cardHtml'] = mb_convert_encoding($result['cardHtml'], 'UTF-8', 'UTF-8');
        }

        echo json_encode($result);
        exit;
    }

    // Fallback for unsupported methods (PUT, PATCH, etc.)
    http_response_code(405);
    echo json_encode(['success' => false, 'messages' => ['Method not supported']]);

} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'messages' => [$e->getMessage()]]);
}