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
        'city',
        'category_id',
        'category_type_id',
        'unit_type_id',
        'house_type_id',
        'bedroom_id',
        'bathroom_id',
        'listing_description',
        'address',
        'country_id',
        'region_id',
        'agreement_type_id',
        'price',
        'property_size',
        'move_in_date',
        'is_ac',
        'is_furnished',
        'parking',
        'pets_allowed',
        'amenities',
        'youtube_url',
        'contact_phone',
        'status_id',
        'comments',
        'views'
    ];

    protected $casts = [
        'listing_id'       => 'integer',
        'orig_user_id'     => 'integer',
        'category_id'      => 'integer',
        'category_type_id' => 'integer',
        'unit_type_id'     => 'integer',
        'house_type_id'    => 'integer',
        'bedroom_id'       => 'integer',
        'bathroom_id'      => 'integer',
        'country_id'       => 'integer',
        'region_id'        => 'integer',
        'agreement_type_id' => 'integer',
        'status_id'        => 'integer',
        'views'            => 'integer',
        'amenities'        => 'array', // Crucial for JSON storage
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * AMENITIES HELPER
     * Since amenities are stored as a JSON array of IDs, this method
     * fetches the actual Amenity models for those IDs.
     */
    public function getAmenityModels()
    {
        if (empty($this->amenities)) return collect();
        return Amenity::whereIn('amenity_id', $this->amenities)->get();
    }

    /* -------------------------------------------------------------------------- */
    /* RELATIONSHIPS                               */
    /* -------------------------------------------------------------------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orig_user_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ListingCategory::class, 'category_id', 'category_id');
    }

    public function categoryType(): BelongsTo
    {
        return $this->belongsTo(ListingCategoryType::class, 'category_type_id', 'category_type_id');
    }

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id', 'unit_type_id');
    }

    public function houseType(): BelongsTo
    {
        return $this->belongsTo(HouseType::class, 'house_type_id', 'house_type_id');
    }

    public function bedroom(): BelongsTo
    {
        return $this->belongsTo(Bedroom::class, 'bedroom_id', 'bedroom_id');
    }

    public function bathroom(): BelongsTo
    {
        return $this->belongsTo(Bathroom::class, 'bathroom_id', 'bathroom_id');
    }

    public function agreementType(): BelongsTo
    {
        return $this->belongsTo(AgreementType::class, 'agreement_type_id', 'agreement_type_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function pictures(): HasMany
    {
        return $this->hasMany(ListingPic::class, 'listing_id', 'listing_id');
    }

    /* -------------------------------------------------------------------------- */
    /* LOGIC                                                                      */
    /* -------------------------------------------------------------------------- */

    public function isDraft(): bool
    {
        return $this->status_id === 0;
    }
    public function isPosted(): bool
    {
        return $this->status_id === 1;
    }
    public function isArchived(): bool
    {
        return $this->status_id === 2;
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
