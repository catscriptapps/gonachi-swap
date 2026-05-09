<?php
// /server/models/ListingCondition.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ListingCondition extends Model
{
    protected $table = 'listing_conditions';
    protected $primaryKey = 'condition_id';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'condition_name'
    ];

    /**
     * Get all listings that have this condition.
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'condition_id', 'condition_id');
    }
}
