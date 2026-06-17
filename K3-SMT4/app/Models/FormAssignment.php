<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FormAssignment extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'form_id', 'assigned_to_type', 'assigned_to_id', 'frequency', 'due_date', 'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(MonitoringForm::class, 'form_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class, 'assignment_id');
    }

    public function assignedDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'assigned_to_id');
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function getAssignedToNameAttribute(): string
    {
        if ($this->assigned_to_type === 'department') {
            return $this->assignedDepartment?->name ?? 'Departemen #' . $this->assigned_to_id;
        }

        return $this->assignedUser?->name ?? 'User #' . $this->assigned_to_id;
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'daily'     => 'Harian',
            'weekly'    => 'Mingguan',
            'monthly'   => 'Bulanan',
            'once'      => 'Sekali',
            'per_event' => 'Per Kejadian',
            'ad_hoc'    => 'Ad Hoc',
            default     => $this->frequency,
        };
    }
}
