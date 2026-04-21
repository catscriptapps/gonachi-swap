<?php
// /server/models/Follow.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follows';
    protected $primaryKey = 'follow_id';

    protected $fillable = [
        'follower_id', // The person doing the following
        'following_id', // The person being followed
    ];

    public $timestamps = true;

    /**
     * The person who is following.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * The person being followed.
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
