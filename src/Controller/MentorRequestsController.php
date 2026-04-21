<?php
// /src/Controller/MentorRequestsController.php

declare(strict_types=1);

namespace Src\Controller;

use App\Models\MentorRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as Capsule;

class MentorRequestsController
{
    /**
     * Accept a Mentorship Request ✅
     */
    public function acceptRequest(array $data): array
    {
        return $this->processHandshake($data, MentorRequest::STATUS_ACCEPTED);
    }

    /**
     * Decline a Mentorship Request ❌
     */
    public function declineRequest(array $data): array
    {
        return $this->processHandshake($data, MentorRequest::STATUS_DECLINED);
    }

    /**
     * Core Logic for Handshake Processing 🤝
     */
    private function processHandshake(array $data, string $newStatus): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) {
            return ['success' => false, 'message' => 'Login required.', 'status' => 401];
        }

        try {
            $notificationId = (int)($data['notification_id'] ?? 0);
            $mentorResponseMessage = $data['message'] ?? '';

            // 1. Find the original notification to get the Request ID
            $originalNotification = Notification::find($notificationId);
            if (!$originalNotification) {
                return ['success' => false, 'message' => 'Notification not found.', 'status' => 404];
            }

            // 2. Find the Mentor Request
            $request = MentorRequest::with(['sender', 'mentor.user'])->find($originalNotification->target_id);
            if (!$request) {
                return ['success' => false, 'message' => 'Request record not found.', 'status' => 404];
            }

            // Security check: Only the receiver of the original notification can accept/decline
            if ((int)$originalNotification->receiver_id !== (int)$currentUserId) {
                return ['success' => false, 'message' => 'Unauthorized action.', 'status' => 403];
            }

            $verb = ($newStatus === MentorRequest::STATUS_ACCEPTED) ? 'accepted' : 'declined';

            Capsule::transaction(function () use ($request, $originalNotification, $newStatus, $mentorResponseMessage, $verb) {

                // A. Update the Request Status
                $request->update(['status' => $newStatus]);

                // B. Mark original notification as read AND update snapshot
                // This kills the buttons for the mentor immediately
                $originalNotification->update([
                    'is_read' => 1,
                    'target_status' => $newStatus // 'accepted' or 'declined'
                ]);

                // C. Data Prep for Notifications 💎
                // We fetch the full User models with their location relationships loaded
                $sender = User::with(['region', 'country'])->find($request->sender_id);
                $mUser = User::with(['region', 'country'])->find($originalNotification->receiver_id);

                // This helper now looks deep into the relationship for the 'region' and 'country' names
                $loc = function ($u) {
                    $regionName = $u->region->region ?? 'Unknown Region';
                    $countryName = $u->country->country ?? 'Unknown Country';
                    return "{$u->full_name} - {$u->city}, {$regionName}, {$countryName}";
                };

                // D. Notify the MENTOR 
                NotificationsController::create([
                    'receiver_id'          => $mUser->id,
                    'sender_id'            => $sender->id,
                    'type'                 => Notification::TYPE_SYSTEM,
                    'target_id'            => $request->id,
                    'target_status'        => $newStatus, // Snapshot status
                    'subject'              => "Mentor Handshake " . ucfirst($verb),
                    'notification_message' => "You {$verb} new mentor request from " . $loc($sender),
                ]);

                // E. Notify the SENDER
                NotificationsController::create([
                    'receiver_id'          => $sender->id,
                    'sender_id'            => $mUser->id,
                    'type'                 => Notification::TYPE_MENTOR,
                    'target_id'            => $request->id,
                    'target_status'        => $newStatus, // Snapshot status
                    'subject'              => "Mentor Handshake " . ucfirst($verb),
                    'notification_message' => $loc($mUser) . " {$verb} your new mentor request. " . ($mentorResponseMessage ? "Message: \"{$mentorResponseMessage}\"" : ""),
                ]);
            });

            return [
                'success' => true,
                'message' => "Request {$verb} successfully."
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'status' => 500];
        }
    }

    /**
     * Handle Connection Requests 🤝
     * Creates a MentorRequest record and a Notification via the NotificationsController.
     */
    public function sendRequest(array $data): array
    {
        $currentUserId = $_SESSION['user_id'] ?? null;
        if (!$currentUserId) {
            return ['success' => false, 'message' => 'Login required.', 'status' => 401];
        }

        try {
            $mentorId = isset($data['mentor_id']) ? (int)$data['mentor_id'] : null;
            $pitch = $data['message'] ?? '';
            $receiverId = (int)($data['receiver_id'] ?? 0); // The Mentor's User ID

            if (!$mentorId || empty($pitch)) {
                return ['success' => false, 'message' => 'Message is required.', 'status' => 400];
            }

            if ((int)$currentUserId === (int)$receiverId) {
                return ['success' => false, 'message' => 'You cannot connect with yourself.', 'status' => 400];
            }

            // CHECK: Ensure we are checking the specific pairing of THIS sender and THIS mentor
            $existing = MentorRequest::where('sender_id', (int)$currentUserId)
                ->where('mentor_id', (int)$mentorId)
                // Only block if the request is still PENDING or already ACCEPTED
                // If it was DECLINED, we might want to let them try again (depending on your rules)
                ->whereIn('status', [MentorRequest::STATUS_PENDING, MentorRequest::STATUS_ACCEPTED])
                ->first();

            if ($existing) {
                return [
                    'success' => false,
                    'message' => 'You already have an active request with this mentor.',
                    'status' => 400
                ];
            }

            $result = Capsule::transaction(function () use ($currentUserId, $mentorId, $pitch, $receiverId) {

                // 1. Create the persistent Request record 💎
                $request = MentorRequest::create([
                    'sender_id' => $currentUserId,
                    'mentor_id' => $mentorId,
                    'status'    => MentorRequest::STATUS_PENDING,
                    'message'   => $pitch,
                ]);

                // 2. Create the Notification via NotificationsController 🔔
                NotificationsController::create([
                    'receiver_id'          => $receiverId,
                    'sender_id'            => $currentUserId,
                    'type'                 => Notification::TYPE_MENTOR,
                    'target_id'            => $request->id,
                    'subject'              => 'New Mentorship Request',
                    'notification_message' => $pitch,
                ]);

                return $request;
            });

            return [
                'success' => true,
                'message' => 'Mentor Handshake sent successfully!',
                'data'    => $result
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'status' => 500];
        }
    }
}
