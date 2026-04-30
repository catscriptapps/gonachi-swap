<?php
// /server/models/Notification.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    // Enable Eloquent timestamps
    public $timestamps = true;

    // Type Constants
    const TYPE_LISTING = 'LISTING';
    const TYPE_SYSTEM = 'SYSTEM';

    protected $fillable = [
        'receiver_id',        // The person seeing the notification (User ID)
        'sender_id',          // The person who triggered it (User ID)
        'type',               // LISTING, SYSTEM
        'target_id',          // The ID of the related Request eg mentor request, ad notificaiton, quotation/listing response etc
        'target_status',      // The status of the request
        'subject',            // Short title
        'notification_message', // Body text
        'is_read',            // For the status badge
    ];

    /**
     * The person who receives the notification
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * The person who performed the action
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
