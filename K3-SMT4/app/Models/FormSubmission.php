<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_id', 'assignment_id', 'submitted_by', 'department_id',
        'submitted_at', 'data', 'status',
    ];

    protected $casts = [
        'data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(MonitoringForm::class, 'form_id');
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(FormAssignment::class, 'assignment_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'Terkirim',
            'draft' => 'Draft',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'green',
            'draft' => 'yellow',
            default => 'gray',
        };
    }
}
