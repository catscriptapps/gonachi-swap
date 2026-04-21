<?php
// /server/models/AvailableParking.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableParking extends Model
{
    protected $table = 'available_parking';
    protected $primaryKey = 'parking_id';
    public $timestamps = true;

    protected $fillable = ['parking_id', 'parking'];

    protected $casts = [
        'parking_id' => 'integer'
    ];
}
