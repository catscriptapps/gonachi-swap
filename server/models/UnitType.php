<?php
// /server/models/UnitType.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitType extends Model
{
    protected $table = 'unit_types';
    protected $primaryKey = 'unit_type_id';

    public $incrementing = true;

    protected $fillable = [
        'unit_type',
        'for_sale'
    ];

    protected $casts = [
        'unit_type_id' => 'integer',
        'for_sale'     => 'boolean', // 0/1 becomes false/true automatically
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    /**
     * Relationship: Quotations associated with this unit type
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'unit_type_id', 'unit_type_id');
    }
}
