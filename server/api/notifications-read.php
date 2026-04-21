<?php
// /server/api/notifications-read.php

declare(strict_types=1);

use App\Models\Notification;
use Src\Service\AuthService;

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$noteId = $input['notification_id'] ?? null;
$currentUserId = AuthService::userId();

if (!$noteId || !$currentUserId) {
    json_response(['success' => false, 'message' => 'Invalid request'], 400);
}

try {
    $notification = Notification::where('id', $noteId)
        ->where('receiver_id', $currentUserId)
        ->first();

    if ($notification && (int)$notification->is_read === 0) {
        $notification->update(['is_read' => 1]);
        json_response(['success' => true]);
    }

    json_response(['success' => true, 'message' => 'Already read']);
} catch (\Throwable $e) {
    json_response(['success' => false, 'message' => $e->getMessage()], 500);
}
