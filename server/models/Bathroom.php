<?php
// /server/models/Bathroom.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bathroom extends Model
{
    protected $table = 'bathrooms';
    protected $primaryKey = 'bathroom_id';
    public $timestamps = true;

    protected $fillable = ['bathroom_id', 'bathroom'];

    protected $casts = [
        'bathroom_id' => 'integer'
    ];
}
