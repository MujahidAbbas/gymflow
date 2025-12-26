<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'member_id',
        'date',
        'check_in_time',
        'check_out_time',
        'duration_minutes',
        'notes',
    ];
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Apply tenant scoping for data isolation
        static::addGlobalScope(new \App\Models\Scopes\TenantScope);
    }


    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'check_in_time' => 'datetime:H:i',
            'check_out_time' => 'datetime:H:i',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Get the owner/parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Check if member is checked out
     */
    public function isCheckedOut(): bool
    {
        return $this->check_out_time !== null;
    }

    /**
     * Calculate and update duration
     */
    public function calculateDuration(): void
    {
        if ($this->check_out_time) {
            $checkIn = $this->check_in_time;
            $checkOut = $this->check_out_time;

            $this->duration_minutes = $checkOut->diffInMinutes($checkIn);
            $this->save();
        }
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute(): string
    {
        if (! $this->duration_minutes) {
            return 'In Progress';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Scope for today's attendance
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope for active (not checked out)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('check_out_time');
    }
}
