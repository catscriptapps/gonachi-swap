<?php
// /server/api/mentors.php

declare(strict_types=1);

use Src\Controller\MentorsController;
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
    $controller = new MentorsController();

    // HANDLE BROWSE / SEARCH (GET)
    if ($method === 'GET') {
        $controller->index();
        exit;
    }

    // HANDLE ACTIONS (POST)
    if ($method === 'POST') {
        // Support for DELETE override like quotations.php 🗑️
        $override = strtoupper($input['_method'] ?? '');

        if ($override === 'DELETE') {
            // Use the delete function
            $result = $controller->delete($input['id'] ?? 0);
        } else {
            // Create or Update mentor profile
            $result = $controller->save($input);
        }

        // Final UTF-8 Clean for JSON safety (essential for generated HTML)
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
