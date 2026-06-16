<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hazard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'hazard_number', 'location', 'department_id', 'task_description',
        'hazard_description', 'hazard_type', 'likelihood', 'severity',
        'risk_score', 'risk_level', 'existing_controls', 'additional_controls',
        'responsible_person_id', 'target_completion_date', 'status',
        'sop_id', 'identified_by',
    ];

    protected $casts = [
        'target_completion_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function responsiblePerson()
    {
        return $this->belongsTo(Employee::class, 'responsible_person_id');
    }

    public function sop()
    {
        return $this->belongsTo(Sop::class);
    }

    public function identifier()
    {
        return $this->belongsTo(User::class, 'identified_by');
    }

    public function getHazardTypeLabelAttribute(): string
    {
        return match($this->hazard_type) {
            'physical'     => 'Physical',
            'chemical'     => 'Chemical',
            'biological'   => 'Biological',
            'ergonomic'    => 'Ergonomic',
            'psychosocial' => 'Psychosocial',
            'electrical'   => 'Electrical',
            'mechanical'   => 'Mechanical',
            default        => '-',
        };
    }

    public function getRiskLevelLabelAttribute(): string
    {
        return ucfirst($this->risk_level);
    }

    public function getRiskLevelColorAttribute(): string
    {
        return match($this->risk_level) {
            'low'     => 'green',
            'medium'  => 'yellow',
            'high'    => 'orange',
            'extreme' => 'red',
            default   => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'identified' => 'Identified',
            'controlled' => 'Controlled',
            'closed'     => 'Closed',
            default      => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'identified' => 'red',
            'controlled' => 'yellow',
            'closed'     => 'green',
            default      => 'gray',
        };
    }

    /**
     * Calculate risk score from likelihood * severity
     */
    public static function calculateRiskScore(int $likelihood, int $severity): array
    {
        $score = $likelihood * $severity;
        $level = match(true) {
            $score >= 20 => 'extreme',
            $score >= 12 => 'high',
            $score >= 6  => 'medium',
            default      => 'low',
        };

        return ['risk_score' => $score, 'risk_level' => $level];
    }
}