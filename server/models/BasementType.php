<?php
// /server/models/BasementType.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BasementType extends Model
{
    protected $table = 'basement_types';
    protected $primaryKey = 'basement_type_id';
    public $timestamps = true;

    protected $fillable = ['basement_type_id', 'basement_type'];

    protected $casts = [
        'basement_type_id' => 'integer'
    ];
}
