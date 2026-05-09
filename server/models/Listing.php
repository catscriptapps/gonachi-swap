<?php
// /server/models/Listing.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listing extends Model
{
    protected $table = 'listings';
    protected $primaryKey = 'listing_id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'orig_user_id',
        'listing_title',
        'listing_description',
        'category_id',
        'type_id',           // 1: Swap, 2: Sale, 3: Gift
        'condition_id',      // 1: New, 2: Like New, 3: Used, 4: Parts
        'status_id',         // 0: Draft, 1: Posted, 2: Completed, 3: Archived
        'price',
        'trade_pref',
        'city',
        'region_id',
        'country_id',
        'latitude',
        'longitude',
        'youtube_url',
        'views',
        'contact_phone'
    ];

    protected $casts = [
        'listing_id'   => 'integer',
        'orig_user_id' => 'integer',
        'category_id'  => 'integer',
        'type_id'      => 'integer',
        'condition_id' => 'integer',
        'status_id'    => 'integer',
        'region_id'    => 'integer',
        'country_id'   => 'integer',
        'latitude'     => 'float',
        'longitude'    => 'float',
        'price'        => 'decimal:2',
        'views'        => 'integer',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /* -------------------------------------------------------------------------- */
    /* RELATIONSHIPS                                                              */
    /* -------------------------------------------------------------------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orig_user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ListingCategory::class, 'category_id', 'category_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ListingType::class, 'type_id', 'type_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(ListingCondition::class, 'condition_id', 'condition_id');
    }

    public function pictures(): HasMany
    {
        return $this->hasMany(ListingPic::class, 'listing_id', 'listing_id');
    }

    /* -------------------------------------------------------------------------- */
    /* LOGIC                                                                      */
    /* -------------------------------------------------------------------------- */

    public function isSwap(): bool
    {
        return (int)$this->type_id === 1;
    }

    public function isSale(): bool
    {
        return (int)$this->type_id === 2;
    }

    public function isGift(): bool
    {
        return (int)$this->type_id === 3;
    }

    public function isPosted(): bool
    {
        return (int)$this->status_id === 1;
    }

    protected static function booted()
    {
        static::deleting(function ($listing) {
            $listing->pictures()->get()->each(function ($pic) {
                $pic->delete();
            });
        });
    }
}
