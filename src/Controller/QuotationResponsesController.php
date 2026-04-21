<?php
// /src/Controller/QuotationResponsesController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\QuotationResponse;
use App\Models\Notification;
use App\Models\User;
use App\Utils\IdEncoder;
use Illuminate\Database\Capsule\Manager as Capsule;

class QuotationResponsesController
{
    public function acceptResponse(array $data): array
    {
        return $this->processHandshake($data, QuotationResponse::STATUS_ACCEPTED);
    }

    public function declineResponse(array $data): array
    {
        return $this->processHandshake($data, QuotationResponse::STATUS_DECLINED);
    }

    private function processHandshake(array $data, string $newStatus): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) return ['success' => false, 'message' => 'Login required.', 'status' => 401];

        try {
            $notificationId = (int)($data['notification_id'] ?? 0);
            $ownerResponseMessage = $data['message'] ?? '';

            $originalNotification = Notification::find($notificationId);
            if (!$originalNotification) return ['success' => false, 'message' => 'Notification not found.', 'status' => 404];

            $response = QuotationResponse::with(['sender', 'quotation.owner'])->find($originalNotification->target_id);
            if (!$response) return ['success' => false, 'message' => 'Response record not found.', 'status' => 404];

            if ((int)$originalNotification->receiver_id !== (int)$currentUserId) {
                return ['success' => false, 'message' => 'Unauthorized action.', 'status' => 403];
            }

            $verb = ($newStatus === QuotationResponse::STATUS_ACCEPTED) ? 'accepted' : 'declined';

            Capsule::transaction(function () use ($response, $originalNotification, $newStatus, $ownerResponseMessage, $verb) {
                $response->update(['status' => $newStatus]);
                $originalNotification->update(['is_read' => 1, 'target_status' => $newStatus]);

                $sender = User::with(['region', 'country'])->find($response->sender_id);
                $owner = User::with(['region', 'country'])->find($originalNotification->receiver_id);

                $loc = function ($u) {
                    return "{$u->full_name} - {$u->city}, " . ($u->region->region ?? 'Unknown');
                };

                // Notify Owner
                NotificationsController::create([
                    'receiver_id' => $owner->id,
                    'sender_id' => $sender->id,
                    'type' => Notification::TYPE_SYSTEM,
                    'target_id' => $response->id,
                    'target_status' => $newStatus,
                    'subject' => "Quotation Bid " . ucfirst($verb),
                    'notification_message' => "You {$verb} a bid from " . $loc($sender),
                ]);

                // Notify Bidder (Sender)
                NotificationsController::create([
                    'receiver_id' => $sender->id,
                    'sender_id' => $owner->id,
                    'type' => Notification::TYPE_SYSTEM,
                    'target_id' => $response->id,
                    'target_status' => $newStatus,
                    'subject' => "Quotation Bid " . ucfirst($verb),
                    'notification_message' => $loc($owner) . " {$verb} your quotation response. " . ($ownerResponseMessage ? "Message: \"{$ownerResponseMessage}\"" : ""),
                ]);
            });

            return ['success' => true, 'message' => "Response {$verb} successfully."];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'status' => 500];
        }
    }

    public function sendResponse(array $data): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) return ['success' => false, 'message' => 'Login required.', 'status' => 401];

        try {
            $rawId = $data['quotation_id'] ?? '';
            $message = trim($data['message'] ?? '');
            $receiverId = (int)($data['receiver_id'] ?? 0);

            // 💎 Use IdEncoder to handle encoded strings like "Mg"
            $quotationId = is_numeric($rawId) ? (int)$rawId : IdEncoder::decode((string)$rawId);

            // 💎 Explicit Validation
            if (!$quotationId) {
                return ['success' => false, 'message' => 'Invalid Quotation reference.', 'status' => 400];
            }

            if (empty($message)) {
                return ['success' => false, 'message' => 'Message is required.', 'status' => 400];
            }

            if ((int)$currentUserId === (int)$receiverId) {
                return ['success' => false, 'message' => 'You cannot bid on your own quotation.', 'status' => 400];
            }

            $existing = QuotationResponse::where('sender_id', $currentUserId)
                ->where('quotation_id', $quotationId)
                ->whereIn('status', [QuotationResponse::STATUS_PENDING, QuotationResponse::STATUS_ACCEPTED])
                ->first();

            if ($existing) {
                return ['success' => false, 'message' => 'Active response already exists.', 'status' => 400];
            }

            $result = Capsule::transaction(function () use ($currentUserId, $quotationId, $message, $receiverId) {
                $res = QuotationResponse::create([
                    'sender_id' => $currentUserId,
                    'quotation_id' => $quotationId,
                    'status' => QuotationResponse::STATUS_PENDING,
                    'message' => $message,
                ]);

                NotificationsController::create([
                    'receiver_id' => $receiverId,
                    'sender_id' => $currentUserId,
                    'type' => Notification::TYPE_QUOTATION,
                    'target_id' => $res->id,
                    'subject' => 'New Quotation Response',
                    'notification_message' => $message,
                ]);
                return $res;
            });

            return ['success' => true, 'message' => 'Response sent!', 'data' => $result];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'status' => 500];
        }
    }
}
