<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Health extends Model
{
    /**
     * Measurement types available
     */
    public const MEASUREMENT_TYPES = [
        'height' => 'Height (cm)',
        'weight' => 'Weight (kg)',
        'waist' => 'Waist (cm)',
        'chest' => 'Chest (cm)',
        'thigh' => 'Thigh (cm)',
        'arms' => 'Arms (cm)',
        'body_fat' => 'Body Fat (%)',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parent_id',
        'member_id',
        'measurement_date',
        'measurements',
        'notes',
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
            'measurement_date' => 'date',
            'measurements' => 'array',
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
     * Get the member
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get a specific measurement
     */
    public function getMeasurement(string $type): ?float
    {
        return $this->measurements[$type] ?? null;
    }

    /**
     * Calculate BMI if height and weight are available
     */
    public function getBmiAttribute(): ?float
    {
        $height = $this->getMeasurement('height');
        $weight = $this->getMeasurement('weight');

        if ($height && $weight && $height > 0) {
            $heightM = $height / 100; // convert cm to meters

            return round($weight / ($heightM * $heightM), 1);
        }

        return null;
    }

    /**
     * Get BMI category
     */
    public function getBmiCategoryAttribute(): ?string
    {
        $bmi = $this->bmi;

        if (! $bmi) {
            return null;
        }

        if ($bmi < 18.5) {
            return 'Underweight';
        }
        if ($bmi < 25) {
            return 'Normal';
        }
        if ($bmi < 30) {
            return 'Overweight';
        }

        return 'Obese';
    }

    public function getActionButtonsAttribute()
    {

        return '<div class="d-inline-block">'
            . '<a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-fill fs-17"></i></a>'
            . '<ul class="dropdown-menu dropdown-menu-end m-0">'
            . $this->view($this)
            . $this->edit($this)
            .  $this->deleteModel(route("healths.destroy", $this), csrf_token(), "healths-table")
            . '</ul></div>';
    }

    public function edit($customer)
    {
        return '<li><a href="' . route('healths.edit', $customer) . '" class="dropdown-item">Edit</a></li>';
    }

    public function view($customer)
    {
        return '<li><a href="' . route('healths.show', $customer) . '" class="dropdown-item">View</a></li>';
    }

    public function deleteModel($route, $token, $dataTableId)
    {
        return '<li><a href="#" onclick="deleteRow(`' . $route . '`,`' . $token . '`' . ',`' . $dataTableId . '`' . ')" title="Delete" class="dropdown-item text-danger">Delete</a></li>';
    }
}
