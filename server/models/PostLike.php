<?php
// /server/models/PostLike.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    protected $primaryKey = 'like_id';

    protected $fillable = [
        'post_id',
        'orig_user_id',
    ];

    public $timestamps = true;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'orig_user_id');
    }
}
