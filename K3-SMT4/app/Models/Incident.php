<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'incident_number', 'title', 'description', 'incident_date', 'location',
        'department_id', 'incident_type', 'severity', 'injured_persons', 'witnesses',
        'immediate_action_taken', 'reported_by', 'investigated_by', 'status',
        'root_cause', 'lesson_learned', 'evidence_urls', 'capa_required',
        'incident_capa_id', 'closed_at',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'closed_at' => 'datetime',
        'evidence_urls' => 'array',
        'capa_required' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function investigator()
    {
        return $this->belongsTo(User::class, 'investigated_by');
    }

    public function capa()
    {
        return $this->belongsTo(Capa::class, 'incident_capa_id');
    }

    public function getIncidentTypeLabelAttribute(): string
    {
        return match($this->incident_type) {
            'near_miss'           => 'Near Miss',
            'first_aid'           => 'First Aid',
            'medical_treatment'   => 'Medical Treatment',
            'lost_time_injury'    => 'Lost Time Injury',
            'fatality'            => 'Fatality',
            'property_damage'     => 'Property Damage',
            'environmental'       => 'Environmental',
            default               => '-',
        };
    }

    public function getSeverityLabelAttribute(): string
    {
        return ucfirst($this->severity);
    }

    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'low'      => 'green',
            'medium'   => 'yellow',
            'high'     => 'orange',
            'critical' => 'red',
            default    => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'reported'             => 'Reported',
            'under_investigation'  => 'Under Investigation',
            'corrective_action'    => 'Corrective Action',
            'closed'               => 'Closed',
            default                => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'reported'            => 'red',
            'under_investigation' => 'yellow',
            'corrective_action'   => 'blue',
            'closed'              => 'green',
            default               => 'gray',
        };
    }

    public function isLostTimeInjury(): bool
    {
        return $this->incident_type === 'lost_time_injury';
    }
}