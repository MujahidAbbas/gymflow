<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workout extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'workout_id',
        'member_id',
        'trainer_id',
        'name',
        'description',
        'workout_date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'workout_date' => 'date',
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

        static::creating(function ($workout) {
            if (empty($workout->workout_id)) {
                $workout->workout_id = self::generateWorkoutId($workout->parent_id);
            }
        });
    }

    /**
     * Generate unique workout ID
     */
    public static function generateWorkoutId($parentId = null): string
    {
        $parentId = $parentId ?? parentId();
        $lastWorkout = self::where('parent_id', $parentId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastWorkout && preg_match('/#WRK-(\d+)/', $lastWorkout->workout_id, $matches)) {
            $number = intval($matches[1]) + 1;
        } else {
            $number = 1;
        }

        return '#WRK-'.str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the owner/parent
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the trainer
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get all activities
     */
    public function activities(): HasMany
    {
        return $this->hasMany(WorkoutActivity::class)->orderBy('order');
    }

    /**
     * Check if workout is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentageAttribute(): int
    {
        $total = $this->activities()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->activities()->where('is_completed', true)->count();

        return round(($completed / $total) * 100);
    }

    /**
     * Scope for today's workouts
     */
    public function scopeToday($query)
    {
        return $query->whereDate('workout_date', today());
    }

    /**
     * Scope for active workouts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getActionButtonsAttribute()
    {

        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->view($this)
            . $this->edit($this)
            .  $this->deleteModel(route("workouts.destroy", $this), csrf_token(), "workout-table")
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('workouts.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="' . route('workouts.show', $customer) . '" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
