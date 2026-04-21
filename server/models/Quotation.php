<?php
// /server/models/Quotation.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    protected $table = 'quotations';
    protected $primaryKey = 'quotation_id';

    /**
     * Standard Eloquent timestamps enabled (created_at, updated_at).
     */
    public $timestamps = true;

    protected $fillable = [
        'orig_user_id',
        'country_id',
        'region_id',
        'contractor_type_id',
        'skilled_trade_id',
        'quotation_title',
        'city',
        'description_of_work_to_be_done',
        'unit_type_id',
        'house_type_id',
        'start_date',
        'finish_date',
        'start_time',
        'finish_time',
        'quotation_budget',
        'quotation_type_id',
        'quotation_dest_id',
        'youtube_url',
        'contact_phone',
        'notify',
        'status_id',
        'views'
    ];

    protected $casts = [
        'quotation_id'       => 'integer',
        'orig_user_id'       => 'integer',
        'country_id'         => 'integer',
        'region_id'          => 'integer',
        'contractor_type_id' => 'integer',
        'skilled_trade_id'   => 'integer',
        'unit_type_id'       => 'integer',
        'house_type_id'      => 'integer',
        'quotation_type_id'  => 'integer',
        'quotation_dest_id'  => 'integer',
        'status_id'          => 'integer',
        'views'              => 'integer',
        'created_at'         => 'datetime',
        'updated_at'         => 'datetime',
    ];

    // --- AUTH & OWNERSHIP ---

    /**
     * Relationship: The user who created the quotation
     */
    public function owner(): BelongsTo
    {
        // This maps the 'orig_user_id' in quotations to 'id' in users
        return $this->belongsTo(User::class, 'orig_user_id', 'id');
    }

    // --- GEOGRAPHY ---

    /**
     * Relationship: The country where the work is located
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Relationship: The state/province/region
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    // --- CATEGORIES & TRADES ---

    /**
     * Relationship: The type of contractor requested (General, Sub-contractor, etc)
     */
    public function contractorType(): BelongsTo
    {
        return $this->belongsTo(ContractorType::class, 'contractor_type_id', 'contractor_type_id');
    }

    /**
     * Relationship: The specific trade requested (Plumbing, Electrician, etc)
     */
    public function skilledTrade(): BelongsTo
    {
        return $this->belongsTo(SkilledTrade::class, 'skilled_trade_id', 'skilled_trade_id');
    }

    // --- PROPERTY DETAILS ---

    /**
     * Relationship: The type of unit (Apartment, House, etc)
     */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id', 'unit_type_id');
    }

    /**
     * Relationship: The style of the house (Detached, Bungalow, etc)
     */
    public function houseType(): BelongsTo
    {
        return $this->belongsTo(HouseType::class, 'house_type_id', 'house_type_id');
    }

    // --- QUOTATION LOGIC ---

    /**
     * Relationship: The labor scope (Labor Only vs Labor and Materials)
     */
    public function quotationType(): BelongsTo
    {
        return $this->belongsTo(QuotationType::class, 'quotation_type_id', 'quotation_type_id');
    }

    /**
     * Relationship: The visibility scope (Region vs Country)
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(QuotationDestination::class, 'quotation_dest_id', 'quotation_dest_id');
    }

    // --- ATTACHMENTS ---

    /**
     * Relationship: Images uploaded for this quotation
     */
    public function pictures(): HasMany
    {
        return $this->hasMany(QuotationPic::class, 'quotation_id', 'quotation_id');
    }

    protected static function booted()
    {
        static::deleting(function ($quotation) {
            // Trigger individual model deletion for each picture
            $quotation->pictures()->get()->each(function ($pic) {
                $pic->delete();
            });
        });
    }
}
