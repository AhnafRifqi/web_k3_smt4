<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditChecklistItem extends Model
{
    protected $fillable = [
        'audit_id', 'item_number', 'description', 'standard_ref',
        'evidence_type', 'evidence_id', 'conformance_status', 'notes',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function evidence(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'evidence_type', 'evidence_id');
    }

    public function getConformanceStatusLabelAttribute(): string
    {
        return match ($this->conformance_status) {
            'conforming' => 'Conforming',
            'minor_nc' => 'Minor NC',
            'major_nc' => 'Major NC',
            'observation' => 'Observation',
            'not_assessed' => 'Not Assessed',
            default => $this->conformance_status,
        };
    }

    public function getConformanceStatusColorAttribute(): string
    {
        return match ($this->conformance_status) {
            'conforming' => 'green',
            'minor_nc' => 'yellow',
            'major_nc' => 'red',
            'observation' => 'blue',
            'not_assessed' => 'gray',
            default => 'gray',
        };
    }
}
