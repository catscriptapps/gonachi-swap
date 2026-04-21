<?php
// /server/models/Advert.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Eloquent Advert model
 */
class Advert extends Model
{
    // --- Package Constants ---
    public const PACKAGE_FREE = 1;
    public const PACKAGE_PAID = 2;

    // --- Status Constants ---
    public const STATUS_ACTIVE   = 'active';
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_EXPIRED  = 'expired';

    protected $table = 'adverts';
    protected $primaryKey = 'advert_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'orig_user_id',
        'title',
        'description',
        'call_to_action_id',
        'keywords',
        'landing_page_url',
        'selected_countries',
        'selected_user_types',
        'advert_package',
        'status',
        'expires_at',
        'views'
    ];

    protected $casts = [
        'selected_countries'    => 'array',
        'selected_user_types'   => 'array',
        'advert_package'        => 'integer',
        'call_to_action_id'     => 'integer',
        'expires_at'            => 'datetime',
        'views'                 => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function ($advert) {
            $advert->pictures()->get()->each(function ($pic) {
                $pic->delete();
            });
        });
    }

    // ============================================================
    // Relationships
    // ============================================================

    public function pictures(): HasMany
    {
        return $this->hasMany(AdvertPic::class, 'advert_id', 'advert_id');
    }

    public function cta(): BelongsTo
    {
        return $this->belongsTo(AdvertCallToAction::class, 'call_to_action_id', 'call_to_action_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orig_user_id', 'id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(AdvertPackage::class, 'advert_package', 'package_id');
    }

    // --- Helpers ---

    /**
     * Helper to check if the ad is currently active
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
