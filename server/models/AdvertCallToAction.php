<?php
// /server/models/AdvertCallToAction.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdvertCallToAction extends Model
{
    protected $table = 'adverts_call_to_action';
    protected $primaryKey = 'call_to_action_id';

    protected $fillable = [
        'call_to_action',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: One CTA can be used by many Adverts
     */
    public function adverts(): HasMany
    {
        return $this->hasMany(Advert::class, 'call_to_action_id', 'call_to_action_id');
    }
}
