<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'member_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'trial_end_date',
        'status',
        'auto_renew',
        'payment_gateway',
        'gateway_subscription_id',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'trial_end_date' => 'date',
            'auto_renew' => 'boolean',
        ];
    }

    /**
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the subscription plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Get payment transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    /**
     * Check if subscription is on trial
     */
    public function isOnTrial(): bool
    {
        return $this->status === 'trial' &&
               $this->trial_end_date &&
               $this->trial_end_date->isFuture();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, $this->end_date->diffInDays(now(), false) * -1);
    }

    /**
     * Renew subscription
     */
    public function renew(): void
    {
        $this->start_date = $this->end_date->addDay();
        $this->end_date = $this->start_date->addDays($this->plan->duration_days);
        $this->status = 'active';
        $this->save();
    }

    /**
     * Cancel subscription
     */
    public function cancel(): void
    {
        $this->status = 'cancelled';
        $this->auto_renew = false;
        $this->save();
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>', now());
    }

    /**
     * Scope for expiring soon (within 7 days)
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(7)]);
    }
}
