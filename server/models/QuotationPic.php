<?php
// /server/models/QuotationPic.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationPic extends Model
{
    protected $table = 'quotations_pics';
    protected $primaryKey = 'entry_id';

    public $incrementing = true;

    protected $fillable = [
        'quotation_id',
        'pic_name',
        'pic_caption',
        'pos_index'
    ];

    protected $casts = [
        'entry_id'          => 'integer',
        'quotation_id'      => 'integer',
        'pos_index'         => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Relationship: The quotation this picture belongs to
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id', 'quotation_id');
    }

    /**
     * Check if the picture belongs to an advert owned by the given user.
     */
    public function isOwnedBy(int $userId): bool
    {
        // Hop to the parent advert and check 'orig_user_id'
        return $this->quotation && (int)$this->quotation->orig_user_id === $userId;
    }

    protected static function booted()
    {
        static::deleting(function ($pic) {
            $basePath = dirname(__DIR__, 2);
            $filePath = $basePath . '/public/images/uploads/quotations/' . $pic->pic_name;

            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        });
    }
}
