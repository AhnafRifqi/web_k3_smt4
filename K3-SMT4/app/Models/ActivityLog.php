<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false; // We only track created_at manually

    protected $fillable = [
        'user_id', 'user_name', 'user_role', 'action', 'module',
        'subject_type', 'subject_id', 'description',
        'ip_address', 'user_agent', 'old_values', 'new_values', 'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}