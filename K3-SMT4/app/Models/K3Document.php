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
        'title', 'category', 'document_number', 'revision',
        'effective_date', 'file_url', 'description', 'status', 'uploaded_by',
    ];

    protected $casts = ['effective_date' => 'date'];

    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }

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
}
