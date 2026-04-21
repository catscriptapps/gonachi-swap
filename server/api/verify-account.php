<?php
// /server/api/verify-account.php

declare(strict_types=1);

use Src\Controller\AuthController;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['success' => false, 'messages' => ['Invalid method']], 405);
    exit;
}

// Call the verifyAccount method we discussed earlier in AuthController
$result = AuthController::verifyAccount($input);

json_response($result);
