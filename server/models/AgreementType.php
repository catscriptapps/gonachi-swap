<?php
// /server/models/AgreementType.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgreementType extends Model
{
    protected $table = 'agreement_types';
    protected $primaryKey = 'agreement_type_id';
    public $timestamps = true;

    protected $fillable = ['agreement_type_id', 'agreement_type'];

    protected $casts = [
        'agreement_type_id' => 'integer'
    ];
}
