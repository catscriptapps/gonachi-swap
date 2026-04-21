<?php
// /server/models/ListingCategory.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListingCategory extends Model
{
    // Following legacy SQL naming convention
    protected $table = 'listings_categories';
    protected $primaryKey = 'category_id';

    public $incrementing = true;

    protected $fillable = [
        'category'
    ];

    protected $casts = [
        'category_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Relationship: Listings belonging to this category
     * (We will build the Listing model next)
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'category_id', 'category_id');
    }
}
