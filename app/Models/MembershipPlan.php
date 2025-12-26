<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'price',
        'duration_type',
        'duration_value',
        'is_active',
        'features',
        'max_classes',
        'personal_training',
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
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'personal_training' => 'boolean',
            'features' => 'array',
            'max_classes' => 'integer',
            'duration_value' => 'integer',
        ];
    }

    /**
     * Get the  owner/parent of this plan
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get members with this plan
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Calculate expiry date based on start date
     */
    public function calculateExpiryDate($startDate): string
    {
        $start = \Carbon\Carbon::parse($startDate);

        return match ($this->duration_type) {
            'daily' => $start->addDays($this->duration_value)->format('Y-m-d'),
            'weekly' => $start->addWeeks($this->duration_value)->format('Y-m-d'),
            'monthly' => $start->addMonths($this->duration_value)->format('Y-m-d'),
            'quarterly' => $start->addMonths($this->duration_value * 3)->format('Y-m-d'),
            'half_yearly' => $start->addMonths($this->duration_value * 6)->format('Y-m-d'),
            'yearly' => $start->addYears($this->duration_value)->format('Y-m-d'),
            'lifetime' => null, // Lifetime memberships don't expire
            default => $start->addMonths(1)->format('Y-m-d'),
        };
    }

    /**
     * Scope to get only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getActionButtonsAttribute()
    {

        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->view($this)
            . $this->edit($this)
            .  $this->deleteModel(route("membership-plans.destroy", $this), csrf_token(), "membership-table")
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('membership-plans.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="' . route('membership-plans.show', $customer) . '" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
