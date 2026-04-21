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

    protected $fillable = [
        'listing_id',
        'pic_name',
        'pic_caption',
        'pos_index'
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
     * Check if the picture belongs to a listing owned by the given user.
     */
    public function isOwnedBy(int $userId): bool
    {
        // Hop to the parent listing and check 'orig_user_id'
        return $this->listing && (int)$this->listing->orig_user_id === $userId;
    }

    protected static function booted()
    {
        static::deleting(function ($pic) {
            $basePath = dirname(__DIR__, 2);
            $filePath = $basePath . '/public/images/uploads/listings/' . $pic->pic_name;

            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        });
    }
}
