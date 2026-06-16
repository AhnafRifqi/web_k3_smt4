<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class K3Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'k3_documents';

    protected $fillable = [
        'title', 'category', 'document_number', 'revision', 'version',
        'effective_date', 'file_url', 'description', 'status', 'workflow_status',
        'visibility', 'allowed_departments',
        'uploaded_by', 'submitted_by', 'reviewed_by', 'approved_by',
        'submitted_at', 'reviewed_at', 'approved_at', 'review_due_date',
        'parent_document_id',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'review_due_date' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
        'allowed_departments' => 'array',
    ];

    // Relationships
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
    public function submitter() { return $this->belongsTo(User::class, 'submitted_by'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }

    public function parent()
    {
        return $this->belongsTo(K3Document::class, 'parent_document_id');
    }

    public function versions()
    {
        return $this->hasMany(K3Document::class, 'parent_document_id');
    }

    // Accessors
    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'kebijakan_k3'     => 'Kebijakan K3',
            'sop'              => 'SOP',
            'hiradc'           => 'HIRADC',
            'apd'              => 'APD',
            'emergency_response' => 'Emergency Response Plan',
            'audit'            => 'Audit',
            'training'         => 'Training',
            'lainnya'          => 'Lainnya',
            default            => '-',
        };
    }

    public function getWorkflowStatusLabelAttribute(): string
    {
        return match($this->workflow_status) {
            'draft'       => 'Draft',
            'under_review' => 'Under Review',
            'approved'    => 'Approved',
            'obsolete'    => 'Obsolete',
            default       => '-',
        };
    }

    public function getWorkflowStatusColorAttribute(): string
    {
        return match($this->workflow_status) {
            'draft'       => 'gray',
            'under_review' => 'yellow',
            'approved'    => 'green',
            'obsolete'    => 'red',
            default       => 'gray',
        };
    }

    public function isExpiringSoon(): bool
    {
        if (!$this->review_due_date) return false;
        return $this->review_due_date->isFuture() && $this->review_due_date->diffInDays(now()) <= 30;
    }
}