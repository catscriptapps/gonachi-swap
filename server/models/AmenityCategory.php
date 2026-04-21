<?php
// /server/models/AmenityCategory.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmenityCategory extends Model
{
    protected $table = 'amenity_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = true;

    protected $fillable = ['category_id', 'name'];

    public function amenities(): HasMany
    {
        return $this->hasMany(Amenity::class, 'category_id', 'category_id');
    }
}
