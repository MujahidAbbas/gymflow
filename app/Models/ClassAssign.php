<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassAssign extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'gym_class_id',
        'member_id',
        'enrollment_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
        ];
    }

    /**
     * Get the class
     */
    public function gymClass(): BelongsTo
    {
        return $this->belongsTo(GymClass::class, 'gym_class_id');
    }

    /**
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Check if assignment is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Scope to get only active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
