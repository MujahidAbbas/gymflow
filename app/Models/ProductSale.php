<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSale extends Model
{
    protected $fillable = [
        'parent_id',
        'product_id',
        'member_id',
        'sold_by',
        'sale_id',
        'quantity',
        'unit_price',
        'total_amount',
        'discount',
        'final_amount',
        'payment_method',
        'notes',
        'sale_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'sale_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->sale_id)) {
                $sale->sale_id = self::generateSaleId();
            }
        });
    }

    public static function generateSaleId(): string
    {
        $parentId = parentId();
        $lastSale = self::where('parent_id', $parentId)->orderBy('id', 'desc')->first();

        if ($lastSale && preg_match('/#SALE-(\d+)/', $lastSale->sale_id, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return '#SALE-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function soldBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_by');
    }
}
