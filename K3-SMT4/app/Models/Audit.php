<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_number', 'type', 'audit_date', 'audit_date_end',
        'auditor_name', 'audit_agency', 'area', 'scope', 'standard',
        'checklist', 'summary', 'status', 'report_url', 'created_by',
    ];

    protected $casts = [
        'audit_date'     => 'date',
        'audit_date_end' => 'date',
        'checklist'      => 'array',
    ];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function findings() { return $this->hasMany(AuditFinding::class); }
    public function capas() { return $this->hasMany(Capa::class); }
    public function checklistItems() { return $this->hasMany(AuditChecklistItem::class); }
    public function documents() { return $this->hasMany(K3Document::class, 'uploaded_by', 'created_by'); }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'internal' => 'Audit Internal',
            'eksternal' => 'Audit Eksternal',
            default => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'planned'   => 'Direncanakan',
            'ongoing'   => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default     => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planned'   => 'blue',
            'ongoing'   => 'yellow',
            'completed' => 'green',
            'cancelled' => 'red',
            default     => 'gray',
        };
    }
}
