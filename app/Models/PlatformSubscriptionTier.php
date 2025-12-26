<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PlatformSubscriptionTier extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'interval',
        'trial_days',
        'max_members_per_tenant',
        'max_trainers_per_tenant',
        'max_staff_per_tenant',
        'features',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name
        static::creating(function ($tier) {
            if (empty($tier->slug)) {
                $tier->slug = Str::slug($tier->name);
            }
        });
    }

    /**
     * Get tenants subscribed to this tier
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'platform_subscription_tier_id');
    }

    /**
     * Get active tenants count
     */
    public function getActiveTenantCountAttribute(): int
    {
        return $this->tenants()->where('status', 'active')->count();
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price, 2);
    }

    /**
     * Get interval label
     */
    public function getIntervalLabelAttribute(): string
    {
        return match($this->interval) {
            'monthly' => '/month',
            'quarterly' => '/3 months',
            'yearly' => '/year',
            'lifetime' => 'lifetime',
            default => '',
        };
    }

    /**
     * Check if tier has unlimited members
     */
    public function hasUnlimitedMembers(): bool
    {
        return $this->max_members_per_tenant === null || $this->max_members_per_tenant === 0;
    }

    /**
     * Check if tier has unlimited trainers
     */
    public function hasUnlimitedTrainers(): bool
    {
        return $this->max_trainers_per_tenant === null || $this->max_trainers_per_tenant === 0;
    }

    /**
     * Scope for active tiers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured tiers
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordered tiers
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function getActionButtonsAttribute()
    {

        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->view($this)
            . $this->edit($this)
            .  $this->deleteModel(route("super-admin.platform-subscriptions.destroy", $this), csrf_token(), "subscription-table")
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('super-admin.platform-subscriptions.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="' . route('super-admin.platform-subscriptions.show', $customer) . '" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
