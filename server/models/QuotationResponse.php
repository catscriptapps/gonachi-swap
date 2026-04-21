<?php
// /server/models/QuotationResponse.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationResponse extends Model
{
    protected $table = 'quotations_responses';

    public $timestamps = true;

    protected $fillable = [
        'sender_id',
        'quotation_id',
        'status',          // 'pending', 'accepted', 'declined'
        'message',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    /**
     * Relationship to the user who sent the response
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship to the Quotation being responded to
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
