<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'subdomain',
        'status',
        'max_members',
        'max_trainers',
        'trial_ends_at',
        'platform_subscription_tier_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'max_members' => 'integer',
        'max_trainers' => 'integer',
    ];

    /**
     * Get the owner user for this tenant.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all users under this tenant.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id', 'user_id');
    }

    /**
     * Get the platform subscription tier.
     */
    public function platformSubscriptionTier(): BelongsTo
    {
        return $this->belongsTo(PlatformSubscriptionTier::class);
    }

    /**
     * Check if the tenant subscription is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the tenant is on trial.
     */
    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if the tenant is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function getActionButtonsAttribute()
    {
        $suspend = '';

        if ($this->status !== 'suspended') {
            $suspend = '<li><form id="suspend-form-'.$this->id.'" action="'.route('super-admin.customers.suspend', $this).'" method="POST">'
                .csrf_field()
                .'<button type="submit" class="dropdown-item text-warning">
            Suspend
        </button></form></li>';
        }

        $impersonate = '<li>
    <a href="javascript:void(0);" class="dropdown-item" onclick="impersonateCustomer(' . $this->id . ')">
        <i class="ri-user-shared-line align-bottom me-2 text-muted"></i> Impersonate
    </a>
</li>';

        return '<div class="d-inline-block">'
            .'<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            .'<ul class="dropdown-menu dropdown-menu-end m-0">'
            .$this->view($this)
            .$this->edit($this)
              .$this->deleteModel(route('super-admin.customers.destroy', $this), csrf_token(), 'customer-table')
            .$suspend
            .$impersonate
            .'</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="'.route('super-admin.customers.edit', $customer).'" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="'.route('super-admin.customers.show', $customer).'" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`'.$route.'`,`'.$token.'`'.',`'.$dataTableId.'`'.')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
