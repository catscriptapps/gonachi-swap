<?php
// /server/models/ListingCategory.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListingCategory extends Model
{
    protected $table = 'listing_categories';
    protected $primaryKey = 'category_id';

    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'category_name',
        'category_icon', // For the UI (e.g., FontAwesome class)
        'category_slug', // For clean URLs
        'status_id'      // 1: Active, 0: Hidden
    ];

    protected $casts = [
        'category_id' => 'integer',
        'status_id'   => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /* -------------------------------------------------------------------------- */
    /* RELATIONSHIPS                                                              */
    /* -------------------------------------------------------------------------- */

    /**
     * Get all listings belonging to this category.
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'category_id', 'category_id');
    }

    /* -------------------------------------------------------------------------- */
    /* LOGIC                                                                      */
    /* -------------------------------------------------------------------------- */

    /**
     * Check if the category is currently visible.
     */
    public function isActive(): bool
    {
        return (int)$this->status_id === 1;
    }
}
