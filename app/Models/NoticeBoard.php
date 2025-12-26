<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoticeBoard extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'title',
        'content',
        'priority',
        'publish_date',
        'expiry_date',
        'is_active',
        'attachment',
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
            'publish_date' => 'date',
            'expiry_date' => 'date',
            'is_active' => 'boolean',
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
     * Check if notice is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Check if notice is published
     */
    public function isPublished(): bool
    {
        return $this->publish_date->isPast() && ! $this->isExpired();
    }

    /**
     * Get attachment URL
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment ? asset('storage/'.$this->attachment) : null;
    }

    /**
     * Scope for active notices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('publish_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now());
            });
    }

    /**
     * Scope for high priority
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }
}
