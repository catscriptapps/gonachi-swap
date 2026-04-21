<?php
// /server/models/Mentor.php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mentor extends Model
{
    protected $table = 'mentors';
    protected $primaryKey = 'id';

    /**
     * Standard Eloquent timestamps enabled.
     */
    public $timestamps = true;

    protected $fillable = [
        'orig_user_id',
        'country_id',
        'region_id',
        'city',               // Added 💎
        'target_user_type_id',
        'headline',
        'bio',
        'skills',
        'years_experience',   // Added 💎
        'youtube_url',        // Added 💎
        'website_url',        // Added 💎
        'is_active',
        'status_id'
    ];

    protected $casts = [
        'id'                  => 'integer',
        'orig_user_id'        => 'integer',
        'country_id'          => 'integer',
        'region_id'           => 'integer',
        'target_user_type_id' => 'integer',
        'years_experience'    => 'integer',
        'skills'              => 'array',
        'is_active'           => 'boolean',
        'status_id'           => 'integer',
        'created_at'          => 'datetime',
        'updated_at'          => 'datetime',
    ];

    // --- AUTH & OWNERSHIP ---

    /**
     * Relationship: The actual user who is acting as a mentor
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orig_user_id', 'id');
    }

    // --- TARGETING ---

    /**
     * Relationship: The specific user type this mentor is targeting (Mentees)
     */
    public function targetUserType(): BelongsTo
    {
        return $this->belongsTo(UserType::class, 'target_user_type_id', 'user_type_id');
    }

    // --- GEOGRAPHY ---

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    // --- CONNECTIONS ---

    /**
     * Relationship: All requests received by this mentor
     */
    public function requests(): HasMany
    {
        return $this->hasMany(MentorRequest::class, 'mentor_id', 'id');
    }
}
