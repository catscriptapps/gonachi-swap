<?php
// /server/api/adverts.php

declare(strict_types=1);

use Src\Controller\AdvertsController;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=UTF-8');

// 1. Auth Check - Adverts are restricted to logged-in users
$userId = AuthService::userId();
if (!$userId) {
    json_response(['success' => false, 'messages' => ['Authentication required']], 401);
    exit;
}

$input  = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $controller = new AdvertsController();

    // HANDLE SEARCH / FETCH (GET)
    // Supports infinite scroll (?page=x) and search (?q=term)
    if ($method === 'GET') {
        $controller->index();
        exit;
    }

    // HANDLE SAVE/DELETE (POST)
    if ($method === 'POST') {
        $override = strtoupper($input['_method'] ?? '');

        if ($override === 'DELETE') {
            // Controller handles internal decoding of the encoded_id or raw ID
            $result = $controller->delete($input['id'] ?? 0);
        } else {
            // Create or Update
            $result = $controller->save($input);
        }

        // Final UTF-8 Clean for JSON safety (essential for the generated cardHtml)
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
