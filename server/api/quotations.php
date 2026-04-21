<?php
// /server/api/quotations.php

declare(strict_types=1);

use Src\Controller\QuotationsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

$input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new QuotationsController();

    // HANDLE SEARCH / FETCH (GET)
    if ($method === 'GET') {
        $controller->index();
        exit;
    }

    // HANDLE SAVE / DELETE / DEACTIVATE / REACTIVATE (POST)
    if ($method === 'POST') {
        $override = strtoupper($input['_method'] ?? '');
        $intent   = $input['intent'] ?? ''; // 💎 Capture the intent (deactivate/reactivate)

        if ($override === 'DELETE') {
            $result = $controller->delete($input['id'] ?? 0);
        }
        // 💎 Handle specific status change intents
        elseif ($intent === 'deactivate') {
            $result = $controller->deactivate($input['id'] ?? 0);
        } elseif ($intent === 'reactivate') {
            $result = $controller->reactivate($input['id'] ?? 0);
        } else {
            // Create or Update
            $result = $controller->save($input);
        }

        // Final UTF-8 Clean for JSON safety (crucial for cardHtml)
        if (!empty($result['cardHtml'])) {
            $result['cardHtml'] = mb_convert_encoding($result['cardHtml'], 'UTF-8', 'UTF-8');
        }

        json_response($result);
    } else {
        json_response(['success' => false, 'messages' => ['Method not supported']], 405);
    }
} catch (\Throwable $e) {
    json_response(['success' => false, 'messages' => [$e->getMessage()]], 500);
}
