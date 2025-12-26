<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LockerAssignment extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'locker_id',
        'member_id',
        'assigned_date',
        'expiry_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    /**
     * Get the locker
     */
    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class);
    }

    /**
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Check if assignment is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Scope for active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for expired assignments
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired')
            ->orWhere(function ($q) {
                $q->where('status', 'active')
                    ->where('expiry_date', '<', now());
            });
    }
}
