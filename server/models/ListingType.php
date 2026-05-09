<?php
// /server/models/ListingType.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListingType extends Model
{
    protected $table = 'listing_types';
    protected $primaryKey = 'type_id';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'type_name'
    ];

    /**
     * Get all listings belonging to this transaction type.
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'type_id', 'type_id');
    }
}
