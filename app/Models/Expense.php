<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'expense_number',
        'type_id',
        'title',
        'description',
        'amount',
        'expense_date',
        'payment_method',
        'receipt',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'expense_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Boot method to auto-generate expense number
     */
    protected static function boot()
    {
        parent::boot();

        // Apply tenant scoping for data isolation
        static::addGlobalScope(new \App\Models\Scopes\TenantScope);

        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $expense->expense_number = self::generateExpenseNumber($expense->parent_id);
            }
        });
    }

    /**
     * Generate unique expense number
     */
    public static function generateExpenseNumber($parentId = null): string
    {
        $parentId = $parentId ?? parentId();
        $lastExpense = self::where('parent_id', $parentId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastExpense && preg_match('/#EXP-(\d+)/', $lastExpense->expense_number, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return '#EXP-'.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the owner/parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the type
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Get receipt URL attribute
     */
    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt ? asset('storage/'.$this->receipt) : null;
    }

    public function getActionButtonsAttribute()
    {
        $buttons = '';

        // View button - check permission
        $buttons .= $this->view($this);

        // Edit button - check permission

            $buttons .= $this->edit($this);


        // Delete button - check permission

            $buttons .= $this->deleteModel(route("expenses.destroy", $this), csrf_token(), "expense-table");



        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $buttons
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('expenses.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="' . route('expenses.show', $customer) . '" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
