<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymClass extends Model
{
    protected $table = 'gym_classes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'class_id',
        'category_id',
        'name',
        'description',
        'max_capacity',
        'duration_minutes',
        'difficulty_level',
        'image',
        'status',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'max_capacity' => 'integer',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Apply tenant scoping for data isolation
        static::addGlobalScope(new \App\Models\Scopes\TenantScope);

        static::creating(function ($class) {
            if (empty($class->class_id)) {
                $class->class_id = self::generateClassId($class->parent_id);
            }
        });
    }

    /**
     * Generate unique class ID
     */
    public static function generateClassId($parentId = null): string
    {
        $parentId = $parentId ?? parentId();
        $lastClass = self::where('parent_id', $parentId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastClass && preg_match('/#CLS-(\d+)/', $lastClass->class_id, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return '#CLS-'.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the owner/parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all schedules for this class
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class, 'gym_class_id');
    }

    /**
     * Get all member assignments
     */
    public function assigns(): HasMany
    {
        return $this->hasMany(ClassAssign::class, 'gym_class_id');
    }

    /**
     * Get enrolled members count
     */
    public function getEnrolledCountAttribute(): int
    {
        return $this->assigns()->where('status', 'active')->count();
    }

    /**
     * Check if class is full
     */
    public function isFull(): bool
    {
        return $this->enrolled_count >= $this->max_capacity;
    }

    /**
     * Check if class is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Scope to get only active classes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getActionButtonsAttribute()
    {
        $buttons = '';

        // View button - check permission
        $buttons .= $this->view($this);

        // Edit button - check permission

            $buttons .= $this->edit($this);


        // Delete button - check permission

            $buttons .= $this->deleteModel(route("gym-classes.destroy", $this), csrf_token(), "gym-table");



        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $buttons
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('gym-classes.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="' . route('gym-classes.show', $customer) . '" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
