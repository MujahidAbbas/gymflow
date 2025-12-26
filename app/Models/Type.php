<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'name',
        'category',
        'description',
        'is_active',
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
     * Get expenses for this type
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Scope for income types
     */
    public function scopeIncome($query)
    {
        return $query->where('category', 'income');
    }

    /**
     * Scope for expense types
     */
    public function scopeExpense($query)
    {
        return $query->where('category', 'expense');
    }

    /**
     * Scope for active types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActionButtonsAttribute()
    {
        $buttons = '';


        // Edit button - check permission

        $buttons .= $this->edit($this);


        // Delete button - check permission

        $buttons .= $this->deleteModel(route("types.destroy", $this), csrf_token(), "expense-table");



        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $buttons
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('types.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
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
