<?php
// /server/models/ConversationMessage.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationMessage extends Model
{
    protected $table = 'conversation_messages';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message_text',
        'message_type', // text, image, video, youtube
        'attachment_url',
        'is_read'
    ];

    protected $casts = [
        'id'              => 'integer',
        'conversation_id' => 'integer',
        'sender_id'       => 'integer',
        'is_read'         => 'boolean',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime',
    ];


    /**
     * Relationship: Back to the parent conversation
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }

    /**
     * Relationship: The user who sent this message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
}
