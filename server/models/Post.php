<?php
// /server/models/Post.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Eloquent Post model for the Social Feed
 */
class Post extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'post_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'orig_user_id',
        'content',
        'media_url',
        'media_type', // 'image', 'video', or 'none'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public $timestamps = true;

    // ============================================================
    // Relationships
    // ============================================================

    /**
     * The user who authored the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'orig_user_id');
    }

    /**
     * Comments on this post.
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id')->orderBy('created_at', 'asc');
    }

    /**
     * Likes on this post.
     */
    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    // ============================================================
    // Query Scopes
    // ============================================================

    /**
     * Scope: Only posts from users the current user follows.
     * We'll pass an array of followed user IDs here.
     */
    public function scopeFromFollowedUsers($query, array $userIds)
    {
        return $query->whereIn('orig_user_id', $userIds);
    }

    /**
     * Scope: Filter by media type (e.g., just show video posts).
     */
    public function scopeWithMedia($query, string $type = null)
    {
        if ($type) {
            return $query->where('media_type', $type);
        }
        return $query->where('media_type', '!=', 'none');
    }
}
