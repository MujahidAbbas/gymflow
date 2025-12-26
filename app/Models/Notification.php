<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'parent_id',
        'module',
        'subject',
        'message',
        'enabled_email',
        'enabled_web',
    ];

    protected $casts = [
        'enabled_email' => 'boolean',
        'enabled_web' => 'boolean',
    ];

    /**
     * Get the parent user (owner/admin)
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
