<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditFinding extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id', 'finding_number', 'description', 'severity', 'finding_type',
        'area', 'standard_ref', 'recommendation', 'status',
    ];

    public function audit() { return $this->belongsTo(Audit::class); }
    public function capa() { return $this->hasOne(Capa::class, 'finding_id'); }

    public function getSeverityLabelAttribute(): string
    {
        return match($this->severity) {
            'minor'    => 'Minor',
            'major'    => 'Major',
            'critical' => 'Critical',
            default    => '-',
        };
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'minor'    => 'yellow',
            'major'    => 'orange',
            'critical' => 'red',
            default    => 'gray',
        };
    }

    public function getFindingTypeLabelAttribute(): string
    {
        return match($this->finding_type) {
            'non_conformance' => 'Ketidaksesuaian (NCR)',
            'conformance'     => 'Kesesuaian (OFI)',
            'observation'     => 'Observasi',
            default           => '-',
        };
    }

    public function getFindingTypeColorAttribute(): string
    {
        return match($this->finding_type) {
            'non_conformance' => 'red',
            'conformance'     => 'green',
            'observation'     => 'blue',
            default           => 'gray',
        };
    }
}
