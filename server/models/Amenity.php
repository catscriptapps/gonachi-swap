<?php
// /server/models/Amenity.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $table = 'amenities';
    protected $primaryKey = 'amenity_id';
    public $timestamps = true;

    protected $fillable = ['amenity_id', 'category_id', 'name'];
}
