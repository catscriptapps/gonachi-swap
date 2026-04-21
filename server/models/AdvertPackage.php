<?php
// /server/models/AdvertPackage.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvertPackage extends Model
{
    protected $table = 'adverts_packages';
    protected $primaryKey = 'package_id';

    // IMPORTANT: Tell Eloquent not to try and auto-increment the ID
    public $incrementing = false;

    protected $fillable = [
        'package_id', // Must be fillable since we are setting it manually
        'package_name',
        'package_description',
        'package_icon',
        'package_order'
    ];

    protected $casts = [
        'package_id'    => 'integer',
        'package_order' => 'integer',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function adverts(): HasMany
    {
        return $this->hasMany(Advert::class, 'advert_package', 'package_id');
    }
}
