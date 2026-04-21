<?php
// /server/models/MentorRequest.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model
{
    protected $table = 'mentors_requests';

    public $timestamps = true;

    protected $fillable = [
        'sender_id',
        'mentor_id',
        'status',          // 'pending', 'accepted', 'declined'
        'message',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /**
     * Relationship to the user who sent the request
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship to the Mentor Card
     */
    public function mentor()
    {
        return $this->belongsTo(Mentor::class, 'mentor_id');
    }
}
