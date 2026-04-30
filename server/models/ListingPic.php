<?php
// /server/models/ListingPic.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingPic extends Model
{
    protected $table = 'listings_pics';
    protected $primaryKey = 'entry_id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'listing_id',
        'pic_name',
        'pic_caption',
        'pos_index',
        'media_type' // 'image' or 'video'
    ];

    protected $casts = [
        'entry_id'   => 'integer',
        'listing_id' => 'integer',
        'pos_index'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: The listing this picture belongs to
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class, 'listing_id', 'listing_id');
    }

    /**
     * Ownership check helper
     */
    public function isOwnedBy(int $userId): bool
    {
        return $this->listing && (int)$this->listing->orig_user_id === $userId;
    }

    protected static function booted()
    {
        static::deleting(function ($pic) {
            $basePath = dirname(__DIR__, 2);
            // Adjusted path to follow standard project structure
            $filePath = $basePath . '/public/images/uploads/listings/' . $pic->pic_name;

            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        });
    }
}
