<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locker extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'locker_number',
        'location',
        'status',
        'monthly_fee',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'monthly_fee' => 'decimal:2',
        ];
    }

    /**
     * Boot method to auto-generate locker number
     */
    protected static function boot()
    {
        parent::boot();

        // Apply tenant scoping for data isolation
        static::addGlobalScope(new \App\Models\Scopes\TenantScope);

        static::creating(function ($locker) {
            if (empty($locker->locker_number)) {
                $locker->locker_number = self::generateLockerNumber();
            }
        });
    }

    /**
     * Generate unique locker number
     */
    public static function generateLockerNumber(): string
    {
        $parentId = parentId();
        $lastLocker = self::where('parent_id', $parentId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastLocker && preg_match('/#LKR-(\d+)/', $lastLocker->locker_number, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return '#LKR-'.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the owner/parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get locker assignments
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(LockerAssignment::class);
    }

    /**
     * Get current active assignment
     */
    public function currentAssignment()
    {
        return $this->hasOne(LockerAssignment::class)->where('status', 'active')->latest();
    }

    /**
     * Check if locker is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Scope for available lockers
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for occupied lockers
     */
    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }
}
