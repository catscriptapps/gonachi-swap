<?php
// /server/models/AdvertPic.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertPic extends Model
{
    protected $table = 'adverts_pics';
    protected $primaryKey = 'entry_id';

    public $incrementing = true;

    protected $fillable = [
        'advert_id',
        'pic_name',
        'pic_caption',
        'pos_index'
    ];

    protected $casts = [
        'entry_id'   => 'integer',
        'advert_id'  => 'integer',
        'pos_index'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The "booted" method to handle physical file deletion.
     */
    protected static function booted()
    {
        static::deleting(function ($pic) {
            /** * We calculate the path from the document root. 
             * If your models are in /server/models/, we go up two levels 
             * to reach the project root, then into public/...
             */
            $basePath = dirname(__DIR__, 2);
            $filePath = $basePath . '/public/images/uploads/adverts/' . $pic->pic_name;

            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        });
    }

    /**
     * Relationship: The advert this picture belongs to
     */
    public function advert(): BelongsTo
    {
        return $this->belongsTo(Advert::class, 'advert_id', 'advert_id');
    }

    /**
     * Check ownership (via Parent Advert)
     */
    public function isOwnedBy(int $userId): bool
    {
        return $this->advert && (int)$this->advert->orig_user_id === $userId;
    }
}
