<?php
// /server/models/Conversation.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $table = 'conversations';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message_at'
    ];

    protected $casts = [
        'id'              => 'integer',
        'user_one_id'     => 'integer',
        'user_two_id'     => 'integer',
        'last_message_at' => 'datetime',
    ];

    /**
     * Relationship: All messages in this specific conversation
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class, 'conversation_id', 'id');
    }

    /**
     * Relationship: The latest message for the Inbox snippet
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(ConversationMessage::class, 'conversation_id', 'id')->latestOfMany();
    }

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id', 'id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id', 'id');
    }
}
