<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subscription_id',
        'member_id',
        'transaction_id',
        'gateway_transaction_id',
        'payment_gateway',
        'amount',
        'currency',
        'status',
        'type',
        'description',
        'metadata',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'metadata' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Boot method to auto-generate transaction ID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_id)) {
                $transaction->transaction_id = self::generateTransactionId();
            }
        });
    }

    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId(): string
    {
        $lastTransaction = self::orderBy('id', 'desc')->first();

        if ($lastTransaction && preg_match('/#TXN-(\d+)/', $lastTransaction->transaction_id, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return '#TXN-'.str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get the subscription
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Check if payment is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): void
    {
        $this->status = 'completed';
        $this->paid_at = now();
        $this->save();
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(): void
    {
        $this->status = 'failed';
        $this->save();
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return $this->currency.' '.number_format($this->amount, 2);
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope by payment gateway
     */
    public function scopeByGateway($query, string $gateway)
    {
        return $query->where('payment_gateway', $gateway);
    }
}
