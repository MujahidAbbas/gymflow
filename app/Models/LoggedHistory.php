<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoggedHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip',
        'country',
        'browser',
        'os',
        'device',
        'referer',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
