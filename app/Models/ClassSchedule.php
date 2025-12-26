<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'gym_class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'trainer_id',
        'room_location',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    /**
     * Get the class
     */
    public function gymClass(): BelongsTo
    {
        return $this->belongsTo(GymClass::class, 'gym_class_id');
    }

    /**
     * Get the assigned trainer
     */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute(): string
    {
        return $this->start_time->format('H:i').' - '.$this->end_time->format('H:i');
    }
}
