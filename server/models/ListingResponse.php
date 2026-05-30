<?php
// /server/models/ListingResponse.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingResponse extends Model
{
    protected $table = 'listings_responses';

    public $timestamps = true;

    protected $fillable = [
        'sender_id',
        'listing_id',
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
     * Relationship to the Listing being responded to
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }
}
