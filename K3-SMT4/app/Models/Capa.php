<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capa extends Model
{
    use HasFactory;

    protected $table = 'capas';

    protected $fillable = [
        'capa_number', 'finding_id', 'audit_id', 'description', 'root_cause',
        'corrective_action', 'preventive_action', 'pic_id', 'target_date',
        'completed_date', 'status', 'evidence_url', 'verification_notes', 'verified_by',
    ];

    protected $casts = [
        'target_date'    => 'date',
        'completed_date' => 'date',
    ];

    public function finding() { return $this->belongsTo(AuditFinding::class, 'finding_id'); }
    public function audit() { return $this->belongsTo(Audit::class); }
    public function pic() { return $this->belongsTo(Employee::class, 'pic_id'); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open'        => 'Open',
            'in_progress' => 'In Progress',
            'closed'      => 'Closed',
            default       => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open'        => 'red',
            'in_progress' => 'yellow',
            'closed'      => 'green',
            default       => 'gray',
        };
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'closed' && $this->target_date < now();
    }
}
