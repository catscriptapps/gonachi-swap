<?php
// /server/models/ContractorType.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractorType extends Model
{
    protected $table = 'contractor_types';
    protected $primaryKey = 'contractor_type_id';

    /**
     * Standard Auto-incrementing ID
     */
    public $incrementing = true;

    protected $fillable = [
        'contractor_type'
    ];

    protected $casts = [
        'contractor_type_id' => 'integer',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    /**
     * Define relationship to Quotations once that model is ready.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'contractor_type_id', 'contractor_type_id');
    }
}
